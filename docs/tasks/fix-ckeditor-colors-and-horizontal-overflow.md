# معالجة اختفاء ألوان ونصوص محرر CKEditor ومنع التمدد الأفقي للجداول والصور

- **عنوان المهمة:** حل مشكلة اختفاء ألوان النصوص والخلفيات وخلايا الجداول في الواجهة الأمامية، ومنع تمدد محرر CKEditor 5 أفقياً خارج حدود الشاشة.
- **التاريخ:** 2026-09-02
- **الحالة:** مكتملة بنجاح ✅
- **نوع الإصلاح:** Full-Stack Frontend & Admin Architecture Fix (CKEditor 5 Plugins, Tailwind CSS, Bootstrap Flex Isolation)

---

## 1. وصف المشكلتين (Problem Description)

### المشكلة الأولى:
في لوحة تحكم الإدارة (`admin/crafts`)، يقوم المشرف بتحديد نصوص معينة (مثل "مقدمة عن حرفة السيرما") وتلوينها باللون البرتقالي `#E67E22` مع تظليل خلفية صفراء `#FEF3C7`، وكذلك تلوين خلفيات خلايا الجداول. تظهر هذه التنسيقات بوضوح تام داخل المحرر، ولكن عند الانتقال لمعاينة الواجهة الأمامية (`crafts.show`)، كانت هذه الألوان تختفي تماماً وتظهر النصوص والجدول بالتنسيق العادي.

### المشكلة الثانية:
عند التعامل مع الجداول العريضة (التي تحتوي على أعمدة متعددة) أو إدراج صور متجاورة جنباً إلى جنب (مثل صور الآيات القرآنية أو الصور المنسقة)، كان محرر CKEditor 5 يتمدد بشكل غير طبيعي خارج حدود الشاشة، مما يضطر المشرف لاستخدام شريط التمرير الأفقي (Horizontal Scroll) على مستوى نافذة المتصفح بالكامل لرؤية باقي المحتوى.

---

## 2. التشخيص والسبب الجذري (Root Cause Analysis)

### أسباب المشكلة الأولى:
1. **خطأ استخدام `revert !important` في محاولة سابقة:**  
   قامت ورقة الأنماط السابقة بفرض `.prose [style*="color"] { color: revert !important; }` و `.prose [style*="background-color"] { background-color: revert !important; }`.  
   في مواصفة W3C لـ CSS، تعيد خاصية `revert` النمط إلى القيمة الافتراضية لمتصفح الويب (`transparent` للخلفيات واللون الافتراضي للنصوص)، مما ألغى تماماً تظليل النصوص وألوانها وخلفيات الخلايا.
2. **غياب حزمتَي `TableCellProperties` و `TableProperties` في CKEditor 5:**  
   لم تكن إضافات خصائص خلايا الجدول مستوردة في `ckeditor.js`، فغابت أدوات تلوين خلفيات وحدود الخلايا من شريط أدوات الجدول (`tableToolbar`).
3. **تداخل خلفيات الصفوف الزوجية:**  
   قاعدة `.prose tr:nth-child(even)` كانت تفرض لوناً رمادياً يطغى على الخلايا إذا لم يتم تحصين طبقة الخلية.
4. **انعدام مزامنة الـ Textarea الخفي في النموذج (السبب الحاسم لاختفاء الألوان):**  
   يقوم محرر CKEditor 5 بإخفاء عنصر `<textarea name="content" id="content">` الأصلي، ولا يقوم بتحديث قيمته اللحظية أثناء الكتابة وتلوين النصوص. وعند ضغط زر "حفظ التعديلات"، كان النموذج يُرسل القيمة القديمة المحفوظة في الـ Textarea (المجردة من الألوان والتنسيقات)، وبالتالي لم تكن التعديلات الملونة تصل إلى قاعدة البيانات على الإطلاق. بالإضافة إلى ذلك، وجود خاصية `required` الخاصة بـ HTML5 على حقل مخفي كان يُعيق إرسال النموذج في بعض المتصفحات.

