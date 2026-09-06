# 🔐 تقرير التدقيق الأمني الشامل

**المشروع:** توثيق الحرف التراثية بمحافظة المنوفية — جامعة مدينة السادات
**الإطار التقني:** Laravel 13 + PHP 8.3 + MySQL/SQLite + Fortify + CKEditor 5 + Tailwind 4
**تاريخ الفحص:** 9/6/2026
**نوع الفحص:** مراجعة ثابتة للمصدر (Static Source Code Review) + فحص إعدادات النشر والتخزين
**ملاحظة هامة:** هذا التقرير وصفي فقط، ولم يتم إجراء أي تعديل على أي ملف من ملفات المشروع.

---

## 1. ملخص تنفيذي (Executive Summary)

المشروع تطبيق Laravel سليم البنية إلى حدٍّ كبير (يستخدم `Fortify` للمصادقة، و`Eloquent` مع حماية من الحقن، وطبقة تطهير HTML مخصّصة، وترويسات أمنية). **إلا أنه يحتوي على عدد من الثغرات والتجاوزات الأمنية المهمة** تتراوح بين **حرجة** و**منخفضة**، أهمها:

1. تشغيل التطبيق بوضع **التصحيح (APP_DEBUG=true)** وببيئة **local** في ملف الإعدادات الفعلي.
2. **بيانات دخول مدير افتراضية مثبتة في الكود** (`admin@sadat.test` / `password` / `super_admin`).
3. **استراتيجية نشر غير آمنة** تعتمد كلياً على ملف `.htaccess` واحد لحماية كل ملفات المشروع (بما فيها `.env` و`.git` وقاعدة البيانات) من الوصول المباشر.
4. **مُطهّر HTML مكتوب بتعبيرات نمطية (Regex)** قابل للتجاوز، والمحتوى يُعرض بدون تهريب (`{!! !!}`) في 3 صفحات عامة → **ثغرة XSS مخزنة (Stored XSS)**.
5. **غياب ترويسات CSP و HSTS**، وغياب ملف `.htaccess` داخل مجلد التخزين العام مما يُبقي باب رفع الأكواد مفتوحاً نظرياً.

**التقدير العام للمخاطر:** 🟠 **مرتفع** — يجب معالجة البنود الحرجة والعالية قبل النشر على الإنتاج.

---

## 2. منهجية الفحص

- مراجعة يدوية ملف-بملف لكل من: `routes/`, `app/Http/Controllers/`, `app/Http/Middleware/`, `app/Models/`, `app/Services/`, `bootstrap/`, `config/`, `database/`, `resources/views/`, `resources/js/`, ملفات الجذر.
- فحص إعدادات Apache (`.htaccess` الجذر + `public/.htaccess`).
- فحص حالة Git (الملفات المتتبعة، تاريخ الملفات الحساسة، المستودع البعيد).
- فحص ملفات البيئة (`.env`, `.env.example`) والسجلات (`storage/logs/laravel.log`).
- فحص آليات رفع الملفات وسلسلة الوصول إلى الـ Web Shell.
- لم يتم تنفيذ أي اختبار اختراق ديناميكي (Black-box) — التقرير يعتمد على تحليل المصدر.

---

## 3. جدول ملخص المخاطر

