<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FormationAdSenseUnit extends Model
{
    protected $table = 'formation_adsense_units';
    
    protected $fillable = [
        'formation_slug',
        'adsense_unit_id',
        'position',
        'order',
        'status',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Relation avec l'unité AdSense
     */
    public function adsenseUnit()
    {
        return $this->belongsTo(AdSenseUnit::class);
    }

    /**
     * Scope pour les associations actives
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope pour une formation spécifique
     */
    public function scopeForFormation($query, $slug)
    {
        return $query->where('formation_slug', $slug);
    }

    /**
     * Scope pour une position spécifique
     */
    public function scopeForPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    /**
     * Obtenir les annonces pour une formation
     * Inclut les annonces spécifiques à la formation ET les annonces globales (formation_slug = 'all')
     */
    public static function getAdsForFormation($slug, $position = null)
    {
        $cacheKey = "formation_ads_{$slug}" . ($position ? "_{$position}" : '');
        
        return Cache::remember($cacheKey, 3600, function () use ($slug, $position) {
            $query = static::active()
                ->with('adsenseUnit')
                ->whereHas('adsenseUnit', function($q) {
                    $q->where('status', 'active');
                })
                ->where(function($q) use ($slug) {
                    // Annonces spécifiques à cette formation
                    $q->where('formation_slug', $slug)
                      // OU annonces globales pour toutes les formations
                      ->orWhere('formation_slug', 'all');
                })
                ->orderBy('order');
            
            if ($position) {
                $query->forPosition($position);
            }
            
            return $query->get();
        });
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Invalider le cache lors de la création, mise à jour ou suppression
        static::saved(function ($model) {
            // Invalider le cache pour cette formation spécifique
            Cache::forget("formation_ads_{$model->formation_slug}");
            Cache::forget("formation_ads_{$model->formation_slug}_header");
            Cache::forget("formation_ads_{$model->formation_slug}_content");
            Cache::forget("formation_ads_{$model->formation_slug}_sidebar");
            Cache::forget("formation_ads_{$model->formation_slug}_footer");
            
            // Si c'est une annonce globale, invalider le cache de toutes les formations
            if ($model->formation_slug === 'all') {
                $allFormations = ['html5', 'css3', 'javascript', 'php', 'python', 'java', 'sql', 'c', 'bootstrap', 'git', 'wordpress', 'ia', 'cpp', 'csharp', 'dart'];
                foreach ($allFormations as $slug) {
                    Cache::forget("formation_ads_{$slug}");
                    Cache::forget("formation_ads_{$slug}_header");
                    Cache::forget("formation_ads_{$slug}_content");
                    Cache::forget("formation_ads_{$slug}_sidebar");
                    Cache::forget("formation_ads_{$slug}_footer");
                }
            }
        });

        static::deleted(function ($model) {
            // Invalider le cache pour cette formation spécifique
            Cache::forget("formation_ads_{$model->formation_slug}");
            Cache::forget("formation_ads_{$model->formation_slug}_header");
            Cache::forget("formation_ads_{$model->formation_slug}_content");
            Cache::forget("formation_ads_{$model->formation_slug}_sidebar");
            Cache::forget("formation_ads_{$model->formation_slug}_footer");
            
            // Si c'est une annonce globale, invalider le cache de toutes les formations
            if ($model->formation_slug === 'all') {
                $allFormations = ['html5', 'css3', 'javascript', 'php', 'python', 'java', 'sql', 'c', 'bootstrap', 'git', 'wordpress', 'ia', 'cpp', 'csharp', 'dart'];
                foreach ($allFormations as $slug) {
                    Cache::forget("formation_ads_{$slug}");
                    Cache::forget("formation_ads_{$slug}_header");
                    Cache::forget("formation_ads_{$slug}_content");
                    Cache::forget("formation_ads_{$slug}_sidebar");
                    Cache::forget("formation_ads_{$slug}_footer");
                }
            }
        });
    }
}
