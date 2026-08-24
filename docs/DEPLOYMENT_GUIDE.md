# دليل نشر المشروع على بيئة الإنتاج (Production Deployment Guide)
# مشروع توثيق الحرف التراثية بمحافظة المنوفية — جامعة مدينة السادات

> **الإطار البرمجي:** Laravel 13  
> **متطلبات النظام:** PHP ^8.3 | MySQL ^8.0 / MariaDB ^10.4 | Nginx / Apache  
> **مسار الـ Document Root:** مجلد `public/` حصراً

---

## الفهرس (Table of Contents)
1. [المتطلبات الأساسية للخادم (Server Requirements)](#1-المتطلبات-الأساسية-للخادم-server-requirements)
2. [خطوات تثبيت ونشر المشروع لأول مرة (Initial Deployment)](#2-خطوات-تثبيت-ونشر-المشروع-لأول-مرة-initial-deployment)
3. [إعدادات خادم الويب (Web Server Configurations)](#3-إعدادات-خادم-الويب-web-server-configurations)
   - [إعداد Nginx (موصى به)](#أ-إعداد-nginx-موصى-به)
   - [إعداد Apache](#ب-إعداد-apache)
   - [إعداد الاستضافات المشتركة (cPanel / Shared Hosting)](#ج-إعداد-الاستضافات-المشتركة-cpanel--shared-hosting)
4. [تحسينات الأداء والإنتاج (Production Optimization)](#4-تحسينات-الأداء-والإنتاج-production-optimization)
5. [جدولة المهام والـ Cron Jobs (Task Scheduling)](#5-جدولة-المهام-والـ-cron-jobs-task-scheduling)
6. [إجراءات الأمان والحماية (Security Checklist)](#6-إجراءات-الأمان-والحماية-security-checklist)
7. [خطوات تحديث المشروع مستقبلاً (Zero-Downtime Update Workflow)](#7-خطوات-تحديث-المشروع-مستقبلاً-zero-downtime-update-workflow)

---

## 1. المتطلبات الأساسية للخادم (Server Requirements)

تأكد من توفر الحزم التالية على الخادم (مثال: Ubuntu 22.04 / 24.04 LTS):

- **PHP 8.3** أو أحدث مع الإضافات التالية:
  ```bash
  sudo apt update
  sudo apt install -y php8.3-fpm php8.3-cli php8.3-mysql php8.3-mbstring \
      php8.3-xml php8.3-curl php8.3-zip php8.3-bcmath php8.3-gd php8.3-intl
  ```
- **Composer 2.x** لإدارة حزم PHP:
  ```bash
  curl -sS https://getcomposer.org/installer | php
  sudo mv composer.phar /usr/local/bin/composer
  ```
- **خادم قواعد البيانات:** MySQL 8.0+ أو MariaDB 10.4+
- **خادم الويب:** Nginx (مفضل) أو Apache2
- **Git** لإدارة الإصدارات
- **شهادة SSL** (Let's Encrypt / Certbot)

---

## 2. خطوات تثبيت ونشر المشروع لأول مرة (Initial Deployment)

### الخطوة 1: استنساخ المستودع (Clone Repository)
قم بسحب المشروع من المستودع إلى المسار المخصص على الخادم (مثلاً `/var/www/sadat`):

```bash
cd /var/www
sudo git clone https://github.com/Mohaamaad7/egyhandcrafts.git sadat
cd sadat
```

### الخطوة 2: ضبط الصلاحيات والملكية (File Permissions)
خادم الويب (مثل `www-data`) يحتاج إلى صلاحية الكتابة على مجلدي `storage` و `bootstrap/cache`:

```bash
sudo chown -R www-data:www-data /var/www/sadat
sudo find /var/www/sadat -type f -exec chmod 644 {} \;
sudo find /var/www/sadat -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/sadat/storage /var/www/sadat/bootstrap/cache
```

### الخطوة 3: تثبيت الاعتماديات للإنتاج (Install Dependencies)
قم بتثبيت حزم Composer مع تعطيل حزم التطوير وتحسين الـ autoloader:

```bash
composer install --no-dev --optimize-autoloader
```

### الخطوة 4: إعداد ملف البيئة (`.env`)
قم بنسخ ملف البيئة وضبط المتغيرات:

```bash
cp .env.example .env
nano .env
```

**تأكد من ضبط القيم التالية للإنتاج:**
```dotenv
APP_NAME="مشروع توثيق الحرف التراثية"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.edu.eg

APP_LOCALE=ar
APP_FALLBACK_LOCALE=ar
APP_TIMEZONE=Africa/Cairo

LOG_CHANNEL=daily
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sadat_production
DB_USERNAME=sadat_user
DB_PASSWORD=your_secure_password_here

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### الخطوة 5: توليد مفتاح التطبيق (Generate App Key)
```bash
php artisan key:generate --force
```

### الخطوة 6: إنشاء قاعدة البيانات وتشغيل الـ Migrations
قم بإنشاء قاعدة البيانات في MySQL:
```sql
CREATE DATABASE sadat_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sadat_user'@'localhost' IDENTIFIED BY 'your_secure_password_here';
GRANT ALL PRIVILEGES ON sadat_production.* TO 'sadat_user'@'localhost';
FLUSH PRIVILEGES;
```

ثم نفذ الـ migrations:
```bash
php artisan migrate --force
```

### الخطوة 7: ربط مجلد التخزين (Storage Symlink)
```bash
php artisan storage:link
```

---

## 3. إعدادات خادم الويب (Web Server Configurations)

> [!CRITICAL]
> **هام جداً:** يجب توجيه الـ `root` في الخادم دائماً إلى مجلد `public/` وليس إلى المجلد الرئيسي للمشروع، لحماية ملفات النظام وملف `.env` من الوصول المباشر.

### أ. إعداد Nginx (موصى به)

أنشئ ملف إعداد للموقع `/etc/nginx/sites-available/sadat.conf`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.edu.eg www.your-domain.edu.eg;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name your-domain.edu.eg www.your-domain.edu.eg;

    # مسار الجذر لمجلد public الخاص بـ Laravel
    root /var/www/sadat/public;
    index index.php index.html;

    # شهادة SSL (Certbot)
    ssl_certificate /etc/letsencrypt/live/your-domain.edu.eg/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/your-domain.edu.eg/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # ترميز الحروف
    charset utf-8;

    # حماية الملفات المخفية
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # التوجيه لـ Laravel
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # إعدادات ملفات الوسائط والصور الثابتة مع التخزين المؤقت
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
        access_log off;
    }

    # معالجة PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # أحجام الرفع والسجلات
    client_max_body_size 64M;
    access_log /var/log/nginx/sadat_access.log;
    error_log /var/log/nginx/sadat_error.log error;
}
```

تفعيل الإعداد وإعادة تشغيل Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/sadat.conf /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

### ب. إعداد Apache

أنشئ ملف VirtualHost في `/etc/apache2/sites-available/sadat.conf`:

```apache
<VirtualHost *:80>
    ServerName your-domain.edu.eg
    ServerAlias www.your-domain.edu.eg
    DocumentRoot /var/www/sadat/public

    <Directory /var/www/sadat/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/sadat_error.log
    CustomLog ${APACHE_LOG_DIR}/sadat_access.log combined
</VirtualHost>
```

تفعيل الموديولات والإعداد:
```bash
sudo a2enmod rewrite ssl headers
sudo a2ensite sadat.conf
sudo systemctl restart apache2
```

---

### ج. إعداد الاستضافات المشتركة (cPanel / Shared Hosting)

إذا كنت تنشر المشروع على استضافة cPanel مشتركة:
1. قم برفع ملفات المشروع في مجلد خارج الـ `public_html` (مثلاً: `/home/username/sadat`).
2. قم بضبط الـ Document Root للنطاق الأساسي أو الفرعي ليشير إلى `/home/username/sadat/public`.
3. أو أنشئ رابط رمزي (Symlink) بين `public_html` و `sadat/public`:
   ```bash
   ln -s /home/username/sadat/public /home/username/public_html
   ```

---

## 4. تحسينات الأداء والإنتاج (Production Optimization)

قم بتنفيذ أوامر التخزين المؤقت (Caching) لتسريع استجابة النظام لأقصى حد:

```bash
# 1. تخزين إعدادات الـ Config
php artisan config:cache

# 2. تخزين المسارات Routes
php artisan route:cache

# 3. تجميع قوالب Blade وتخزينها
php artisan view:cache

# 4. تخزين أحداث النظام Events
php artisan event:cache

# أو تنفيذ الأمر المجمع:
php artisan optimize
```

> [!NOTE]
> إذا قمت بأي تعديل مستقبلاً على ملف `.env` أو مسارات `routes/web.php`، يجب مسح الكاش عبر:
> ```bash
> php artisan optimize:clear
> php artisan optimize
> ```

---

## 5. جدولة المهام والـ Cron Jobs (Task Scheduling)

لتمكين جدولة مهام Laravel (تنظيف الجلسات المنتهية، النسخ الاحتياطي، المهام الدورية):

افتح الـ Crontab الخاص بمستخدم الخادم (`www-data` أو المستخدم المخصص):
```bash
sudo crontab -u www-data -e
```

أضف السطر التالي:
```cron
* * * * * cd /var/www/sadat && php artisan schedule:run >> /dev/null 2>&1
```

---

## 6. إجراءات الأمان والحماية (Security Checklist)

- [ ] **تعطيل وضع التطوير:** التأكد بنسبة 100% أن `APP_DEBUG=false` في ملف `.env`.
- [ ] **شهادة SSL / HTTPS:** تفعيل تشفير SSL عبر Let's Encrypt:
  ```bash
  sudo certbot --nginx -d your-domain.edu.eg -d www.your-domain.edu.eg
  ```
- [ ] **حماية ملفات النظام:** منع الوصول إلى أي ملفات `.env` أو `.git` من خادم الويب.
- [ ] **حماية الـ Admin Dashboard:** تفعيل نظام التحقق وتأمين مسار `/admin`.
- [ ] **أمان قاعدة البيانات:** استخدام كلمة مرور معقدة وعدم إتاحة منفذ 3306 للخارج (فقط localhost).
- [ ] **النسخ الاحتياطي التلقائي:** ضبط سكربت نسخ احتياطي يومي لقاعدة البيانات والملفات المرفوعة:
  ```bash
  mysqldump -u sadat_user -p sadat_production > /backup/sadat_$(date +%F).sql
  ```

---

## 7. خطوات تحديث المشروع مستقبلاً (Zero-Downtime Update Workflow)

عند رفع أي تعديلات أو ميزات جديدة إلى مستودع Git، اتبع الخطوات التالية للتحديث الآمن:

```bash
# 1. الانتقال لمجلد المشروع
cd /var/www/sadat

# 2. تفعيل وضع الصيانة المؤقت
php artisan down --render="errors::503" --secret="sadat-update-bypass"

# 3. سحب آخر التحديثات من Git
git pull origin main

# 4. تثبيت أي حزم جديدة
composer install --no-dev --optimize-autoloader

# 5. تشغيل الـ Migrations إن وجدت
php artisan migrate --force

# 6. تحديث الكاش والتحسينات
php artisan optimize:clear
php artisan optimize

# 7. إلغاء وضع الصيانة واستئناف العمل
php artisan up
```

---

*تم إعداد وتوثيق هذا الدليل خصيصاً لمشروع توثيق الحرف التراثية بمحافظة المنوفية — كلية السياحة والفنادق، جامعة مدينة السادات.*