| # | الثغرة / المشكلة | الملف/الموقع | الشدة |
|---|---|---|---|
| C-1 | وضع التصحيح والبيئة المحلية مفعّلان | `.env` | 🔴 حرجة |
| C-2 | بيانات دخول مدير افتراضية مثبتة بالكود | `database/seeders/AdminUserSeeder.php` | 🔴 حرجة |
| C-3 | استراتيجية نشر تعتمد على `.htaccess` فقط لحماية كل الملفات | `docs/DEPLOYMENT_GUIDE.md`, `.htaccess` | 🔴 حرجة |
| C-4 | بيانات قاعدة البيانات ضعيفة (root بدون كلمة مرور) | `.env` | 🔴 حرجة |
| H-1 | مُطهّر HTML بتعبيرات نمطية قابل للتجاوز → XSS مخزنة | `app/Services/HtmlSanitizer.php` | 🟠 عالية |
| H-2 | عرض المحتوى بدون تهريب `{!! !!}` | 3 ملفات Blade | 🟠 عالية |
| H-3 | CKEditor يسمح بكل الوسوم/الأحداث + SourceEditing | `resources/js/ckeditor.js` | 🟠 عالية |
| H-4 | غياب CSP و HSTS | `app/Http/Middleware/SecurityHeaders.php` | 🟠 عالية |
| H-5 | مجلد `.git` داخل جذر الويب | جذر المشروع | 🟠 عالية |
| H-6 | لا يوجد `.htaccess` داخل مجلد التخزين العام | `storage/app/public/` | 🟠 عالية |
| M-1 | رفع ملفات صوتية حتى 50MB (استنزاف) | `Admin/CraftsmanStoryController.php` | 🟡 متوسطة |
| M-2 | ملفات مرفوعة (محتويات المستخدم) مودعة في Git | `storage/app/public/crafts/*.jpg` | 🟡 متوسطة |
| M-3 | سجل يحتوي آثاراً ومعلومات داخلية | `storage/logs/laravel.log` | 🟡 متوسطة |
| M-4 | الجلسات غير مشفرة وبدون خاصية Secure | `config/session.php`, `.env` | 🟡 متوسطة |
| M-5 | مكتبات خارجية عبر CDN بدون SRI | العروض | 🟡 متوسطة |
| M-6 | نظام مزدوج (موقع ثابت + Laravel) وملفات يتيمة | جذر المشروع | 🟡 متوسطة |
| L-1 | `robots.txt` يسمح بالزحف الكامل | `public/robots.txt` | 🟢 منخفضة |
| L-2 | `APP_DEBUG=true` افتراضياً في النموذج | `.env.example` | 🟢 منخفضة |
| L-3 | صفحة Laravel الافتراضية غير المستخدمة | `resources/views/welcome.blade.php` | 🟢 منخفضة |
| L-4 | مهلة الجلسة 120 دقيقة | `.env` | 🟢 منخفضة |

---

## 4. الثغرات الحرجة (Critical)

### C-1 — وضع التصحيح والبيئة المحلية مفعّلان في ملف البيئة الفعلي
**الموقع:** `c:\laragon\www\sadat\.env` (الأسطر 2 و 4)

```dotenv
APP_ENV=local
APP_DEBUG=true
```

**الخطورة:** عند تشغيل التطبيق بهذه القيم، تُعرض صفحات الأخطاء التفصيلية (Whoops) كاملةً مع **تتبّع المكدس (Stack Trace) ومتغيرات البيئة وأسماء الجداول واستعلامات SQL ومسارات الملفات الكاملة** لأي زائر. هذا يكشف بنية التطبيق الداخلية ويسهّل استغلال ثغرات أخرى.

**الإصلاح الموصى به:** في بيئة الإنتاج يجب أن تكون القيم كالتالي:
```dotenv
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error
```

---

### C-2 — بيانات دخول مدير افتراضية مثبتة داخل الكود المصدري
**الموقع:** `c:\laragon\www\sadat\database\seeders\AdminUserSeeder.php`

```php
User::updateOrCreate(
    ['email' => 'admin@sadat.test'],
    [
        'name' => 'Administrator',
        'username' => 'admin',
        'role' => 'super_admin',      // ← صلاحية مدير النظام الكاملة
        'password' => 'password',     // ← كلمة مرور افتراضية ضعيفة
        'email_verified_at' => now(),
    ],
);
```

**الخطورة:** إذا نُفّذ أمر `php artisan db:seed` (أو `migrate --seed`) على الخادم — وهو أمر شائع أثناء النشر — فسيتم إنشاء حساب **مدير نظام (super_admin)** بكلمة مرور معروفة علناً `password`. أي مهاجم يعرف هذا يحصل على تحكم كامل بالمنصة (إدارة المستخدمين، رفع المحتوى، الصلاحيات).

**الإصلاح الموصى به:**
- حذف هذا الـ Seeder من بيئة الإنتاج نهائياً أو حصره بـ `if (!app()->environment('production'))`.
- استخدام `Str::random()` بدلاً من كلمة مرور ثابتة، أو فرض تغيير كلمة المرور عند أول تسجيل دخول.
- عدم تثبيت أي بيانات اعتماد في الكود نهائياً.

---

### C-3 — استراتيجية النشر تعتمد على `.htaccess` واحد لحماية كل ملفات المشروع
**الموقع:** `c:\laragon\www\sadat\docs\DEPLOYMENT_GUIDE.md` (الطريقة الأولى) و `c:\laragon\www\sadat\.htaccess`

