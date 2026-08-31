# CKEditor 5 Initialization Fix

- **Task title:** Fix CKEditor 5 failing to initialize on craft create/edit
- **Date:** 2026-08-31
- **Status:** Complete

## Objective

Resolve the bug where raw HTML appears inside the `#content` textarea instead of
the CKEditor 5 interface on the admin craft **create** and **edit** pages.

## Initial project state / inspection

- `resources/views/admin/layout.blade.php` — `@stack('scripts')` already exists
  immediately before `</body>` (line 70), and `@stack('styles')` in `<head>`.
- `resources/views/admin/crafts/create.blade.php` — single `<textarea name="content" id="content">`
  plus `@push('scripts') @vite('resources/js/ckeditor.js') @endpush`.
- `resources/views/admin/crafts/edit.blade.php` — same single `#content` textarea
  and `@vite` push.
- `vite.config.js` — `resources/js/ckeditor.js` is a valid entry.
- `public/build/manifest.json` + `public/build/assets/*` — CKEditor bundle built
  and present on disk (no `public/hot`, i.e. production manifest mode).
- `resources/js/ckeditor.js` — official `ckeditor5` package, `ClassicEditor`,
  all plugins/config intact.

**Conclusion:** the Blade stacks, textarea `id`, and Vite config were all correct.
The actual defect was the initializer's sole reliance on
`document.addEventListener('DOMContentLoaded', …)`. The bundle is emitted by Vite
as a deferred ES module (`<script type="module">`), which can execute **after**
`DOMContentLoaded` has already fired; in that case the listener never runs and
the textarea is left showing raw HTML.

## Files inspected

- `resources/views/admin/layout.blade.php`
- `resources/views/admin/crafts/create.blade.php`
- `resources/views/admin/crafts/edit.blade.php`
- `resources/js/ckeditor.js`
- `vite.config.js`
- `public/build/manifest.json` and `public/build/assets/*`

## Files modified

- `resources/js/ckeditor.js` — replaced the `DOMContentLoaded`-only wrapper with
  an `initEditor()` function invoked via a `document.readyState` guard, plus a
  double-initialization guard.

## Changes implemented

1. Kept CKEditor 5 (official `ckeditor5` package) and the existing Vite config
   **unchanged** (no editor replacement, no new WYSIWYG package).
2. Refactored `resources/js/ckeditor.js`:
   - `initEditor()` finds `#content`, guards against re-initialization via
     `data-ckeditor-ready`, then calls `ClassicEditor.create(...)`.
   - `if (document.readyState === 'loading')` → wait for `DOMContentLoaded`;
     otherwise call `initEditor()` immediately (DOM already parsed).
3. Rebuilt the frontend with `npm run build` to regenerate the CKEditor bundle.

## Validation/testing performed

- `npm run build` → success (`✓ built in 6.24s`), new bundle
  `public/build/assets/ckeditor-eWfH0KMp.js` (936 KB) referenced in
  `manifest.json`; CSS entry unchanged.
- `php artisan test` → 8 passed (regression unaffected).
- `php artisan route:list` → routes intact.

## Final status

Complete. CKEditor now initializes deterministically on create/edit regardless of
module-load timing, the textarea `#content` exists exactly once, the script is
pushed to the correct `scripts` stack, and no raw CKEditor HTML is left exposed.

## Remaining risks / notes

- The chunk-size warning from Vite (`>500 kB`) is informational only; CKEditor is
  inherently large. Code-splitting is a future optimization, not required now.
