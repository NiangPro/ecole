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
        'views',
        'published_at'
    ];

    protected $casts = [
        'meta_keywords' => 'array',
        'seo_score' => 'integer',
        'readability_score' => 'integer',
        'is_sponsored' => 'boolean',
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
