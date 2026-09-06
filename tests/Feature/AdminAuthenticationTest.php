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
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@sadat.test',
            'role' => 'super_admin',
            'password' => 'password',
        ]);
    }

    public function test_unauthenticated_users_are_redirected_from_admin(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/crafts')->assertRedirect('/login');
        $this->get('/admin/workshops')->assertRedirect('/login');
        $this->get('/admin/stories')->assertRedirect('/login');
        $this->get('/admin/users')->assertRedirect('/login');
        $this->get('/admin/profile')->assertRedirect('/login');
    }

    public function test_login_page_renders_with_clean_inputs_and_no_hardcoded_placeholders(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertDontSee('placeholder="admin@sadat.test"', false);
        $response->assertSee('تذكرني');
        $response->assertSee('نسيت كلمة المرور؟');
        $response->assertSee('بوابة الإدارة والتوثيق الأكاديمي');
    }

    public function test_login_with_valid_email_credentials(): void
    {
        $this->adminUser();

        $this->post('/login', [
            'email' => 'admin@sadat.test',
            'password' => 'password',
        ])->assertRedirect('/admin');

        $this->assertAuthenticated();
    }

    public function test_login_with_valid_username_credentials(): void
    {
        $this->adminUser();

        $this->post('/login', [
            'email' => 'admin',
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

    public function test_login_with_invalid_username_is_rejected(): void
    {
        $this->adminUser();

        $this->from('/login')->post('/login', [
            'email' => 'wrong_username',
            'password' => 'password',
        ])->assertRedirect('/login');

        $this->assertGuest();
    }

    public function test_authenticated_user_can_access_admin_dashboard_and_sections(): void
    {
        $this->actingAs($this->adminUser());

        $this->get('/admin')->assertStatus(200);
        $this->get('/admin/crafts')->assertStatus(200);
        $this->get('/admin/workshops')->assertStatus(200);
        $this->get('/admin/stories')->assertStatus(200);
        $this->get('/admin/users')->assertStatus(200);
        $this->get('/admin/profile')->assertStatus(200);
    }

    public function test_logout(): void
    {
        $this->actingAs($this->adminUser());

        $this->post('/logout')->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_forgot_password_page_renders(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
        $response->assertSee('استعادة كلمة المرور');
        $response->assertSee('البريد الإلكتروني');
    }

    public function test_forgot_password_submission_handles_email_cleanly_without_500(): void
    {
        $this->adminUser();

        $response = $this->post('/forgot-password', [
            'email' => 'admin@sadat.test',
        ]);

        $response->assertSessionHas('status');
        $this->assertNotEquals(500, $response->getStatusCode());
    }

    public function test_forgot_password_gracefully_handles_mailer_transport_exception(): void
    {
        $user = $this->adminUser();

        \Illuminate\Support\Facades\Notification::shouldReceive('send')
            ->andThrow(new \Symfony\Component\Mailer\Exception\TransportException('SMTP Connection refused on port 2525'));

        $response = $this->from('/forgot-password')->post('/forgot-password', [
            'email' => 'admin@sadat.test',
        ]);

        $response->assertRedirect('/forgot-password');
        $response->assertSessionHas('status', 'تم استلام طلبك. نظراً لعدم ربط خادم البريد في بيئة العمل الحالية، يُرجى مراجعة مدير النظام لإعادة تعيين كلمة المرور يدوياً.');
    }
}
