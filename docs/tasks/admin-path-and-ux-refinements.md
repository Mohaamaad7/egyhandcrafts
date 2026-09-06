# Dynamic Admin Path, Job Titles, RTL Alignment & UX Refinements

- **Task Title:** Dynamic Admin Path, Custom Job Titles, Header RTL Overflow Fix, Link Deduplication, and Multi-Admin CRUD Refinements
- **Date:** 2026-09-06
- **Status:** Complete
- **Test Results:** 121 Passed (416 Assertions), 100% Success Rate
- **Asset Compilation:** Vite Production Build Completed Cleanly

---

## 1. Executive Summary & Problem Statement

Following production review on the live deployment (`egyhandcrafts.com/admin/profile`), five specific architectural, administrative, and UX deficiencies were identified:
1. **Hardcoded Administrative Route Prefix (`/admin`):** The prefix `/admin` was hardcoded across `routes/web.php`, `bootstrap/app.php`, and `config/fortify.php`, exposing the portal to generic automated path scanners. The administrative prefix needed to be dynamically configurable via a database setting (falling back to cached/env config) and manageable through the super admin profile interface, with the old `/admin` path returning a standard `404 Not Found` when a custom path is active.
2. **Hardcoded Role Title ("مسؤول توثيق"):** The application hardcoded the Arabic role badge string "مسؤول توثيق" for all non-super admins. In an academic institution (University of Sadat City), field researchers, media personnel, and documentation officers possess specialized academic and administrative titles. The system required a nullable `job_title` column on `users`, dynamic header and profile badge rendering, self-service editing in the profile screen, and full CRUD support in team management.
3. **User Profile Dropdown RTL Overflow Clipping:** In RTL mode (`dir="rtl"`), the user avatar dropdown menu in the top navigation bar overflowed off the left edge of the screen because it used `dropdown-menu-start`, which anchors to the right edge and extends leftward. In a left-aligned action group within an RTL header, this caused the menu to be clipped outside the viewport.
4. **Duplication of "Visit Portal / معاينة البوابة" Links:** Users were exposed to three redundant links to the public portal: a button in the top navigation bar, a button in the sidebar footer, and an item in the user profile dropdown. The UX protocol required keeping strictly one unified entry point: the top navigation bar button ("معاينة البوابة").
5. **Multi-Admin Management Refinements:** The team management CRUD required support for custom job titles, clean Arabic role definitions, and strict deletion safeguards protecting the primary super admin account (`ID = 1` or username `'admin'`).

---

## 2. Architectural Design & Implementation

### 2.1 Database Migrations & Schemas

1. **Settings Table Migration (`2026_09_06_170000_create_settings_table.php`):**
   - Created `settings` table with columns:
     - `id`: Big increments primary key.
     - `key`: String(191), unique, indexed.
     - `value`: Text, nullable.
     - `timestamps`: `created_at` and `updated_at`.
2. **Users Job Title Migration (`2026_09_06_171000_add_job_title_to_users_table.php`):**
   - Added nullable `job_title` (`string('job_title')->nullable()->after('name')`) to the `users` table.

### 2.2 Models & Global Helpers

1. **`app/Models/Setting.php`:**
   - Implemented high-performance caching layer with graceful fallback:
     - `Setting::get(string $key, mixed $default = null)`: Uses `Cache::rememberForever("app_setting.{$key}", ...)` and wraps database queries in `try ... catch (\Throwable)` to protect early bootstrap and unmigrated test runs.
     - `Setting::set(string $key, mixed $value)`: Upserts the setting and evicts cache via `Cache::forget()`.
     - `Setting::remove(string $key)`: Deletes key and clears cache.
2. **`app/helpers.php`:**
   - Registered `admin_path(): string` helper returning `Setting::get('admin_path', config('app.admin_path', env('ADMIN_PATH', 'admin')))`.
   - Sanitizes leading and trailing slashes; falls back to `'admin'` if empty.
   - Autoloaded in `composer.json` under `autoload.files` and required in `bootstrap/app.php`.
3. **`app/Models/User.php`:**
   - Added `'job_title'` to `#[Fillable([...])]`.
   - Updated `role_label` accessor:
     ```php
     public function getRoleLabelAttribute(): string
     {
         if (! empty($this->job_title)) {
             return $this->job_title;
         }

         return match ($this->role) {
             'super_admin' => 'مدير النظام',
             default => 'مسؤول نظام',
         };
     }
     ```
     Eliminates the hardcoded "مسؤول توثيق" while prioritizing custom academic/professional titles.

### 2.3 Dynamic Routing & Redirect Architecture

