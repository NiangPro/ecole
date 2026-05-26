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
     * Vues gonflées formatées — utilisé partout sauf dans l'admin.
     * Formule dégressive : 0→~1.1k, 5→~2.5k, 50→~5.8k, 100→~12k.
     */
    public function getFeaturedDisplayViewsAttribute(): string
    {
        return self::formatViewCount($this->calcInflatedViews());
    }

    private function calcInflatedViews(): int
    {
        $v = (int) ($this->attributes['views'] ?? 0);

        if ($v === 0) {
            // Valeur stable basée sur l'ID pour éviter les fluctuations au rechargement
            return 1000 + (($this->id ?? 0) % 300);
        }
        if ($v < 10) {
            return (int) (1150 + $v * 270);        // 1 → 1.4k … 9 → 3.6k
        }
        if ($v < 50) {
            return (int) (3700 + ($v - 10) * 52.5); // 10 → 3.7k … 49 → 5.8k
        }
        if ($v < 100) {
            return (int) (5800 + ($v - 50) * 124);  // 50 → 5.8k … 99 → 12k
        }
        if ($v < 1000) {
            return (int) (12000 + ($v - 100) * 50); // 100 → 12k … 999 → 56.9k
        }
        return $v * 60;                              // 1000+ → 60k+
    }

    private static function formatViewCount(int $n): string
    {
        if ($n >= 1_000_000) {
            $v = round($n / 1_000_000, 1);
            return ($v == (int) $v ? (int) $v : $v) . 'M';
        }
        $k = round($n / 1_000, 1);
        return ($k == (int) $k ? (int) $k : $k) . 'k';
    }

    /**
     * Total des vues gonflées pour le compteur global (page emplois).
     */
    public static function getTotalInflatedViews(): string
    {
        $count    = static::where('status', 'published')->count();
        $realSum  = (int) static::where('status', 'published')->sum('views');
        $inflated = ($count * 1150) + ($realSum * 120);
        return self::formatViewCount(max($inflated, 1000));
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
