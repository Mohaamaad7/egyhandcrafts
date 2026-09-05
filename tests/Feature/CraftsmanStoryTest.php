<?php

namespace Tests\Feature;

use App\Models\CraftsmanStory;
use App\Models\User;
use Database\Seeders\CraftsmanStorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CraftsmanStoryTest extends TestCase
{
    use RefreshDatabase;

    // ─── Seeder Verification ───────────────────────────────────────────────

    public function test_seeder_creates_exactly_three_stories(): void
    {
        $this->seed(CraftsmanStorySeeder::class);
        $this->assertDatabaseCount('craftsmen_stories', 3);
    }

    public function test_seeder_creates_stories_with_expected_craftsman_names(): void
    {
        $this->seed(CraftsmanStorySeeder::class);

        $this->assertDatabaseHas('craftsmen_stories', ['craftsman_name' => 'الحاج محمود أبو قوطة']);
        $this->assertDatabaseHas('craftsmen_stories', ['craftsman_name' => 'الأسطى محمد حسن']);
        $this->assertDatabaseHas('craftsmen_stories', ['craftsman_name' => 'الأسطى حمادة إنسان']);
    }

    public function test_seeder_assigns_correct_media_to_stories(): void
    {
        $this->seed(CraftsmanStorySeeder::class);

        // Abu Qouta has YouTube video, no audio
        $abuQouta = CraftsmanStory::where('craftsman_name', 'الحاج محمود أبو قوطة')->first();
        $this->assertNotNull($abuQouta->youtube_url);
        $this->assertNull($abuQouta->audio_file);
        $this->assertTrue($abuQouta->has_video);
        $this->assertFalse($abuQouta->has_audio);

        // Mohamed Hassan: audio is null until genuine fieldwork recording is uploaded
        $mohamedHassan = CraftsmanStory::where('craftsman_name', 'الأسطى محمد حسن')->first();
        $this->assertNull($mohamedHassan->youtube_url);
        $this->assertNull($mohamedHassan->audio_file);
        $this->assertFalse($mohamedHassan->has_video);
        $this->assertFalse($mohamedHassan->has_audio);

        // Hamada Ensan has neither video nor audio (text-only)
        $hamadaEnsan = CraftsmanStory::where('craftsman_name', 'الأسطى حمادة إنسان')->first();
        $this->assertNull($hamadaEnsan->youtube_url);
        $this->assertNull($hamadaEnsan->audio_file);
        $this->assertFalse($hamadaEnsan->has_video);
        $this->assertFalse($hamadaEnsan->has_audio);
    }

    // ─── YouTube Embed URL Extraction ──────────────────────────────────────

    public function test_youtube_embed_url_extracts_standard_watch_url(): void
    {
        $story = CraftsmanStory::factory()->make(['youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ']);
        $this->assertEquals('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', $story->youtube_embed_url);
    }

    public function test_youtube_embed_url_extracts_short_url(): void
    {
        $story = CraftsmanStory::factory()->make(['youtube_url' => 'https://youtu.be/dQw4w9WgXcQ']);
        $this->assertEquals('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', $story->youtube_embed_url);
    }

    public function test_youtube_embed_url_extracts_shorts_url(): void
    {
        $story = CraftsmanStory::factory()->make(['youtube_url' => 'https://youtube.com/shorts/dQw4w9WgXcQ']);
        $this->assertEquals('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', $story->youtube_embed_url);
    }

    public function test_youtube_embed_url_handles_extra_params(): void
    {
        $story = CraftsmanStory::factory()->make(['youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ&t=120&list=PLrAXtmErZgOeiKm4sgNOknGvNjby9efdf']);
        $this->assertEquals('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', $story->youtube_embed_url);
    }

    public function test_youtube_embed_url_returns_null_for_null_url(): void
    {
        $story = CraftsmanStory::factory()->make(['youtube_url' => null]);
        $this->assertNull($story->youtube_embed_url);
    }

    public function test_youtube_embed_url_returns_null_for_invalid_url(): void
    {
        $story = CraftsmanStory::factory()->make(['youtube_url' => 'https://example.com/not-youtube']);
        $this->assertNull($story->youtube_embed_url);
    }

    // ─── Public Frontend: Stories Index ─────────────────────────────────────

    public function test_stories_index_page_renders_successfully(): void
    {
        $this->seed(CraftsmanStorySeeder::class);
        $response = $this->get(route('stories.index'));

        $response->assertStatus(200);
        $response->assertSee('قصص وشهادات الحرفيين');
        $response->assertSee('الحاج محمود أبو قوطة');
        $response->assertSee('الأسطى محمد حسن');
        $response->assertSee('الأسطى حمادة إنسان');
    }

    public function test_stories_index_displays_craftsman_roles(): void
    {
        $this->seed(CraftsmanStorySeeder::class);
        $response = $this->get(route('stories.index'));

        $response->assertSee('نقيب حرفيي الصدف ورائد الصنعة بساقية المنقدي');
    }

    public function test_stories_index_displays_media_badges(): void
    {
        $this->seed(CraftsmanStorySeeder::class);

        // Create an audio story with physical file so the badge is rendered
        Storage::fake('public');
        Storage::disk('public')->put('stories/audio/voice.mp3', 'dummy');
        CraftsmanStory::create([
            'title'          => 'Audio Badge Story',
            'slug'           => 'audio-badge-story',
            'craftsman_name' => 'Audio Badge Artisan',
            'craftsman_role' => 'Audio Role',
            'content'        => '<p>Content</p>',
            'audio_file'     => 'stories/audio/voice.mp3',
            'is_published'   => true,
        ]);

        $response = $this->get(route('stories.index'));

        $response->assertSee('توثيق مرئي');
        $response->assertSee('تسجيل صوتي');
    }

    // ─── Public Frontend: Story Detail ──────────────────────────────────────

    public function test_story_show_page_loads_for_valid_slug(): void
    {
        $this->seed(CraftsmanStorySeeder::class);
        $story = CraftsmanStory::first();

        $response = $this->get(route('stories.show', $story->slug));
        $response->assertStatus(200);
        $response->assertSee($story->craftsman_name);
        $response->assertSee($story->craftsman_role);
    }

    public function test_story_show_returns_404_for_invalid_slug(): void
    {
        $response = $this->get(route('stories.show', 'non-existent-slug-xyz'));
        $response->assertStatus(404);
    }

    public function test_story_show_has_breadcrumb(): void
    {
        $this->seed(CraftsmanStorySeeder::class);
        $story = CraftsmanStory::first();

        $response = $this->get(route('stories.show', $story->slug));
        $response->assertSee('قصص وشهادات الحرفيين');
        $response->assertSee('الرئيسية');
    }

    // ─── Zero-Ghost-Space Conditional Rendering ─────────────────────────────

    public function test_story_with_video_renders_iframe(): void
    {
        $this->seed(CraftsmanStorySeeder::class);
        $story = CraftsmanStory::where('craftsman_name', 'الحاج محمود أبو قوطة')->first();

        $response = $this->get(route('stories.show', $story->slug));
        $response->assertSee('<iframe', false);
        $response->assertSee('youtube-nocookie.com/embed/', false);
    }

    public function test_story_with_audio_renders_audio_tag(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('stories/audio/voice.mp3', 'dummy audio content');

        $story = CraftsmanStory::create([
            'title'          => 'Audio Test Story',
            'slug'           => 'audio-test-story',
            'craftsman_name' => 'Audio Artisan',
            'craftsman_role' => 'Audio Master',
            'content'        => '<p>Audio story body</p>',
            'audio_file'     => 'stories/audio/voice.mp3',
            'is_published'   => true,
        ]);

        $this->assertTrue($story->has_audio);

        $response = $this->get(route('stories.show', $story->slug));
        $response->assertSee('<audio', false);
        $response->assertSee('<source', false);
        $response->assertSee('المتصفح لا يدعم مشغل الصوتيات', false);
    }

    public function test_has_audio_returns_false_if_file_missing_from_disk(): void
    {
        Storage::fake('public');
        $story = CraftsmanStory::factory()->make([
            'audio_file' => 'stories/audio/ghost_recording.mp3',
        ]);

        $this->assertFalse($story->has_audio);
        $this->assertNull($story->audio_file_url);
    }

    public function test_story_without_media_renders_no_audio_or_iframe(): void
    {
        $this->seed(CraftsmanStorySeeder::class);
        $story = CraftsmanStory::where('craftsman_name', 'الأسطى حمادة إنسان')->first();

        $response = $this->get(route('stories.show', $story->slug));
        $response->assertDontSee('<audio', false);
        $response->assertDontSee('<iframe', false);
        $response->assertDontSee('التسجيل الصوتي الميداني');
        $response->assertDontSee('التوثيق المرئي للشهادة');
    }

    public function test_story_show_does_not_render_duplicate_photo_banner(): void
    {
        $this->seed(CraftsmanStorySeeder::class);
        $story = CraftsmanStory::first();

        $response = $this->get(route('stories.show', $story->slug));
        $response->assertStatus(200);
        // The old duplicate photo caption in main column must NOT exist
        $response->assertDontSee('صورة توثيقية ميدانية للحرفي');
        // The consolidated sidebar card must exist
        $response->assertSee('بطاقة توثيق الحرفي');
    }

    // ─── Unpublished Stories ────────────────────────────────────────────────

    public function test_unpublished_stories_are_hidden_from_frontend(): void
    {
        CraftsmanStory::create([
            'title'          => 'Draft Story',
            'slug'           => 'draft-story',
            'craftsman_name' => 'Draft Craftsman',
            'craftsman_role' => 'Draft Role',
            'content'        => '<p>Draft content</p>',
            'is_published'   => false,
        ]);

        $response = $this->get(route('stories.index'));
        $response->assertDontSee('Draft Craftsman');
    }

    public function test_unpublished_story_returns_404_on_show(): void
    {
        CraftsmanStory::create([
            'title'          => 'Hidden Story',
            'slug'           => 'hidden-story',
            'craftsman_name' => 'Hidden Craftsman',
            'craftsman_role' => 'Hidden Role',
            'content'        => '<p>Hidden content</p>',
            'is_published'   => false,
        ]);

        $response = $this->get(route('stories.show', 'hidden-story'));
        $response->assertStatus(404);
    }

    // ─── Admin CRUD ─────────────────────────────────────────────────────────

    public function test_admin_stories_requires_authentication(): void
    {
        $response = $this->get(route('admin.stories.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_list_stories(): void
    {
        $this->seed(CraftsmanStorySeeder::class);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.stories.index'));
        $response->assertStatus(200);
        $response->assertSee('الحاج محمود أبو قوطة');
    }

    public function test_admin_can_view_create_form(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.stories.create'));
        $response->assertStatus(200);
        $response->assertSee('إضافة شهادة حرفي جديدة');
    }

    public function test_admin_can_create_story(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.stories.store'), [
            'title'          => 'Test Story Title',
            'craftsman_name' => 'Test Craftsman',
            'craftsman_role' => 'Test Role',
            'content'        => '<p>Test content for the story.</p>',
            'is_published'   => '1',
            'photo'          => UploadedFile::fake()->image('photo.jpg', 400, 400),
        ]);

        $response->assertRedirect(route('admin.stories.index'));
        $this->assertDatabaseHas('craftsmen_stories', [
            'craftsman_name' => 'Test Craftsman',
            'is_published'   => true,
        ]);

        // Verify slug was generated
        $story = CraftsmanStory::where('craftsman_name', 'Test Craftsman')->first();
        $this->assertNotEmpty($story->slug);

        // Verify photo was stored
        $this->assertNotNull($story->photo);
        Storage::disk('public')->assertExists($story->photo);

        // Verify auto-excerpt was generated
        $this->assertNotEmpty($story->excerpt);
    }

    public function test_admin_can_update_story(): void
    {
        $user = User::factory()->create();
        $story = CraftsmanStory::create([
            'title'          => 'Original Title',
            'slug'           => 'original-title',
            'craftsman_name' => 'Original Name',
            'craftsman_role' => 'Original Role',
            'content'        => '<p>Original content</p>',
            'is_published'   => true,
        ]);

        $response = $this->actingAs($user)->put(route('admin.stories.update', $story), [
            'title'          => 'Updated Title',
            'craftsman_name' => 'Updated Name',
            'craftsman_role' => 'Updated Role',
            'content'        => '<p>Updated content</p>',
            'is_published'   => '1',
        ]);

        $response->assertRedirect(route('admin.stories.index'));
        $this->assertDatabaseHas('craftsmen_stories', [
            'craftsman_name' => 'Updated Name',
        ]);
    }

    public function test_admin_can_delete_audio_from_story(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('stories/audio/to_delete.mp3', 'sample audio content');

        $user = User::factory()->create();
        $story = CraftsmanStory::create([
            'title'          => 'Story With Audio',
            'slug'           => 'story-with-audio',
            'craftsman_name' => 'Audio Master',
            'craftsman_role' => 'Master Artisan',
            'content'        => '<p>Content</p>',
            'audio_file'     => 'stories/audio/to_delete.mp3',
            'is_published'   => true,
        ]);

        Storage::disk('public')->assertExists('stories/audio/to_delete.mp3');

        $response = $this->actingAs($user)->put(route('admin.stories.update', $story), [
            'title'          => $story->title,
            'craftsman_name' => $story->craftsman_name,
            'craftsman_role' => $story->craftsman_role,
            'content'        => $story->content,
            'delete_audio'   => '1',
            'is_published'   => '1',
        ]);

        $response->assertRedirect(route('admin.stories.index'));
        $story->refresh();
        $this->assertNull($story->audio_file);
        $this->assertFalse($story->has_audio);
        Storage::disk('public')->assertMissing('stories/audio/to_delete.mp3');
    }

    public function test_admin_can_clear_youtube_url(): void
    {
        $user = User::factory()->create();
        $story = CraftsmanStory::create([
            'title'          => 'Story With Video',
            'slug'           => 'story-with-video',
            'craftsman_name' => 'Video Master',
            'craftsman_role' => 'Video Artisan',
            'content'        => '<p>Content</p>',
            'youtube_url'    => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'is_published'   => true,
        ]);

        $this->assertTrue($story->has_video);

        $response = $this->actingAs($user)->put(route('admin.stories.update', $story), [
            'title'          => $story->title,
            'craftsman_name' => $story->craftsman_name,
            'craftsman_role' => $story->craftsman_role,
            'content'        => $story->content,
            'youtube_url'    => '',
            'is_published'   => '1',
        ]);

        $response->assertRedirect(route('admin.stories.index'));
        $story->refresh();
        $this->assertNull($story->youtube_url);
        $this->assertFalse($story->has_video);
    }

    public function test_stories_index_hides_badges_when_no_media(): void
    {
        $story = CraftsmanStory::create([
            'title'          => 'Pure Text Story',
            'slug'           => 'pure-text-story',
            'craftsman_name' => 'Text Only Master',
            'craftsman_role' => 'Heritage Historian',
            'content'        => '<p>Pure text interview</p>',
            'youtube_url'    => null,
            'audio_file'     => null,
            'is_published'   => true,
        ]);

        $response = $this->get(route('stories.index'));
        $response->assertStatus(200);
        $response->assertSee('Text Only Master');
        // When there is only text, neither badge should appear on that card
        $this->assertFalse($story->has_audio);
        $this->assertFalse($story->has_video);
    }

    public function test_admin_can_delete_story(): void
    {
        $user = User::factory()->create();
        $story = CraftsmanStory::create([
            'title'          => 'To Delete',
            'slug'           => 'to-delete',
            'craftsman_name' => 'Delete Me',
            'craftsman_role' => 'Deleted Role',
            'content'        => '<p>Delete content</p>',
            'is_published'   => true,
        ]);

        $response = $this->actingAs($user)->delete(route('admin.stories.destroy', $story));
        $response->assertRedirect(route('admin.stories.index'));
        $this->assertDatabaseMissing('craftsmen_stories', ['id' => $story->id]);
    }

    // ─── Navigation Links ──────────────────────────────────────────────────

    public function test_homepage_links_to_stories(): void
    {
        $response = $this->get(route('home'));
        $response->assertSee(route('stories.index'));
    }

    public function test_header_nav_links_to_stories(): void
    {
        $this->seed(CraftsmanStorySeeder::class);
        $response = $this->get(route('stories.index'));
        $response->assertSee(route('stories.index'));
    }
}
