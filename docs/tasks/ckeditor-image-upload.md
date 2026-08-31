# رفع الصور مباشرة في محرر CKEditor 5 (Direct Image Upload & Drag-and-Drop)

- **عنوان المهمة:** تفعيل رفع الصور المباشر داخل محرر CKEditor 5 مع دعم السحب والإفلات وتغيير الحجم والمحاذاة
- **التاريخ:** 2026-08-31
- **الحالة:** مكتملة بنجاح ✅
- **نوع الترخيص:** مفتوح المصدر / مجاني (GPL Compatible)

---

## 1. وصف المشكلة (Problem Description)
عند إضافة صورة داخل مقال الحرفة في لوحة الإدارة، كان محرر CKEditor 5 يكتفي بطلب رابط خارجي للصورة فقط (`Insert image via URL`)، دون إمكانية اختيار صورة ورفعها مباشرة من الجهاز، أو سحبها وإفلاتها، أو لصقها من الحافظة (Clipboard)، مع انعدام خيارات التحكم في أحجام وتنسيقات الصور داخل المقال.

---

## 2. التشخيص والسبب الجذري (Root Cause Analysis)
1. **عدم تضمين محول الرفع (Missing Upload Adapter):**
   - كان ملف `resources/js/ckeditor.js` يحتوي على حزم العرض الأساسية فقط (`Image`, `ImageToolbar`, `ImageStyle`, `ImageCaption`).
   - لم يتم استيراد `ImageUpload`, `ImageInsert`, `ImageResize`, `AutoImage`, أو `SimpleUploadAdapter`.
2. **غياب مسار وخادم المعالجة (Missing Backend Upload Endpoint):**
   - لم يكن هناك مسار مخصص في Laravel لاستقبال طلبات الرفع متعددة الأجزاء (Multipart Upload) وتخزينها في مسار التخزين العام مع التحقق الأمني.

---

## 3. الملفات التي تم فحصها وتعديلها (Inspected & Modified Files)
- `app/Http/Controllers/Admin/CraftController.php` — إضافة دالة `uploadImage(Request $request)`.
- `routes/web.php` — تسجيل المسار `POST /admin/crafts/upload-image` مع حماية `auth`.
- `resources/js/ckeditor.js` — استيراد وتهيئة `SimpleUploadAdapter`, `ImageUpload`, `ImageInsert`, `ImageResize`, `AutoImage` مع ربط الـ CSRF Token وتوفير شريط أدوات متقدم لتغيير المقاسات والمحاذاة والالتفاف.
- `resources/css/app.css` — إضافة فئات CSS متقدمة لدعم محاذاة الصور (`image-style-side`, `image-style-align-left`, `image-style-align-right`, `image-style-align-center`) وهوامش التعليقات التوضيحية (`figcaption`).
- `tests/Feature/AdminCraftImageUploadTest.php` — إنشاء اختبارات آلية للتحقق من أمان وصحة الرفع.

---

## 4. الحل التقني المنفذ (Implemented Solution)

### أ. في الخادم (Backend - Laravel):
- استقبال حقل `upload` المرسل تلقائياً من CKEditor 5.
- التحقق الصارم من صحة ونوع وامتداد الملف:
  ```php
  $validated = $request->validate([
      'upload' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
  ]);
  ```
- تخزين الصور في القرص العام: `storage/app/public/crafts/content/`.
- إرجاع استجابة JSON المتوافقة مع معيار CKEditor 5:
  ```json
  {
      "url": "http://sadat.test/storage/crafts/content/xxxx.jpg"
  }
  ```

### ب. في المحرر (Frontend - CKEditor 5):
- تفعيل خاصية الرفع المباشر عبر `SimpleUploadAdapter`:
  ```javascript
  simpleUpload: {
      uploadUrl: '/admin/crafts/upload-image',
      headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
          'Accept': 'application/json',
      },
  }
  ```
- تفعيل خيارات تغيير الحجم بنسب مئوية (`25%`, `50%`, `75%`, `100%`, `Original`) وسحب المقابض.
- دعم السحب والإفلات (Drag & Drop) ولصق لقطات الشاشة (`Ctrl + V`) مباشرة داخل النص.
- دعم محاذاة الصور مع التفاف النص حولها (`Wrap Text`) أو ككتلة مستقلة (`Break Text`).

---

## 5. طريقة التحقق من نجاح الحل (Validation & Verification)
- **الاختبارات الآلية (Feature Tests):** نجاح كامل لاختبارات `Tests\Feature\AdminCraftImageUploadTest`:
  - منع الزوار غير المسجلين من الرفع (HTTP 401).
  - نجاح رفع وحفظ الصور للمشرفين المعتمدين (HTTP 200) مع توليد رابط عام صالح.
  - رفض الملفات غير المسموحة (HTTP 422).
- **بناء الأصول (Vite Build):** تجميع الحزمة بنجاح في `public/build/assets/ckeditor-*.js`.

---

## 6. الحالة النهائية (Final Status)
**مكتملة بنجاح ✅** — أصبح بإمكان المشرف رفع الصور مباشرة من جهازه، وسحبها وإفلاتها داخل المقال، والتحكم التام في مقاساتها ومحاذاتها بكل سهولة.
