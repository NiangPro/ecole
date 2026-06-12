<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CorrigePurchase extends Model
{
    protected $fillable = [
        'epreuve_id',
        'user_id',
        'customer_email',
        'customer_name',
        'customer_phone',
        'country_code',
        'whatsapp_enabled',
        'payment_id',
        'amount_paid',
        'currency',
        'status',
        'purchased_at',
        'download_count',
        'download_token',
        'token_expires_at',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'purchased_at' => 'datetime',
        'token_expires_at' => 'datetime',
        'download_count' => 'integer',
        'whatsapp_enabled' => 'boolean',
    ];

    public function epreuve(): BelongsTo
    {
        return $this->belongsTo(Epreuve::class, 'epreuve_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    /**
     * Génère un token de téléchargement valable 30 jours.
     */
    public function generateDownloadToken(): string
    {
        $token = Str::random(64);
        $this->update([
            'download_token' => $token,
            'token_expires_at' => now()->addDays(30),
        ]);

        return $token;
    }

    public function isTokenValid(string $token): bool
    {
        if (!$this->download_token || !hash_equals($this->download_token, $token)) {
            return false;
        }
        if ($this->token_expires_at && $this->token_expires_at->isPast()) {
            return false;
        }

        return $this->status === 'completed';
    }

    /**
     * Marque l'achat comme complété, génère le token et déclenche la livraison
     * (e-mail + WhatsApp). Idempotent : sans effet si déjà complété.
     */
    public function markCompletedAndDeliver(): void
    {
        if ($this->status === 'completed' && $this->download_token) {
            return;
        }

        $this->update([
            'status' => 'completed',
            'purchased_at' => $this->purchased_at ?? now(),
        ]);

        if (!$this->download_token) {
            $this->generateDownloadToken();
        }

        // Livraison par e-mail
        if ($this->customer_email) {
            try {
                \Illuminate\Support\Facades\Mail::to($this->customer_email)
                    ->send(new \App\Mail\CorrigePurchaseConfirmation($this));
            } catch (\Throwable $e) {
                \Log::error('Erreur envoi email corrigé: ' . $e->getMessage());
            }
        }

        // Livraison par WhatsApp
        $waSettings = \App\Models\WhatsAppSettings::getSettings();
        if ($waSettings->enabled && $this->customer_phone && $this->country_code && $this->whatsapp_enabled) {
            try {
                \App\Services\WhatsAppService::sendCorrigePurchaseMessage($this);
            } catch (\Throwable $e) {
                \Log::error('Erreur envoi WhatsApp corrigé: ' . $e->getMessage());
            }
        }
    }

    /**
     * Achat valide existant pour ce corrigé, par e-mail ou téléphone.
     */
    public static function findCompletedFor(int $epreuveId, ?string $email, ?string $phone): ?self
    {
        return self::completed()
            ->where('epreuve_id', $epreuveId)
            ->where(function ($q) use ($email, $phone) {
                if ($email) {
                    $q->orWhere('customer_email', $email);
                }
                if ($phone) {
                    $q->orWhere('customer_phone', $phone);
                }
            })
            ->latest()
            ->first();
    }
}
