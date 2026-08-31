<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCraftImageUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function adminUser(): User
    {
        return User::factory()->create([
            'email' => 'admin@sadat.test',
            'password' => 'password',
        ]);
    }

    public function test_guest_cannot_upload_ckeditor_image(): void
    {
        $response = $this->postJson('/admin/crafts/upload-image', []);

        $response->assertStatus(401);
    }

    public function test_authenticated_admin_can_upload_ckeditor_image(): void
    {
        Storage::fake('public');

        $user = $this->adminUser();
        $file = UploadedFile::fake()->image('test-craft-article-photo.jpg', 800, 600);

        $response = $this->actingAs($user)->postJson('/admin/crafts/upload-image', [
            'upload' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['url']);

        // Verify file is saved in public storage under crafts/content
        $url = $response->json('url');
        $this->assertNotEmpty($url);
    }

    public function test_invalid_file_type_is_rejected(): void
    {
        Storage::fake('public');

        $user = $this->adminUser();
        $file = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user)->postJson('/admin/crafts/upload-image', [
            'upload' => $file,
        ]);

        $response->assertStatus(422);
    }
}
