<?php

namespace Tests\Feature;

use App\Models\Craft;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkshopMapTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seed the database before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\AdminUserSeeder::class);
        $this->seed(\Database\Seeders\CraftSeeder::class);
        $this->seed(\Database\Seeders\WorkshopSeeder::class);
    }

    // ── Seeder Tests ──────────────────────────────────────────────

    public function test_seeder_creates_exactly_18_workshops(): void
    {
        $this->assertEquals(18, Workshop::count());
    }

    public function test_seeder_preserves_both_duplicate_named_workshops(): void
    {
        // "ورشة الإتحاد لصناعة الصدف الأرابيسك" should exist TWICE
        $count = Workshop::where('name', 'ورشة الإتحاد لصناعة الصدف الأرابيسك')->count();
        $this->assertEquals(2, $count);

        // They should have different slugs
        $slugs = Workshop::where('name', 'ورشة الإتحاد لصناعة الصدف الأرابيسك')
            ->pluck('slug')
            ->toArray();
        $this->assertNotEquals($slugs[0], $slugs[1]);
    }

    public function test_seeder_links_workshops_to_crafts(): void
    {
        $sadafWorkshops = Workshop::where('craft_type', 'الصدف')->get();
        $this->assertEquals(14, $sadafWorkshops->count());

        // At least one should have a craft_id
        $linked = $sadafWorkshops->whereNotNull('craft_id');
        $this->assertTrue($linked->count() > 0);
    }

    // ── Frontend Map Tests ────────────────────────────────────────

    public function test_map_page_loads_successfully(): void
    {
        $response = $this->get(route('map.index'));
        $response->assertStatus(200);
        $response->assertSee('heritageMap');
        $response->assertSee('leaflet');
    }

    public function test_map_page_contains_workshop_data(): void
    {
        $response = $this->get(route('map.index'));
        $response->assertStatus(200);

        // Check that workshop names appear in the JSON data
        $response->assertSee('ورشة اللؤلؤة للصدف');
        $response->assertSee('ورشة احمد خليل');
    }

    public function test_map_page_contains_localized_labels(): void
    {
        $response = $this->get(route('map.index'));
        $response->assertStatus(200);
        $response->assertSee('الحرفة');
        $response->assertSee('المكان');
        $response->assertSee('عرض ملف الورشة');
    }

    // ── Frontend Workshop Show Tests ──────────────────────────────

    public function test_workshop_show_page_loads(): void
    {
        $workshop = Workshop::first();
        $response = $this->get(route('workshops.show', $workshop->slug));
        $response->assertStatus(200);
        $response->assertSee($workshop->name);
        $response->assertSee($workshop->craft_type);
        $response->assertSee($workshop->owner);
    }

    public function test_workshop_show_page_has_breadcrumb(): void
    {
        $workshop = Workshop::first();
        $response = $this->get(route('workshops.show', $workshop->slug));
        $response->assertSee('الخريطة التفاعلية');
        $response->assertSee('الرئيسية');
    }

    public function test_workshop_show_page_has_mini_map(): void
    {
        $workshop = Workshop::first();
        $response = $this->get(route('workshops.show', $workshop->slug));
        $response->assertSee('workshopMiniMap');
    }

    public function test_workshop_show_404_for_invalid_slug(): void
    {
        $response = $this->get('/workshops/nonexistent-workshop-slug');
        $response->assertStatus(404);
    }

    public function test_inactive_workshop_returns_404_on_show(): void
    {
        $workshop = Workshop::first();
        $workshop->update(['is_active' => false]);

        $response = $this->get(route('workshops.show', $workshop->slug));
        $response->assertStatus(404);
    }

    // ── Admin Workshop Tests ──────────────────────────────────────

    public function test_admin_workshops_requires_authentication(): void
    {
        $response = $this->get(route('admin.workshops.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_list_workshops(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->get(route('admin.workshops.index'));
        $response->assertStatus(200);
        $response->assertSee('ورش الحرف التراثية');
    }

    public function test_admin_can_view_create_form(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->get(route('admin.workshops.create'));
        $response->assertStatus(200);
        $response->assertSee('coordinateMap');
    }

    public function test_admin_can_create_workshop(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->post(route('admin.workshops.store'), [
            'name'          => 'ورشة اختبارية جديدة',
            'craft_type'    => 'الصدف',
            'location'      => 'ساقية المنقدي',
            'owner'         => 'أحمد اختبار',
            'workers_count' => '5',
            'phone'         => '01000000000',
            'latitude'      => 30.3800000,
            'longitude'     => 30.8850000,
        ]);

        $response->assertRedirect(route('admin.workshops.index'));
        $this->assertDatabaseHas('workshops', [
            'name'  => 'ورشة اختبارية جديدة',
            'owner' => 'أحمد اختبار',
        ]);
    }

    public function test_admin_can_update_workshop(): void
    {
        $user = User::first();
        $workshop = Workshop::first();

        $response = $this->actingAs($user)->put(route('admin.workshops.update', $workshop), [
            'name'          => 'ورشة معدلة',
            'craft_type'    => $workshop->craft_type,
            'location'      => $workshop->location,
            'owner'         => 'مالك جديد',
            'workers_count' => '10',
            'phone'         => '01111111111',
            'latitude'      => $workshop->latitude,
            'longitude'     => $workshop->longitude,
        ]);

        $response->assertRedirect(route('admin.workshops.index'));
        $workshop->refresh();
        $this->assertEquals('ورشة معدلة', $workshop->name);
        $this->assertEquals('مالك جديد', $workshop->owner);
    }

    public function test_admin_can_delete_workshop(): void
    {
        $user = User::first();
        $workshop = Workshop::latest('id')->first();
        $workshopId = $workshop->id;

        $response = $this->actingAs($user)->delete(route('admin.workshops.destroy', $workshop));

        $response->assertRedirect(route('admin.workshops.index'));
        $this->assertDatabaseMissing('workshops', ['id' => $workshopId]);
    }

    // ── Navigation Links Tests ────────────────────────────────────

    public function test_homepage_links_to_map(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee(route('map.index'));
    }
}
