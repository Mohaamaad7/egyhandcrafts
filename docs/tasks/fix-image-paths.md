# إصلاح مسارات وتحميل صور الغلاف (Craft Cover Images Fix)

- **عنوان المهمة:** معالجة مشكلة الصور المكسورة وعدم ظهور صور غلاف الحرف في الواجهة الأمامية ولوحة الإدارة
- **التاريخ:** 2026-08-31
- **الحالة:** مكتملة بنجاح ✅

---

## 1. وصف المشكلة (Problem Description)
في الواجهة الأمامية لدليل الحرف التراثية (في صفحة قائمة الحرف `index.blade.php` وصفحة تفاصيل الحرفة `show.blade.php`)، وكذلك في لوحة الإدارة (`admin/crafts`)، كانت صور الغلاف تظهر كصور مكسورة (Broken Images) أو لا تظهر على الإطلاق؛ حيث كان المتصفح يُرجع خطأ `404 Not Found` لأي صورة يتم طلبها عبر المسار العام `/storage/crafts/...`.

---

## 2. التشخيص والسبب الجذري (Root Cause Analysis)
1. **غياب الرابط الرمزي (Missing Storage Symlink):**
   - في معمارية Laravel، يتم حفظ ملفات الـ Upload الموجهة للقرص العام داخل مجلد `storage/app/public/crafts/`.
   - يقوم المساعد `Storage::url('crafts/...')` بتوليد روابط عامة تشير إلى `/storage/crafts/...`.
   - عند فحص المجلد `public/` في المشروع، تبيّن أن الرابط الرمزي `public/storage` **لم يكن موجوداً** في نظام الملفات (لم يتم تنفيذ أمر `php artisan storage:link`).
   - وبالتالي فإن أي طلب من المتصفح لجلب ملف من المسار `/storage/...` يفشل ويرجع بكود `404 Not Found`.
2. **بيانات الحرف المبدئية في قاعدة البيانات (Seeder Data):**
   - عند فحص سجلات قاعدة البيانات وملف `database/seeders/CraftSeeder.php`، تبيّن أن 8 من أصل 9 حرف تم إنشاؤها بدون مسار لصورة الغلاف (`cover_image = null`).
   - في صفحات Blade، كان الشرط `@if($craft->cover_image)` يُخفي الصورة تماماً في صفحة التفاصيل أو يعرض أيقونة فارغة في صفحة الفهرس لعدم توفر صور بديلة متناسقة.

---

## 3. الملفات التي تم فحصها (Inspected Files)
- `config/filesystems.php`
- `.env`
- `public/` و `public/assets/images/`
- `storage/app/public/` و `storage/app/public/crafts/`
- `app/Models/Craft.php`
- `app/Http/Controllers/Admin/CraftController.php`
- `app/Http/Controllers/FrontendCraftController.php`
- `database/seeders/CraftSeeder.php`
- `resources/views/crafts/index.blade.php`
- `resources/views/crafts/show.blade.php`
- `resources/views/admin/crafts/index.blade.php`
- `resources/views/admin/crafts/edit.blade.php`

---

## 4. الملفات التي تم تعديلها أو إنشاؤها (Modified Files)
- `public/storage` — إنشاء الرابط الرمزي (Symlink) المشير إلى `storage/app/public`.
- `storage/app/public/crafts/` — توفير صور تراثية واقعية عالية الدقة لجميع الحرف الـ 9.
- `app/Models/Craft.php` — إضافة Accessor ذكي وآمن `getCoverImageUrlAttribute()` للتعامل مع الروابط والصور المخزنة وتوفير صورة بديلة افتراضية (Fallback Image) عند غياب الملف.
- `database/seeders/CraftSeeder.php` — تحديث الـ Seeder لربط كل حرفة بصورتها التراثية المخصصة في الـ Storage مع منطق `updateOrCreate`.
- `resources/views/crafts/index.blade.php` — الاعتماد على `$craft->cover_image_url` مع معالج `onerror` لضمان عدم انكسار أي صورة.
- `resources/views/crafts/show.blade.php` — تحديث عرض صورة الغلاف باستخدام `$craft->cover_image_url` ومعالج `onerror`.
- `resources/views/admin/crafts/index.blade.php` — تحديث الصورة الرمزية (Avatar) لتستخدم `$craft->cover_image_url`.
- `resources/views/admin/crafts/edit.blade.php` — تحديث المعاينة لتعتمد على `$craft->cover_image_url`.

---

## 5. الأوامر التي تم تشغيلها (Executed Commands)
```bash
# إنشاء الرابط الرمزي الرسمي للـ Storage
php artisan storage:link

# تحديث بيانات الحرف وربط الصور عبر الـ Seeder
php artisan db:seed --class=CraftSeeder

# تشغيل الاختبارات الآلية
php artisan test
```

---

## 6. الحل القياسي المنفذ (Implemented Solution)
1. **تفعيل الـ Storage Symlink**:
   - تم ربط `c:\laragon\www\sadat\public\storage` بـ `c:\laragon\www\sadat\storage\app/public` بنجاح عبر أمر Laravel القياسي.
2. **توفير أصول مرئية حقيقية لكل الحرف التراثية**:
   - تم وضع صور واقعية لكل حرفة في `storage/app/public/crafts/`:
     - السجاد اليدوي (`carpet.jpg`)
     - الكليم والجوبلان (`kilim.jpg`)
     - التطعيم بالصدف (`sadaf.jpg`)
     - التطريز بالسيرما (`serma.png`)
     - الخزف والفخار (`pottery.jpg`)
     - النجف الإسلامي (`chandelier.jpg`)
     - البامبو (`bamboo.jpg`)
     - الجريد (`jareed.jpg`)
     - الأرابيسك (`arabesque.jpg`)
3. **طبقة الحماية في الـ Model (Resilient Accessor)**:
   - تم إنشاء `cover_image_url` في `Craft.php` ليتأكد من وجود الملف في قرص الـ public ويولد الرابط الصحيح، وإذا لم يتوفر يرجع بالصورة الاحتياطية المعتمدة تلقائياً.
4. **تأمين الواجهات الأمامية والإدارية**:
   - تم تضمين خاصية `onerror="this.onerror=null; this.src='...';"` في وسوم `<img>` لحماية الواجهة من أي انكسار مرئي في حال حذف أو نقل أي ملف.

---

## 7. طريقة التحقق من نجاح الحل (Validation & Verification)
- **فحص الرابط الرمزي:** تم التأكد من إتاحة المجلد `public/storage/crafts/` ومطابقته التامة لـ `storage/app/public/crafts/`.
- **فحص قاعدة البيانات:** تأكيد أن جميع سجلات الحرف الـ 9 تحتوي على مسار `cover_image` صالح في الـ Storage.
- **الاختبارات الآلية:** نجاح كافة اختبارات `Tests\Feature\CraftsDirectoryTest` والتأكد من إرجاع روابط الصور بنجاح داخل الـ HTML.
- **استجابة الخادم:** تحقق إرجاع الصور بكود `HTTP 200 OK` بدون أي أخطاء `404`.

---

## 8. الحالة النهائية (Final Status)
**مكتملة بنجاح ✅** — تعمل جميع صور الغلاف في صفحة قائمة الحرف، وصفحة تفاصيل الحرفة، ولوحة تحكم الإدارة بكفاءة تامة ودون أي انكسار.
