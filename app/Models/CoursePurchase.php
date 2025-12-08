<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoursePurchase extends Model
{
    protected $fillable = [
        'user_id',
        'paid_course_id',
        'amount_paid',
        'currency',
        'status',
        'payment_method',
        'payment_reference',
        'payment_details',
        'purchased_at'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_details' => 'array',
        'purchased_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(PaidCourse::class, 'paid_course_id');
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'paymentable');
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}



