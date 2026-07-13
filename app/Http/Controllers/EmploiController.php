<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\JobArticle;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Ad;
use App\Http\Controllers\Concerns\LocaleTrait;
use App\Helpers\ContentEnhancer;

/**
 * ─── ARCHITECTURE SILO SÉMANTIQUE ───────────────────────────────────────────
 *
 * Silo 1 — ÉDUCATION TECH        : formations, quiz, exercices
 * Silo 2 — CARRIÈRE & RECRUTEMENT: offres-emploi, candidature-spontanee,
 *                                   opportunites-professionnelles, bourses-etudes
 * Silo 3 — ADMINISTRATION        : concours, admin-docs
 *
 * Règle d'étanchéité :
 *   • Les "Articles Similaires" restent TOUJOURS dans le même silo (filtre category_id).
 *   • Le seul lien inter-silos autorisé est l'encadré e-book NiangProgrammeur
 *     (Le Décodeur de Carrière / Money API) — voir vue emplois/article.blade.php.
 * ────────────────────────────────────────────────────────────────────────────
 */
class EmploiController extends Controller
{
    use LocaleTrait;

    // Slugs appartenant au silo Carrière & Recrutement
    private const SILO_CARRIERE = [
        'offres-emploi',
        'candidature-spontanee',
        'opportunites-professionnelles',
        'bourses-etudes',
    ];

    // Slugs appartenant au silo Administration
    private const SILO_ADMIN = ['concours'];

