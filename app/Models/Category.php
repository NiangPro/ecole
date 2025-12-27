<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    protected $table = 'job_categories';
    
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'image_type',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(JobArticle::class);
    }

    public function publishedArticles(): HasMany
    {
        return $this->hasMany(JobArticle::class)->published();
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Invalider le cache lors de la création, mise à jour ou suppression
        static::created(function ($category) {
            Cache::forget('active_categories');
            Cache::forget("category_{$category->slug}");
        });

        static::updated(function ($category) {
            Cache::forget('active_categories');
            Cache::forget("category_{$category->slug}");
        });

        static::deleted(function ($category) {
            Cache::forget('active_categories');
            Cache::forget("category_{$category->slug}");
        });
    }
}
