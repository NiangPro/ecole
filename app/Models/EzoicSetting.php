<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EzoicSetting extends Model
{
    protected $table = 'ezoic_settings';
    
    protected $fillable = [
        'site_id',
        'privacy_scripts',
        'ezoic_code',
        'ezoic_body_code',
        'ezoic_footer_code',
        'header_banner',
        'sidebar_banner',
        'footer_banner',
        'in_content',
        'auto_ads'
    ];

    protected $casts = [
        'header_banner' => 'boolean',
        'sidebar_banner' => 'boolean',
        'footer_banner' => 'boolean',
        'in_content' => 'boolean',
        'auto_ads' => 'boolean',
    ];
    
    public static function clearCache()
    {
        \Illuminate\Support\Facades\Cache::forget('ezoic_settings');
    }
}
