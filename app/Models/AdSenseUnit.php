<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AdSenseUnit extends Model
{
    protected $table = 'adsense_units';
    
    protected $fillable = [
        'name',
        'description',
        'ad_slot',
        'ad_format',
        'position',
        'location',
        'size',
        'responsive',
        'status',
        'order',
        'custom_code',
    ];

    protected $casts = [
        'responsive' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope pour les unités actives
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
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
     * Scope pour ordonner par ordre
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at');
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Invalider le cache lors de la création, mise à jour ou suppression
        static::saved(function () {
            Cache::forget('adsense_units');
            Cache::forget('adsense_units_active');
        });

        static::deleted(function () {
            Cache::forget('adsense_units');
            Cache::forget('adsense_units_active');
        });
    }

    /**
     * Obtenir toutes les unités actives avec cache
     */
    public static function getActiveUnits()
    {
        return Cache::remember('adsense_units_active', 3600, function () {
            return static::active()->ordered()->get();
        });
    }

    /**
     * Obtenir les unités pour une position spécifique
     */
    public static function getUnitsForPosition($position, $location = null)
    {
        $cacheKey = "adsense_units_{$position}" . ($location ? "_{$location}" : '');
        
        return Cache::remember($cacheKey, 3600, function () use ($position, $location) {
            $query = static::active()->forPosition($position)->ordered();
            
            if ($location) {
                $query->where(function($q) use ($location) {
                    $q->whereNull('location')
                      ->orWhere('location', $location);
                });
            } else {
                $query->whereNull('location');
            }
            
            return $query->get();
        });
    }
}
