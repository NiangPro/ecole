<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return $this->hasMany(JobArticle::class)->where('status', 'published');
    }
}
