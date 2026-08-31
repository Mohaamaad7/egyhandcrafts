<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function adminUser(): User
    {
        return User::factory()->create([
            'email' => 'admin@sadat.test',
            'password' => 'password',
        ]);
    }

    public function test_unauthenticated_users_are_redirected_from_admin(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/crafts')->assertRedirect('/login');
    }

    public function test_login_page_renders(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_login_with_valid_credentials(): void
    {
        $this->adminUser();

        $this->post('/login', [
            'email' => 'admin@sadat.test',
            'password' => 'password',
        ])->assertRedirect('/admin');

        $this->assertAuthenticated();
    }

    public function test_login_with_invalid_credentials_is_rejected(): void
    {
        $this->adminUser();

        $this->from('/login')->post('/login', [
            'email' => 'admin@sadat.test',
            'password' => 'wrong-password',
        ])->assertRedirect('/login');

        $this->assertGuest();
    }

    public function test_authenticated_user_can_access_admin(): void
    {
        $this->actingAs($this->adminUser());

        $this->get('/admin')->assertStatus(200);
        $this->get('/admin/crafts')->assertStatus(200);
    }

    public function test_logout(): void
    {
        $this->actingAs($this->adminUser());

        $this->post('/logout')->assertRedirect('/');

        $this->assertGuest();
    }
}