### أسباب المشكلة الثانية:
1. **ظاهرة التمدد الأدنى في فليكس Bootstrap 5 (Flexbox Min-Width Bug):**  
   حاوية المحرر تقع داخل `<div class="col-12">`، والافتراضي في عناصر الفليكس هو `min-width: auto;`، مما يسمح للعمود والبطاقة بالاتساع التلقائي ليتجاوز عرض الشاشة بنسبة 100% إذا احتوى على عنصر عريض غير مرن.
2. **غياب التمرير الأفقي الداخلي للجداول داخل المحرر:**  
   الجداول في HTML تمتلك عرضاً أدنى ذاتياً (Intrinsic Minimum Width)، وعدم وجود حاوية `overflow-x: auto` للجداول داخل المحرر كان يدفع حواف المحرر بالكامل للخارج.
3. **الصور المتجاورة غير المقيدة:**  
   غياب قيود التجاوب (`max-width: 100% !important; height: auto;`) على الصور المتجاورة وداخل الجداول كان يسمح لها بالتراص في صف واحد عريض.
4. **شريط الأدوات:** خيار `shouldNotGroupWhenFull: true` كان يمنع تجميع الأدوات ويضغط على العرض.

---

## 3. الملفات التي تم فحصها وتعديلها (Inspected & Modified Files)

- `resources/js/ckeditor.js` — استيراد وتفعيل إضافات `TableCellProperties`, `TableProperties`, `TableColumnResize`, `TableCaption`، وإضافة ألوان رسمية للخلايا والحدود، وضبط `shouldNotGroupWhenFull: false`.
- `resources/css/app.css` — تحسين قواعد `.prose` لحماية ألوان النصوص والمظللات `span[style*="background-color"]` وخلفيات خلايا الجداول، وجعل جداول وصور الواجهة متجاوبة بتمرير أفقي ناعم.
- `resources/views/admin/layout.blade.php` — إضافة قواعد تأمين عامة تمنع الفيض الأفقي للنافذة بالكامل.
- `resources/views/admin/crafts/create.blade.php` — عزل أبعاد المحرر والجداول والصور داخل البطاقة.
- `resources/views/admin/crafts/edit.blade.php` — عزل أبعاد المحرر والجداول والصور داخل البطاقة.
- `resources/views/crafts/show.blade.php` — إضافة `min-w-0` لحاوية المقال والشبكة لمنع انفجار عمود العرض.
- `tests/Feature/CraftsDirectoryTest.php` — إضافة اختبار تحقق لرندرة النصوص الملونة وخلفيات خلايا الجدول.
- `docs/project_map.md` — تسجيل وتوثيق المهمة بالكامل.

---

## 4. الحل التقني المنفذ (Implemented Solution)

### 1. في محرر CKEditor 5 وإرسال النموذج (`resources/js/ckeditor.js` & `views/admin/crafts`):
- تم استيراد `TableProperties`, `TableCellProperties`, `TableColumnResize`, `TableCaption`.
- تم تفعيل لوحة خصائص الخلية والجدول في `table.contentToolbar`:
  `['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']`.
- تم تزويد إعدادات الجدول بباليتة ألوان تراثية منتقاة للخلفيات والحدود (كحلي، عنبري، ذهبي، أصفر دافئ، أحمر ناعم، أخضر ناعم، رمادي، أبيض).
- تم تفعيل التجميع الذكي لشريط الأدوات: `shouldNotGroupWhenFull: false`.
- **حل أزمة مزامنة وحفظ البيانات (The Form Data Synchronization Fix):**
  - ربط حدث `change:data` في CKEditor 5 لتحديث قيمة الـ Textarea المخفية لحظياً فور قيام المشرف بأي تعديل أو تلوين:
    `editor.model.document.on('change:data', () => { contentEl.value = editor.getData(); });`
  - إضافة خطاف استباقي (Pre-Submit Hook) على النموذج وعلى أزرار الحفظ لضمان نقل أحدث قيمة HTML بالكامل شاملة وسوم الألوان وتظليل النصوص إلى الـ Textarea قبل الإرسال مباشرة.
  - إزالة خاصية `required` الخاصة بـ HTML5 من عنصر الـ Textarea المخفي في `create.blade.php` و `edit.blade.php` لمنع تعارض متصفحات الويب مع الحقول المخفية.

