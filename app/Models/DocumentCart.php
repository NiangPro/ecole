<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class DocumentCart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'document_id',
        'quantity',
        'price'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec le document
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    /**
     * Scope pour un utilisateur spécifique
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour une session spécifique
     */
    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId)
            ->whereNull('user_id');
    }

    /**
     * Obtenir le panier actuel (utilisateur connecté ou session)
     */
    public static function getCurrentCart()
    {
        if (Auth::check()) {
            return static::forUser(Auth::id())->with('document')->get();
        }
        
        return static::forSession(session()->getId())
            ->with('document')
            ->get();
    }

    /**
     * Obtenir le total du panier
     */
    public static function getTotal($items = null): float
    {
        if (!$items) {
            $items = static::getCurrentCart();
        }

        return $items->sum(function($item) {
            return $item->price * $item->quantity;
        });
    }

    /**
     * Vider le panier
     */
    public static function clear(): void
    {
        if (Auth::check()) {
            static::forUser(Auth::id())->delete();
        } else {
            static::forSession(session()->getId())->delete();
        }
    }

    /**
     * Obtenir le sous-total d'un item
     */
    public function getSubtotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }
}
