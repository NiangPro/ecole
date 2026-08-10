<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceTransaction extends Model
{
    protected $fillable = [
        'finance_category_id', 'type', 'amount', 'currency',
        'amount_xof', 'exchange_rate', 'label', 'notes',
        'transaction_date', 'is_recurring_instance', 'finance_recurring_id',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
        'amount_xof' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'is_recurring_instance' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(FinanceCategory::class, 'finance_category_id');
    }

    public function recurring()
    {
        return $this->belongsTo(FinanceRecurring::class, 'finance_recurring_id');
    }
}
