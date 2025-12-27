<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentBundleItem extends Model
{
    protected $fillable = [
        'bundle_id',
        'document_id',
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
     * Relation avec le document
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
