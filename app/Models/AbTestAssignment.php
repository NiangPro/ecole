<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle pour les assignations de tests A/B
 */
class AbTestAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'session_id',
        'user_id',
        'variant',
        'converted',
        'converted_at',
    ];

    protected $casts = [
        'converted' => 'boolean',
        'converted_at' => 'datetime',
    ];

    /**
     * Relation avec le test
     */
    public function test(): BelongsTo
    {
        return $this->belongsTo(AbTest::class, 'test_id');
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
