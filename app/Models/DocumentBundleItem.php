<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DocumentBundleItem extends Model
{
    protected $fillable = [
        'bundle_id',
        'item_type',
        'item_id',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Relation avec le bundle
     */
    public function bundle(): BelongsTo
    {
        return $this->belongsTo(DocumentBundle::class, 'bundle_id');
    }

    /**
     * Relation polymorphe vers le contenu du pack (Document ou Epreuve)
     */
    public function itemable(): MorphTo
    {
        // Le 1er argument doit être le nom de la relation (= nom de la méthode) et non
        // le préfixe des colonnes : sinon l'eager loading matche sous une mauvaise clé
        // ("item" au lieu de "itemable") et $item->itemable reste toujours null.
        return $this->morphTo('itemable', 'item_type', 'item_id');
    }
}
