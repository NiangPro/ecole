<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'site_description',
        'contact_email',
        'contact_phone',
        'contact_address',
        'facebook_url',
        'tiktok_url',
        'linkedin_url',
        'instagram_url',
        'youtube_url',
        'github_url',
        'google_analytics_id',
        'bing_api_key',
        'show_achievements_section',
    ];

    public static function get($key, $default = null)
    {
        $settings = \Illuminate\Support\Facades\Cache::remember('site_settings', 3600, function () {
            return self::first();
        });
        return $settings ? ($settings->$key ?? $default) : $default;
    }
    
    public static function clearCache()
    {
        \Illuminate\Support\Facades\Cache::forget('site_settings');
    }
}
