# الخريطة التفاعلية لورش الحرف التراثية ودليل الورش (The Interactive Heritage Map & Workshops Directory)

- **عنوان المهمة:** بناء وتكامل الخريطة التفاعلية لورش الحرف التراثية بمحافظة المنوفية مع نظام إدارة المحتوى (CMS) ولوحة التحكم وصفحات الورش المستقلة
- **التاريخ:** 2026-09-03
- **الحالة:** مكتملة بنجاح ✅

---

## 1. وصف المتطلبات والأهداف (Context & Objectives)

بناء الموديول الأساسي الثالث للمشروع: **"الخريطة التفاعلية" (The Interactive Heritage Map)**، وتحويل النموذج الأولي المستقل (`Menofia_handicrafts_workshops_map.html`) الذي يضم 18 ورشة حرفية ميدانية موثقة (14 ورشة صدف بساقية المنقدي و 4 ورش سيرما بشما - أشمون) إلى منظومة ديناميكية متكاملة داخل بيئة **Laravel 13**:

1. **المطابقة البصرية والسلوكية الصارمة للنموذج الأولي (Visual & Behavioral Fidelity):**
   - استخدام مكتبة **Leaflet.js** مع طبقات خرائط جوجل (Google Maps Streets & Satellite).
   - تطبيق فلتر النمط التراثي السيبيا الأصيل (`sepia(0.65) hue-rotate(-15deg) contrast(1.1) brightness(0.95)`).
   - لوحة فلترة عائمة متجاوبة تتيح التصفية بحسب الحرفة والمكان بالخطوط التراثية (`Amiri` و `Cairo`).
   - علامات مخصصة على الخريطة (Custom Markers) برموز وألوان تمثل كل حرفة (Deep Teal مع أيقونة `fa-gem` للصدف، Golden مع أيقونة `fa-scroll` للسيرما، و Brown مع أيقونة `fa-hammer` لغيرها).
   - نوافذ منبثقة (Popups) منسقة بالكامل مع دعم اتجاه النصوص RTL وأرقام الهواتف LTR وتلميحات التحويم (Tooltips).
2. **امتداد الميزة بصفحات بروفايل مخصصة للورش (Dedicated Workshop Pages):**
   - إضافة زر تحفيزي أنيق (CTA Button) داخل كل نافذة منبثقة ينقل الزائر إلى صفحة العرض المخصصة للورشة (`/workshops/{slug}`).
   - تصميم صفحة الورشة وفق نسق بطاقات التوثيق ومعارض الصور وقصص الحرفيين وخريطة مصغرة لموقع الورشة.
3. **إدارة المحتوى وقابلية التوسع (CMS & Tabler Admin CRUD):**
   - تخزين بيانات الورش في قاعدة البيانات وربطها بالحرف مع دعم كامل لعمليات الإضافة والتعديل والحذف عبر لوحة تحكم **Tabler**.
   - توفير محدد إحداثيات تفاعلي (Interactive Leaflet Coordinate Picker) داخل نماذج الإدارة لتسهيل إدخال وتعديل الإحداثيات الجغرافية بالنقر أو السحب.
   - تهيئة البنية البرمجية والنصوص لدعم التوطين والترجمة المستقبلية (Localization Ready).
4. **تضمين الـ 18 ورشة وحل تصادم الأسماء المتطابقة (Collision Resolution):**
   - معالجة ورشة "ورشة الإتحاد لصناعة الصدف الأرابيسك" المكررة في النموذج الأولي بإحداثيات وأعداد عمالة مختلفة، وضمان زراعة الـ 18 ورشة كاملة دون استبدال أو فقدان للبيانات.

---

## 2. الملفات التي تم إنشاؤها (New Files)

