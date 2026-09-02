<?php

namespace Tests\Feature;

use App\Models\Craft;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CraftsDirectoryTest extends TestCase
{
    use RefreshDatabase;

    protected function createCraft(array $overrides = []): Craft
    {
        return Craft::create(array_merge([
            'title'             => 'ساقية أبو شعرة للسجاد',
            'slug'              => 'saqyet-abu-shara',
            'location'          => 'ساقية أبو شعرة - أشمون',
            'short_description' => 'صناعة السجاد اليدوي العريق في المنوفية.',
            'content'           => '<h2>تاريخ السجاد</h2><p>نص تجريبي غني بالوسوم.</p>',
            'cover_image'       => 'crafts/carpet.jpg',
        ], $overrides));
    }

    public function test_crafts_index_page_renders_successfully(): void
    {
        $craft = $this->createCraft();

        $response = $this->get('/crafts');

        $response->assertStatus(200);
        $response->assertSee('دليل الحرف التراثية بالمنوفية');
        $response->assertSee($craft->title);
        $response->assertSee('HeroBG.jpg');
    }

    public function test_craft_show_page_renders_with_rich_content_and_image(): void
    {
        $craft = $this->createCraft();

        $response = $this->get('/crafts/' . $craft->slug);

        $response->assertStatus(200);
        $response->assertSee($craft->title);
        $response->assertSee($craft->location);
        $response->assertSee('بطاقة توثيق الحرفة');
        $response->assertSee('تاريخ السجاد');
        $response->assertSee($craft->cover_image_url, false);
    }

    public function test_craft_show_page_renders_styled_colors_and_table_content(): void
    {
        $styledContent = '<h2><span style="color:#E67E22;background-color:#FEF3C7;">مقدمة عن حرفة السيرما</span></h2>'
            . '<figure class="table"><table><tbody><tr><td style="background-color:#FFF6D6;">خلية ملونة</td></tr></tbody></table></figure>';

        $craft = $this->createCraft([
            'content' => $styledContent,
        ]);

        $response = $this->get('/crafts/' . $craft->slug);

        $response->assertStatus(200);
        $response->assertSee('style="color:#E67E22;background-color:#FEF3C7;"', false);
        $response->assertSee('مقدمة عن حرفة السيرما');
        $response->assertSee('style="background-color:#FFF6D6;"', false);
        $response->assertSee('خلية ملونة');
    }

    public function test_non_existent_craft_slug_returns_404(): void
    {
        $response = $this->get('/crafts/non-existent-craft-slug-12345');

        $response->assertStatus(404);
    }
}

