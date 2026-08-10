<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceNotification extends Model
{
    protected $fillable = [
        'finance_recurring_id', 'title', 'message',
        'due_date', 'is_read', 'read_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function recurring()
    {
        return $this->belongsTo(FinanceRecurring::class);
    }

    public function markAsRead(): void
    {
        $this->update(['is_read' => true, 'read_at' => now()]);
    }
}
