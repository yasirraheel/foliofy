<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\PortfolioContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PortfolioApplicationTest extends TestCase
{
    use RefreshDatabase;

    private string $uploadsPath;

    private string $profileImagePath;

    protected function setUp(): void
    {
        parent::setUp();

        $basePath = storage_path('framework/testing/portfolio');
        File::ensureDirectoryExists($basePath);

        $this->uploadsPath = $basePath.'/uploads';
        $this->profileImagePath = $basePath.'/profile.png';

        File::delete($this->profileImagePath);
        File::deleteDirectory($this->uploadsPath);

        config([
            'portfolio.uploads_path' => $this->uploadsPath,
            'portfolio.profile_image_path' => $this->profileImagePath,
        ]);
    }

    protected function tearDown(): void
    {
        File::delete($this->profileImagePath);
        File::deleteDirectory($this->uploadsPath);

        parent::tearDown();
    }

    public function test_portfolio_and_admin_pages_are_served_from_laravel(): void
    {
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

        $this->postJson('/admin/login', ['password' => 'secret123'])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'user' => [
                    'id' => $admin->id,
                    'email' => $admin->email,
                ],
            ]);

        $this->getJson('/admin/bootstrap')
            ->assertOk()
            ->assertJson([
                'authenticated' => true,
                'user' => [
                    'id' => $admin->id,
                    'email' => $admin->email,
                ],
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
                'siteTitle' => 'Updated Portfolio',
            ],
            'contact' => [
                'email' => 'hello@example.com',
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

        $this->assertDatabaseHas('portfolio_contents', ['id' => 1]);
        $this->assertSame('Updated Name', PortfolioContent::query()->findOrFail(1)->data['meta']['name']);
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
    }
}
