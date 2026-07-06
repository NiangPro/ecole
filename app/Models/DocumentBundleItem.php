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
        return $this->morphTo('item', 'item_type', 'item_id');
    }
}
