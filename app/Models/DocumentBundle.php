<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class DocumentBundle extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'cover_image',
        'cover_type',
        'price',
        'discount_price',
        'is_featured',
        'is_active',
        'sales_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'sales_count' => 'integer',
    ];

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bundle) {
            if (empty($bundle->slug)) {
                $bundle->slug = Str::slug($bundle->name);
            }
        });
    }

    /**
     * Relation avec les items du bundle
     */
    public function items(): HasMany
    {
        return $this->hasMany(DocumentBundleItem::class, 'bundle_id')->orderBy('order');
    }

    /**
     * Relation avec les documents du bundle
     */
    public function documents()
    {
        return $this->hasManyThrough(
            Document::class,
            DocumentBundleItem::class,
            'bundle_id',
            'id',
            'id',
            'document_id'
        )->orderBy('document_bundle_items.order');
    }

    /**
     * Obtenir le prix actuel (avec réduction si applicable)
     */
    public function getCurrentPriceAttribute(): float
    {
        return $this->discount_price ?? $this->price;
    }

    /**
     * Vérifier si le bundle a une réduction
     */
    public function hasDiscount(): bool
    {
        return !is_null($this->discount_price) && $this->discount_price < $this->price;
    }

    /**
     * Obtenir le pourcentage de réduction
     */
    public function getDiscountPercentage(): float
    {
        if (!$this->hasDiscount()) {
            return 0;
        }
        
        return round((($this->price - $this->discount_price) / $this->price) * 100, 2);
    }

    /**
     * Calculer le prix total des documents individuels
     */
    public function getTotalIndividualPriceAttribute(): float
    {
        return $this->documents->sum(function($document) {
            return $document->discount_price ?? $document->price;
        });
    }

    /**
     * Obtenir l'économie réalisée
     */
    public function getSavingsAttribute(): float
    {
        $totalIndividual = $this->total_individual_price;
        $bundlePrice = $this->current_price;
        return max(0, $totalIndividual - $bundlePrice);
    }

    /**
     * Scope pour les bundles actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les bundles en vedette
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
