<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PortfolioApplicationTest extends TestCase
{
    private string $messagesPath;

    private string $uploadsPath;

    private string $profileImagePath;

    protected function setUp(): void
    {
        parent::setUp();

        $basePath = storage_path('framework/testing/portfolio');
        File::ensureDirectoryExists($basePath);

        $this->messagesPath = $basePath.'/messages.json';
        $this->uploadsPath = $basePath.'/uploads';
        $this->profileImagePath = $basePath.'/profile.png';

        File::delete($this->messagesPath);
        File::delete($this->profileImagePath);
        File::deleteDirectory($this->uploadsPath);

        config([
            'portfolio.messages_path' => $this->messagesPath,
            'portfolio.uploads_path' => $this->uploadsPath,
            'portfolio.profile_image_path' => $this->profileImagePath,
        ]);
    }

    protected function tearDown(): void
    {
        File::delete($this->messagesPath);
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

    public function test_message_endpoint_keeps_legacy_save_get_delete_flow(): void
    {
        $this->getJson('/api_messages.php?action=get')
            ->assertOk()
            ->assertExactJson([]);

        $this->postJson('/api_messages.php?action=save', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Project Inquiry',
            'message' => 'Hello from the contact form.',
        ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'total' => 1,
            ]);

        $this->getJson('/api_messages.php?action=get')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.email', 'john@example.com');

        $this->post('/api_messages.php?action=delete&index=0')
            ->assertOk()
            ->assertJson([
                'success' => true,
                'total' => 0,
            ]);
    }

    public function test_upload_endpoint_returns_the_same_legacy_json_shape(): void
    {
        $response = $this->post('/upload.php', [
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