| المسار | الوصف والدور |
|---|---|
| `database/migrations/2026_09_03_120000_create_workshops_table.php` | هجرة جدول الورش مع حقول الاسم، الرابط، الربط بالحرفة، الإحداثيات، العمالة، الهاتف، والمحتوى |
| `app/Models/Workshop.php` | نموذج الورشة مع العلاقات، التحويلات البرمجية (`casts`)، واستخراج رابط الصورة الافتراضية |
| `database/seeders/WorkshopSeeder.php` | بادر الـ 18 ورشة الأصلية مع ربط الحرف وحل تصادم الـ Slugs |
| `app/Http/Controllers/Admin/WorkshopController.php` | متحكم لوحة التحكم لعمليات CRUD للورش وإدارة الصور وتوليد الـ Slugs الفريدة |
| `resources/views/admin/workshops/index.blade.php` | واجهة عرض وإدارة الورش في لوحة Tabler مع الشارات والإحصائيات والبحث والتصفح |
| `resources/views/admin/workshops/create.blade.php` | نموذج إضافة ورشة جديدة مع خريطة Leaflet لاختيار وتحديث الإحداثيات تلقائياً |
| `resources/views/admin/workshops/edit.blade.php` | نموذج تعديل بيانات الورشة مع محدد الإحداثيات المتموضع على موقع الورشة الفعلي |
| `app/Http/Controllers/MapController.php` | متحكم الواجهة الأمامية لتسليم الخريطة مجهزة بالبيانات وترميز نصوص الترجمة وعرض صفحة الورشة |
| `resources/views/map/index.blade.php` | صفحة الخريطة التفاعلية الكاملة مع طبقات الخرائط والفلترة والنوافذ المنبثقة وزر الانتقال |
| `resources/views/workshops/show.blade.php` | واجهة بروفايل الورشة المستقلة مع بطاقة البيانات، الخريطة المصغرة، ومحتوى التوثيق |
| `tests/Feature/WorkshopMapTest.php` | مجموعة اختبارات ميزة آلية شاملة (17 اختباراً) للتحقق من الموديول بالكامل |

---

## 3. الملفات التي تم تعديلها (Modified Files)

| المسار | طبيعة التعديل |
|---|---|
| `app/Models/Craft.php` | إضافة العلاقة العكسية `public function workshops(): HasMany` |
| `database/seeders/DatabaseSeeder.php` | استدعاء `WorkshopSeeder` بعد `CraftSeeder` |
| `routes/web.php` | تسجيل 6 مسارات إدارة للورش ومساري الواجهة الأمامية (`/map` و `/workshops/{slug}`) |
| `resources/views/admin/layout.blade.php` | إضافة رابط "ورش الحرف (الخريطة)" في القائمة الجانبية مع أيقونة `ti-map-pin` |
| `resources/views/layouts/app.blade.php` | إضافة خطافات `@stack('styles')` و `@stack('scripts')` وتحديث روابط الخريطة بالهيدر |
| `resources/views/home.blade.php` | ربط أزرار وبطاقة "الخريطة التفاعلية" بالمسار الفعلي `route('map.index')` |
| `docs/project_map.md` | توثيق تفاصيل المهمة والخطوات البرمجية المنفذة في السجل المرجعي للمشروع |

---

## 4. البنية المعمارية والتفاصيل التقنية (Technical Architecture)

