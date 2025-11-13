<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'image_type',
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
}
