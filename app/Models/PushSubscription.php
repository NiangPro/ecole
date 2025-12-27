<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle pour les abonnements push
 */
class PushSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'endpoint',
        'keys',
        'user_agent',
    ];

    protected $casts = [
        'keys' => 'array',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir les clés décodées
     */
    public function getKeysAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Définir les clés encodées
     */
    public function setKeysAttribute($value)
    {
        $this->attributes['keys'] = is_string($value) ? $value : json_encode($value);
    }
}
