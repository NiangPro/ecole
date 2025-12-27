<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle pour les heatmaps
 */
class AnalyticsHeatmap extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_url',
        'page_title',
        'session_id',
        'user_id',
        'x_position',
        'y_position',
        'viewport_width',
        'viewport_height',
        'element_selector',
        'element_type',
        'interaction_type',
        'scroll_depth',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour une URL
     */
    public function scopeForUrl($query, string $url)
    {
        return $query->where('page_url', $url);
    }

    /**
     * Scope pour un type d'interaction
     */
    public function scopeInteractionType($query, string $type)
    {
        return $query->where('interaction_type', $type);
    }
}
