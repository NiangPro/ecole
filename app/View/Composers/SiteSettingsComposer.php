<?php

namespace App\View\Composers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SiteSettingsComposer
{
    public function compose(View $view): void
    {
        $siteSettings = Cache::remember('site_settings', 3600, fn () => SiteSetting::first());
        $view->with('siteSettings', $siteSettings);
    }
}
