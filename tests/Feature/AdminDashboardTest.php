<?php

namespace Tests\Feature;

use App\Models\Craft;
use App\Models\CraftsmanStory;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function adminUser(): User
    {
        return User::factory()->create([
            'name' => 'Dr. Admin',
            'username' => 'admin_doc',
            'email' => 'admin@sadat.test',
            'role' => 'super_admin',
        ]);
    }

    public function test_dashboard_renders_live_kpi_metrics_and_quick_actions(): void
    {
        $admin = $this->adminUser();

        // Create sample records
        Craft::create([
            'title' => 'حرفة السجاد اليدوي',
            'slug' => 'carpet-craft',
            'short_description' => 'وصف حرفة السجاد',
            'content' => '<p>محتوى الحرفة</p>',
            'location' => 'ساقية أبو شعرة',
        ]);

        Workshop::create([
            'name' => 'ورشة السجاد الأصيل',
            'slug' => 'authentic-carpet-workshop',
            'craft_type' => 'سجاد يدوياً',
            'location' => 'ساقية أبو شعرة',
            'owner' => 'الحاج أحمد',
            'phone' => '01012345678',
            'latitude' => 30.345,
            'longitude' => 31.012,
            'workers_count' => 12,
            'is_active' => true,
        ]);

        CraftsmanStory::create([
            'title' => 'شهادة الأسطى حسن',
            'slug' => 'hassan-story',
            'craftsman_name' => 'الأسطى حسن التراثي',
            'craftsman_role' => 'حرفي أقدم',
            'content' => '<p>قصة كفاح وتراث</p>',
            'is_published' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('لوحة المؤشرات والقيادة الميدانية');
        $response->assertSee('الحرف التراثية الموثقة');
        $response->assertSee('ورش العمل والمواقع');
        $response->assertSee('شهادات وقصص الحرفيين');
        $response->assertSee('فريق العمل والمسؤولين');

        // Recent items
        $response->assertSee('ورشة السجاد الأصيل');
        $response->assertSee('الأسطى حسن التراثي');

        // Quick actions
        $response->assertSee(route('admin.crafts.create'));
        $response->assertSee(route('admin.workshops.create'));
        $response->assertSee(route('admin.stories.create'));
        $response->assertSee(route('admin.users.create'));

        // Visit Portal link
        $response->assertSee('معاينة البوابة');
    }
}
