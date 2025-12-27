<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentWishlist extends Model
{
    protected $fillable = [
        'user_id',
        'document_id',
        'notify_on_discount',
    ];

    protected $casts = [
        'notify_on_discount' => 'boolean',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le document
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Vérifier si un document est dans la wishlist d'un utilisateur
     */
    public static function isInWishlist(int $userId, int $documentId): bool
    {
        return self::where('user_id', $userId)
            ->where('document_id', $documentId)
            ->exists();
    }

    /**
     * Ajouter un document à la wishlist
     */
    public static function addToWishlist(int $userId, int $documentId, bool $notifyOnDiscount = true): self
    {
        return self::firstOrCreate(
            [
                'user_id' => $userId,
                'document_id' => $documentId,
            ],
            [
                'notify_on_discount' => $notifyOnDiscount,
            ]
        );
    }

    /**
     * Retirer un document de la wishlist
     */
    public static function removeFromWishlist(int $userId, int $documentId): bool
    {
        return self::where('user_id', $userId)
            ->where('document_id', $documentId)
            ->delete() > 0;
    }
}
