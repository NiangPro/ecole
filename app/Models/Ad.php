<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Ad extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'image_type',
        'ad_code',
        'link_url',
        'position',
        'location',
        'status',
        'order',
        'start_date',
        'end_date',
        'clicks',
        'impressions'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'clicks' => 'integer',
        'impressions' => 'integer',
        'order' => 'integer'
    ];

    /**
     * Une publicité vidéo (YouTube) stocke ses données oEmbed en JSON dans la
     * colonne 'ad_code' — colonne texte présente depuis la création de la table
     * mais jamais utilisée jusqu'ici, réemployée pour éviter une migration.
     * Retourne null pour une publicité image classique.
     */
    public function getVideoDataAttribute(): ?array
    {
        if (empty($this->attributes['ad_code'])) {
            return null;
        }

        $data = json_decode($this->attributes['ad_code'], true);

        return (is_array($data) && !empty($data['youtube_url'])) ? $data : null;
    }

    public function isVideoAd(): bool
    {
        return $this->video_data !== null;
    }

    /**
     * Scope pour les publicités actives
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function($q) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Scope pour une position spécifique
     */
    public function scopeForPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    /**
     * Scope pour une location spécifique
     */
    public function scopeForLocation($query, $location)
    {
        return $query->where('location', $location);
    }

    /**
     * Incrémenter les impressions
     */
    public function incrementImpressions()
    {
        $this->increment('impressions');
    }

    /**
     * Incrémenter les clics
     */
    public function incrementClicks()
    {
        $this->increment('clicks');
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Invalider le cache lors de la création, mise à jour ou suppression
        static::created(function ($ad) {
            Cache::forget('sidebar_ads_content');
            Cache::forget('homepage_ads_after_exercises');
            Cache::forget('sidebar_ads_articles');
            Cache::forget('expiring_ads');
            Cache::forget('dashboard_active_ads');
            Cache::forget('dashboard_total_ads');
        });

        static::updated(function ($ad) {
            Cache::forget('sidebar_ads_content');
            Cache::forget('homepage_ads_after_exercises');
            Cache::forget('sidebar_ads_articles');
            Cache::forget('expiring_ads');
            Cache::forget('dashboard_active_ads');
            Cache::forget('dashboard_total_ads');
        });

        static::deleted(function ($ad) {
            Cache::forget('sidebar_ads_content');
            Cache::forget('homepage_ads_after_exercises');
            Cache::forget('sidebar_ads_articles');
            Cache::forget('expiring_ads');
            Cache::forget('dashboard_active_ads');
            Cache::forget('dashboard_total_ads');
        });
    }
}
