<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    // ── Static helpers ────────────────────────────────────────────────────

    /**
     * Ambil satu setting berdasarkan key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = static::allCached();
        return $settings[$key] ?? $default;
    }

    /**
     * Simpan satu setting.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('site_settings');
    }

    /**
     * Ambil semua setting sebagai array key => value (dengan cache).
     */
    public static function allCached(): array
    {
        return Cache::remember('site_settings', 3600, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Hapus cache settings.
     */
    public static function clearCache(): void
    {
        Cache::forget('site_settings');
    }

    // ── Logo URL helper ───────────────────────────────────────────────────

    public static function logoUrl(): ?string
    {
        $logo = static::get('logo');
        return $logo ? asset('storage/' . $logo) : null;
    }

    public static function faviconUrl(): ?string
    {
        $favicon = static::get('favicon');
        return $favicon ? asset('storage/' . $favicon) : null;
    }
}
