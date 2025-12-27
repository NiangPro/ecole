<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle pour les événements analytics
 */
class AnalyticsEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'event_type',
        'event_name',
        'page_url',
        'page_title',
        'element_id',
        'element_class',
        'element_text',
        'metadata',
        'ip_address',
        'user_agent',
        'referrer',
        'device_type',
        'browser',
        'os',
        'country',
        'city',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour un type d'événement
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('event_type', $type);
    }

    /**
     * Scope pour une période
     */
    public function scopeInPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope pour une URL
     */
    public function scopeForUrl($query, string $url)
    {
        return $query->where('page_url', $url);
    }
}
