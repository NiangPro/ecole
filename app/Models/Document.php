<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Document extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'excerpt',
        'category_id',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'file_extension',
        'cover_image',
        'cover_type',
        'price',
        'discount_price',
        'is_free',
        'is_featured',
        'is_active',
        'status',
        'download_count',
        'sales_count',
        'views_count',
        'author_id',
        'tags',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'is_free' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'download_count' => 'integer',
        'sales_count' => 'integer',
        'views_count' => 'integer',
        'tags' => 'array',
        'published_at' => 'datetime'
    ];

    /**
     * Relation avec la catégorie
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'category_id');
    }

    /**
     * Relation avec l'auteur
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Relation avec les achats
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(DocumentPurchase::class, 'document_id');
    }

    /**
     * Relation avec les achats complétés
     */
    public function completedPurchases(): HasMany
    {
        return $this->hasMany(DocumentPurchase::class, 'document_id')
            ->where('status', 'completed');
    }

    /**
     * Relation avec le panier
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(DocumentCart::class, 'document_id');
    }

    /**
     * Relation avec les avis
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(DocumentReview::class);
    }

    /**
     * Relation avec les avis approuvés
     */
    public function approvedReviews(): HasMany
    {
        return $this->hasMany(DocumentReview::class)->where('is_approved', true);
    }

    /**
     * Relation avec la wishlist
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(DocumentWishlist::class);
    }

    /**
     * Relation avec les bundles
     */
    public function bundleItems(): HasMany
    {
        return $this->hasMany(DocumentBundleItem::class);
    }

    /**
     * Relation avec les bundles
     */
    public function bundles()
    {
        return $this->belongsToMany(
            DocumentBundle::class,
            'document_bundle_items',
            'document_id',
            'bundle_id'
        )->withTimestamps();
    }

    /**
     * Scope pour les documents publiés
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Scope pour les documents actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les documents en vedette
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope pour filtrer par catégorie
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope pour la recherche
     */
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function($q) use ($searchTerm) {
            $q->where('title', 'like', "%{$searchTerm}%")
              ->orWhere('description', 'like', "%{$searchTerm}%")
              ->orWhere('excerpt', 'like', "%{$searchTerm}%")
              ->orWhereJsonContains('tags', $searchTerm);
        });
    }

    /**
     * Obtenir le prix actuel (avec réduction si applicable)
     */
    public function getCurrentPriceAttribute(): float
    {
        if ($this->is_free) {
            return 0;
        }
        return $this->discount_price ?? $this->price;
    }

    /**
     * Vérifier si le document est gratuit
     */
    public function isFree(): bool
    {
        return $this->is_free || $this->price == 0;
    }

    /**
     * Obtenir la note moyenne des avis
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    /**
     * Obtenir le nombre total d'avis approuvés
     */
    public function getReviewsCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Vérifier si le document a une réduction
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
     * Obtenir l'URL du fichier de téléchargement sécurisé
     */
    public function getFileUrl(): string
    {
        return route('documents.download', ['id' => $this->id]);
    }

    /**
     * Obtenir le chemin complet du fichier
     */
    public function getFilePath(): string
    {
        return Storage::disk('local')->path($this->file_path);
    }

    /**
     * Vérifier si un utilisateur peut télécharger ce document
     */
    public function canBeDownloadedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        $purchase = DocumentPurchase::where('user_id', $user->id)
            ->where('document_id', $this->id)
            ->where('status', 'completed')
            ->first();

        if (!$purchase) {
            return false;
        }

        return $purchase->canDownload();
    }

    /**
     * Incrémenter le compteur de vues
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Incrémenter le compteur de ventes
     */
    public function incrementSales(): void
    {
        $this->increment('sales_count');
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Générer le slug automatiquement si non fourni
        static::creating(function ($document) {
            if (empty($document->slug)) {
                $baseSlug = Str::slug($document->title);
                $slug = $baseSlug;
                $counter = 1;
                
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                
                $document->slug = $slug;
            }

            // Définir published_at si le statut est published
            if ($document->status === 'published' && !$document->published_at) {
                $document->published_at = now();
            }
        });

        // Mettre à jour published_at lors de la publication
        static::updating(function ($document) {
            if ($document->isDirty('status') && $document->status === 'published' && !$document->published_at) {
                $document->published_at = now();
            }
        });
    }
}
