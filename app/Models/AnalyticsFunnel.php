<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle pour les funnels de conversion
 */
class AnalyticsFunnel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'steps',
        'is_active',
    ];

    protected $casts = [
        'steps' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec les conversions
     */
    public function conversions(): HasMany
    {
        return $this->hasMany(AnalyticsFunnelConversion::class, 'funnel_id');
    }

    /**
     * Calculer le taux de conversion
     */
    public function getConversionRateAttribute(): float
    {
        $total = $this->conversions()->count();
        if ($total === 0) {
            return 0;
        }
        $completed = $this->conversions()->where('completed', true)->count();
        return round(($completed / $total) * 100, 2);
    }

    /**
     * Obtenir les statistiques par étape
     */
    public function getStepStatsAttribute(): array
    {
        $stats = [];
        foreach ($this->steps as $index => $step) {
            $reached = $this->conversions()
                ->where('step_reached', '>=', $index + 1)
                ->count();
            $stats[] = [
                'step' => $index + 1,
                'name' => $step['name'] ?? "Étape " . ($index + 1),
                'reached' => $reached,
            ];
        }
        return $stats;
    }
}
