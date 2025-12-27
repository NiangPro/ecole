<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle représentant une conversation entre deux utilisateurs
 * 
 * Une conversation regroupe tous les messages échangés entre deux utilisateurs.
 * Elle peut être supprimée par l'un ou l'autre utilisateur sans affecter l'autre.
 * 
 * @property int $id
 * @property int $user1_id ID du premier utilisateur
 * @property int $user2_id ID du deuxième utilisateur
 * @property \Carbon\Carbon|null $last_message_at Date du dernier message
 * @property bool $user1_deleted Indique si user1 a supprimé la conversation
 * @property bool $user2_deleted Indique si user2 a supprimé la conversation
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read \App\Models\User $user1 Premier utilisateur
 * @property-read \App\Models\User $user2 Deuxième utilisateur
 * @property-read \Illuminate\Database\Eloquent\Collection|Message[] $messages Messages de la conversation
 * @property-read Message|null $lastMessage Dernier message de la conversation
 * 
 * @package App\Models
 * @author NiangProgrammeur
 * @since 2024-12-25
 */
class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user1_id',
        'user2_id',
        'last_message_at',
        'user1_deleted',
        'user2_deleted',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'user1_deleted' => 'boolean',
        'user2_deleted' => 'boolean',
    ];

    /**
     * Relation avec le premier utilisateur de la conversation
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
     * @example
     * $conversation = Conversation::find(1);
     * $user1 = $conversation->user1; // Retourne User
     */
    public function user1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    /**
     * Relation avec le deuxième utilisateur de la conversation
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
     * @example
     * $conversation = Conversation::find(1);
     * $user2 = $conversation->user2; // Retourne User
     */
    public function user2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    /**
     * Relation avec tous les messages de la conversation
     * 
     * Les messages sont triés par date de création (plus anciens en premier).
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * 
     * @example
     * $conversation = Conversation::find(1);
     * $messages = $conversation->messages; // Collection de Message
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Relation avec le dernier message de la conversation
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * 
     * @example
     * $conversation = Conversation::find(1);
     * $lastMessage = $conversation->lastMessage; // Retourne Message ou null
     */
    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    /**
     * Récupère l'autre utilisateur de la conversation
     * 
     * Étant donné l'ID d'un utilisateur, retourne l'autre utilisateur
     * de la conversation.
     * 
     * @param int $userId ID de l'utilisateur actuel
     * @return \App\Models\User|null L'autre utilisateur ou null
     * 
     * @example
     * $conversation = Conversation::find(1);
     * $otherUser = $conversation->getOtherUser(5); // Retourne User (l'autre utilisateur)
     */
    public function getOtherUser($userId)
    {
        if ($this->user1_id == $userId) {
            return $this->user2;
        }
        return $this->user1;
    }

    /**
     * Vérifie si un utilisateur peut voir la conversation
     * 
     * Une conversation peut être vue si l'utilisateur n'a pas marqué
     * sa version de la conversation comme supprimée.
     * 
     * @param int $userId ID de l'utilisateur
     * @return bool True si l'utilisateur peut voir la conversation, false sinon
     * 
     * @example
     * $conversation = Conversation::find(1);
     * $canView = $conversation->canBeViewedBy(5); // Retourne true ou false
     */
    public function canBeViewedBy($userId)
    {
        if ($this->user1_id == $userId) {
            return !$this->user1_deleted;
        }
        if ($this->user2_id == $userId) {
            return !$this->user2_deleted;
        }
        return false;
    }
}
