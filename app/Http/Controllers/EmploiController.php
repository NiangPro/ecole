<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\JobArticle;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Ad;
use App\Http\Controllers\Concerns\LocaleTrait;

class EmploiController extends Controller
{
    use LocaleTrait;

    /**
     * Page principale des emplois
     */
    public function index()
    {
        // Cache les catégories actives avec sélection optimisée (15 minutes)
        $categories = Cache::remember('active_categories', 900, function () {
            $categories = Category::where('is_active', true)
                ->withCount([
                    'articles' => function($query) {
                        $query->where('status', 'published');
                    }
                ])
                ->select('id', 'name', 'slug', 'description', 'icon', 'image', 'image_type', 'order')
                ->orderBy('order')
                ->get();
            
            foreach ($categories as $category) {
                if (!isset($category->articles_count)) {
                    $category->articles_count = JobArticle::where('category_id', $category->id)
                        ->where('status', 'published')
                        ->count();
                }
                $category->published_articles_count = $category->articles_count ?? 0;
            }
            
            return $categories;
        });
        
        // Cache les 6 derniers articles avec sélection optimisée (15 minutes) - Optimisé avec eager loading
        $recentArticles = Cache::remember('recent_job_articles', 900, function () {
            return JobArticle::where('status', 'published')
                ->with(['category:id,name,slug']) // Eager loading optimisé
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->take(6)
                ->get();
        });
        
        return view('emplois.index', compact('categories', 'recentArticles'));
    }