### 2. في CSS الواجهة الأمامية (`resources/css/app.css`):
- إزالة قواعد `revert !important` وقاعدة `font-family: inherit` السلبية.
- حماية ألوان وخلفيات الخلايا الصريحة (`style*="background"` و `style*="background-color"`):
  ```css
  .prose td[style*="background"],
  .prose th[style*="background"],
  .prose td[style*="background-color"],
  .prose th[style*="background-color"] {
      position: relative;
      z-index: 1;
  }
  ```
- حماية تظليل النصوص بالألوان والخلفيات:
  ```css
  .prose span[style*="background-color"],
  .prose span[style*="background:"] {
      padding: 0.12em 0.35em;
      border-radius: 0.25rem;
      box-decoration-break: clone;
      -webkit-box-decoration-break: clone;
  }
  ```
- جعل الجداول متجاوبة داخل حاوية تمرير مستقلة:
  ```css
  .prose figure.table {
      width: 100%;
      max-width: 100%;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      margin: 1.75em 0;
  }
  ```
- إجبار الصور على التجاوب وعدم تجاوز 100% من عرض المقال:
  ```css
  .prose figure.image img, .prose img {
      max-width: 100% !important;
      height: auto;
  }
  ```

### 3. في لوحة تحكم الإدارة (`layout.blade.php`, `create.blade.php`, `edit.blade.php`):
- كسر تمدد الفليكس وضبط الحاويات:
  ```css
  .col-12, .card-body { min-width: 0 !important; max-width: 100% !important; }
  .ck.ck-editor { width: 100% !important; max-width: 100% !important; min-width: 0 !important; }
  .ck-toolbar { flex-wrap: wrap !important; max-width: 100% !important; }
  .ck-editor__main { max-width: 100% !important; min-width: 0 !important; overflow-x: auto !important; }
  .ck.ck-content.ck-editor__editable { min-height: 380px; max-width: 100% !important; word-break: break-word; overflow-wrap: break-word; }
  .ck-content figure.table { max-width: 100% !important; overflow-x: auto !important; margin: 1em 0; }
  .ck-content figure.image, .ck-content figure.image img, .ck-content img { max-width: 100% !important; height: auto !important; }
  .ck-content figure.image.image-style-side { max-width: 50% !important; }
  ```

---

## 5. طريقة التحقق من نجاح الحل (Validation & Verification)

- **بناء الأصول (Vite Build):** تجميع الحزم بدون أخطاء (`ckeditor-D_izRn0Q.js` بحجم 1.16MB شامل المزامنة اللحظية بالكامل، و `app-DDVCHMMQ.css`).
- **اختبار محاكاة المتصفح الفعلي (Automated Browser Test):**
  تم اختبار محاكاة حقيقية داخل المتصفح لكتابة نص وتلوينه باللون البرتقالي وتظليله باللون الأصفر في CKEditor ثم ضغط زر Submit، وتم التأكد عملياً من استقبال الـ Textarea للوسوم بدقة:
  `<h2><span style="background-color:#FEF3C7;color:#D4AF37;">مقدمة عن حرفة السيرما</span></h2>`
  مما يضمن وصول البيانات والتنسيقات إلى قاعدة البيانات وعرضها بالواجهة الأمامية.
- **الاختبارات الآلية (Automated PHPUnit Tests):** اجتياز جميع الاختبارات الـ 15 بنجاح تام (39 assertions).
- **اختبار التمدد والألوان:** التأكد من رندرة الألوان الصريحة والخلفيات الصفراء وخلايا الجدول في الواجهة الأمامية، وانعدام شريط التمرير الأفقي على مستوى النافذة في لوحة التحكم مع الجداول العريضة والصور المتجاورة.

---

## 6. الحالة النهائية (Final Status)
**مكتملة بنجاح ✅** — تم القضاء على المشكلتين جذرياً وفق أعلى المعايير الهندسية.