1. **`routes/web.php`:**
   - Decoupled hardcoded `/admin` prefix by binding all administrative sub-routes to `$adminPrefix = admin_path()`:
     ```php
     Route::prefix($adminPrefix)->name('admin.')->group(function () {
         Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard')->middleware('auth');
         // Profile, users, crafts, workshops, stories sub-groups...
     });
     ```
   - Added `PUT /profile/settings` route mapped to `AdminProfileController@updateAdminPath`.
   - Retained all existing route names (`admin.dashboard`, `admin.profile.*`, `admin.users.*`, `admin.crafts.*`, `admin.workshops.*`, `admin.stories.*`), ensuring zero breaking changes across views and tests.
   - When a custom prefix is active, `/admin` is not registered and automatically triggers a standard HTTP 404 response.
2. **`bootstrap/app.php`:**
   - Replaced static `$middleware->redirectUsersTo('/admin')` with dynamic callable:
     ```php
     $middleware->redirectUsersTo(fn () => '/' . admin_path());
     ```
3. **`config/app.php` & `config/fortify.php`:**
   - Registered `'admin_path' => env('ADMIN_PATH', 'admin')` in `config/app.php`.
   - Registered dynamic Fortify home in `app/Providers/AppServiceProvider.php`:
     ```php
     config(['fortify.home' => '/' . admin_path()]);
     ```

### 2.4 Administrative Controllers

1. **`app/Http/Controllers/Admin/ProfileController.php`:**
   - In `edit()`: Passes current user and `$currentAdminPath = admin_path()` to view.
   - In `update()`: Added `'job_title' => ['nullable', 'string', 'max:255']` validation and persistence.
   - Added `updateAdminPath(Request $request)`:
     - Enforces super admin check (`auth()->user()->isSuperAdmin()`).
     - Validates against empty, length, regex `[a-zA-Z0-9_\-]+`, and blacklists reserved application routes (`login, logout, register, forgot-password, reset-password, api, sanctum, up, storage, assets, crafts, workshops, stories, map, home`).
     - Updates setting and immediately redirects to the new path URL with a confirmation alert.
2. **`app/Http/Controllers/Admin/UserController.php`:**
   - In `index()`: Enhanced search query to include `job_title` matches alongside name, username, and email.
   - In `store()` and `update()`: Validates and saves `job_title`.
   - In `destroy()`: Enforced primary super admin account safeguard (`$user->id === 1 || $user->username === 'admin'`), in addition to preventing self-deletion and last-remaining super admin deletion.

### 2.5 User Interface & RTL Refinements

1. **`resources/views/admin/layout.blade.php`:**
   - **RTL Dropdown Fix:** Replaced `dropdown-menu-start` with `dropdown-menu-end` and added CSS rule `.dropdown-menu-end[data-bs-popper] { right: auto !important; left: 0 !important; }` to anchor the menu safely inward into the viewport.
   - **Deduplication:**
     - Kept ONLY the top navigation bar button `btn-visit-portal` ("معاينة البوابة").
     - Removed the duplicate "معاينة البوابة العامة" button from the bottom of the sidebar.
     - Removed the duplicate "زيارة البوابة العامة" dropdown item from the user profile dropdown.
   - **Job Title Badge:** Header badge displays `{{ auth()->user()->role_label }}`, reflecting the dynamic title.
2. **`resources/views/admin/profile/edit.blade.php`:**
   - Added `job_title` input field ("المسمى الوظيفي / الأكاديمي") with contextual help text.
   - Added a dedicated super admin configuration card: "مسار لوحة التحكم المخصص (Admin Route Prefix)" displaying the active URL, input field with live prefix, security warning, and immediate activation button.
3. **`resources/views/admin/users/create.blade.php` & `edit.blade.php`:**
   - Added `job_title` input field in both forms.
   - Standardized role select options to "مسؤول نظام (Admin)" and "مدير النظام (Super Admin)".
4. **`resources/views/admin/users/index.blade.php`:**
   - Displays `job_title` underneath the user's name.
   - Role badges dynamically render the custom title or role label.
   - Protects the primary super admin account from showing the delete modal button.

---

## 3. Files Modified & Created

### 3.1 Created Files
- `database/migrations/2026_09_06_170000_create_settings_table.php`
- `database/migrations/2026_09_06_171000_add_job_title_to_users_table.php`
- `app/Models/Setting.php`
- `app/helpers.php`
- `tests/Feature/AdminPathAndUxRefinementsTest.php`
- `docs/tasks/admin-path-and-ux-refinements.md`

