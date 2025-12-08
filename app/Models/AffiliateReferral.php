<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateReferral extends Model
{
    protected $fillable = [
        'affiliate_id',
        'user_id',
        'referral_type',
        'referral_id',
        'amount',
        'commission',
        'status',
        'approved_at',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission' => 'decimal:2',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime'
    ];

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}



