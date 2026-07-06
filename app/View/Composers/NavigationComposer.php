<?php

namespace App\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class NavigationComposer
{
    public function compose(View $view)
    {
        // Cache les catégories pour améliorer les performances (15 min — aligné sur le TTL
        // de 'active_categories' utilisé par /emplois, pour que les deux compteurs
        // convergent au même rythme même si une invalidation événementielle est manquée)
        $jobCategories = \Illuminate\Support\Facades\Cache::remember('navigation_job_categories', 900, function () {
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

