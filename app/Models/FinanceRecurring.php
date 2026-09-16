<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FinanceRecurring extends Model
{
    protected $fillable = [
        'finance_category_id', 'type', 'amount', 'label', 'notes',
        'recurrence_type', 'recurrence_value', 'reminder_days_before',
        'next_due_date', 'last_generated_date', 'auto_create_transaction',
        'is_active', 'start_date', 'end_date',
    ];

    protected $casts = [
        'next_due_date' => 'date',
        'last_generated_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
        'auto_create_transaction' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(FinanceCategory::class, 'finance_category_id');
    }

    public function notifications()
    {
        return $this->hasMany(FinanceNotification::class);
    }

    public function transactions()
    {
        return $this->hasMany(FinanceTransaction::class);
    }

    // Calculer la prochaine échéance après une date donnée
    public function calculateNextDueDate(?Carbon $from = null): Carbon
    {
        $from = $from ?? now();

        if ($this->recurrence_type === 'day_of_month') {
            $day = (int) $this->recurrence_value;
            $next = $from->copy()->startOfMonth()->addDays(min($day, $from->copy()->endOfMonth()->day) - 1);
            if ($next->lte($from)) {
                $next = $next->addMonthNoOverflow()->startOfMonth();
                $next = $next->addDays(min($day, $next->copy()->endOfMonth()->day) - 1);
            }
            return $next;
        }

        // every_n_days
        return $from->copy()->addDays((int) $this->recurrence_value);
    }

    /**
     * Fait avancer next_due_date jusqu'à la prochaine échéance future si elle est
     * dépassée — utile quand la page notifications est visitée avant le passage du
     * cron finance:check-reminders (07:00), ou si celui-ci a manqué plusieurs jours.
     * Ne crée aucune transaction (contrairement au cron) : uniquement la date.
     */
    public function rescheduleIfOverdue(): bool
    {
        if (!$this->next_due_date || $this->next_due_date->gte(today())) {
            return false;
        }

        $next = $this->next_due_date;
        $iterations = 0;
        while ($next->lt(today()) && $iterations < 1000) {
            $advanced = $this->calculateNextDueDate($next);
            if ($advanced->lte($next)) {
                break; // Garde-fou : recurrence_value mal configurée (0 ou négative).
            }
            $next = $advanced;
            $iterations++;
        }

        $this->next_due_date = $next;
        $this->save();

        return true;
    }
}
