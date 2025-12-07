<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use App\Models\JobArticle;
use App\Http\Controllers\Concerns\LocaleTrait;

class SearchController extends Controller
{
    use LocaleTrait;

    /**
     * Recherche globale (formations + articles)
     */
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $category = $request->get('category', '');
        $dateFilter = $request->get('date', '');
        $sortBy = $request->get('sort', 'relevance');
        
        $results = [
            'formations' => collect(),
            'articles' => collect(),
            'total' => 0
        ];
        
        // Récupérer toutes les catégories pour les filtres - Cache (1 heure)
        $categories = Cache::remember('all_categories_search', 3600, function () {
            return Category::where('is_active', true)
                ->orderBy('name')
                ->get();
        });
        
        if (strlen($query) >= 2) {
            // Recherche dans les formations (titres et descriptions)
            $formations = collect([
                ['name' => 'HTML5', 'url' => route('formations.html5'), 'description' => 'Formation complète HTML5'],
                ['name' => 'CSS3', 'url' => route('formations.css3'), 'description' => 'Formation complète CSS3'],
                ['name' => 'JavaScript', 'url' => route('formations.javascript'), 'description' => 'Formation complète JavaScript'],
                ['name' => 'PHP', 'url' => route('formations.php'), 'description' => 'Formation complète PHP'],
                ['name' => 'Bootstrap', 'url' => route('formations.bootstrap'), 'description' => 'Formation complète Bootstrap'],
                ['name' => 'Git', 'url' => route('formations.git'), 'description' => 'Formation complète Git'],
                ['name' => 'WordPress', 'url' => route('formations.wordpress'), 'description' => 'Formation complète WordPress'],
                ['name' => 'Intelligence Artificielle', 'url' => route('formations.ia'), 'description' => 'Formation Intelligence Artificielle'],
            ])->filter(function($formation) use ($query) {
                return stripos($formation['name'], $query) !== false || 
                       stripos($formation['description'], $query) !== false;
            });
            
            // Recherche dans les articles d'emploi publiés avec filtres - Optimisé avec eager loading
            $articlesQuery = JobArticle::where('status', 'published')
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%")
                      ->orWhere('excerpt', 'like', "%{$query}%");
                });
            
            // Filtre par catégorie
            if ($category) {
                $articlesQuery->where('category_id', $category);
            }
            
            // Filtre par date
            if ($dateFilter) {
                switch($dateFilter) {
                    case 'today':
                        $articlesQuery->whereDate('published_at', today());
                        break;
                    case 'week':
                        $articlesQuery->where('published_at', '>=', now()->subWeek());
                        break;
                    case 'month':
                        $articlesQuery->where('published_at', '>=', now()->subMonth());
                        break;
                    case 'year':
                        $articlesQuery->where('published_at', '>=', now()->subYear());
                        break;
                }
            }
            
            // Tri
            switch($sortBy) {
                case 'date':
                    $articlesQuery->orderBy('published_at', 'desc');
                    break;
                case 'views':
                    $articlesQuery->orderBy('views', 'desc');
                    break;
                case 'title':
                    $articlesQuery->orderBy('title', 'asc');
                    break;
                case 'relevance':
                default:
                    $articlesQuery->orderBy('published_at', 'desc');
                    break;
            }
            
            // Cache optimisé avec eager loading (5 minutes pour les résultats de recherche)
            $cacheKey = "search_articles_{$query}_{$category}_{$dateFilter}_{$sortBy}";
            $articles = Cache::remember($cacheKey, 300, function () use ($articlesQuery) {
                return $articlesQuery->with(['category:id,name,slug']) // Eager loading optimisé
                    ->limit(50)
                    ->get();
            });
            
            $results['formations'] = $formations;
            $results['articles'] = $articles;
            $results['total'] = $formations->count() + $articles->count();
        }
        
        return view('search', compact('query', 'results', 'categories', 'category', 'dateFilter', 'sortBy'));
    }
}

