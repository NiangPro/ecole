<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    protected $fillable = [
        'user_id',
        'donor_name',
        'donor_email',
        'amount',
        'currency',
        'status',
        'payment_method',
        'payment_reference',
        'message',
        'is_anonymous',
        'show_on_wall',
        'completed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'show_on_wall' => 'boolean',
        'completed_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'paymentable');
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->is_anonymous ? 'Anonyme' : $this->donor_name;
    }
}



