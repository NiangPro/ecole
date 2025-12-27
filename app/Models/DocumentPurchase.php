<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class DocumentPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'customer_email',
        'customer_name',
        'customer_phone',
        'country_code',
        'whatsapp_enabled',
        'document_id',
        'payment_id',
        'amount_paid',
        'currency',
        'status',
        'purchased_at',
        'download_count',
        'download_limit',
        'expires_at',
        'download_token',
        'token_expires_at'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'purchased_at' => 'datetime',
        'expires_at' => 'datetime',
        'token_expires_at' => 'datetime',
        'download_count' => 'integer',
        'download_limit' => 'integer',
        'whatsapp_enabled' => 'boolean'
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
     * Relation avec le paiement
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    /**
     * Relation avec les téléchargements
     */
    public function downloads(): HasMany
    {
        return $this->hasMany(DocumentDownload::class, 'purchase_id');
    }

    /**
     * Scope pour les achats complétés
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope pour les achats en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Générer un token unique pour le téléchargement
     */
    public function generateDownloadToken(): string
    {
        $token = \Illuminate\Support\Str::random(64);
        $this->update([
            'download_token' => $token,
            'token_expires_at' => now()->addDays(30), // Token valide 30 jours
        ]);
        return $token;
    }

    /**
     * Vérifier si le token est valide
     */
    public function isTokenValid(string $token): bool
    {
        if ($this->download_token !== $token) {
            return false;
        }

        if ($this->token_expires_at && $this->token_expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Vérifier si l'utilisateur peut télécharger
     */
    public function canDownload(): bool
    {
        // Vérifier le statut
        if ($this->status !== 'completed') {
            return false;
        }

        // Vérifier l'expiration
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        // Vérifier la limite de téléchargements
        if ($this->download_count >= $this->download_limit) {
            return false;
        }

        return true;
    }

    /**
     * Vérifier si l'achat a expiré
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Incrémenter le compteur de téléchargements
     */
    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }

    /**
     * Obtenir le nombre de téléchargements restants
     */
    public function getRemainingDownloads(): int
    {
        return max(0, $this->download_limit - $this->download_count);
    }
}
