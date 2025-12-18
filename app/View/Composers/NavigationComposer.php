<?php

namespace App\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class NavigationComposer
{
    public function compose(View $view)
    {
        $jobCategories = Category::where('is_active', true)
            ->withCount('publishedArticles')
            ->orderBy('published_articles_count', 'desc')
            ->orderBy('order')
            ->take(7)
            ->get();
        
        $view->with('jobCategories', $jobCategories);
    }
}

