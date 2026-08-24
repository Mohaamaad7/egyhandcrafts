# مشروع توثيق الحرف التراثية بمحافظة المنوفية
### جامعة مدينة السادات - كلية السياحة والفنادق

البوابة الرقمية الرسمية لمشروع توثيق وأرشفة الحرف والصناعات التقليدية والتراثية بمحافظة المنوفية.

---

## 🛠️ التقنيات المستخدمة (Tech Stack)
- **الإطار البرمجي:** Laravel 13 (PHP ^8.3)
- **قواعد البيانات:** MySQL / MariaDB (دعم SQLite للتطوير)
- **محرك القوالب:** Blade Templating Engine (RTL Dynamic Layouts)
- **التصميم البصري:** Tailwind CSS (CDN & Config) + Custom CSS
- **لوحة التحكم (Admin Panel):** Tabler UI (Bootstrap 5 based)
- **المكتبات التفاعلية:** FontAwesome 6, AOS.js (Scroll Animations)

---

## 📁 هيكلية المشروع (Directory Structure)
```text
sadat/
├── app/                  # Application Controllers, Models & Providers
├── config/               # App configuration files
├── database/             # Migrations, Seeders & Factories
├── docs/                 # Documentation & Architecture logs
│   ├── project_map.md    # Comprehensive changelog & system state
│   └── DEPLOYMENT_GUIDE.md # Full Production Deployment Guide
├── lang/                 # Localization (Arabic ar.json & English en.json)
├── public/               # Web root (Entry point index.php & public assets)
│   └── assets/           # Images (logos, hero bg), CSS & JS
├── resources/            # Blade views & templates
│   └── views/
│       ├── layouts/      # Master layouts (app.blade.php)
│       ├── admin/        # Admin panel views (Tabler layout & dashboard)
│       └── home.blade.php # Frontend homepage
└── routes/               # Web routing (web.php)
```

---

## 🚀 التشغيل والتطوير المحلي (Local Development)
1. تثبيت الحزم: `composer install`
2. ضبط ملف البيئة: `cp .env.example .env` ثم `php artisan key:generate`
3. تنفيذ الـ Migrations: `php artisan migrate`
4. تشغيل الخادم المحلي: `php artisan serve` أو عبر Laragon مباشرة على `http://sadat.test`

---

## 🌐 دليل النشر على الإنتاج (Production Deployment)
للحصول على دليل شامل وتفصيلي لنشر التطبيق على خوادم الإنتاج (Nginx / Apache / cPanel / SSL / Caching)، يرجى مراجعة:
👉 **[دليل النشر على بيئة الإنتاج (DEPLOYMENT_GUIDE.md)](docs/DEPLOYMENT_GUIDE.md)**

---

## 🏛️ حقوق الملكية الفكرية
جميع الحقوق محفوظة لجامعة مدينة السادات - كلية السياحة والفنادق.
