<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle représentant un vote sur une réponse du forum
 * 
 * Un vote peut être positif (upvote) ou négatif (downvote).
 * Un utilisateur ne peut voter qu'une seule fois par réponse.
 * 
 * @property int $id
 * @property int $reply_id ID de la réponse
 * @property int $user_id ID de l'utilisateur qui vote
 * @property string $type Type de vote ('upvote' ou 'downvote')
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read ForumReply $reply Réponse votée
 * @property-read \App\Models\User $user Utilisateur qui a voté
 * 
 * @package App\Models
 * @author NiangProgrammeur
 * @since 2024-12-25
 */
class ForumVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'reply_id',
        'user_id',
        'type',
    ];

    /**
     * Gère les événements lors de la création et suppression d'un vote
     * 
     * Lors de la création : incrémente le compteur de votes de la réponse.
     * Lors de la suppression : décrémente le compteur de votes de la réponse.
     * 
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($vote) {
            $vote->reply->incrementVotes($vote->type);
        });

        static::deleted(function ($vote) {
            $vote->reply->decrementVotes();
        });
    }

    /**
     * Relation avec la réponse votée
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
     * @example
     * $vote = ForumVote::find(1);
     * $reply = $vote->reply; // Retourne ForumReply
     */
    public function reply(): BelongsTo
    {
        return $this->belongsTo(ForumReply::class, 'reply_id');
    }

    /**
     * Relation avec l'utilisateur qui a voté
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
     * @example
     * $vote = ForumVote::find(1);
     * $user = $vote->user; // Retourne User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
