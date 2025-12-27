<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentDownload extends Model
{
    protected $fillable = [
        'purchase_id',
        'user_id',
        'document_id',
        'ip_address',
        'user_agent',
        'downloaded_at'
    ];

    protected $casts = [
        'downloaded_at' => 'datetime'
    ];

    /**
     * Relation avec l'achat
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(DocumentPurchase::class, 'purchase_id');
    }

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
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Définir downloaded_at automatiquement
        static::creating(function ($download) {
            if (!$download->downloaded_at) {
                $download->downloaded_at = now();
            }
        });
    }
}
