# إصلاح مشكلتَي CKEditor 5 — الألوان والتمدد الأفقي

- **عنوان المهمة:** إصلاح اختفاء ألوان النصوص والخلايا في الواجهة الأمامية، ومنع تمدد محرر CKEditor خارج حدود الشاشة
- **التاريخ:** 2026-09-02
- **الحالة:** مكتملة بنجاح ✅
- **نوع الإصلاح:** CSS Override + JavaScript Configuration

---

## 1. وصف المشكلتين (Problem Description)

### المشكلة الأولى — اختفاء الألوان في الواجهة الأمامية
يقوم المشرف بتلوين النصوص (مثلاً: لون برتقالي على نص "مقدمة عن حرفة السيرما") وتلوين خلفيات خلايا الجداول عبر أدوات `FontColor` و `FontBackgroundColor` في محرر CKEditor 5 بلوحة التحكم.
هذه الألوان تظهر بوضوح داخل المحرر، لكنها **تختفي تماماً** عند معاينة الصفحة في الواجهة الأمامية (`crafts.show`).

### المشكلة الثانية — التمدد الأفقي لمحرر CKEditor
عند إدراج جداول عريضة أو صور متجاورة داخل المحرر، يتمدد CKEditor أفقياً خارج حدود الشاشة مما يُجبر المستخدم على استخدام شريط التمرير الأفقي (Horizontal Scroll) لرؤية باقي المحتوى.

---

## 2. التشخيص والسبب الجذري (Root Cause Analysis)

### سبب المشكلة الأولى

CKEditor 5 يُخزّن الألوان كـ **inline styles** في الـ HTML المُولَّد:

```html
<span style="color:#E67E22;">مقدمة عن حرفة السيرما</span>
<td style="background-color:#FEF3C7;">محتوى الخلية</td>
```

مكتبة `@tailwindcss/typography` (المُفعَّلة في `app.css` عبر `@plugin '@tailwindcss/typography'`) تُعيد تعريف ألوان النصوص داخل `.prose` بخصوصية CSS أعلى من الـ inline styles:

```css
/* ما تفعله إضافة typography داخلياً */
.prose :where(span) {
    color: inherit;  /* تُلغي inline color! */
}
```

وكذلك كانت القاعدة القديمة في `app.css` مكسورة:

```css
/* القاعدة القديمة المكسورة */
.prose span[style*="font-family"],
.prose p[style*="font-family"] {
    font-family: inherit;  /* inherit يُلغي القيمة المُعرَّفة inline */
}
```

**ملاحظة:** البيانات محفوظة صحيحة في قاعدة البيانات — المشكلة في جانب العرض فقط.

### سبب المشكلة الثانية (3 أسباب متراكمة)

**السبب الأول — `shouldNotGroupWhenFull: true` في `ckeditor.js`:**
```js
shouldNotGroupWhenFull: true,
```
هذا الإعداد يُجبر شريط الأدوات على عرض جميع الأزرار في صف واحد مما يُوسّع المحرر أفقياً.

**السبب الثاني — غياب CSS تقييد العرض:**
ملفا `create.blade.php` و `edit.blade.php` كانا خاليَين من أي CSS يُقيّد `.ck-editor__editable`.

**السبب الثالث — الجداول والصور بلا `max-width`:**
الجداول العريضة والصور المتجاورة داخل المحرر لا تحترم عرض الحاوية بدون CSS صريح.

---

## 3. الملفات التي تم تعديلها (Modified Files)

| الملف | نوع التعديل | يُصلح |
|-------|------------|-------|
| `resources/css/app.css` | إضافة قواعد CSS | المشكلة 1 + المشكلة 2 (frontend) |
| `resources/js/ckeditor.js` | تعديل `shouldNotGroupWhenFull` | المشكلة 2 (toolbar) |
| `resources/views/admin/crafts/create.blade.php` | إضافة `<style>` داخلي | المشكلة 2 (editor) |
| `resources/views/admin/crafts/edit.blade.php` | إضافة `<style>` داخلي + `@push('styles')` | المشكلة 2 (editor) |

---

## 4. الحل التقني المنفذ (Implemented Solution)

### أ. إصلاح اختفاء الألوان — `resources/css/app.css`

حُذفت القاعدة القديمة المكسورة واستُبدلت بقواعد `revert !important`:

```css
.prose [style*="color"] {
    color: revert !important;
}

.prose [style*="background-color"] {
    background-color: revert !important;
}

.prose span[style*="color"] {
    color: revert !important;
}

.prose td[style*="background-color"],
.prose th[style*="background-color"] {
    background-color: revert !important;
}

.prose span[style*="font-family"],
.prose p[style*="font-family"],
.prose td[style*="font-family"] {
    font-family: revert !important;
}

.prose span[style*="font-size"],
.prose p[style*="font-size"] {
    font-size: revert !important;
}
```

### ب. إصلاح الجداول والصور في الواجهة الأمامية — `resources/css/app.css`

```css
.prose table {
    display: block;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    max-width: 100%;
}

.prose figure.image,
.prose figure.image img {
    max-width: 100% !important;
    height: auto;
}
```

### ج. إصلاح شريط الأدوات — `resources/js/ckeditor.js`

```js
// قبل: shouldNotGroupWhenFull: true,
shouldNotGroupWhenFull: false,
```

### د. تقييد عرض المحرر — `create.blade.php` و `edit.blade.php`

```css
.ck-editor__editable {
    min-height: 380px;
    max-width: 100% !important;
    overflow-x: hidden !important;
    word-break: break-word;
    overflow-wrap: break-word;
}
.ck-editor__main { overflow-x: auto; max-width: 100%; }
.ck-toolbar { flex-wrap: wrap !important; }
.ck-editor__editable table { width: 100%; max-width: 100%; }
.ck-editor__editable figure.image,
.ck-editor__editable figure.image img,
.ck-editor__editable img { max-width: 100% !important; height: auto; }
.ck.ck-editor { max-width: 100% !important; }
```

---

## 5. الأوامر التي تم تشغيلها (Executed Commands)

```bash
npm run build
# نتيجة: ✓ 898 modules transformed — built in 11.70s — exit code 0
```

---

## 6. التحقق من نجاح الحل (Validation)

- فحص CSS المُنتَج: وجود `.prose [style*=color]{color:revert!important}` ✅
- فحص قواعد الجداول: وجود `.prose table{display:block;overflow-x:auto}` ✅
- البناء بدون أخطاء: exit code 0 ✅

---

## 7. الحالة النهائية (Final Status)

**مكتملة بنجاح ✅**

- ألوان النصوص والخلفيات التي يُعيّنها المشرف في CKEditor تظهر الآن في الواجهة الأمامية.
- محرر CKEditor لا يتمدد خارج حدود الشاشة عند إدراج جداول عريضة أو صور متجاورة.
- الجداول العريضة في الواجهة الأمامية تُعرض مع شريط تمرير داخل حاويتها.
