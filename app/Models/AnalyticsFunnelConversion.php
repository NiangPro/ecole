<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle pour les conversions de funnel
 */
class AnalyticsFunnelConversion extends Model
{
    use HasFactory;

    protected $fillable = [
        'funnel_id',
        'session_id',
        'user_id',
        'step_reached',
        'completed',
        'started_at',
        'completed_at',
        'time_to_complete',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Relation avec le funnel
     */
    public function funnel(): BelongsTo
    {
        return $this->belongsTo(AnalyticsFunnel::class, 'funnel_id');
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
