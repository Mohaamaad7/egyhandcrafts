# Task: Craftsmen Stories & Testimonials Module (قصص وشهادات الحرفيين)

## Problem Statement

The Menoufia Heritage Documentation Platform lacked a dedicated module for preserving and presenting first-person craftsman testimonies — the personal stories, oral histories, and multimedia records of the artisans who carry the region's heritage. The fourth core pillar needed to support rich text, audio recordings, and video embeds while maintaining the platform's established visual identity and engineering standards.

## Schema: `craftsmen_stories` Table

| Column          | Type           | Constraints                      |
|-----------------|----------------|----------------------------------|
| `id`            | BIGINT UNSIGNED | PK, auto-increment              |
| `title`         | VARCHAR(255)   | NOT NULL                         |
| `slug`          | VARCHAR(255)   | NOT NULL, UNIQUE                 |
| `craftsman_name`| VARCHAR(255)   | NOT NULL                         |
| `craftsman_role`| VARCHAR(255)   | NOT NULL                         |
| `photo`         | VARCHAR(255)   | NULLABLE                         |
| `content`       | LONGTEXT       | NOT NULL (CKEditor 5 HTML)       |
| `excerpt`       | TEXT           | NULLABLE (auto-generated)        |
| `youtube_url`   | VARCHAR(500)   | NULLABLE                         |
| `audio_file`    | VARCHAR(255)   | NULLABLE                         |
| `is_published`  | BOOLEAN        | DEFAULT true                     |
| `created_at`    | TIMESTAMP      |                                  |
| `updated_at`    | TIMESTAMP      |                                  |

**Architecture:** Standalone, decoupled entity. No FK constraints to `crafts` or `workshops`.

## Files Created

### Migration & Model
- `database/migrations/2026_09_05_150000_create_craftsmen_stories_table.php`
- `app/Models/CraftsmanStory.php` — Accessors: `photo_url`, `audio_file_url`, `youtube_embed_url`, `has_audio`, `has_video`, `excerpt_text`. Scope: `scopePublished()`.
- `database/factories/CraftsmanStoryFactory.php`

### Admin CRUD (Tabler)
- `app/Http/Controllers/Admin/CraftsmanStoryController.php` — Full CRUD with photo/audio uploads, slug collision loop, auto-excerpt, old-file deletion.
- `resources/views/admin/stories/index.blade.php` — Data table with portrait thumbnails, media badges, publication status.
- `resources/views/admin/stories/create.blade.php` — Form with CKEditor 5, photo/audio/YouTube inputs, publish toggle.
- `resources/views/admin/stories/edit.blade.php` — Pre-populated form with current media previews.

### Public Frontend (Tailwind RTL)
- `app/Http/Controllers/CraftsmanStoryController.php` — `index()` (paginated published stories), `show($slug)` (detail with prev/next).
- `resources/views/stories/index.blade.php` — Heritage hero, 3-column responsive grid, media badges, excerpt snippets.
- `resources/views/stories/show.blade.php` — 2-column layout, zero-ghost-space conditional multimedia, social share, identity sidebar.

### Seeder
- `database/seeders/CraftsmanStorySeeder.php` — 3 authentic fieldwork testimonies (Abu Qouta/video, Mohamed Hassan/audio, Hamada Ensan/text-only).

### Tests
- `tests/Feature/CraftsmanStoryTest.php` — 28 tests, 66 assertions.

## Files Modified

- `routes/web.php` — Admin and frontend story routes.
- `resources/views/admin/layout.blade.php` — Added sidebar nav item with `ti-microphone` icon.
- `resources/views/layouts/app.blade.php` — Desktop + mobile nav links updated from `#artisans-stories` to `route('stories.index')`.
- `resources/views/home.blade.php` — Card 4 CTA converted to `<a>` linking to `route('stories.index')`.
- `database/seeders/DatabaseSeeder.php` — Wired `CraftsmanStorySeeder`.

## Key Architectural Decisions

### Zero-Ghost-Space Conditional Rendering
The show view uses strict Blade `@if` guards: video and audio blocks are rendered **only** when their respective data exists. No wrapper containers, iframes, or spacer elements are emitted when media is absent. Verified by test `test_story_without_media_renders_no_audio_or_iframe`.

### Robust YouTube ID Extraction
The `getYoutubeEmbedUrlAttribute()` accessor uses a resilient regex pattern:
```
/(?:youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/)|youtu\.be\/)([\w-]{11})/
```
Handles: standard watch URLs, `youtu.be` short links, YouTube Shorts, URLs with extra query params (`?t=`, `&list=`). Returns privacy-enhanced `youtube-nocookie.com` embed URLs. Verified by 6 dedicated tests.

### Cross-Browser Audio Player
Audio tag uses nested `<source>` element with MIME type and Arabic fallback text for mobile Safari compatibility:
```html
<audio controls class="w-full" preload="metadata">
    <source src="{{ $story->audio_file_url }}" type="audio/mpeg">
    المتصفح لا يدعم مشغل الصوتيات.
</audio>
```

### Fallback Image Verification
`getPhotoUrlAttribute()` performs runtime `file_exists()` check on `public/assets/images/card_stories.jpg` and falls back to verified `HeroBG.jpg` if absent.

### Audio Upload Validation
Supports uploads up to 50MB (`max:51200`) with MIME types: `mp3,wav,m4a,aac,ogg`.

## Verification Output

```
Tests:    60 passed (146 assertions)
Duration: 4.28s

PASS  Tests\Feature\CraftsmanStoryTest (28 tests, 66 assertions)
- Seeder: 3 stories, correct media, expected names
- YouTube: 6 URL format extraction tests
- Frontend: index renders, roles displayed, media badges visible
- Detail: valid slug 200, invalid slug 404, breadcrumb present
- Zero-Ghost-Space: video=iframe, audio=<audio>, none=neither
- Unpublished: hidden from index, 404 on show
- Admin CRUD: auth gate, list, create form, store+slug+photo, update, delete
- Navigation: homepage and header link to stories.index
```

No regressions in existing test suites (AdminAuthenticationTest, AdminCraftImageUploadTest, CraftsDirectoryTest, WorkshopMapTest).
