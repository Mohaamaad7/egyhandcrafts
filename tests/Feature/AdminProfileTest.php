<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function adminUser(): User
    {
        return User::factory()->create([
            'name' => 'Dr. Mahmoud',
            'username' => 'mahmoud',
            'email' => 'mahmoud@sadat.test',
            'password' => 'oldpassword123',
        ]);
    }

    public function test_unauthenticated_user_cannot_access_profile(): void
    {
        $this->get('/admin/profile')->assertRedirect('/login');
    }

    public function test_authenticated_admin_can_view_profile(): void
    {
        $user = $this->adminUser();

        $response = $this->actingAs($user)->get('/admin/profile');

        $response->assertStatus(200);
        $response->assertSee('Dr. Mahmoud');
        $response->assertSee('mahmoud');
        $response->assertSee('البيانات الأساسية للحساب');
        $response->assertSee('الأمان وتغيير كلمة المرور');
    }

    public function test_admin_can_update_profile_info(): void
    {
        $user = $this->adminUser();

        $response = $this->actingAs($user)->put('/admin/profile', [
            'name' => 'Prof. Mahmoud Updated',
            'username' => 'mahmoud_updated',
            'email' => 'mahmoud_new@sadat.test',
        ]);

        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('Prof. Mahmoud Updated', $user->name);
        $this->assertEquals('mahmoud_updated', $user->username);
        $this->assertEquals('mahmoud_new@sadat.test', $user->email);
    }

    public function test_admin_can_save_profile_without_changing_username_or_email(): void
    {
        $user = $this->adminUser();

        // Submit the exact same username and email
        $response = $this->actingAs($user)->put('/admin/profile', [
            'name' => 'Dr. Mahmoud Same',
            'username' => 'mahmoud',
            'email' => 'mahmoud@sadat.test',
        ]);

        $response->assertSessionHas('success');
        $response->assertSessionDoesntHaveErrors();

        $user->refresh();
        $this->assertEquals('Dr. Mahmoud Same', $user->name);
        $this->assertEquals('mahmoud', $user->username);
        $this->assertEquals('mahmoud@sadat.test', $user->email);
    }

    public function test_admin_profile_update_fails_when_username_or_email_belongs_to_another_user(): void
    {
        $user = $this->adminUser();
        $otherUser = User::factory()->create([
            'username' => 'existing_user',
            'email' => 'existing@sadat.test',
        ]);

        $response = $this->actingAs($user)->put('/admin/profile', [
            'name' => 'Dr. Mahmoud Duplicate',
            'username' => 'existing_user',
            'email' => 'existing@sadat.test',
        ]);

        $response->assertSessionHasErrors(['username', 'email']);
    }

    public function test_admin_can_update_password_with_correct_current_password(): void
    {
        $user = $this->adminUser();

        $response = $this->actingAs($user)->put('/admin/profile/password', [
            'current_password' => 'oldpassword123',
            'password' => 'brandnewpassword456',
            'password_confirmation' => 'brandnewpassword456',
        ]);

        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('brandnewpassword456', $user->password));
    }

    public function test_password_update_fails_with_incorrect_current_password(): void
    {
        $user = $this->adminUser();

        $response = $this->actingAs($user)->put('/admin/profile/password', [
            'current_password' => 'wrongcurrentpassword',
            'password' => 'brandnewpassword456',
            'password_confirmation' => 'brandnewpassword456',
        ]);

        $response->assertSessionHasErrors('current_password');

        $user->refresh();
        $this->assertTrue(Hash::check('oldpassword123', $user->password));
    }
}
