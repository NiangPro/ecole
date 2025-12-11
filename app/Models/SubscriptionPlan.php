<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'currency',
        'billing_period',
        'duration_days',
        'features',
        'is_active',
        'order',
        'badge',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_days' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer',
        'is_featured' => 'boolean',
    ];

    /**
     * Relation avec les abonnements
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_type', 'slug');
    }

    /**
     * Scope pour les plans actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour ordonner par ordre
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('price');
    }

    /**
     * Scope pour les plans mis en avant
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Obtenir le prix formaté
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' ' . $this->currency;
    }

    /**
     * Obtenir la durée formatée
     */
    public function getFormattedDurationAttribute(): string
    {
        if ($this->billing_period === 'yearly') {
            return 'par an';
        }
        return 'par mois';
    }
}
