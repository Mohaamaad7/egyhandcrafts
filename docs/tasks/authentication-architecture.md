# Authentication Architecture — Recommendation & Decision

- **Task title:** Admin authentication architecture decision
- **Date:** 2026-08-31
- **Status:** Implemented

## Objective

Protect the `/admin` area with a professional, production-appropriate
authentication solution for the Laravel 13 application, while preserving the
existing Blade/Tailwind frontend and the Tabler admin UI.

## Initial Project State (inspection findings)

- Laravel **13.26.1**, PHP 8.3, plain **Blade** (no Livewire/Inertia/Vue/React).
- Frontend uses Tailwind via CDN inside `resources/views/layouts/app.blade.php`.
- Admin uses Tabler (Bootstrap 5) via CDN inside `resources/views/admin/layout.blade.php`.
- `config/auth.php` already configured: `web` guard (session) → Eloquent `User`.
- `User` model already extends `Authenticatable`, has `password => hashed` cast.
- `users`, `sessions`, `password_reset_tokens` tables already exist (MySQL `sadat`).
- **No auth controllers/routes/views existed.** `/admin` and `/admin/crafts/*`
  were completely public.
- **No roles/permissions system existed** — a single administrator is sufficient.

## Recommendation

**Package: Laravel Fortify** (first-party, headless authentication backend).

## Why Fortify fits this specific project

1. **Headless** — Fortify provides backend auth (login, logout, session
   management, validation, throttling, redirects) with **no views, CSS, or JS**.
   This is the key reason it fits: it cannot overwrite `layouts/app.blade.php`,
   the Tabler admin layout, the Tailwind design, or the RTL layout.
2. **Plain Blade** — Breeze/Jetstream would inject Tailwind/Livewire/Vue
   scaffolding and overwrite the existing layouts; laravel/ui would publish a
   conflicting Bootstrap `layouts/app.blade.php`. Fortify does none of that.
3. **First-party & Laravel 13 compatible** — maintained in lockstep with Laravel.
4. **Session-based** — uses the already-configured `web` guard.
5. **Secure password hashing** — leverages the existing `hashed` cast on `User`.
6. **Built-in validation & login throttling** — `LoginRequest` validation and
   a login rate limiter.
7. **Single admin, no public registration** — all optional features are disabled.

## Files the solution generates/modifies

- `composer.json` / `composer.lock` — added `laravel/fortify`.
- `config/fortify.php` — **new** (published), configured (`features` disabled, `home => /admin`).
- `app/Providers/AppServiceProvider.php` — **modified** (login view + login rate limiter).
- `bootstrap/app.php` — **modified** (guest/user redirect targets).
- `resources/views/auth/login.blade.php` — **new** (Tabler-styled login page).
- `routes/web.php` — **modified** (admin routes protected with `auth`).
- `resources/views/admin/layout.blade.php` — **modified** (logout button added).
- `database/seeders/AdminUserSeeder.php` — **new** (idempotent admin user).
- `database/seeders/DatabaseSeeder.php` — **modified** (calls AdminUserSeeder).
- `tests/Feature/AdminAuthenticationTest.php` — **new** (regression coverage).

## Impact assessment

- **`layouts/app.blade.php`:** NOT affected.
- **`routes/web.php`:** only admin routes touched (wrapped with `auth`);
  Fortify auto-registers `/login` (GET/POST) and `/logout` (POST).
- **Additional frontend dependencies:** NONE (no npm changes).
- **Migration/database changes:** NONE required — existing `users` table already
  has `password` + `remember_token`; Fortify's 2FA/passkey migrations and action
  classes were intentionally **not** published.
- **Risks/conflicts with Tabler:** none — admin layout preserved; the login view
  is a standalone Tabler-styled page reusing the same CDN assets.

## Alternatives considered (and rejected)

- **Breeze** — overrides existing layouts/frontend build; too invasive.
- **Jetstream** — Livewire/Inertia + teams; way beyond requirements.
- **laravel/ui** — publishes a conflicting Bootstrap `layouts/app.blade.php`.
- **Fully custom auth** — works but the task requires an established package;
  Fortify is the minimal-footprint official option.

## Decision

Fortify (headless) with login/logout enabled and all optional features disabled,
plus a hand-written Tabler login view. This is the smallest production-safe
footprint that satisfies the requirements without disturbing existing UI.
