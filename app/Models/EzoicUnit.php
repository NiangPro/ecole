<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class EzoicUnit extends Model
{
    protected $table = 'ezoic_units';
    
    protected $fillable = [
        'name',
        'description',
        'ad_id',
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
     * Scope pour ordonner
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at');
    }

    /**
     * Boot du modèle - Invalider le cache lors de la création, mise à jour ou suppression
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            Cache::forget('ezoic_units');
            Cache::forget('ezoic_units_active');
            if ($model->position) {
                Cache::forget("ezoic_units_{$model->position}");
            }
        });

        static::deleted(function ($model) {
            Cache::forget('ezoic_units');
            Cache::forget('ezoic_units_active');
            if ($model->position) {
                Cache::forget("ezoic_units_{$model->position}");
            }
        });
    }

    /**
     * Obtenir toutes les unités actives avec cache
     */
    public static function getActiveUnits()
    {
        return Cache::remember('ezoic_units_active', 3600, function () {
            return static::active()->ordered()->get();
        });
    }

    /**
     * Obtenir les unités pour une position spécifique
     */
    public static function getUnitsForPosition($position, $location = null)
    {
        $cacheKey = "ezoic_units_{$position}" . ($location ? "_{$location}" : '');
        
        return Cache::remember($cacheKey, 3600, function () use ($position, $location) {
            $query = static::active()->forPosition($position)->ordered();
            
            if ($location) {
                $query->forLocation($location);
            }
            
            return $query->get();
        });
    }
}
