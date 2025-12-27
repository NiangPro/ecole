<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class DocumentCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'image_type',
        'parent_id',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'parent_id' => 'integer'
    ];

    /**
     * Relation avec les documents
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'category_id');
    }

    /**
     * Relation avec les documents publiés
     */
    public function publishedDocuments(): HasMany
    {
        return $this->hasMany(Document::class, 'category_id')
            ->where('status', 'published')
            ->where('is_active', true);
    }

    /**
     * Relation avec la catégorie parente
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'parent_id');
    }

    /**
     * Relation avec les catégories enfants
     */
    public function children(): HasMany
    {
        return $this->hasMany(DocumentCategory::class, 'parent_id')
            ->orderBy('order');
    }

    /**
     * Scope pour les catégories actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour trier par ordre
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    /**
     * Obtenir le chemin complet avec parents
     */
    public function getFullPath(): string
    {
        $path = [$this->name];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }
        
        return implode(' > ', $path);
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Générer le slug automatiquement si non fourni
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        // Invalider le cache lors de la création, mise à jour ou suppression
        static::created(function ($category) {
            Cache::forget('active_document_categories');
            Cache::forget("document_category_{$category->slug}");
        });

        static::updated(function ($category) {
            Cache::forget('active_document_categories');
            Cache::forget("document_category_{$category->slug}");
        });

        static::deleted(function ($category) {
            Cache::forget('active_document_categories');
            Cache::forget("document_category_{$category->slug}");
        });
    }
}