يوضح دليل النشر «الطريقة الأولى» وضع **كل ملفات المشروع** (بما فيها `.env`, `.git`, `vendor`, `database.sqlite`, `storage`) مباشرة داخل `public_html`، والاعتماد على ملف `.htaccess` الجذر التالي فقط:

```apache
RewriteEngine On
# حظر جزئي
RewriteRule ^(\.env|\.git|composer\.(json|lock)|package\.(json|lock)|artisan|storage/logs) - [F,L,NC]
# توجيه كل الطلبات إلى public/
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^(.*)$ public/$1 [L,QSA]
```

**المشاكل الجوهرية:**
1. **الحماية هشّة بطبيعتها:** إذا كان `AllowOverride None` على الخادم، أو لم تُفعَّل وحدة `mod_rewrite`، يُتجاهل ملف `.htaccess` كلياً وتُصبح **كل الملفات الحساسة قابلة للتنزيل المباشر** (`/.env`, `/.git/`, `/database/database.sqlite`, `/vendor/...`).
2. **القائمة السوداء غير كاملة:** لا تحظر `.env.example`, `database/`, `config/`, `bootstrap/`, `docs/`, `vendor/`, `storage/app/public/`, `tests/`, `includes/`.
3. الحماية تعمل فقط لأن التوجيه يُرسل كل شيء إلى `public/` (فلا يوجد الملف هناك) — وهي مصادفة معمارية وليست حماية مقصودة للملفات غير المدرجة.

**الإصلاح الموصى به:** اعتماد **الطريقة الثانية حصراً** (فصل ملفات المشروع خارج جذر الويب وربط `public/` فقط عبر Symlink)، وعدم نشر أي ملف خارج `public/` في جذر الويب إطلاقاً.

---

### C-4 — بيانات قاعدة البيانات ضعيفة
**الموقع:** `c:\laragon\www\sadat\.env` (الأسطر 24–29)

```dotenv
DB_CONNECTION=mysql
DB_DATABASE=sadat
DB_USERNAME=root
DB_PASSWORD=
```

**الخطورة:** اسم مستخدم `root` بدون كلمة مرور. في حال تمكّن المهاجم من أي ثغرة قراءة ملفات (LFI) أو تسريب `.env`، يحصل على صلاحيات كاملة على قاعدة البيانات. حتى في البيئة المحلية يوصى بكلمة مرور قوية.

**الإصلاح الموصى به:** إنشاء مستخدم قاعدة بيانات مخصص بأقل صلاحيات مطلوبة وكلمة مرور قوية.

---

## 5. الثغرات العالية (High)

### H-1 — مُطهّر HTML مكتوب بتعبيرات نمطية قابل للتجاوز (Stored XSS)
**الموقع:** `c:\laragon\www\sadat\app\Services\HtmlSanitizer.php`

يعتمد التطهير على سلسلة `preg_replace` لاقتلاع الوسوم والأحداث، وهي الطريقة المعروفة بسهولة تجاوزها مقابل المحللات الحقيقية (مثل `HTMLPurifier`). من أمثلة متجهات التجاوز المحتملة:
- تشفير الكيانات المتكرر (`&lt;svg onload=...`) أو الكيانات السداسية/العشرية.
- التلاعب بالمسافات البيضاء وفواصل الأسطر داخل الوسم.
- دمج وسوم متداخلة تكسر النمط النمطي.
- استخدام `srcset`, `style` مع `url()` بترميزات مزدوجة.

**السبب الجذري:** التعبيرات النمطية **لا تتعامل مع HTML بشكل موثوق**، وقد وُثّقت عمليات تجاوز متكررة لهذا النوع من المطهّرات.

**الإصلاح الموصى به:** استبدال `HtmlSanitizer` بمكتبة معتمدة مثل **`mews/purifier` (HTMLPurifier)** أو `symfony/html-sanitizer`، وتكوين قائمة سماح صارمة (whitelist) للوسوم والخصائص.

---

### H-2 — عرض المحتوى بدون تهريب (Unescaped Output)
**المواقع:**
- `c:\laragon\www\sadat\resources\views\crafts\show.blade.php` (سطر 112)
- `c:\laragon\www\sadat\resources\views\stories\show.blade.php` (سطر 106)
- `c:\laragon\www\sadat\resources\views\workshops\show.blade.php` (سطر 134)

```blade
{!! $craft->content !!}
{!! $story->content !!}
{!! $workshop->content !!}
```

