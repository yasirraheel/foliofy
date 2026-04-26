<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Database\Seeders\PortfolioDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PortfolioApplicationTest extends TestCase
{
    use RefreshDatabase;

    private string $uploadsPath;

    private string $profileImagePath;

    private string $ogImagePath;

    protected function setUp(): void
    {
        parent::setUp();

        $basePath = storage_path('framework/testing/portfolio');
        File::ensureDirectoryExists($basePath);

        $this->uploadsPath = $basePath.'/uploads';
        $this->profileImagePath = $basePath.'/profile.png';
        $this->ogImagePath = $basePath.'/og-image.jpg';

        File::delete($this->profileImagePath);
        File::delete($this->ogImagePath);
        File::deleteDirectory($this->uploadsPath);

        config([
            'portfolio.uploads_path' => $this->uploadsPath,
            'portfolio.profile_image_path' => $this->profileImagePath,
            'portfolio.og_image_path' => $this->ogImagePath,
        ]);
    }

    protected function tearDown(): void
    {
        File::delete($this->profileImagePath);
        File::delete($this->ogImagePath);
        File::deleteDirectory($this->uploadsPath);

        parent::tearDown();
    }

    public function test_portfolio_and_admin_pages_are_served_from_laravel(): void
    {
        $this->assertFalse(Schema::hasTable('portfolio_contents'));

        $this->get('/')
            ->assertOk()
            ->assertSee('Muhammad Asif');

        $this->get('/index.html')
            ->assertOk()
            ->assertSee('Muhammad Asif');

        $this->get('/admin.html')
            ->assertOk()
            ->assertSee('Portfolio Admin Dashboard');
    }

    public function test_public_page_injects_database_portfolio_data_into_the_frontend_boot_payload(): void
    {
        $this->seed(PortfolioDataSeeder::class);

        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('window.__PORTFOLIO_DATA__=', false)
            ->assertSee('"name":"Muhammad Asif Shabbir"', false)
            ->assertSee('"email":"islammujahid921@gmail.com"', false)
            ->assertSee('"portfolioUrl":"https://foliofy.me/"', false)
            ->assertSee('"themeDefault":"dark"', false)
            ->assertSee('"period":"2021 - 2024"', false)
            ->assertSee('<html lang="en" data-theme="dark">', false)
            ->assertSee('style.css?v=', false)
            ->assertSee('data.js?v=', false)
            ->assertSee('renderer.js?v=', false)
            ->assertSee('script.js?v=', false);

        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('no-cache', (string) $response->headers->get('Cache-Control'));
    }

    public function test_public_page_renders_server_side_social_meta_for_large_image_cards(): void
    {
        $this->seed(PortfolioDataSeeder::class);
        $this->createProfileImage(700, 1100);

        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('<meta property="og:image" content="http://localhost/og-image.jpg?v=', false)
            ->assertSee('<meta property="og:image:width" content="1200" />', false)
            ->assertSee('<meta property="og:image:height" content="630" />', false)
            ->assertSee('<meta name="twitter:card" content="summary_large_image" />', false)
            ->assertSee('<meta name="twitter:title" content="Muhammad Asif Shabbir | Network Support Engineer" />', false)
            ->assertSee('<meta name="twitter:description" content="Professional portfolio of Muhammad Asif Shabbir, a networking-focused IT professional with Pakistan Army leadership experience, CCNA in progress, Packet Tracer lab practice, and supporting web and Android development skills." />', false)
            ->assertSee('<meta name="twitter:image" content="http://localhost/og-image.jpg?v=', false);

        $this->assertTrue(File::exists($this->ogImagePath));

        [$width, $height] = getimagesize($this->ogImagePath);

        $this->assertSame(1200, $width);
        $this->assertSame(630, $height);
    }

    public function test_public_page_renders_main_sections_in_server_html_for_seo(): void
    {
        $this->seed(PortfolioDataSeeder::class);

        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('<span class="skill-name">IP Addressing and Subnetting</span>', false)
            ->assertSee('<h3 class="project-title">CineWorm Web Platform</h3>', false)
            ->assertSee('<h3 class="timeline-title">Instructor Assignment - Kingdom of Saudi Arabia Army</h3>', false)
            ->assertSee('<script type="application/ld+json">', false)
            ->assertSee('"@type": "Person"', false)
            ->assertDontSee('Need user input');
    }

    public function test_robots_and_sitemap_are_served_from_laravel_with_the_canonical_url(): void
    {
        $this->seed(PortfolioDataSeeder::class);

        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('User-agent: *', false)
            ->assertSee('Sitemap: https://foliofy.me/sitemap.xml', false);

        $sitemap = $this->get('/sitemap.xml');

        $sitemap
            ->assertOk()
            ->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false)
            ->assertSee('<loc>https://foliofy.me/</loc>', false);

        $this->assertStringContainsString('application/xml', (string) $sitemap->headers->get('Content-Type'));
    }

    public function test_project_root_bridges_live_server_requests_into_laravel(): void
    {
        $this->assertFileExists(base_path('index.php'));
        $this->assertSame(
            "<?php\n\ndeclare(strict_types=1);\n\nrequire __DIR__.'/public/index.php';\n",
            File::get(base_path('index.php'))
        );

        $rootHtaccess = File::get(base_path('.htaccess'));

        $this->assertStringContainsString('DirectoryIndex index.php', $rootHtaccess);
        $this->assertStringContainsString('public/$1', $rootHtaccess);
        $this->assertStringContainsString('RewriteRule ^uploads/(.*)$ public/uploads/$1 [L]', $rootHtaccess);
        $this->assertStringContainsString('index.php [L]', $rootHtaccess);
        $this->assertStringContainsString('index\\.html|admin\\.html', $rootHtaccess);
    }

    public function test_admin_login_bootstrap_and_password_update_use_session_auth(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => 'secret123',
        ]);

        $this->getJson('/admin/bootstrap')
            ->assertOk()
            ->assertJson([
                'authenticated' => false,
                'user' => null,
            ]);

        $this->getJson('/admin/ping')
            ->assertStatus(401);

        $loginResponse = $this->postJson('/admin/login', [
            'password' => 'secret123',
            'remember' => true,
        ]);

        $loginResponse
            ->assertOk()
            ->assertJson([
                'success' => true,
                'user' => [
                    'id' => $admin->id,
                    'email' => $admin->email,
                ],
            ]);
        $loginResponse->assertCookie(Auth::guard()->getRecallerName());

        $this->getJson('/admin/bootstrap')
            ->assertOk()
            ->assertJson([
                'authenticated' => true,
                'user' => [
                    'id' => $admin->id,
                    'email' => $admin->email,
                ],
            ]);

        $this->getJson('/admin/ping')
            ->assertOk()
            ->assertJson([
                'authenticated' => true,
            ]);

        $this->postJson('/admin/password', [
            'current_password' => 'secret123',
            'new_password' => 'updated456',
            'new_password_confirmation' => 'updated456',
        ])->assertOk()->assertJson(['success' => true]);

        $this->postJson('/admin/logout')
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->postJson('/admin/login', ['password' => 'updated456'])
            ->assertOk()
            ->assertJson(['success' => true]);
    }

    public function test_admin_can_save_portfolio_data_to_the_database(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => 'secret123',
        ]);

        $payload = [
            'meta' => [
                'name' => 'Updated Name',
                'themeDefault' => 'light',
                'siteTitle' => 'Updated Portfolio',
                'siteKeywords' => 'networking, support',
            ],
            'contact' => [
                'email' => 'hello@example.com',
                'portfolioUrl' => 'https://example.com',
                'resumeUrl' => 'https://example.com/resume.pdf',
                'whatsappApi' => [
                    'enabled' => true,
                    'apiKey' => 'abc123',
                    'accountName' => 'portfolio',
                    'targetNumber' => '923001234567',
                ],
            ],
        ];

        $this->actingAs($admin)
            ->postJson('/admin/portfolio-data', ['data' => $payload])
            ->assertOk()
            ->assertJsonPath('data.meta.name', 'Updated Name');

        $this->assertDatabaseHas('portfolio_meta', [
            'id' => 1,
            'name' => 'Updated Name',
            'theme_default' => 'light',
            'site_title' => 'Updated Portfolio',
            'site_keywords' => 'networking, support',
        ]);
        $this->assertDatabaseHas('portfolio_contact', [
            'id' => 1,
            'email' => 'hello@example.com',
            'portfolio_url' => 'https://example.com',
            'resume_url' => 'https://example.com/resume.pdf',
            'whatsapp_enabled' => 1,
            'whatsapp_account_name' => 'portfolio',
            'whatsapp_target_number' => '923001234567',
        ]);
    }

    public function test_portfolio_data_seeder_populates_normalized_tables_with_existing_content(): void
    {
        $this->seed(PortfolioDataSeeder::class);

        $this->assertDatabaseHas('portfolio_meta', [
            'id' => 1,
            'name' => 'Muhammad Asif Shabbir',
            'brand_text' => 'MAS',
            'theme_default' => 'dark',
        ]);
        $this->assertDatabaseHas('portfolio_hero', [
            'id' => 1,
            'highlight_name' => 'Shabbir',
        ]);
        $this->assertDatabaseHas('portfolio_about', [
            'id' => 1,
            'degree' => 'BS IT - In Progress',
        ]);
        $this->assertDatabaseHas('portfolio_contact', [
            'id' => 1,
            'email' => 'islammujahid921@gmail.com',
            'portfolio_url' => 'https://foliofy.me/',
        ]);
        $this->assertDatabaseHas('portfolio_experiences', [
            'title' => 'Instructor Assignment - Kingdom of Saudi Arabia Army',
            'period' => '2021 - 2024',
        ]);
        $this->assertGreaterThan(0, DB::table('portfolio_skills')->count());
        $this->assertGreaterThan(0, DB::table('portfolio_projects')->count());
        $this->assertGreaterThan(0, DB::table('portfolio_experiences')->count());
        $this->assertGreaterThan(0, DB::table('portfolio_achievements')->count());
        $this->assertGreaterThan(0, DB::table('portfolio_education')->count());
        $this->assertGreaterThan(0, DB::table('portfolio_languages')->count());
        $this->assertDatabaseCount('portfolio_testimonials', 0);
    }

    public function test_portfolio_data_seeder_preserves_existing_database_content_when_data_already_exists(): void
    {
        $this->seed(PortfolioDataSeeder::class);

        DB::table('portfolio_meta')->where('id', 1)->update([
            'name' => 'Custom Live Name',
        ]);

        $this->seed(PortfolioDataSeeder::class);

        $this->assertDatabaseHas('portfolio_meta', [
            'id' => 1,
            'name' => 'Custom Live Name',
        ]);
    }

    public function test_contact_messages_are_saved_in_mysql_and_manageable_from_admin(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => 'secret123',
        ]);

        $this->getJson('/api_messages.php?action=get')
            ->assertStatus(401);

        $this->postJson('/api_messages.php?action=save', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Project Inquiry',
            'message' => 'Hello from the contact form.',
        ])->assertOk()->assertJson([
            'success' => true,
            'total' => 1,
        ]);

        $message = ContactMessage::query()->firstOrFail();
        $this->assertSame('john@example.com', $message->email);

        $this->actingAs($admin)
            ->getJson('/api_messages.php?action=get')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $message->id)
            ->assertJsonPath('0.email', 'john@example.com');

        $this->actingAs($admin)
            ->postJson('/api_messages.php?action=delete&id='.$message->id)
            ->assertOk()
            ->assertJson([
                'success' => true,
                'total' => 0,
            ]);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_upload_endpoint_requires_admin_and_returns_the_legacy_json_shape(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => 'secret123',
        ]);

        $this->post('/upload.php', [
            'image' => UploadedFile::fake()->image('avatar.png'),
            'imageKey' => 'hero',
        ])->assertStatus(401);

        $response = $this->actingAs($admin)->post('/upload.php', [
            'image' => UploadedFile::fake()->image('avatar.png'),
            'imageKey' => 'hero',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $uploadedUrl = $response->json('url');

        $this->assertNotNull($uploadedUrl);
        $this->assertStringStartsWith('uploads/', $uploadedUrl);
        $this->assertTrue(File::exists($this->uploadsPath.'/'.basename($uploadedUrl)));
        $this->assertTrue(File::exists($this->profileImagePath));
        $this->assertTrue(File::exists($this->ogImagePath));

        [$width, $height] = getimagesize($this->ogImagePath);

        $this->assertSame(1200, $width);
        $this->assertSame(630, $height);
    }

    public function test_upload_endpoint_allows_admin_cv_pdf_uploads(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => 'secret123',
        ]);

        $response = $this->actingAs($admin)->post('/upload.php', [
            'file' => UploadedFile::fake()->create('resume.pdf', 256, 'application/pdf'),
            'assetKey' => 'resume',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $uploadedUrl = $response->json('url');

        $this->assertNotNull($uploadedUrl);
        $this->assertStringStartsWith('uploads/cv_', $uploadedUrl);
        $this->assertStringEndsWith('.pdf', $uploadedUrl);
        $this->assertTrue(File::exists($this->uploadsPath.'/'.basename($uploadedUrl)));
    }

    public function test_og_image_route_serves_the_generated_social_preview_asset(): void
    {
        $this->createProfileImage(680, 1180);

        $response = $this->get('/og-image.jpg');

        $response->assertOk();
        $this->assertStringContainsString('image/jpeg', (string) $response->headers->get('Content-Type'));
        $this->assertTrue(File::exists($this->ogImagePath));

        [$width, $height] = getimagesize($this->ogImagePath);

        $this->assertSame(1200, $width);
        $this->assertSame(630, $height);
    }

    private function createProfileImage(int $width, int $height): void
    {
        $image = UploadedFile::fake()->image('profile.png', $width, $height);
        File::copy($image->getPathname(), $this->profileImagePath);
    }
}
