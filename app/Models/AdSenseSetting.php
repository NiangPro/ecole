<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdSenseSetting extends Model
{
    protected $table = 'adsense_settings';
    
    protected $fillable = [
        'publisher_id',
        'adsense_code',
        'header_banner',
        'sidebar_banner',
        'footer_banner'
    ];

    protected $casts = [
        'header_banner' => 'boolean',
        'sidebar_banner' => 'boolean',
        'footer_banner' => 'boolean',
    ];
}
