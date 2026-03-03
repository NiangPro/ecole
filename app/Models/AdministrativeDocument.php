<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AdministrativeDocument extends Model
{
    protected $fillable = [
        'title',
        'seo_title',
        'slug',
        'category',
        'summary',
        'seo_description',
        'seo_keywords',
        'purpose',
        'target_audience',
        'required_documents',
        'where_to_apply',
        'approx_cost',
        'approx_delay',
        'tips',
        'cover_image',
        'cover_type',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'required_documents' => 'array',
        'where_to_apply' => 'array',
        'is_featured' => 'boolean',
    ];

    /**
     * Portée : uniquement les fiches publiées.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }
}

