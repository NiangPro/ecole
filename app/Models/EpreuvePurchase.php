<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class EpreuvePurchase extends Model
{
    protected $fillable = [
        'epreuve_id',
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

    public function markCompleted(): void
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
    }

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
