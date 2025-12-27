<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle pour les tests A/B
 */
class AbTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'test_type',
        'target_url',
        'variants',
        'goal_event',
        'traffic_percentage',
        'is_active',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'variants' => 'array',
        'is_active' => 'boolean',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'traffic_percentage' => 'integer',
    ];

    /**
     * Relation avec les assignations
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(AbTestAssignment::class, 'test_id');
    }

    /**
     * Obtenir les statistiques par variant
     */
    public function getVariantStatsAttribute(): array
    {
        $stats = [];
        foreach ($this->variants as $variant) {
            $variantName = $variant['name'];
            $total = $this->assignments()->where('variant', $variantName)->count();
            $converted = $this->assignments()
                ->where('variant', $variantName)
                ->where('converted', true)
                ->count();
            
            $stats[$variantName] = [
                'total' => $total,
                'converted' => $converted,
                'conversion_rate' => $total > 0 ? round(($converted / $total) * 100, 2) : 0,
            ];
        }
        return $stats;
    }

    /**
     * Vérifier si le test est actif
     */
    public function isCurrentlyActive(): bool
    {
        return $this->is_active &&
               (!$this->started_at || $this->started_at->isPast()) &&
               (!$this->ended_at || $this->ended_at->isFuture());
    }
}