**الخطورة:** المحتوى القادم من CKEditor يُعرض بدون تهريب. الدفاع الوحيد هو `HtmlSanitizer` (البند H-1). أي تجاوز له → تنفيذ JavaScript عشوائي في متصفح الزائر (ومنها سرقة جلسات المسؤولين عبر CSRF token / Session Hijacking) → **استيلاء كامل على لوحة التحكم**.

**الإصلاح الموصى به:** تقوية المطهّر (H-1) + إضافة **CSP** كطبقة دفاع ثانية، والنظر في التهريب التلقائي مع السماح بقائمة وسوم آمنة فقط.

---

### H-3 — CKEditor يسمح بكل الوسوم والأحداث + تفعيل SourceEditing
**الموقع:** `c:\laragon\www\sadat\resources\js\ckeditor.js` (سطر 95 و 249)

```js
SourceEditing,                       // تحرير HTML الخام
htmlSupport: {
    allow: [{ name: /.*/, attributes: true, classes: true, styles: true }],
},
```

**الخطورة:** إعداد `htmlSupport` يسمح بأي وسم وأي خاصية وأي صنف وأي نمط، ما يعني أن المحرر نفسه لا يقوم بأي فلترة، وكل الاعتماد يقع على المطهّر الخادمي (H-1). هذا يُوسّع سطح الهجوم ويجعل أي تجاوز في المطهّر الخادمي أسهل استغلالاً.

**الإصلاح الموصى به:** تضييق `htmlSupport` لقائمة وسوم/خصائص محددة، وتقييد `SourceEditing` للمسؤولين فقط، والاعتماد على فلترة خادمية قوية.

---

### H-4 — غياب ترويسات CSP و HSTS
**الموقع:** `c:\laragon\www\sadat\app\Http\Middleware\SecurityHeaders.php`

الترويسات المُضافة حالياً: `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`.

**الغائب المهم:**
- **Content-Security-Policy (CSP):** أقوى خط دفاع ضد XSS، ولم يُضف.
- **Strict-Transport-Security (HSTS):** يفرض HTTPS، ولم يُضف.

**الإصلاح الموصى به:** إضافة الترويسات:
```php
$response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
$response->headers->set('Content-Security-Policy', "default-src 'self'; img-src 'self' data: https:; style-src 'self' 'unsafe-inline' https:; script-src 'self' https:; font-src 'self' https:;");
```

---

### H-5 — مجلد `.git` داخل جذر الويب
**الموقع:** `c:\laragon\www\sadat\.git\`

**الخطورة:** المستودع يحتوي كامل تاريخ الكود المصدري. في وضع النشر «الطريقة الأولى» (C-3) يكون `.git` داخل `public_html`. إذا تعطّل `.htaccess` لأي سبب، يستطيع المهاجم تنزيل `/.git/` بالكامل (أو استخراج محتوياته بأدوات مثل GitHacker) والحصول على كامل المصدر والتاريخ.

**الإصلاح الموصى به:** عدم نشر مجلد `.git` في جذر الويب إطلاقاً، والاعتماد على النشر عبر `public/` فقط.

---

### H-6 — لا يوجد ملف `.htaccess` داخل مجلد التخزين العام (خطر تنفيذ PHP)
**الموقع:** `c:\laragon\www\sadat\storage\app\public\` (لا يحتوي `.htaccess`)

الملفات المرفوعة تُخزّن في `storage/app/public/...` وتُقدَّم عبر الرابط الرمزي `public/storage`. **لا يوجد أي ملف يمنع تنفيذ PHP داخل هذا المجلد.**

**الخطورة:** إذا تمكن أي ملف بامتداد قابل للتنفيذ (`.php`, `.phtml`, `.php5`…) من الوصول لهذا المجلد (عبر ثغرة رفع، أو عبر ثغرة أخرى تسمح بكتابة ملفات)، فسيعالجه الخادم كـ PHP ويُنفّذه → **Web Shell**. هذا هو المسار الأكثر واقعية لاختراق كامل.

**الإصلاح الموصى به:** إضافة `.htaccess` داخل `storage/app/public/`:
```apache
<FilesMatch "\.(php|phtml|php3|php4|php5|php7|phar|cgi|pl|py|asp|aspx|sh)$">
    Require all denied
