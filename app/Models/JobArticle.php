<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;

class JobArticle extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'cover_image',
        'cover_type',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'seo_score',
        'readability_score',
        'status',
        'is_sponsored',
        'is_featured',
        'views',
        'published_at'
    ];

    protected $casts = [
        'meta_keywords' => 'array',
        'seo_score' => 'integer',
        'readability_score' => 'integer',
        'is_sponsored' => 'boolean',
        'is_featured' => 'boolean',
        'views' => 'integer',
        'published_at' => 'datetime'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->where('parent_id', null)->where('status', 'approved')->orderBy('created_at', 'desc');
    }

    public function allComments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->where('status', 'approved')->orderBy('created_at', 'desc');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Libellé d'affichage des vues pour la section "Articles Vedettes" (paliers fictifs).
     * Utiliser dans la page d'accueil uniquement : {{ $article->featured_display_views }}
     */
    public function getFeaturedDisplayViewsAttribute(): string
    {
        $vues = (int) ($this->attributes['views'] ?? 0);
        $suffix = app()->getLocale() === 'fr' ? ' vues' : ' views';

        if ($vues > 1000) return '2,5 M' . $suffix;
        if ($vues > 500) return '1 M' . $suffix;
        if ($vues > 200) return '20 K' . $suffix;
        if ($vues > 100) return '10,1 K' . $suffix;
        if ($vues > 50) return '4,5 K' . $suffix;
        if ($vues > 40) return '3,8 K' . $suffix;
        if ($vues > 30) return '3,5 K' . $suffix;
        if ($vues > 20) return '3,2 K' . $suffix;
        if ($vues > 15) return '2,8 K' . $suffix;
        if ($vues > 10) return '2,5 K' . $suffix;
        if ($vues >= 5) return '2,1 K' . $suffix;

        return '1,5 K' . $suffix;
    }

    /**
     * Scope pour filtrer les articles réellement publiés
     * (statut "published" et published_at <= maintenant)
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title') && empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });

        // Invalider le cache lors de la création, mise à jour ou suppression
        static::created(function ($article) {
            Cache::forget('latest_jobs');
            Cache::forget('recent_job_articles');
            Cache::forget('sponsored_articles');
            Cache::forget('career_advice_articles');
            Cache::forget('featured_articles');
            // Invalider le cache des articles les plus vus (sidebar)
            Cache::forget('top_viewed_articles_sidebar');
            // Invalider le cache du sitemap pour forcer sa régénération
            Cache::forget('sitemap_articles_lastmod');
            Cache::forget('sitemap_index_' . md5('https://niangprogrammeur.com'));
            Cache::forget('sitemap_articles_' . md5('https://niangprogrammeur.com'));
            if ($article->category_id) {
                $category = $article->category ?? Category::find($article->category_id);
                if ($category) {
                    Cache::forget("category_{$category->slug}");
                }
            }
        });

        static::updated(function ($article) {
            Cache::forget('latest_jobs');
            Cache::forget('recent_job_articles');
            Cache::forget("job_article_{$article->slug}");
            Cache::forget("related_articles_{$article->id}");
            Cache::forget('sponsored_articles');
            Cache::forget('career_advice_articles');
            Cache::forget('featured_articles');
            Cache::forget('homepage_view_fr');
            Cache::forget('homepage_view_en');
            // Invalider le cache des articles les plus vus (sidebar)
            Cache::forget('top_viewed_articles_sidebar');
            // Invalider le cache du sitemap pour forcer sa régénération
            Cache::forget('sitemap_articles_lastmod');
            Cache::forget('sitemap_index_' . md5('https://niangprogrammeur.com'));
            Cache::forget('sitemap_articles_' . md5('https://niangprogrammeur.com'));
            if ($article->category_id) {
                $category = $article->category ?? Category::find($article->category_id);
                if ($category) {
                    Cache::forget("category_{$category->slug}");
                }
            }
            // Invalider les caches des catégories
            Cache::forget('active_categories');
        });

        static::deleted(function ($article) {
            Cache::forget('latest_jobs');
            Cache::forget('recent_job_articles');
            Cache::forget("job_article_{$article->slug}");
            Cache::forget("related_articles_{$article->id}");
            Cache::forget('career_advice_articles');
            Cache::forget('featured_articles');
            // Invalider le cache des articles les plus vus (sidebar)
            Cache::forget('top_viewed_articles_sidebar');
            // Invalider le cache du sitemap pour forcer sa régénération
            Cache::forget('sitemap_articles_lastmod');
            Cache::forget('sitemap_index_' . md5('https://niangprogrammeur.com'));
            Cache::forget('sitemap_articles_' . md5('https://niangprogrammeur.com'));
            if ($article->category_id) {
                $category = Category::find($article->category_id);
                if ($category) {
                    Cache::forget("category_{$category->slug}");
                }
            }
            // Invalider les caches des catégories
            Cache::forget('active_categories');
        });
    }
}