### 3.2 Modified Files
- `app/Models/User.php`
- `app/Http/Controllers/Admin/ProfileController.php`
- `app/Http/Controllers/Admin/UserController.php`
- `app/Providers/AppServiceProvider.php`
- `bootstrap/app.php`
- `composer.json`
- `config/app.php`
- `config/fortify.php`
- `database/factories/UserFactory.php`
- `database/seeders/AdminUserSeeder.php`
- `resources/views/admin/layout.blade.php`
- `resources/views/admin/profile/edit.blade.php`
- `resources/views/admin/users/create.blade.php`
- `resources/views/admin/users/edit.blade.php`
- `resources/views/admin/users/index.blade.php`
- `routes/web.php`
- `docs/project_map.md`

---

## 4. Verification & Testing

### 4.1 Automated Tests
A dedicated feature test suite `tests/Feature/AdminPathAndUxRefinementsTest.php` (13 tests, 44 assertions) was implemented:
- `test_default_admin_path_is_admin`: Verifies default helper resolves to `admin`.
- `test_admin_dashboard_accessible_at_default_prefix`: Verifies dashboard renders at `/admin`.
- `test_custom_admin_path_serves_routes_and_old_path_returns_404`: Verifies routes re-bind to custom prefix and old `/admin` returns HTTP 404.
- `test_super_admin_can_update_admin_path`: Verifies super admin can update setting and get redirected to new URL.
- `test_regular_admin_cannot_update_admin_path`: Verifies regular admin receives 403 Forbidden.
- `test_reserved_or_invalid_admin_paths_are_rejected`: Verifies reserved paths and invalid characters fail validation.
- `test_job_title_accessor_and_role_label`: Verifies elimination of hardcoded "مسؤول توثيق" and dynamic custom title resolution.
- `test_user_can_update_job_title_in_profile`: Verifies self-service title update on profile screen.
- `test_layout_renders_job_title_and_rtl_dropdown_class`: Verifies header displays job title and `dropdown-menu-end`.
- `test_portal_preview_links_are_deduplicated`: Verifies only header `btn-visit-portal` exists and duplicates are absent.
- `test_super_admin_can_create_and_update_admin_with_job_title`: Verifies full team management CRUD with job titles.
- `test_primary_super_admin_account_cannot_be_deleted`: Verifies account protection for ID 1 / username `admin`.
- `test_regular_admin_can_access_content_management`: Verifies regular admins manage crafts, workshops, and stories while restricted from user management.

### 4.2 Test Suite Execution
```bash
php artisan test
```
**Output:**
```
Tests:    121 passed (416 assertions)
Duration: 7.81s
```

### 4.3 Front-End Asset Compilation
```bash
npm run build
```
**Output:**
```
vite v7.3.6 building client environment for production...
✓ 898 modules transformed.
✓ built in 7.03s
```

---

## 6. Real-Time Client-Side Input Validation (Live Feedback)

### Problem & UX Need
Users previously had to submit user creation and password edit forms before receiving feedback on:
1. Minimum password length requirement (at least 8 characters).
2. Password confirmation mismatch.
3. Username character restrictions (letters, numbers, underscores).

### Implementation
- **Instantaneous Feedback (`input` event):** Added real-time evaluation hooks in `resources/views/admin/layout.blade.php` that trigger immediately as the administrator types.
- **Visual Styling:** Integrates seamlessly with Tabler's `is-invalid` (red border, alert badge) and `is-valid` (green border, confirmation badge). In `.input-group-flat`, borders and eye toggle controls remain unified.
- **Dynamic Error Suppression:** Stale server-side error banners (`.alert-danger`) and static error texts are dismissed immediately once the user starts making corrections.
- **Client-Side Submit Interceptor:** Forms configured with `novalidate` intercept submission when any required field or credentials check fails, highlighting and focusing the offending element without unnecessary server roundtrips.

### 7. Real-Time Uniqueness Verification (AJAX Availability Check)
- **Problem Resolved:** The regex test previously produced false-positive `is-valid` green checkmarks for usernames already present in the database, and email fields had no real-time duplicate warning.
- **Endpoint Added:** `POST /admin/check-availability` in `AdminUserController::checkAvailability` supporting `username` and `email` checking with `ignore_id` for editing existing records.
- **Debounced Interaction:** In `resources/views/admin/layout.blade.php`, user input triggers a 300ms–350ms debounced asynchronous verification.
- **State Transition:** Format validation passes into a pending loading indicator (`جاري التحقق من التوفر...`), and only marks `is-valid` once the database confirms uniqueness. If duplicate, immediate `is-invalid` (red border and specific warning message) is rendered.
- **Submit Guard:** In-flight or unresolved checks are awaited before form submission, completely preventing duplicate record submissions on the client side.


