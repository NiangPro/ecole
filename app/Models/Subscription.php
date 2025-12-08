<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_type',
        'amount',
        'currency',
        'status',
        'start_date',
        'end_date',
        'next_billing_date',
        'payment_method',
        'payment_reference',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'next_billing_date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'paymentable');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' 
            && ($this->end_date === null || $this->end_date->isFuture());
    }
}



