<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle représentant un message dans une conversation
 * 
 * Un message est envoyé d'un utilisateur à un autre dans le cadre d'une conversation.
 * Il peut contenir un sujet, un corps de message et des pièces jointes.
 * 
 * @property int $id
 * @property int $conversation_id ID de la conversation
 * @property int $sender_id ID de l'expéditeur
 * @property int $receiver_id ID du destinataire
 * @property string|null $subject Sujet du message
 * @property string $body Contenu du message
 * @property bool $is_read Indique si le message a été lu
 * @property \Carbon\Carbon|null $read_at Date de lecture
 * @property bool $sender_deleted Indique si l'expéditeur a supprimé le message
 * @property bool $receiver_deleted Indique si le destinataire a supprimé le message
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read Conversation $conversation Conversation parente
 * @property-read \App\Models\User $sender Utilisateur expéditeur
 * @property-read \App\Models\User $receiver Utilisateur destinataire
 * @property-read \Illuminate\Database\Eloquent\Collection|MessageAttachment[] $attachments Pièces jointes
 * 
 * @package App\Models
 * @author NiangProgrammeur
 * @since 2024-12-25
 */
class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'receiver_id',
        'subject',
        'body',
        'is_read',
        'read_at',
        'sender_deleted',
        'receiver_deleted',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'sender_deleted' => 'boolean',
        'receiver_deleted' => 'boolean',
    ];

    /**
     * Relation avec la conversation parente
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
     * @example
     * $message = Message::find(1);
     * $conversation = $message->conversation; // Retourne Conversation
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Relation avec l'utilisateur expéditeur du message
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
     * @example
     * $message = Message::find(1);
     * $sender = $message->sender; // Retourne User
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relation avec l'utilisateur destinataire du message
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
     * @example
     * $message = Message::find(1);
     * $receiver = $message->receiver; // Retourne User
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Relation avec les pièces jointes du message
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * 
     * @example
     * $message = Message::find(1);
     * $attachments = $message->attachments; // Collection de MessageAttachment
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(MessageAttachment::class);
    }

    /**
     * Marque le message comme lu
     * 
     * Met à jour le statut de lecture et enregistre la date de lecture.
     * Ne fait rien si le message est déjà marqué comme lu.
     * 
     * @return void
     * 
     * @example
     * $message = Message::find(1);
     * $message->markAsRead(); // Met is_read à true et read_at à now()
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Vérifie si un utilisateur peut voir le message
     * 
     * Un message peut être vu si l'utilisateur est l'expéditeur ou le destinataire
     * et qu'il n'a pas marqué sa version du message comme supprimée.
     * 
     * @param int $userId ID de l'utilisateur
     * @return bool True si l'utilisateur peut voir le message, false sinon
     * 
     * @example
     * $message = Message::find(1);
     * $canView = $message->canBeViewedBy(5); // Retourne true ou false
     */
    public function canBeViewedBy($userId)
    {
        if ($this->sender_id == $userId) {
            return !$this->sender_deleted;
        }
        if ($this->receiver_id == $userId) {
            return !$this->receiver_deleted;
        }
        return false;
    }
}
