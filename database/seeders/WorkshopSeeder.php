<?php

namespace Database\Seeders;

use App\Models\Craft;
use App\Models\Workshop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WorkshopSeeder extends Seeder
{
    /**
     * Seed the 18 documented workshops from the Menoufia Heritage Map prototype.
     *
     * CRITICAL: "ورشة الإتحاد لصناعة الصدف الأرابيسك" appears TWICE
     * at different coordinates/worker counts. We append a unique suffix
     * to avoid slug collision and data loss.
     */
    public function run(): void
    {
        // Look up craft IDs from the crafts directory
        $sadafCraft = Craft::where('title', 'like', '%الصدف%')->first();
        $sirmaCraft = Craft::where('title', 'like', '%السيرما%')->first();

        $sadafId = $sadafCraft?->id;
        $sirmaId = $sirmaCraft?->id;

        $workshops = [
            // ── 14 Sadaf (صدف) workshops in ساقية المنقدي ────────────
            [
                'name'          => 'ورشة الإتحاد لصناعة الصدف الأرابيسك',
                'craft_type'    => 'الصدف',
                'craft_id'      => $sadafId,
                'location'      => 'ساقية المنقدي',
                'owner'         => 'محمود قوطة',
                'workers_count' => '15-20',
                'phone'         => '01020157157',
                'latitude'      => 30.3793551,
                'longitude'     => 30.8876568,
                'slug_suffix'   => '1', // distinguishes from the duplicate
            ],
            [
                'name'          => 'ورشة تطعيم العلب والشكماجيات',
                'craft_type'    => 'الصدف',
                'craft_id'      => $sadafId,
                'location'      => 'ساقية المنقدي',
                'owner'         => 'محمود قوطة',
                'workers_count' => '40',
                'phone'         => '01020157157',
                'latitude'      => 30.3803603,
                'longitude'     => 30.8868226,
            ],
            [
                'name'          => 'ورشة اللؤلؤة للصدف',
                'craft_type'    => 'الصدف',
                'craft_id'      => $sadafId,
                'location'      => 'ساقية المنقدي',
                'owner'         => 'مجدي جمال صالح',
                'workers_count' => '6',
                'phone'         => '01226318630',
                'latitude'      => 30.380026,
                'longitude'     => 30.8850828,
            ],
            [
                'name'          => 'ورشة صبحي شتله',
                'craft_type'    => 'الصدف',
                'craft_id'      => $sadafId,
                'location'      => 'ساقية المنقدي',
                'owner'         => 'صبحي شتله',
                'workers_count' => '3',
                'phone'         => '01006989175',
                'latitude'      => 30.3817154,
                'longitude'     => 30.8854149,
            ],
            [
                'name'          => 'ورشة الوكيل',
                'craft_type'    => 'الصدف',
                'craft_id'      => $sadafId,
                'location'      => 'ساقية المنقدي',
                'owner'         => 'إنسان مبارك الوكيل',
                'workers_count' => '3',
                'phone'         => '01202514728',
                'latitude'      => 30.381715,
                'longitude'     => 30.885415,
            ],
            [
                'name'          => 'ورشة الإتحاد لصناعة الصدف الأرابيسك',
                'craft_type'    => 'الصدف',
                'craft_id'      => $sadafId,
                'location'      => 'ساقية المنقدي',
                'owner'         => 'محمود قوطة',
                'workers_count' => '26',
                'phone'         => '01020157157',
                'latitude'      => 30.3827055,
                'longitude'     => 30.8855553,
                'slug_suffix'   => '2', // distinguishes from the duplicate
            ],
            [
                'name'          => 'ورشة الحاج رجب حسن شتله',
                'craft_type'    => 'الصدف',
                'craft_id'      => $sadafId,
                'location'      => 'ساقية المنقدي',
                'owner'         => 'رجب شتله',
                'workers_count' => '4',
                'phone'         => '01226031833 / 01000930755',
                'latitude'      => 30.3830842,
                'longitude'     => 30.885804,
            ],
            [
                'name'          => 'ورشة وائل عامر',
                'craft_type'    => 'الصدف',
                'craft_id'      => $sadafId,
                'location'      => 'ساقية المنقدي',
                'owner'         => 'وائل عامر',
                'workers_count' => '5',
                'phone'         => '01208965296',
                'latitude'      => 30.3833807,
                'longitude'     => 30.8868975,
            ],
            [
                'name'          => 'ورشة التراث المصري',
                'craft_type'    => 'الصدف',
                'craft_id'      => $sadafId,
                'location'      => 'ساقية المنقدي',
                'owner'         => 'خالد عبد العزيز / أم عبده',
                'workers_count' => '5',
                'phone'         => '01102170874',
                'latitude'      => 30.383381,
                'longitude'     => 30.886898,
            ],
            [
                'name'          => 'مصنع الفراعنة',
                'craft_type'    => 'الصدف',
                'craft_id'      => $sadafId,
                'location'      => 'ساقية المنقدي',
                'owner'         => 'أحمد حسن قوطة / محمد حسن قوطة',
                'workers_count' => '25',
                'phone'         => '01065670032',
                'latitude'      => 30.3802593,
                'longitude'     => 30.8929602,
            ],
            [
                'name'          => 'ورشة متولي أنور',
                'craft_type'    => 'الصدف',
                'craft_id'      => $sadafId,
                'location'      => 'ساقية المنقدي',
                'owner'         => 'متولي أنور',
                'workers_count' => '4',
                'phone'         => '01002302123',
                'latitude'      => 30.3802641,
                'longitude'     => 30.8929209,
            ],
            [
                'name'          => 'ورشة صدف أحمد عربي',
                'craft_type'    => 'الصدف',
                'craft_id'      => $sadafId,
                'location'      => 'ساقية المنقدي',
                'owner'         => 'أحمد عربي',
                'workers_count' => '2',
                'phone'         => '01080330297',
                'latitude'      => 30.3906314,
                'longitude'     => 30.888495,
            ],
            [
                'name'          => 'ورشة قوطة براند',
                'craft_type'    => 'الصدف',
                'craft_id'      => $sadafId,
                'location'      => 'ساقية المنقدي',
                'owner'         => 'خالد قوطة',
                'workers_count' => '4',
                'phone'         => '01007864568 / 01551304612',
                'latitude'      => 30.3830988,
                'longitude'     => 30.8860781,
            ],
            [
                'name'          => 'ورشة الاسطي هاني بديع',
                'craft_type'    => 'الصدف',
                'craft_id'      => $sadafId,
                'location'      => 'ساقية المنقدي',
                'owner'         => 'هاني بديع',
                'workers_count' => '2',
                'phone'         => '01012824041',
                'latitude'      => 30.3906368,
                'longitude'     => 30.8884894,
            ],

            // ── 4 Sirma (سيرما) workshops in شما - اشمون ────────────
            [
                'name'          => 'ورشة احمد خليل',
                'craft_type'    => 'السيرما',
                'craft_id'      => $sirmaId,
                'location'      => 'شما - اشمون',
                'owner'         => 'الحاج احمد خليل',
                'workers_count' => '12',
                'phone'         => '+20 10 11508008',
                'latitude'      => 30.381352,
                'longitude'     => 30.912888,
            ],
            [
                'name'          => 'ورشة عبد الناصر نميس',
                'craft_type'    => 'السيرما',
                'craft_id'      => $sirmaId,
                'location'      => 'شما - اشمون',
                'owner'         => 'عبد الناصر نميس',
                'workers_count' => '9',
                'phone'         => '01026686887',
                'latitude'      => 30.381134,
                'longitude'     => 30.909765,
            ],
            [
                'name'          => 'ورشة محمد سلام',
                'craft_type'    => 'السيرما',
                'craft_id'      => $sirmaId,
                'location'      => 'شما - اشمون',
                'owner'         => 'محمد سلام',
                'workers_count' => '6',
                'phone'         => '+20 11 27992783',
                'latitude'      => 30.380251,
                'longitude'     => 30.909977,
            ],
            [
                'name'          => 'ورشه عماد رواش',
                'craft_type'    => 'السيرما',
                'craft_id'      => $sirmaId,
                'location'      => 'شما - اشمون',
                'owner'         => 'عماد رواش',
                'workers_count' => '6',
                'phone'         => '01024750768',
                'latitude'      => 30.379269,
                'longitude'     => 30.911203,
            ],
        ];

        foreach ($workshops as $data) {
            $baseSlug = Str::slug($data['name'], '-');

            // Handle duplicate names by appending suffix
            if (isset($data['slug_suffix'])) {
                $slug = $baseSlug . '-' . $data['slug_suffix'];
                unset($data['slug_suffix']);
            } else {
                $slug = $baseSlug;
            }

            Workshop::updateOrCreate(
                ['slug' => $slug],
                array_merge($data, [
                    'slug'              => $slug,
                    'short_description' => null,
                    'content'           => null,
                    'cover_image'       => null,
                    'is_active'         => true,
                ])
            );
        }
    }
}
