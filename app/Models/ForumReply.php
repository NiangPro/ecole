<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle représentant une réponse à un topic du forum
 * 
 * Une réponse est un message posté par un utilisateur en réponse à un topic.
 * Elle peut être marquée comme meilleure réponse et recevoir des votes.
 * 
 * @property int $id
 * @property int $topic_id ID du topic
 * @property int $user_id ID de l'utilisateur auteur
 * @property string $body Contenu de la réponse
 * @property bool $is_best_answer Indique si c'est la meilleure réponse
 * @property int $votes_count Nombre de votes (upvotes - downvotes)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read ForumTopic $topic Topic parent
 * @property-read \App\Models\User $user Utilisateur auteur
 * @property-read \Illuminate\Database\Eloquent\Collection|ForumVote[] $votes Votes de la réponse
 * @property-read \Illuminate\Database\Eloquent\Collection|ForumVote[] $upvotes Votes positifs
 * @property-read \Illuminate\Database\Eloquent\Collection|ForumVote[] $downvotes Votes négatifs
 * 
 * @package App\Models
 * @author NiangProgrammeur
 * @since 2024-12-25
 */
class ForumReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'user_id',
        'body',
        'is_best_answer',
        'votes_count',
    ];

    protected $casts = [
        'is_best_answer' => 'boolean',
        'votes_count' => 'integer',
    ];

    /**
     * Gère les événements lors de la création et suppression d'une réponse
     * 
     * Lors de la création : incrémente le compteur de réponses du topic.
     * Lors de la suppression : décrémente le compteur de réponses du topic.
     * 
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->topic->incrementRepliesCount();
            $reply->topic->update(['last_reply_user_id' => $reply->user_id]);
        });

        static::deleted(function ($reply) {
            $reply->topic->decrementRepliesCount();
        });
    }

    /**
     * Relation avec le topic parent
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
     * @example
     * $reply = ForumReply::find(1);
     * $topic = $reply->topic; // Retourne ForumTopic
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(ForumTopic::class, 'topic_id');
    }

    /**
     * Relation avec l'utilisateur auteur de la réponse
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
     * @example
     * $reply = ForumReply::find(1);
     * $author = $reply->user; // Retourne User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec tous les votes de la réponse
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * 
     * @example
     * $reply = ForumReply::find(1);
     * $votes = $reply->votes; // Collection de ForumVote
     */
    public function votes(): HasMany
    {
        return $this->hasMany(ForumVote::class, 'reply_id');
    }

    /**
     * Relation avec les votes positifs (upvotes) de la réponse
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * 
     * @example
     * $reply = ForumReply::find(1);
     * $upvotes = $reply->upvotes; // Collection de ForumVote avec type='upvote'
     */
    public function upvotes(): HasMany
    {
        return $this->hasMany(ForumVote::class, 'reply_id')->where('type', 'upvote');
    }

    /**
     * Relation avec les votes négatifs (downvotes) de la réponse
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * 
     * @example
     * $reply = ForumReply::find(1);
     * $downvotes = $reply->downvotes; // Collection de ForumVote avec type='downvote'
     */
    public function downvotes(): HasMany
    {
        return $this->hasMany(ForumVote::class, 'reply_id')->where('type', 'downvote');
    }

    /**
     * Vérifie si un utilisateur a déjà voté pour cette réponse
     * 
     * @param int $userId ID de l'utilisateur
     * @return bool True si l'utilisateur a voté, false sinon
     * 
     * @example
     * $reply = ForumReply::find(1);
     * $hasVoted = $reply->hasUserVoted(5); // Retourne true ou false
     */
    public function hasUserVoted($userId)
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }

    /**
     * Récupère le vote d'un utilisateur pour cette réponse
     * 
     * @param int $userId ID de l'utilisateur
     * @return ForumVote|null Le vote de l'utilisateur ou null
     * 
     * @example
     * $reply = ForumReply::find(1);
     * $vote = $reply->getUserVote(5); // Retourne ForumVote ou null
     */
    public function getUserVote($userId)
    {
        return $this->votes()->where('user_id', $userId)->first();
    }

    /**
     * Marque cette réponse comme meilleure réponse du topic
     * 
     * Désélectionne automatiquement toutes les autres meilleures réponses du topic
     * avant de marquer cette réponse.
     * 
     * @return void
     * 
     * @example
     * $reply = ForumReply::find(1);
     * $reply->markAsBestAnswer(); // Marque is_best_answer à true
     */
    public function markAsBestAnswer()
    {
        // Désélectionner les autres meilleures réponses du topic
        $this->topic->replies()->where('is_best_answer', true)->update(['is_best_answer' => false]);
        
        // Marquer cette réponse comme meilleure
        $this->update(['is_best_answer' => true]);
    }

    /**
     * Retire le statut de meilleure réponse
     * 
     * @return void
     * 
     * @example
     * $reply = ForumReply::find(1);
     * $reply->unmarkAsBestAnswer(); // Met is_best_answer à false
     */
    public function unmarkAsBestAnswer()
    {
        $this->update(['is_best_answer' => false]);
    }

    /**
     * Incrémente le compteur de votes de la réponse
     * 
     * @param string $type Type de vote ('upvote' ou 'downvote')
     * @return void
     * 
     * @example
     * $reply = ForumReply::find(1);
     * $reply->incrementVotes('upvote'); // Incrémente votes_count
     */
    public function incrementVotes($type = 'upvote')
    {
        $this->increment('votes_count');
    }

    /**
     * Décrémente le compteur de votes de la réponse
     * 
     * @return void
     * 
     * @example
     * $reply = ForumReply::find(1);
     * $reply->decrementVotes(); // Décrémente votes_count
     */
    public function decrementVotes()
    {
        $this->decrement('votes_count');
    }
}
