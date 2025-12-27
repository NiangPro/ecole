<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class DocumentCoupon extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'minimum_amount',
        'document_id',
        'category_id',
        'usage_limit',
        'usage_count',
        'user_limit',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
        'user_limit' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec le document (si applicable)
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Relation avec la catégorie (si applicable)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'category_id');
    }

    /**
     * Vérifier si le coupon est valide
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Vérifier si le coupon peut être utilisé pour un document
     */
    public function canBeUsedFor(Document $document): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        // Si le coupon est spécifique à un document
        if ($this->document_id && $this->document_id !== $document->id) {
            return false;
        }

        // Si le coupon est spécifique à une catégorie
        if ($this->category_id && $document->category_id !== $this->category_id) {
            return false;
        }

        // Vérifier le montant minimum
        $currentPrice = $document->discount_price ?? $document->price;
        if ($this->minimum_amount && $currentPrice < $this->minimum_amount) {
            return false;
        }

        return true;
    }

    /**
     * Calculer la réduction pour un montant donné
     */
    public function calculateDiscount(float $amount): float
    {
        if ($this->type === 'percentage') {
            $discount = ($amount * $this->value) / 100;
            return min($discount, $amount); // Ne pas dépasser le montant
        } else {
            return min($this->value, $amount); // Ne pas dépasser le montant
        }
    }

    /**
     * Appliquer le coupon et incrémenter le compteur
     */
    public function apply(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Scope pour les coupons actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            });
    }
}
