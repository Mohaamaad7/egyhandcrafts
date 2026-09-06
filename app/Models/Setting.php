<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value with in-memory / cache acceleration and graceful fallback.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("app_setting.{$key}", function () use ($key, $default) {
            try {
                $setting = static::where('key', $key)->first();
                return $setting?->value ?? $default;
            } catch (\Throwable) {
                return $default;
            }
        });
    }

    /**
     * Set a setting value and invalidate the cached key.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        Cache::forget("app_setting.{$key}");
    }

    /**
     * Remove a setting and invalidate the cache.
     */
    public static function remove(string $key): void
    {
        try {
            static::where('key', $key)->delete();
        } catch (\Throwable) {
            // Ignore during early bootstrap or test teardown
        }
        Cache::forget("app_setting.{$key}");
    }
}
