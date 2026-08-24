# دليل النشر المخصص لخادم الإنتاج (DirectAdmin / Server07)
# مشروع توثيق الحرف التراثية بمحافظة المنوفية — egyhandcrafts.com

> **مسار الخادم الفعلي:** `/home/amal/domains/egyhandcrafts.com/`  
> **النطاق:** `egyhandcrafts.com`  
> **المستودع:** `https://github.com/Mohaamaad7/egyhandcrafts.git`  
> **الهدف:** نشر المشروع مباشرة في الـ Root دون أي مجلدات فرعية (`sadat`).

---

## 🎯 اختر الطريقة الأنسب لخادمك:

- **[الطريقة الأولى (المباشرة والأسهل):](#-الطريقة-الأولى-النشر-المباشر-داخل-public_html-الأسهل-والأسرع)** النشر داخل `public_html` مباشرة باستخدام الـ Root `.htaccess` المدمج.
- **[الطريقة الثانية (المثالية أمنياً لـ DirectAdmin):](#-الطريقة-الثانية-النشر-الاحترافي-عبر-الـ-symlink-الموصى-بها-في-directadmin)** فصل ملفات النظام عن الويب عبر Symlink لمجلد `public`.

---

## 🚀 الطريقة الأولى: النشر المباشر داخل `public_html` (الأسهل والأسرع)

في هذه الطريقة، توضع جميع ملفات المشروع في الروت المباشر لـ `public_html`، ويقوم ملف `.htaccess` الرئيسي المدمج بتوجيه الزوار تلقائياً إلى مجلد `public` وحظر الوصول لأي ملفات حساسة (`.env`).

### 1. الدخول لمجلد `public_html` وحذف الملفات القديمة:
```bash
cd /home/amal/domains/egyhandcrafts.com/public_html

# حذف الملفات القديمة (تأكد أنك بداخل public_html)
rm -rf assets includes index.php template.html README.md cgi-bin
```

### 2. استنساخ المستودع في المسار الحالي مباشرة (لاحظ النقطة `.` في النهاية):
```bash
git clone https://github.com/Mohaamaad7/egyhandcrafts.git .
```
*(ملاحظة: النقطة `.` تضع الملفات مباشرة في الروت دون إنشاء مجلد فرعي)*

### 3. تثبيت حزم الاعتماديات (Composer):
```bash
composer install --no-dev --optimize-autoloader
```

### 4. إعداد ملف البيئة وتوليد المفتاح:
```bash
cp .env.example .env
nano .env
```
قم بضبط بيانات قاعدة البيانات في ملف `.env`:
```dotenv
APP_NAME="مشروع توثيق الحرف التراثية"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://egyhandcrafts.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=اسم_قاعدة_البيانات
DB_USERNAME=اسم_المستخدم
DB_PASSWORD=كلمة_المرور
```

ثم نفذ:
```bash
php artisan key:generate --force
```

### 5. تشغيل الـ Migrations وتجهيز الـ Storage:
```bash
php artisan migrate --force
php artisan storage:link
```

### 6. ضبط التصاريح وتخزين الكاش:
```bash
chmod -R 775 storage bootstrap/cache
php artisan optimize
```

---

## 🛡️ الطريقة الثانية: النشر الاحترافي عبر الـ Symlink (الموصى بها في DirectAdmin)

في هذه الطريقة، تكون ملفات الـ Core بالكامل خارج مسار الويب العام لحماية تامة، ويتم عمل رابط رمزي (Symlink) من مجلد `public_html` إلى `core/public`.

### 1. الانتقال إلى مجلد النطاق:
```bash
cd /home/amal/domains/egyhandcrafts.com
```

### 2. استبدال مجلد `public_html` القديم برابط رمزي:
```bash
# حذف مجلد public_html القديم
rm -rf public_html

# استنساخ المشروع في مجلد core
git clone https://github.com/Mohaamaad7/egyhandcrafts.git core

# إنشاء الرابط الرمزي لمجلد public
ln -s core/public public_html
```

### 3. الدخول لمجلد `core` وإكمال التثبيت:
```bash
cd /home/amal/domains/egyhandcrafts.com/core

# تثبيت الحزم
composer install --no-dev --optimize-autoloader

# إعداد ملف .env
cp .env.example .env
nano .env

# توليد المفتاح وتشغيل قواعد البيانات
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link

# ضبط الصلاحيات وتحسين الأداء
chmod -R 775 storage bootstrap/cache
php artisan optimize
```

---

## ⚙️ ملخص أوامر الصيانة والتحديث مستقبلاً (Future Updates)

عند الرغبة في سحب أي تحديثات جديدة من المستودع مستقبلاً:

**إذا استخدمت الطريقة الأولى:**
```bash
cd /home/amal/domains/egyhandcrafts.com/public_html
git pull origin main
php artisan migrate --force
php artisan optimize:clear
php artisan optimize
```

**إذا استخدمت الطريقة الثانية:**
```bash
cd /home/amal/domains/egyhandcrafts.com/core
git pull origin main
php artisan migrate --force
php artisan optimize:clear
php artisan optimize
```

---

## 🔒 تأكيدات الأمان (Security Checklist)
- التأكد من أن `APP_DEBUG=false` في ملف `.env`.
- التأكد من تفعيل شهادة SSL عبر لوحة DirectAdmin (SSL Certificates -> Free Let's Encrypt).
- التأكد من تشغيل أمر `php artisan optimize` لرفع سرعة الموقع لأقصى أداء.