</FilesMatch>
Options -Indexes -ExecCGI
```
أو تهيئة الخادم لتعطيل تنفيذ PHP داخل `/storage` كلياً.

---

## 6. الثغرات المتوسطة (Medium)

### M-1 — رفع ملفات صوتية حتى 50MB
**الموقع:** `c:\laragon\www\sadat\app\Http\Controllers\Admin\CraftsmanStoryController.php` (سطر 44)

```php
'audio_file' => 'nullable|file|mimes:mp3,wav,m4a,aac,ogg|max:51200',  // 50MB
```

**الخطورة:** حجم أقصى كبير (50MB) لكل رفع صوتي، مما يسمح باستنزاف مساحة القرص (DoS) عند رفع متكرر. الحجم مقبول للصوت لكن يجب اقترانه بحدود على مستوى الخادم (`upload_max_filesize`, `post_max_size`) وعدد الطلبات.

**الإصلاح الموصى به:** خفض الحجم إلى قيمة منطقية (مثلاً 20MB) وفرض حدود خادمية.

---

### M-2 — ملفات مرفوعة (محتويات المستخدم) مودعة في Git
**الموقع:** `c:\laragon\www\sadat\storage\app\public\crafts\ubKg7K8ilr7xOElxTFFcOdoDzESpk2GlLnMZ1LWX.jpg` (ملف مرفوع باسم عشوائي) مع صور أخرى.

**السبب:** `storage/app/public/.gitignore` يسمح صراحةً بإيداع محتوى `crafts/`:
```
*
!.gitignore
!crafts/
!crafts/*
```

**الخطورة:** خلط محتوى المستخدم المرفوع مع الكود المصدري، مما قد يُدخل ملفات غير مرغوبة للمستودع ويصعّب التمييز بين محتوى رسمي ومحتوى مرفوع.

**الإصلاح الموصى به:** تجاهل محتوى `storage/app/public/*` بالكامل (باستثناء `.gitignore`)، ووضع الصور الرسمية في `public/assets` بدلاً من مجلد الرفع.

---

### M-3 — سجل يحتوي آثاراً ومعلومات داخلية
**الموقع:** `c:\laragon\www\sadat\storage\logs\laravel.log` (حوالي 272KB)

يحتوي على **تتبّع مكدس، استعلامات SQL، مسارات مطلقة** (`C:/laragon/www/sadat/...`)، وأسماء جداول/قواعد بيانات.

**الخطورة:** كشف معلومات داخلية إذا تعرض السجل للوصول. (الجذر `.htaccess` يحظر `storage/logs` لكن الحماية هشّة كما في C-3.)

**الإصلاح الموصى به:** ضبط `LOG_LEVEL=error` في الإنتاج، وتدوير السجلات، وعدم نشر مجلد `storage` في جذر الويب.

---

### M-4 — الجلسات غير مشفرة وبدون خاصية Secure
**الموقع:** `.env` و `config/session.php`

```dotenv
SESSION_ENCRYPT=false
SESSION_SECURE_COOKIE=  (غير مضبوط)
```

**الخطورة:**
- عدم تشفير الجلسات يجعل محتواها قابلاً للقراءة إن تسربت قاعدة بيانات الجلسات.
- عدم تفعيل `Secure` يعني إرسال كوكيز الجلسة عبر HTTP غير المشفر (قابل للاعتراض MITM).

**الإصلاح الموصى به:** تفعيل `SESSION_ENCRYPT=true` و`SESSION_SECURE_COOKIE=true` في الإنتاج مع HTTPS.

---

### M-5 — مكتبات خارجية عبر CDN بدون SRI
**المواقع:** `includes/header.php`, `resources/views/layouts/app.blade.php`, العروض.

يتم تحميل Tailwind CDN وFontAwesome وAOS وTabler وLeaflet وGoogle Fonts من شبكات خارجية **بدون خاصية Subresource Integrity (SRI)**.

**الخطورة:** إذا تعرضت شبكة CDN للاختراق (Supply-Chain Attack)، يُحقن كود خبيث في الموقع دون أي تحقق.

**الإصلاح الموصى به:** استضافة المكتبات محلياً عبر Vite أو إضافة `integrity` + `crossorigin` للروابط.

---

### M-6 — نظام مزدوج (موقع ثابت + Laravel) وملفات يتيمة في الجذر
**المواقع:** `c:\laragon\www\sadat\index.php`, `includes\header.php`, `includes\footer.php`, `template.html`, `Menofia_handicrafts_workshops_map.html`

**الخطورة:** وجود موقع ثابت قديم (index.php الجذر مع `include`) بجانب تطبيق Laravel يخلق ارتباكاً في مسارات الوصول، ويزيد من مساحة الهجوم (ملفات PHP في الجذر يمكن الوصول إليها مباشرة إذا تعطل `.htaccess`)، ويصعّب الصيانة الأمنية.

**الإصلاح الموصى به:** حذف الملفات القديمة أو نقلها خارج جذر الويب، والاعتماد على Laravel فقط.

---

## 7. الثغرات المنخفضة (Low)

### L-1 — `robots.txt` يسمح بالزحف الكامل
**الموقع:** `c:\laragon\www\sadat\public\robots.txt`
```
User-agent: *
Disallow:
```
لا يمنع فهرسة أي مسار. **الإصلاح:** منع `Disallow: /admin` على الأقل.

### L-2 — `APP_DEBUG=true` افتراضياً في ملف النموذج
**الموقع:** `c:\laragon\www\sadat\.env.example` — قد ينسخه المطوّر كما هو في الإنتاج. **الإصلاح:** جعل القيمة `false`.

### L-3 — صفحة Laravel الافتراضية غير المستخدمة
**الموقع:** `c:\laragon\www\sadat\resources\views\welcome.blade.php` — صفحة افتراضية غير مستخدمة. **الإصلاح:** حذفها.

### L-4 — مهلة الجلسة 120 دقيقة
**الموقع:** `.env` (`SESSION_LIFETIME=120`). **الإصلاح:** خفضها لمنطقة الإدارة.

---

## 8. تحليل خاص: مسارات رفع الملفات وخطر الـ Web Shell

### 8.1 نقاط الرفع الموجودة
| النقطة | المسار | القيود |
|---|---|---|
| رفع صورة المحتوى (CKEditor) | `POST /admin/crafts/upload-image` → `CraftController::uploadImage` | `image\|mimes:jpg,jpeg,png,webp,gif\|max:5120` |
| صورة الغلاف (الحِرف) | `CraftController::store/update` | `image\|mimes:jpg,jpeg,png,webp\|max:2048` |
| صورة الغلاف (الورش) | `WorkshopController::store/update` | `image\|mimes:jpg,jpeg,png,webp\|max:2048` |
| صورة الحرفي | `CraftsmanStoryController::store/update` | `image\|mimes:jpg,jpeg,png,webp\|max:5120` |
| ملف صوتي | `CraftsmanStoryController::store/update` | `file\|mimes:mp3,wav,m4a,aac,ogg\|max:51200` |

### 8.2 التقييم
- **الإيجابيات:** جميع النقاط محمية بميدل وير `auth` (تتطلب تسجيل دخول)، وتستخدم `$request->validate()` بقواعد `image` و`mimes`، ويتم التخزين عبر `->store()` الذي يولّد **اسماً عشوائياً** (لا يستخدم اسم الملف الأصلي) — مما يمنع **Path Traversal** عبر اسم الملف.
- **نقاط الضعف:**
  1. قاعدة `mimes`/`image` تعتمد على فحص MIME، وهي قوية لكنها **ليست مضمونة 100%** ضد ملفات Polyglot (صورة صالحة تحتوي كوداً).
  2. **لا يوجد `.htaccess` يمنع تنفيذ PHP في مجلد التخزين** (البند H-6) — هذا هو الخطر الحقيقي: أي ملف قابل للتنفيذ يصل للمجلد يُنفَّذ.
  3. امتداد الملف الأصلي يُحتفظ به في `store()`، فإذا تمكن ملف `.php` من المرور لسبب ما، يُخزَّن بامتداده القابل للتنفيذ.
  4. الرفع عبر CKEditor يعيد عنوان URL عاماً (`Storage::url`) فيتم تقديم الملف مباشرة.

### 8.3 سيناريو الاختراق الأكثر واقعية
1. اختراق حساب مسؤول (عبر كلمة المرور الافتراضية C-2، أو XSS في H-1/H-2 لسرقة الجلسة).
2. محاولة رفع ملف خبيث عبر نقاط الرفع.
3. الاستفادة من غياب حظر التنفيذ في `storage/app/public` لتنفيذ الـ Web Shell.

**الخلاصة:** باب رفع الشل **مغلق جزئياً** (قيود التحقق + أسماء عشوائية)، لكنه **غير مقفل بالكامل** بسبب H-6 وH-1/H-2 وC-2.

---

## 9. قائمة الملفات الخطرة / الحساسة

| الملف | لماذا هو خطير |
|---|---|
| `.env` | يحتوي APP_KEY حقيقي + بيانات قاعدة البيانات + وضع التصحيح |
| `.git/` | كامل تاريخ المصدر |
| `database/database.sqlite` (~94KB) | قاعدة بيانات محلية قد تحوي مستخدمين وجلسات |
| `storage/logs/laravel.log` (~272KB) | آثار مكدس + استعلامات SQL + مسارات داخلية |
| `database/seeders/AdminUserSeeder.php` | بيانات دخول مدير افتراضية |
| `docs/DEPLOYMENT_GUIDE.md` | يكشف مسار الخادم، النطاق، ورابط المستودع العام |
| `storage/app/public/crafts/*.jpg` | ملفات مرفوعة من المستخدم مودعة في Git |
| `resources/views/welcome.blade.php` | صفحة افتراضية غير مستخدمة |
| `index.php` (الجذر) + `includes/` + `template.html` + `Menofia_handicrafts_workshops_map.html` | ملفات موقع ثابت قديم داخل الجذر |

---

## 10. التوصيات والإصلاحات (حسب الأولوية)

### أولوية قصوى (فوراً)
1. `APP_ENV=production` و `APP_DEBUG=false` في الإنتاج.
2. إزالة/تقييد `AdminUserSeeder` واستبدال كلمة المرور الافتراضية.
3. اعتماد النشر الآمن (فصل `public/` فقط في جذر الويب).
4. إنشاء مستخدم قاعدة بيانات مخصص بكلمة مرور قوية.
5. إضافة `.htaccess` يمنع تنفيذ PHP داخل `storage/app/public`.

### أولوية عالية
6. استبدال `HtmlSanitizer` بمكتبة HTMLPurifier مع قائمة سماح صارمة.
7. إضافة ترويسات `CSP` و `HSTS`.
8. تضييق `htmlSupport` في CKEditor وتقييد `SourceEditing`.
9. تدوير مفتاح التطبيق (`php artisan key:generate`) إذا كان المفتاح الحالي قد تعرض لأي تسريب، ونقل `.env` خارج أي مسار ويب.

### أولوية متوسطة
10. خفض حجم رفع الصوت وفرض حدود خادمية.
11. تجاهل محتوى `storage/app/public/*` في Git.
12. `SESSION_ENCRYPT=true` و `SESSION_SECURE_COOKIE=true`.
13. استضافة المكتبات محلياً أو إضافة SRI.
14. إزالة الموقع الثابت القديم (ملفات الجذر اليتيمة).
15. `LOG_LEVEL=error` وتدوير السجلات.

### أولوية منخفضة
16. تحديث `robots.txt` لمنع `/admin`.
17. حذف `welcome.blade.php`.
18. خفض مهلة الجلسة.

---

## 11. قائمة تحقق أمنية قبل النشر (Checklist)

- [ ] `APP_DEBUG=false` و `APP_ENV=production`
- [ ] لا توجد بيانات اعتماد مثبتة في الكود/الـ Seeders
- [ ] `public/` فقط معرَّض للويب (لا `.git`, `.env`, `vendor`, `storage`, `database`)
- [ ] `.htaccess` يمنع تنفيذ PHP في `/storage`
- [ ] مُطهّر HTML قوي (HTMLPurifier) مع قائمة سماح
- [ ] ترويسات `CSP` + `HSTS` + `X-Frame-Options` + `X-Content-Type-Options`
- [ ] `SESSION_ENCRYPT=true` و `SESSION_SECURE_COOKIE=true`
- [ ] مستخدم قاعدة بيانات بصلاحيات محدودة وكلمة مرور قوية
- [ ] HTTPS مفعّل (شهادة SSL)
- [ ] حدود رفع ملفات خادمية مضبوطة
- [ ] السجلات بمستوى `error` فقط وتُدوَّر
- [ ] تشغيل `php artisan optimize` وتفعيل التخزين المؤقت

---

> **تنويه:** هذا التقرير أُعدّ لأغراض التدقيق والتوعية الأمنية فقط، ولم يُجرَ أي تعديل على أي ملف من ملفات المشروع. يوصى بإعادة الفحص بعد تطبيق الإصلاحات.



