<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'views',
        'published_at'
    ];

    protected $casts = [
        'meta_keywords' => 'array',
        'seo_score' => 'integer',
        'readability_score' => 'integer',
        'views' => 'integer',
        'published_at' => 'datetime'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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