    /**
     * Page principale des emplois
     */
    public function index()
    {
        // Cache les catégories actives avec sélection optimisée (15 minutes)
        //
        // withCount('publishedArticles') — même relation que NavigationComposer (menu de nav) —
        // au lieu de withCount(['articles' => fn($q) => $q->published()]) + un count() manuel de
        // repli. Les deux formulations sont censées être équivalentes, mais utiliser la même
        // relation nommée des deux côtés élimine tout risque de divergence entre le compteur du
        // menu et celui de /emplois (c'était la source du décalage constaté, ex. "561" vs "549").
        $categories = Cache::remember('active_categories', 900, function () {
            // select() DOIT précéder withCount() : select() remplace toute la liste de colonnes
            // (y compris la sous-requête de comptage ajoutée par withCount) s'il est appelé après.
            return Category::where('is_active', true)
                ->select('id', 'name', 'slug', 'description', 'icon', 'image', 'image_type', 'order')
                ->withCount('publishedArticles')
                ->orderBy('order')
                ->get();
        });
        
        // Cache les 6 derniers articles avec sélection optimisée (15 minutes) - Optimisé avec eager loading
        //
        // Tri par created_at (et non published_at) pour être cohérent avec la page d'accueil
        // (PageController::index(), $latestJobs). published_at peut être antidaté (import,
        // planification) : trier par ce champ faisait apparaître comme "récents" des articles
        // publiés il y a plusieurs jours, alors que created_at reflète le moment réel où
        // l'article a été ajouté — c'était la cause du décalage avec l'accueil, pas le cache.
        $recentArticles = Cache::remember('recent_job_articles', 900, function () {
            return JobArticle::published()
                ->with(['category:id,name,slug']) // Eager loading optimisé
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'created_at', 'views')
                ->orderBy('created_at', 'desc')
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
        $cacheKey = $category ? "job_articles_category_{$category->id}_page_{$page}_v2" : "job_articles_all_page_{$page}_v2";

        $articles = Cache::remember($cacheKey, 900, function () use ($category) {
            $query = JobArticle::published()
                ->with(['category:id,name,slug,icon']) // Eager loading optimisé
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views', 'created_at', 'updated_at');

            if ($category) {
                $query->where('category_id', $category->id);
            }

            return $query->orderBy('published_at', 'desc')
                ->orderBy('updated_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(20); // 20 offres par page
        });

        // Préserve les paramètres de requête (ex: ?category=) dans les liens de pagination
        $articles->appends($request->except('page'));

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
                ->published()
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
        // Charger l'article depuis le cache ou la DB (30 minutes)
        try {
            $article = Cache::remember("job_article_{$slug}", 1800, function () use ($slug) {
                return JobArticle::where('slug', $slug)
                    ->published()
                    ->whereHas('category', function($query) {
                        $query->where('is_active', true);
                    })
                    ->with(['category:id,name,slug'])
                    ->select('id', 'title', 'slug', 'excerpt', 'content', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views', 'meta_title', 'meta_description', 'meta_keywords', 'created_at', 'updated_at')
                    ->firstOrFail();
            });
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404);
        } catch (\Throwable $e) {
            Log::error('EmploiController::show — article load failed', [
                'slug' => $slug,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            abort(500);
        }

        // Incrémenter les vues sans recharger l'article
        try {
            JobArticle::where('id', $article->id)->increment('views');
        } catch (\Throwable $e) {
            Log::warning('EmploiController::show — increment views failed', ['id' => $article->id, 'error' => $e->getMessage()]);
        }

        // Articles similaires (15 minutes)
        try {
            $relatedArticles = Cache::remember("related_articles_{$article->id}", 900, function () use ($article) {
                return JobArticle::published()
                    ->where('category_id', $article->category_id)
                    ->where('id', '!=', $article->id)
                    ->with(['category:id,name,slug'])
                    ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                    ->orderBy('published_at', 'desc')
                    ->take(3)
                    ->get();
            });
        } catch (\Throwable $e) {
            Log::error('EmploiController::show — relatedArticles failed', ['id' => $article->id, 'error' => $e->getMessage()]);
            $relatedArticles = collect();
        }

        // Documents pertinents (15 minutes)
        try {
            $relatedDocuments = Cache::remember("related_documents_article_{$article->id}", 900, function () use ($article) {
                $documents = collect();
                $existingIds = [];

                $articleKeywords = [];
                if ($article->meta_keywords && is_array($article->meta_keywords)) {
                    $articleKeywords = array_map(fn($v) => strtolower((string) $v), $article->meta_keywords);
                } elseif ($article->meta_keywords) {
                    $decoded = json_decode($article->meta_keywords, true);
                    if (is_array($decoded)) {
                        $articleKeywords = array_map(fn($v) => strtolower((string) $v), $decoded);
                    } else {
                        $articleKeywords = array_map(fn($v) => strtolower(trim((string) $v)), explode(',', $article->meta_keywords));
                    }
                }
                $articleKeywords = array_filter($articleKeywords);

                if (!empty($articleKeywords)) {
                    try {
                        $documents = \App\Models\Document::published()
                            ->with(['category:id,name,slug'])
                            ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'price', 'discount_price', 'is_free', 'published_at', 'views_count', 'sales_count')
                            ->where(function($q) use ($articleKeywords) {
                                foreach ($articleKeywords as $keyword) {
                                    if (!empty($keyword)) {
                                        $q->orWhereJsonContains('tags', $keyword)
                                          ->orWhere('title', 'like', "%{$keyword}%")
                                          ->orWhere('description', 'like', "%{$keyword}%")
                                          ->orWhere('meta_keywords', 'like', "%{$keyword}%");
                                    }
                                }
                            })
                            ->orderBy('sales_count', 'desc')
                            ->orderBy('views_count', 'desc')
                            ->take(6)
                            ->get();
                    } catch (\Throwable $e) {
                        Log::error('EmploiController::show — keyword doc search failed', [
                            'article_id' => $article->id,
                            'keywords' => $articleKeywords,
                            'error' => $e->getMessage(),
                        ]);
                        $documents = collect();
                    }
                    $existingIds = $documents->pluck('id')->toArray();
                }

                $docSelect = ['id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'price', 'discount_price', 'is_free', 'published_at', 'views_count', 'sales_count'];

                // Compléter jusqu'à 6 documents avec les featured puis tous les publiés
                if ($documents->count() < 6) {
                    $additionalDocuments = \App\Models\Document::published()
                        ->whereNotIn('id', $existingIds)
                        ->where('is_featured', true)
                        ->with(['category:id,name,slug'])
                        ->select($docSelect)
                        ->orderBy('sales_count', 'desc')
                        ->orderBy('views_count', 'desc')
                        ->take(6 - $documents->count())
                        ->get();
                    $documents = $documents->merge($additionalDocuments);
                    $existingIds = $documents->pluck('id')->toArray();
                }

                if ($documents->count() < 6) {
                    $additionalDocuments = \App\Models\Document::published()
                        ->whereNotIn('id', $existingIds)
                        ->with(['category:id,name,slug'])
                        ->select($docSelect)
                        ->orderBy('sales_count', 'desc')
                        ->orderBy('views_count', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->take(6 - $documents->count())
                        ->get();
                    $documents = $documents->merge($additionalDocuments);
                }

                return $documents->take(6);
            });
        } catch (\Throwable $e) {
            Log::error('EmploiController::show — relatedDocuments failed', ['id' => $article->id, 'error' => $e->getMessage()]);
            $relatedDocuments = collect();
        }

        // Publicités sidebar (30 minutes)
        try {
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
        } catch (\Throwable $e) {
            Log::error('EmploiController::show — sidebarAds failed', ['error' => $e->getMessage()]);
            $sidebarAds = collect();
        }

        // Derniers commentaires approuvés (15 minutes)
        try {
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
        } catch (\Throwable $e) {
            Log::error('EmploiController::show — latestComments failed', ['id' => $article->id, 'error' => $e->getMessage()]);
            $latestComments = collect();
        }

        // Commentaires complets (15 minutes)
        try {
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
        } catch (\Throwable $e) {
            Log::error('EmploiController::show — comments failed', ['id' => $article->id, 'error' => $e->getMessage()]);
            $comments = collect();
        }

        // ── Contenu expert (Tâche 1 — anti-thin-content) ─────────────────────
        $contentType  = ContentEnhancer::getContentTypeFromSlug($article->category?->slug);
        $expertAdvice = ContentEnhancer::generateExpertAdvice(
            $article->title,
            $article->location ?? null,
            $contentType
        );
        $articleFaqs  = ContentEnhancer::generateFAQs(
            $article->title,
            $article->location ?? null,
            $contentType
        );

        return view('emplois.article', compact(
            'article', 'relatedArticles', 'sidebarAds', 'comments',
            'latestComments', 'relatedDocuments',
            'expertAdvice', 'articleFaqs', 'contentType'
        ));
    }

    /**
     * Articles récents
     */
    public function recent()
    {
        // Cache les 70 articles les plus récents avec optimisation SEO (15 minutes) - Optimisé avec eager loading
        $recentArticles = Cache::remember('recent_articles_70_seo', 900, function () {
            return JobArticle::published()
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
            return JobArticle::published()
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
     * Affiche tous les articles d'emploi avec pagination (20 par page, 4 par ligne)
     */
    public function allArticles(Request $request)
    {
        $this->ensureLocale();
        
        $page = $request->get('page', 1);
        
        // Cache optimisé avec eager loading (15 minutes)
        $cacheKey = "all_job_articles_page_{$page}";
        
        $articles = Cache::remember($cacheKey, 900, function () {
            return JobArticle::published()
                ->with(['category:id,name,slug,icon']) // Eager loading optimisé
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views', 'created_at', 'updated_at')
                ->orderBy('published_at', 'desc')
                ->orderBy('updated_at', 'desc')
                ->paginate(20); // 20 articles par page
        });
        
        // Statistiques globales
        $stats = Cache::remember('all_articles_stats', 3600, function () {
            return [
                'total' => JobArticle::published()->count(),
                'categories' => Category::where('is_active', true)->count(),
            ];
        });
        
        return view('emplois.all-articles', compact('articles', 'stats'));
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
            return JobArticle::published()
                ->where('category_id', $category->id ?? null)
                ->with(['category:id,name,slug']) // Eager loading optimisé
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->paginate(12);
        });
        
        return view($view, compact('articles', 'category'));
    }
}

