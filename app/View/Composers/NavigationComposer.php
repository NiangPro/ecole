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
            ->orderBy('order')
            ->get();
        
        $view->with('jobCategories', $jobCategories);
    }
}