    /**
     * Liste des offres d'emploi
     */
    public function offres(Request $request)
    {
        $categorySlug = $request->get('category');
        $page = $request->get('page', 1);
        
        // Cache la catégorie (1 heure)
        if ($categorySlug) {
            $category = Cache::remember("category_{$categorySlug}", 3600, function () use ($categorySlug) {
                return Category::where('slug', $categorySlug)->first();
            });
        } else {
            $category = Cache::remember('category_offres-emploi', 3600, function () {
                return Category::where('slug', 'offres-emploi')->first();
            });
        }
        
        // Cache optimisé avec eager loading (15 minutes)
        $cacheKey = $category ? "job_articles_category_{$category->id}_page_{$page}" : "job_articles_all_page_{$page}";
        
        $articles = Cache::remember($cacheKey, 900, function () use ($category) {
            $query = JobArticle::where('status', 'published')
                ->with(['category:id,name,slug']) // Eager loading optimisé
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views', 'created_at', 'updated_at');
            
            if ($category) {
                $query->where('category_id', $category->id);
            }
            
            return $query->orderBy('published_at', 'desc')
                ->orderBy('updated_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        });
        
        return view('emplois.offres', compact('articles', 'category'));
    }

    /**
     * Articles par catégorie
     */
    public function category($slug)
    {
        $this->ensureLocale();
        
        // Récupérer la catégorie - Cache (15 minutes)
        $category = Cache::remember("category_{$slug}", 900, function () use ($slug) {
            return Category::where('slug', $slug)
                ->where('is_active', true)
                ->firstOrFail();
        });
        
        // Cache les articles de la catégorie (15 minutes) - Optimisé avec eager loading
        $articles = Cache::remember("category_articles_{$slug}", 900, function () use ($category) {
            return JobArticle::where('category_id', $category->id)
                ->where('status', 'published')
                ->with(['category:id,name,slug']) // Eager loading optimisé
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        });
        
        // Cache les publicités pour la sidebar (30 minutes)
        $sidebarAds = Cache::remember('sidebar_ads_content', 1800, function () {
            return Ad::active()
                ->forPosition('content')
                ->whereNull('location')
                ->orderBy('order')
                ->get();
        });
        
        return view('emplois.category', compact('category', 'articles', 'sidebarAds'));
    }

    /**
     * Afficher un article
     */
    public function show($slug)
    {
        // Cache l'article avec sélection optimisée (30 minutes) - Optimisé avec eager loading
        $article = Cache::remember("job_article_{$slug}", 1800, function () use ($slug) {
            return JobArticle::where('slug', $slug)
                ->where('status', 'published')
                ->whereHas('category', function($query) {
                    $query->where('is_active', true);
                })
                ->with(['category:id,name,slug']) // Eager loading optimisé
                ->select('id', 'title', 'slug', 'excerpt', 'content', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views', 'meta_title', 'meta_description', 'meta_keywords', 'created_at', 'updated_at')
                ->firstOrFail();
        });
        
        // Incrémenter les vues de manière optimisée (sans recharger l'article)
        JobArticle::where('id', $article->id)->increment('views');
        
        // Cache les articles similaires avec sélection optimisée (15 minutes) - Optimisé avec eager loading
        $relatedArticles = Cache::remember("related_articles_{$article->id}", 900, function () use ($article) {
            return JobArticle::where('status', 'published')
                ->where('category_id', $article->category_id)
                ->where('id', '!=', $article->id)
                ->with(['category:id,name,slug']) // Eager loading optimisé
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->take(3)
                ->get();
        });
        
        // Cache les 6 articles les plus vus pour la sidebar (5 minutes - durée réduite pour plus de réactivité)
        $topViewedArticles = Cache::remember('top_viewed_articles_sidebar', 300, function () use ($article) {
            return JobArticle::where('status', 'published')
                ->where('id', '!=', $article->id)
                ->with(['category:id,name,slug'])
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('views', 'desc')
                ->take(6)
                ->get();
        });
        
        // Cache les publicités pour la sidebar des articles (30 minutes)
        $sidebarAds = Cache::remember('sidebar_ads_articles', 1800, function () {
            return Ad::active()
                ->forPosition('content')
                ->where(function($q) {
                    $q->whereNull('location')
                      ->orWhere('location', 'article_sidebar');
                })
                ->select('id', 'name', 'description', 'image', 'image_type', 'link_url')
                ->orderBy('order')
                ->get();
        });

        // Cache les 3 derniers commentaires approuvés avec sélection optimisée (15 minutes)
        $latestComments = Cache::remember("article_latest_comments_{$article->id}", 900, function () use ($article) {
            return Comment::where('commentable_type', 'App\\Models\\JobArticle')
                ->where('commentable_id', $article->id)
                ->where('status', 'approved')
                ->whereNull('parent_id')
                ->select('id', 'name', 'email', 'content', 'created_at', 'user_id')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        });

        // Cache les commentaires avec sélection optimisée (15 minutes) - Optimisé avec eager loading
        $latestCommentIds = $latestComments->pluck('id')->toArray();
        $comments = Cache::remember("article_comments_{$article->id}", 900, function () use ($article, $latestCommentIds) {
            $query = Comment::where('commentable_type', 'App\\Models\\JobArticle')
                ->where('commentable_id', $article->id)
                ->where('status', 'approved')
                ->select('id', 'name', 'email', 'content', 'parent_id', 'created_at', 'user_id');
            
            if (!empty($latestCommentIds)) {
                $query->whereNotIn('id', $latestCommentIds);
            }
            
            $comments = $query->orderBy('created_at', 'desc')->get();
            
            // Charger les réponses de manière optimisée avec eager loading
            $commentIds = $comments->pluck('id')->toArray();
            if (!empty($commentIds)) {
                $replies = Comment::whereIn('parent_id', $commentIds)
                    ->where('status', 'approved')
                    ->select('id', 'name', 'email', 'content', 'parent_id', 'created_at', 'user_id')
                    ->get()
                    ->groupBy('parent_id');
                
                foreach ($comments as $comment) {
                    $comment->setRelation('replies', $replies->get($comment->id, collect()));
                }
            }
            
            return $comments;
        });
        
        return view('emplois.article', compact('article', 'relatedArticles', 'sidebarAds', 'comments', 'latestComments', 'topViewedArticles'));
    }

    /**
     * Articles récents
     */
    public function recent()
    {
        // Cache les 70 articles les plus récents avec optimisation SEO (15 minutes) - Optimisé avec eager loading
        $recentArticles = Cache::remember('recent_articles_70_seo', 900, function () {
            return JobArticle::where('status', 'published')
                ->with(['category:id,name,slug']) // Eager loading optimisé
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views', 'meta_title', 'meta_description', 'created_at')
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(70)
                ->get();
        });
        
        return view('emplois.recent-articles', compact('recentArticles'));
    }

    /**
     * Tous les articles vedettes
     */
    public function featured()
    {
        $this->ensureLocale();
        
        // Cache optimisé avec eager loading et pagination (15 minutes)
        $cacheKey = 'featured_articles_all_page_' . request()->get('page', 1);
        
        $articles = Cache::remember($cacheKey, 900, function () {
            return JobArticle::where('status', 'published')
                ->where('is_featured', true)
                ->with(['category:id,name,slug']) // Eager loading optimisé
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        });
        
        return view('emplois.featured-articles', compact('articles'));
    }

    /**
     * Pages spécialisées par catégorie
     */
    public function bourses()
    {
        return $this->getCategoryArticles('bourses-etudes', 'emplois.bourses');
    }

    public function candidatureSpontanee()
    {
        return $this->getCategoryArticles('candidature-spontanee', 'emplois.candidature');
    }

    public function opportunites()
    {
        return $this->getCategoryArticles('opportunites-professionnelles', 'emplois.opportunites');
    }

    public function concours()
    {
        return $this->getCategoryArticles('concours', 'emplois.concours');
    }

    /**
     * Méthode helper pour récupérer les articles d'une catégorie
     */
    private function getCategoryArticles($categorySlug, $view)
    {
        // Cache la catégorie (1 heure)
        $category = Cache::remember("category_{$categorySlug}", 3600, function () use ($categorySlug) {
            return Category::where('slug', $categorySlug)->first();
        });
        
        // Cache optimisé avec eager loading (15 minutes)
        $cacheKey = $category ? "job_articles_{$categorySlug}_page_" . request()->get('page', 1) : "job_articles_{$categorySlug}_all_page_" . request()->get('page', 1);
        
        $articles = Cache::remember($cacheKey, 900, function () use ($category) {
            return JobArticle::where('status', 'published')
                ->where('category_id', $category->id ?? null)
                ->with(['category:id,name,slug']) // Eager loading optimisé
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->paginate(12);
        });
        
        return view($view, compact('articles', 'category'));
    }
}

