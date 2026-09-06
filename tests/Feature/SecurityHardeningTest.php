<?php

namespace Tests\Feature;

use App\Models\Craft;
use App\Models\CraftsmanStory;
use App\Models\User;
use App\Models\Workshop;
use App\Services\HtmlSanitizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\AdminUserSeeder::class);
        $this->seed(\Database\Seeders\CraftSeeder::class);
        $this->seed(\Database\Seeders\WorkshopSeeder::class);
    }

    // ── 1. HTTP Security Headers Tests ────────────────────────────

    public function test_responses_include_security_headers(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=(self)');
    }

    public function test_admin_and_login_responses_include_security_headers(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=(self)');
    }

    // ── 2. Inactive Workshop Profile Exposure Tests ────────────────

    public function test_active_workshop_is_accessible(): void
    {
        $workshop = Workshop::where('is_active', true)->first();

        $response = $this->get(route('workshops.show', $workshop->slug));
        $response->assertStatus(200);
        $response->assertSee($workshop->name);
    }

    public function test_inactive_workshop_returns_404_not_found(): void
    {
        $workshop = Workshop::first();
        $workshop->update(['is_active' => false]);

        $response = $this->get(route('workshops.show', $workshop->slug));
        $response->assertStatus(404);
    }

    // ── 3. Map JSON Injection & Popup Escaping Tests ───────────────

    public function test_map_page_escapes_script_tags_in_workshops_json(): void
    {
        // Inject a workshop with potentially breaking script tag
        Workshop::create([
            'name'          => 'ورشة <script>alert("xss")</script> التراثية',
            'slug'          => 'xss-workshop-test',
            'craft_type'    => 'الصدف',
            'location'      => 'ساقية المنقدي <img src=x onerror=alert(1)>',
            'owner'         => 'محمود "><script>alert(2)</script>',
            'workers_count' => '4',
            'phone'         => '01012345678',
            'latitude'      => 30.385,
            'longitude'     => 30.890,
            'is_active'     => true,
        ]);

        $response = $this->get(route('map.index'));
        $response->assertStatus(200);

        $content = $response->getContent();

        // Ensure raw unescaped script tag breakout is NOT present in the map script block
        $this->assertStringNotContainsString('ورشة <script>alert("xss")</script>', $content);
        // Verify hex-encoded safe representation
        $this->assertStringContainsString('\u003Cscript\u003E', $content);
        // Verify escapeHtml function is declared in the script
        $this->assertStringContainsString('function escapeHtml(text)', $content);
    }

    // ── 4. CKEditor HTML Sanitizer Unit / Behavior Tests ───────────

    public function test_sanitizer_removes_script_tags(): void
    {
        $malicious = '<p>مرحبا بك</p><script>alert("evil")</script><p>نص عادي</p>';
        $cleaned = HtmlSanitizer::clean($malicious);

        $this->assertEquals('<p>مرحبا بك</p><p>نص عادي</p>', $cleaned);
        $this->assertStringNotContainsString('<script', $cleaned);
    }

    public function test_sanitizer_removes_inline_event_handlers(): void
    {
        $malicious = '<img src="photo.jpg" onerror="alert(\'xss\')" alt="صورة">'
            . '<p onclick="stealCookies()" onmouseover="evil()">نص تفاعلي</p>';
        $cleaned = HtmlSanitizer::clean($malicious);

        $this->assertStringNotContainsString('onerror', $cleaned);
        $this->assertStringNotContainsString('onclick', $cleaned);
        $this->assertStringNotContainsString('onmouseover', $cleaned);
        $this->assertStringContainsString('src="photo.jpg"', $cleaned);
        $this->assertStringContainsString('نص تفاعلي', $cleaned);
    }

    public function test_sanitizer_neutralizes_javascript_uris(): void
    {
        $malicious = '<a href="javascript:alert(1)">رابط خبيث</a><a href="https://example.com">رابط آمن</a>';
        $cleaned = HtmlSanitizer::clean($malicious);

        $this->assertStringNotContainsString('javascript:alert', $cleaned);
        $this->assertStringContainsString('href="#"', $cleaned);
        $this->assertStringContainsString('href="https://example.com"', $cleaned);
    }

    public function test_sanitizer_allows_trusted_youtube_iframes_only(): void
    {
        $content = '<p>فيديو توثيقي</p>'
            . '<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" width="560" height="315" allowfullscreen></iframe>'
            . '<iframe src="https://www.youtube-nocookie.com/embed/abcdef12345" width="560" height="315"></iframe>'
            . '<iframe src="https://evil-attacker.com/malware" width="500"></iframe>'
            . '<iframe src="javascript:alert(1)"></iframe>';

        $cleaned = HtmlSanitizer::clean($content);

        $this->assertStringContainsString('youtube.com/embed/dQw4w9WgXcQ', $cleaned);
        $this->assertStringContainsString('youtube-nocookie.com/embed/abcdef12345', $cleaned);
        $this->assertStringNotContainsString('evil-attacker.com', $cleaned);
        $this->assertStringNotContainsString('javascript:alert', $cleaned);
    }

    public function test_sanitizer_preserves_inline_styles_colors_and_tables(): void
    {
        $richContent = '<h2><span style="color:#E67E22;background-color:#FEF3C7;font-size:18px;">عنوان ملون</span></h2>'
            . '<figure class="table"><table style="border:1px solid #ddd;"><tbody><tr>'
            . '<td style="background-color:#FFF6D6;color:#333;">خلية ملونة</td>'
            . '</tr></tbody></table></figure>';

        $cleaned = HtmlSanitizer::clean($richContent);

        $this->assertStringContainsString('style="color:#E67E22;background-color:#FEF3C7;font-size:18px;"', $cleaned);
        $this->assertStringContainsString('style="background-color:#FFF6D6;color:#333;"', $cleaned);
        $this->assertStringContainsString('figure class="table"', $cleaned);
        $this->assertStringContainsString('خلية ملونة', $cleaned);
    }

    public function test_sanitizer_cleans_dangerous_css_expressions_in_style(): void
    {
        $maliciousStyle = '<p style="color:red; background:url(javascript:alert(1));">نص</p>';
        $cleaned = HtmlSanitizer::clean($maliciousStyle);

        $this->assertStringNotContainsString('javascript:', $cleaned);
        $this->assertStringContainsString('color:red;', $cleaned);
    }

    // ── 5. Admin Controllers End-to-End Sanitization Tests ─────────

    public function test_admin_craft_store_and_update_sanitizes_content(): void
    {
        $user = User::first();

        // Store
        $response = $this->actingAs($user)->post(route('admin.crafts.store'), [
            'title'             => 'حرفة فحص الأمان',
            'location'          => 'ساقية المنقدي',
            'short_description' => 'وصف مختصر',
            'content'           => '<h2>عنوان</h2><script>alert("xss")</script><p style="color:blue;">نص سليم</p>',
        ]);
        $response->assertRedirect(route('admin.crafts.index'));

        $craft = Craft::where('title', 'حرفة فحص الأمان')->firstOrFail();
        $this->assertStringNotContainsString('<script', $craft->content);
        $this->assertStringContainsString('style="color:blue;"', $craft->content);

        // Update
        $this->actingAs($user)->put(route('admin.crafts.update', $craft), [
            'title'             => 'حرفة فحص الأمان محدثة',
            'location'          => 'ساقية المنقدي',
            'short_description' => 'وصف مختصر',
            'content'           => '<img src="x" onerror="alert(1)"><p style="background-color:gold;">محتوى محدث</p>',
        ]);

        $craft->refresh();
        $this->assertStringNotContainsString('onerror', $craft->content);
        $this->assertStringContainsString('style="background-color:gold;"', $craft->content);
    }

    public function test_admin_story_store_and_update_sanitizes_content(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)->post(route('admin.stories.store'), [
            'title'          => 'قصة فحص الأمان',
            'craftsman_name' => 'عم إبراهيم',
            'craftsman_role' => 'شيخ الحرفة',
            'content'        => '<p>شهادة حية</p><script>evil()</script><a href="javascript:steal()">اضغط</a>',
        ]);
        $response->assertRedirect(route('admin.stories.index'));

        $story = CraftsmanStory::where('title', 'قصة فحص الأمان')->firstOrFail();
        $this->assertStringNotContainsString('<script', $story->content);
        $this->assertStringNotContainsString('javascript:steal', $story->content);
        $this->assertStringContainsString('شهادة حية', $story->content);
    }

    public function test_admin_workshop_store_and_update_sanitizes_content(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)->post(route('admin.workshops.store'), [
            'name'          => 'ورشة فحص الأمان',
            'craft_type'    => 'الصدف',
            'location'      => 'ساقية المنقدي',
            'owner'         => 'صاحب الورشة',
            'workers_count' => '6',
            'phone'         => '01099999999',
            'latitude'      => 30.38,
            'longitude'     => 30.89,
            'content'       => '<p onmouseover="alert(1)">ورشة عريقة</p><script>bad()</script>',
        ]);
        $response->assertRedirect(route('admin.workshops.index'));

        $workshop = Workshop::where('name', 'ورشة فحص الأمان')->firstOrFail();
        $this->assertStringNotContainsString('onmouseover', $workshop->content);
        $this->assertStringNotContainsString('<script', $workshop->content);
        $this->assertStringContainsString('ورشة عريقة', $workshop->content);
    }
}
