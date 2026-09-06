<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPathAndUxRefinementsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::remove('admin_path');
    }

    protected function tearDown(): void
    {
        Setting::remove('admin_path');
        parent::tearDown();
    }

    /**
     * Test default admin path resolution.
     */
    public function test_default_admin_path_is_admin(): void
    {
        $this->assertEquals('admin', admin_path());
    }

    /**
     * Test admin routes respond at default prefix.
     */
    public function test_admin_dashboard_accessible_at_default_prefix(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
    }

    /**
     * Test custom admin path returns 404 on old /admin path.
     */
    public function test_custom_admin_path_serves_routes_and_old_path_returns_404(): void
    {
        putenv('ADMIN_PATH=secret-portal');
        $_ENV['ADMIN_PATH'] = 'secret-portal';
        $this->refreshApplication();
        $this->artisan('migrate');

        try {
            $this->assertEquals('secret-portal', admin_path());

            $admin = User::factory()->create(['role' => 'super_admin']);

            // New prefix serves dashboard
            $newResponse = $this->actingAs($admin)->get('/secret-portal');
            $newResponse->assertStatus(200);

            // Old /admin returns standard 404
            $oldResponse = $this->actingAs($admin)->get('/admin');
            $oldResponse->assertStatus(404);
        } finally {
            putenv('ADMIN_PATH');
            unset($_ENV['ADMIN_PATH']);
            $this->refreshApplication();
        }
    }

    /**
     * Test super admin can update the admin path prefix safely.
     */
    public function test_super_admin_can_update_admin_path(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($superAdmin)->put('/admin/profile/settings', [
            'admin_path' => 'custom-control',
        ]);

        $response->assertRedirect('/custom-control/profile');
        $response->assertSessionHas('success');

        $this->assertEquals('custom-control', Setting::get('admin_path'));
        $this->assertEquals('custom-control', admin_path());
    }

    /**
     * Test non-super admin cannot update the admin path prefix.
     */
    public function test_regular_admin_cannot_update_admin_path(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->put('/admin/profile/settings', [
            'admin_path' => 'hacked-path',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test invalid or reserved admin path values are rejected.
     */
    public function test_reserved_or_invalid_admin_paths_are_rejected(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        // Reserved name 'crafts'
        $res1 = $this->actingAs($superAdmin)->put('/admin/profile/settings', [
            'admin_path' => 'crafts',
        ]);
        $res1->assertSessionHasErrors('admin_path');

        // Invalid characters with spaces and slashes
        $res2 = $this->actingAs($superAdmin)->put('/admin/profile/settings', [
            'admin_path' => 'invalid path / here',
        ]);
        $res2->assertSessionHasErrors('admin_path');
    }

    /**
     * Test job_title column, model accessor, and elimination of hardcoded 'مسؤول توثيق'.
     */
    public function test_job_title_accessor_and_role_label(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'job_title' => null,
        ]);
        $this->assertEquals('مدير النظام', $superAdmin->role_label);

        $regularAdmin = User::factory()->create([
            'role' => 'admin',
            'job_title' => null,
        ]);
        $this->assertEquals('مسؤول نظام', $regularAdmin->role_label);
        $this->assertNotEquals('مسؤول توثيق', $regularAdmin->role_label);

        $customTitleUser = User::factory()->create([
            'role' => 'admin',
            'job_title' => 'باحث توثيق ميداني',
        ]);
        $this->assertEquals('باحث توثيق ميداني', $customTitleUser->role_label);
    }

    /**
     * Test updating job_title via profile edit screen.
     */
    public function test_user_can_update_job_title_in_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'د. محمد حسن',
            'username' => 'mohamed_hassan',
            'job_title' => null,
        ]);

        $response = $this->actingAs($user)->put('/admin/profile', [
            'name' => 'د. محمد حسن',
            'username' => $user->username,
            'email' => $user->email,
            'job_title' => 'خبير الحرف اليدوية',
        ]);

        $response->assertSessionHas('success');
        $user->refresh();
        $this->assertEquals('خبير الحرف اليدوية', $user->job_title);
        $this->assertEquals('خبير الحرف اليدوية', $user->role_label);
    }

    /**
     * Test header displays dynamic job title and dropdown-menu-end RTL alignment.
     */
    public function test_layout_renders_job_title_and_rtl_dropdown_class(): void
    {
        $user = User::factory()->create([
            'role' => 'super_admin',
            'job_title' => 'المشرف العام للمشروع',
        ]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(200);
        // Header contains dynamic job title
        $response->assertSee('المشرف العام للمشروع');
        // Dropdown menu has RTL inward class
        $response->assertSee('dropdown-menu-end');
        $response->assertDontSee('dropdown-menu-start');
    }

    /**
     * Test deduplication of 'معاينة البوابة' links.
     */
    public function test_portal_preview_links_are_deduplicated(): void
    {
        $user = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(200);

        // Header button is present
        $response->assertSee('btn-visit-portal');
        $response->assertSee('معاينة البوابة');

        // Sidebar footer link is removed
        $response->assertDontSee('معاينة البوابة العامة');

        // Dropdown link is removed
        $response->assertDontSee('زيارة البوابة العامة');
    }

    /**
     * Test multi-admin management with job_title in store and update.
     */
    public function test_super_admin_can_create_and_update_admin_with_job_title(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        // Create new admin with job title
        $createResponse = $this->actingAs($superAdmin)->post('/admin/users', [
            'name' => 'سارة أحمد',
            'job_title' => 'مسؤولة العلاقات والإعلام',
            'username' => 'sara_ahmed',
            'email' => 'sara@sadat.test',
            'role' => 'admin',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $createResponse->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'username' => 'sara_ahmed',
            'job_title' => 'مسؤولة العلاقات والإعلام',
        ]);

        $sara = User::where('username', 'sara_ahmed')->firstOrFail();

        // Update Sara's job title
        $updateResponse = $this->actingAs($superAdmin)->put("/admin/users/{$sara->id}", [
            'name' => 'سارة أحمد',
            'job_title' => 'رئيسة فريق التوثيق الميداني',
            'username' => 'sara_ahmed',
            'email' => 'sara@sadat.test',
            'role' => 'admin',
        ]);

        $updateResponse->assertRedirect('/admin/users');
        $sara->refresh();
        $this->assertEquals('رئيسة فريق التوثيق الميداني', $sara->job_title);
    }

    /**
     * Test primary super admin account cannot be deleted.
     */
    public function test_primary_super_admin_account_cannot_be_deleted(): void
    {
        $primaryAdmin = User::factory()->create([
            'id' => 1,
            'username' => 'admin',
            'role' => 'super_admin',
        ]);

        $anotherSuperAdmin = User::factory()->create([
            'id' => 2,
            'username' => 'second_super',
            'role' => 'super_admin',
        ]);

        $response = $this->actingAs($anotherSuperAdmin)->delete("/admin/users/{$primaryAdmin->id}");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => 1, 'username' => 'admin']);
    }

    /**
     * Test regular admin can log in and manage portal content (crafts, workshops, stories).
     */
    public function test_regular_admin_can_access_content_management(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'job_title' => 'مسؤول توثيق ورش',
        ]);

        $this->actingAs($admin)->get('/admin')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/crafts')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/workshops')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/stories')->assertStatus(200);

        // Cannot access team management
        $this->actingAs($admin)->get('/admin/users')->assertStatus(403);
    }
}
