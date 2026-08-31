# Admin Authentication Implementation

- **Task title:** Protect `/admin` with Laravel Fortify (session auth)
- **Date:** 2026-08-31
- **Status:** Complete

## Objective

Add production-appropriate authentication to the Laravel 13 app: login, logout,
authentication middleware, protected admin routes, secure password hashing,
validation, failed-login handling, and redirect behavior — without breaking the
existing frontend, Tabler admin layout, or crafts CRUD.

## Files inspected

- `docs/project_map.md`, `composer.json`, `package.json`
- `routes/web.php`, `bootstrap/app.php`, `bootstrap/providers.php`
- `config/auth.php`, `config/session.php`, `config/app.php`
- `app/Models/User.php`, `app/Models/Craft.php`
- `app/Http/Controllers/Admin/CraftController.php`, `FrontendCraftController.php`, `HomeController.php`
- `app/Providers/AppServiceProvider.php`
- `resources/views/layouts/app.blade.php`, `resources/views/admin/layout.blade.php`
- `database/seeders/*`, `database/migrations/*`
- `vendor/laravel/fortify/*` (after install)

## Packages installed

- `laravel/fortify` (`v1.39.0`) via Composer.

## Files created

- `config/fortify.php`
- `resources/views/auth/login.blade.php`
- `database/seeders/AdminUserSeeder.php`
- `tests/Feature/AdminAuthenticationTest.php`

## Files modified

- `composer.json` / `composer.lock` (Fortify dependency)
- `app/Providers/AppServiceProvider.php` (login view + login rate limiter)
- `bootstrap/app.php` (guest → `/login`, user → `/admin`)
- `routes/web.php` (admin routes wrapped with `auth`)
- `resources/views/admin/layout.blade.php` (logout button)
- `database/seeders/DatabaseSeeder.php` (call AdminUserSeeder, drop non-idempotent test user)

## Commands executed

- `composer require laravel/fortify`
- `composer install`
- `php artisan vendor:publish --tag=fortify-config`
- `php artisan db:seed --class=AdminUserSeeder` (run twice for idempotency)
- `php artisan route:list --path=admin -v`
- `php artisan test`

## Changes implemented

1. **Fortify configuration** (`config/fortify.php`):
   - `home => '/admin'` (post-login redirect).
   - `features => []` (registration, password reset, email verification, profile
     updates, 2FA, and passkeys disabled — only login/logout remain).
2. **`AppServiceProvider`**:
   - `Fortify::loginView(fn () => view('auth.login'));`
   - `RateLimiter::for('login', ...)` — 5 attempts/min per email+IP (required by
     Fortify's `throttle:login` middleware on POST `/login`).
3. **`bootstrap/app.php`**:
   - `redirectGuestsTo('/login')` and `redirectUsersTo('/admin')`.
4. **`routes/web.php`**:
   - `GET /admin` and the whole `admin/crafts` group now use `->middleware('auth')`.
   - Route names/URLs unchanged (`admin.dashboard`, `admin.crafts.*`).
5. **Login view** (`resources/views/auth/login.blade.php`):
   - Standalone Tabler-styled, RTL-aware page (email/password/remember + CSRF +
     validation errors), posting to `route('login')`.
6. **Admin layout**: added a logout `<form>` (POST `route('logout')` + `@csrf`)
   in the header, keeping the existing Tabler UI intact.
7. **Default admin user** (`AdminUserSeeder`):
   - `User::updateOrCreate(['email' => 'admin@sadat.test'], ['name' => 'Administrator', 'password' => 'password', 'email_verified_at' => now()])`
   - Idempotent (no duplicates, no unique-constraint errors on re-run).

## Problems discovered

- Fortify's POST `/login` route applies `throttle:login`, which requires a named
  `login` rate limiter. This is normally registered in the published
  `FortifyServiceProvider`; because that provider (and its action stubs) was
  intentionally skipped, the limiter was initially missing, causing
  `Rate limiter [login] is not defined` on the first test run. Fixed by
  registering it in `AppServiceProvider::boot()`.

## Validation/testing performed

- `php artisan route:list --path=admin -v` → all 7 admin routes show `web` + `auth`.
- `php artisan db:seed --class=AdminUserSeeder` (twice) → single admin row (`count=1`).
- Tinker: stored password algo `bcrypt`; `Auth::attempt(admin@sadat.test / password)` → `yes`.
- `php artisan test` → **8 passed (18 assertions)**:
  - unauthenticated users redirected from `/admin`
  - `/login` renders (200)
  - login with valid credentials → redirect `/admin` + authenticated
  - login with invalid credentials → rejected (guest)
  - authenticated user can access `/admin` and `/admin/crafts`
  - logout → guest

## Final status

Complete and verified. `/admin` and `/admin/crafts/*` require authentication;
login/logout work; the default admin account is seeded idempotently; existing
homepage, frontend routes/layouts, Tabler admin UI, and crafts CRUD are preserved.

## Remaining risks / notes

- The admin user uses a well-known development password (`password`); change it
  before any production deployment.
- Registration/reset/2FA are intentionally disabled (single-admin use case).
