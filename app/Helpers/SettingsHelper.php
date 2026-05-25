<?php

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    /**
     * Obtenir un paramètre du site depuis le cache
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return Cache::remember("site_setting.{$key}", now()->addHours(24), function () use ($key, $default) {
            $setting = SiteSetting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }
}

if (!function_exists('settings')) {
    /**
     * Obtenir tous les paramètres du site depuis le cache
     *
     * @return array
     */
    function settings()
    {
        return Cache::remember('site_settings_all', now()->addHours(24), function () {
            return SiteSetting::pluck('value', 'key')->toArray();
        });
    }
}

if (!function_exists('clearSettingsCache')) {
    /**
     * Vider le cache des paramètres du site
     *
     * @return void
     */
    function clearSettingsCache()
    {
        Cache::forget('site_settings_all');
        Cache::tags(['site_settings'])->flush();
    }
}
