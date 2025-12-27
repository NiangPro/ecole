<?php

namespace App\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class NavigationComposer
{
    public function compose(View $view)
    {
        // Cache les catégories pour améliorer les performances (30 minutes)
        $jobCategories = \Illuminate\Support\Facades\Cache::remember('navigation_job_categories', 1800, function () {
            return Category::where('is_active', true)
                ->withCount('publishedArticles')
                ->orderBy('published_articles_count', 'desc')
                ->orderBy('order')
                ->take(7)
                ->get();
        });
        
        $view->with('jobCategories', $jobCategories);
    }
}

