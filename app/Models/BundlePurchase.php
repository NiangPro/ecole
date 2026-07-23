<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BundlePurchase extends Model
{
    protected $fillable = [
        'bundle_id',
        'user_id',
        'customer_email',
        'customer_name',
        'customer_phone',
        'country_code',
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
    ];

    public function bundle(): BelongsTo
    {
        return $this->belongsTo(DocumentBundle::class, 'bundle_id');
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
     * Marque l'achat complété, génère le token de téléchargement et livre par e-mail.
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

        $this->bundle()->increment('sales_count');

        if ($this->customer_email) {
            try {
                \Illuminate\Support\Facades\Mail::to($this->customer_email)
                    ->send(new \App\Mail\BundlePurchaseConfirmation($this));
            } catch (\Throwable $e) {
                \Log::error('Erreur envoi email pack: ' . $e->getMessage());
            }
        }
    }

    public static function findCompletedFor(int $bundleId, ?string $email, ?string $phone): ?self
    {
        return self::completed()
            ->where('bundle_id', $bundleId)
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