### 1. بنية جدول الورش (`workshops` Table Schema):
```sql
CREATE TABLE `workshops` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `craft_id` bigint unsigned DEFAULT NULL,
  `craft_type` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `workers_count` varchar(50) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `short_description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`craft_id`) REFERENCES `crafts` (`id`) ON DELETE SET NULL
);
```

### 2. آلية تجنب تداخل الأسماء (Duplicate Name Collision Handling):
تحتوي بيانات النموذج الميداني على ورشتين باسم `"ورشة الإتحاد لصناعة الصدف الأرابيسك"` (الأولى 15-20 عاملاً والثانية 26 عاملاً بإحداثيات مختلفة). تم تزويد الـ Seeder بمنطق ذكي يلحق لواحق فريدة (`-1` و `-2`) بالـ Slug، كما زُوّد المتحكم (`WorkshopController`) بحلقة فحص تلقائية فريدة عند الإدخال والتعديل، مما ضمن استقرار وحفظ الـ 18 ورشة كاملة.

### 3. محدد الإحداثيات التفاعلي في لوحة التحكم (Coordinate Picker):
تم دمج خريطة Leaflet مصغرة تفاعلية بارتفاع 350px في صفحتي الإضافة والتعديل:
- عند النقر على الخريطة أو سحب العلامة (Draggable Marker)، يتم تحديث حقلي `latitude` و `longitude` فوراً بدقة 7 أرقام عشرية.
- عند تعديل الإحداثيات كتابياً في الحقول، تنتقل العلامة والخريطة تلقائياً للموقع الجديد.
- في شاشة التعديل تفتح الخريطة متموضعة بدقة على إحداثيات الورشة بمستوى تكبير (Zoom 16).

### 4. خريطة الواجهة الأمامية وضمان الجاهزية للترجمة (i18n Readiness):
- تمرر بيانات الورش بصيغة JSON نظيفة عبر `$workshops->toJson(JSON_UNESCAPED_UNICODE)` لضمان قراءة الحروف العربية وعدم تحويلها لـ Unicode Escape Characters.
- تم تجميع جميع نصوص الواجهة (عناوين، شارات، أزرار، أسماء الطبقات) في مصفوفة كائن `$labels` تستدعي دوال الترجمة `__()` وتمرر إلى كود JavaScript عبر متغير `labels`، مما يحمي المشروع من أي نصوص عربية صلبة ويسهل دعم اللغات مستقبلاً.
- زُوّدت كل نافذة منبثقة برابط CTA أنيق ينقل الزائر مباشرة إلى `/workshops/{slug}`.

### 5. صفحة بروفايل الورشة (`workshops.show`):
- هيدر تراثي غني بمعلومات الموقع والمالك والحرفة.
- معرض لصورة الورشة مع وصف تمهيدي ومقالة موثقة عبر محرر CKEditor 5.
- خريطة موقع مصغرة مدمجة بأسلوب السيبيا التراثي مثبتة على إحداثيات الورشة.
- بطاقة هوية الورشة الرسمية بالعمود الجانبي وقائمة الورش المقترحة من ذات الحرفة مع روابط سريعة لدليل الحرف والعودة للخريطة.

---

## 5. نتائج الاختبارات والتحقق (Validation & Testing)

تم تنفيذ جميع الاختبارات الآلية عبر `php artisan test`:
- **نتائج اختبارات الموديول (`tests/Feature/WorkshopMapTest.php`):** 17 اختباراً ناجحاً بنسبة 100%.
- **نتائج كامل منظومة المشروع:**
  ```
     PASS  Tests\Unit\ExampleTest (1 test)
     PASS  Tests\Feature\AdminAuthenticationTest (6 tests)
     PASS  Tests\Feature\AdminCraftImageUploadTest (3 tests)
     PASS  Tests\Feature\CraftsDirectoryTest (4 tests)
     PASS  Tests\Feature\ExampleTest (1 test)
     PASS  Tests\Feature\WorkshopMapTest (17 tests)

    Tests:    32 passed (80 assertions)
    Duration: 4.57s
  ```

---

## 6. جدول المسارات المسجلة للموديول (Registered Routes)

| الطريقة | المسار | الاسم البرمجي | المتحكم والإجراء | الحماية |
|---|---|---|---|---|
| `GET` | `/map` | `map.index` | `MapController@index` | عام |
| `GET` | `/workshops/{slug}` | `workshops.show` | `MapController@show` | عام |
| `GET` | `/admin/workshops` | `admin.workshops.index` | `Admin\WorkshopController@index` | `auth` |
| `GET` | `/admin/workshops/create` | `admin.workshops.create` | `Admin\WorkshopController@create` | `auth` |
| `POST` | `/admin/workshops` | `admin.workshops.store` | `Admin\WorkshopController@store` | `auth` |
| `GET` | `/admin/workshops/{workshop}/edit` | `admin.workshops.edit` | `Admin\WorkshopController@edit` | `auth` |
| `PUT` | `/admin/workshops/{workshop}` | `admin.workshops.update` | `Admin\WorkshopController@update` | `auth` |
| `DELETE` | `/admin/workshops/{workshop}` | `admin.workshops.destroy` | `Admin\WorkshopController@destroy` | `auth` |
