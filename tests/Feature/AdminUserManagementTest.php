<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function superAdmin(): User
    {
        return User::factory()->create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'super@sadat.test',
            'role' => 'super_admin',
            'password' => 'password',
        ]);
    }

    protected function regularAdmin(): User
    {
        return User::factory()->create([
            'name' => 'Regular Admin',
            'username' => 'regularadmin',
            'email' => 'regular@sadat.test',
            'role' => 'admin',
            'password' => 'password',
        ]);
    }

    public function test_unauthenticated_user_cannot_access_user_management(): void
    {
        $this->get('/admin/users')->assertRedirect('/login');
        $this->get('/admin/users/create')->assertRedirect('/login');
    }

    public function test_regular_admin_is_forbidden_from_accessing_user_management(): void
    {
        $admin = $this->regularAdmin();
        $targetUser = User::factory()->create();

        $this->actingAs($admin)->get('/admin/users')->assertStatus(403);
        $this->actingAs($admin)->get('/admin/users/create')->assertStatus(403);
        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Unauthorized Admin',
            'username' => 'unauth_admin',
            'email' => 'unauth@sadat.test',
            'role' => 'admin',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertStatus(403);
        $this->actingAs($admin)->get("/admin/users/{$targetUser->id}/edit")->assertStatus(403);
        $this->actingAs($admin)->put("/admin/users/{$targetUser->id}", [
            'name' => 'Hacked Name',
            'username' => 'hacked_username',
            'email' => 'hacked@sadat.test',
            'role' => 'admin',
        ])->assertStatus(403);
        $this->actingAs($admin)->delete("/admin/users/{$targetUser->id}")->assertStatus(403);
    }

    public function test_regular_admin_can_still_access_and_manage_personal_profile(): void
    {
        $admin = $this->regularAdmin();

        $response = $this->actingAs($admin)->get('/admin/profile');
        $response->assertStatus(200);
        $response->assertSee($admin->name);
        $response->assertSee('regularadmin');

        $updateResponse = $this->actingAs($admin)->put('/admin/profile', [
            'name' => 'Regular Admin Updated',
            'username' => 'regularadmin', // same username
            'email' => 'regular@sadat.test',   // same email
        ]);

        $updateResponse->assertSessionHas('success');
        $this->assertEquals('Regular Admin Updated', $admin->fresh()->name);
    }

    public function test_authenticated_super_admin_can_view_users_list(): void
    {
        $admin = $this->superAdmin();

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee($admin->name);
        $response->assertSee('superadmin');
    }

    public function test_admin_can_create_new_administrator(): void
    {
        $admin = $this->superAdmin();

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Dr. Ahmed Taha',
            'username' => 'ahmed_taha',
            'email' => 'ahmed@sadat.edu.eg',
            'role' => 'admin',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'username' => 'ahmed_taha',
            'email' => 'ahmed@sadat.edu.eg',
            'role' => 'admin',
        ]);

        $createdUser = User::where('username', 'ahmed_taha')->first();
        $this->assertTrue(Hash::check('newpassword123', $createdUser->password));
    }

    public function test_user_creation_fails_if_username_or_email_already_exists(): void
    {
        $admin = $this->superAdmin();

        $response = $this->actingAs($admin)->from('/admin/users/create')->post('/admin/users', [
            'name' => 'Duplicate User',
            'username' => 'superadmin', // duplicate
            'email' => 'other@sadat.test',
            'role' => 'admin',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/admin/users/create');
        $response->assertSessionHasErrors('username');
    }

    public function test_admin_can_update_user_keeping_same_username_and_email(): void
    {
        $admin = $this->superAdmin();
        $targetUser = User::factory()->create([
            'name' => 'Original Name',
            'username' => 'same_username',
            'email' => 'same_email@sadat.test',
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->put("/admin/users/{$targetUser->id}", [
            'name' => 'Updated Name',
            'username' => 'same_username', // unchanged
            'email' => 'same_email@sadat.test', // unchanged
            'role' => 'admin',
        ]);

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');
        $response->assertSessionDoesntHaveErrors();

        $targetUser->refresh();
        $this->assertEquals('Updated Name', $targetUser->name);
        $this->assertEquals('same_username', $targetUser->username);
        $this->assertEquals('same_email@sadat.test', $targetUser->email);
    }

    public function test_admin_can_update_user_details_and_reset_password(): void
    {
        $admin = $this->superAdmin();
        $targetUser = User::factory()->create([
            'username' => 'field_officer',
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->put("/admin/users/{$targetUser->id}", [
            'name' => 'Updated Officer Name',
            'username' => 'field_officer_updated',
            'email' => $targetUser->email,
            'role' => 'admin',
            'password' => 'new_secret_password',
            'password_confirmation' => 'new_secret_password',
        ]);

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');

        $targetUser->refresh();
        $this->assertEquals('field_officer_updated', $targetUser->username);
        $this->assertEquals('Updated Officer Name', $targetUser->name);
        $this->assertTrue(Hash::check('new_secret_password', $targetUser->password));
    }

    public function test_admin_cannot_delete_their_own_account(): void
    {
        $admin = $this->superAdmin();

        $response = $this->actingAs($admin)->delete("/admin/users/{$admin->id}");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_last_super_admin_cannot_be_deleted(): void
    {
        $superAdmin1 = $this->superAdmin();
        $superAdmin2 = User::factory()->create(['role' => 'super_admin']);

        // superAdmin1 deletes superAdmin2 -> now only superAdmin1 is left
        $this->actingAs($superAdmin1)->delete("/admin/users/{$superAdmin2->id}");
        $this->assertDatabaseMissing('users', ['id' => $superAdmin2->id]);
        $this->assertEquals(1, User::where('role', 'super_admin')->count());

        // Now superAdmin1 attempts to delete self (which is last super admin)
        $response = $this->actingAs($superAdmin1)->delete("/admin/users/{$superAdmin1->id}");
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $superAdmin1->id]);
    }

    public function test_admin_can_delete_another_user(): void
    {
        $admin = $this->superAdmin();
        $targetUser = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->delete("/admin/users/{$targetUser->id}");

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);
    }

    public function test_check_availability_requires_authentication(): void
    {
        $this->postJson('/admin/check-availability', [
            'field' => 'username',
            'value' => 'someuser',
        ])->assertStatus(401);
    }

    public function test_check_availability_detects_duplicate_and_available_username(): void
    {
        $admin = $this->superAdmin();
        User::factory()->create(['username' => 'existing_user']);

        // Duplicate username
        $response = $this->actingAs($admin)->postJson('/admin/check-availability', [
            'field' => 'username',
            'value' => 'existing_user',
        ]);
        $response->assertOk()->assertJson([
            'available' => false,
        ]);

        // Duplicate username ignored for current user
        $user = User::where('username', 'existing_user')->first();
        $response = $this->actingAs($admin)->postJson('/admin/check-availability', [
            'field' => 'username',
            'value' => 'existing_user',
            'ignore_id' => $user->id,
        ]);
        $response->assertOk()->assertJson([
            'available' => true,
        ]);

        // Invalid username format
        $response = $this->actingAs($admin)->postJson('/admin/check-availability', [
            'field' => 'username',
            'value' => 'invalid username with spaces',
        ]);
        $response->assertOk()->assertJson([
            'available' => false,
        ]);

        // Fresh available username
        $response = $this->actingAs($admin)->postJson('/admin/check-availability', [
            'field' => 'username',
            'value' => 'fresh_unique_user',
        ]);
        $response->assertOk()->assertJson([
            'available' => true,
        ]);
    }

    public function test_check_availability_detects_duplicate_and_available_email(): void
    {
        $admin = $this->superAdmin();
        User::factory()->create(['email' => 'duplicate@sadat.test']);

        // Duplicate email
        $response = $this->actingAs($admin)->postJson('/admin/check-availability', [
            'field' => 'email',
            'value' => 'duplicate@sadat.test',
        ]);
        $response->assertOk()->assertJson([
            'available' => false,
        ]);

        // Duplicate email ignored for same user ID
        $user = User::where('email', 'duplicate@sadat.test')->first();
        $response = $this->actingAs($admin)->postJson('/admin/check-availability', [
            'field' => 'email',
            'value' => 'duplicate@sadat.test',
            'ignore_id' => $user->id,
        ]);
        $response->assertOk()->assertJson([
            'available' => true,
        ]);

        // Invalid email format
        $response = $this->actingAs($admin)->postJson('/admin/check-availability', [
            'field' => 'email',
            'value' => 'not-an-email',
        ]);
        $response->assertOk()->assertJson([
            'available' => false,
        ]);

        // Fresh available email
        $response = $this->actingAs($admin)->postJson('/admin/check-availability', [
            'field' => 'email',
            'value' => 'brand_new_person@sadat.test',
        ]);
        $response->assertOk()->assertJson([
            'available' => true,
        ]);
    }
}
