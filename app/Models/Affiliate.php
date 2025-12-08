<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Affiliate extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'affiliate_code',
        'commission_rate',
        'total_earnings',
        'paid_earnings',
        'pending_earnings',
        'status',
        'notes'
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'paid_earnings' => 'decimal:2',
        'pending_earnings' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($affiliate) {
            if (empty($affiliate->affiliate_code)) {
                $affiliate->affiliate_code = Str::upper(Str::random(8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(AffiliateReferral::class);
    }

    public function getReferralUrlAttribute(): string
    {
        return url('/?ref=' . $this->affiliate_code);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}



