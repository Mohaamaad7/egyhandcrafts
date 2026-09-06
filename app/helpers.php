<?php

use App\Models\Setting;

if (! function_exists('admin_path')) {
    /**
     * Get the configured administrative route prefix.
     */
    function admin_path(): string
    {
        $default = config('app.admin_path', env('ADMIN_PATH', 'admin')) ?: 'admin';
        $path = Setting::get('admin_path', $default);

        $path = trim((string) $path, '/');

        return $path !== '' ? $path : 'admin';
    }
}
