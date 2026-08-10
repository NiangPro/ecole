<?php

namespace App\Console\Commands;

use App\Models\FinanceNotification;
use App\Models\FinanceRecurring;
use App\Models\FinanceTransaction;
use Illuminate\Console\Command;

class CheckFinanceReminders extends Command
{
    protected $signature = 'finance:check-reminders';
    protected $description = 'Vérifie les dépenses/revenus récurrents et génère les notifications dashboard';

    public function handle(): int
    {
        $today = now()->toDateString();
        $actives = FinanceRecurring::where('is_active', true)
            ->where('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->get();

        $generated = 0;

        foreach ($actives as $recurring) {
            $dueDate = $recurring->next_due_date;
            $reminderDate = $dueDate->copy()->subDays($recurring->reminder_days_before);

            // Générer notification si on est dans la fenêtre de rappel
            if (now()->gte($reminderDate) && now()->lt($dueDate->copy()->addDay())) {
                $exists = FinanceNotification::where('finance_recurring_id', $recurring->id)
                    ->where('due_date', $dueDate->toDateString())
                    ->exists();

                if (!$exists) {
                    $typeLabel = $recurring->type === 'expense' ? 'Dépense' : 'Revenu';
                    FinanceNotification::create([
                        'finance_recurring_id' => $recurring->id,
                        'title' => "⏰ {$typeLabel} récurrent : {$recurring->label}",
                        'message' => 'Échéance le '.$dueDate->format('d/m/Y').' — '.number_format($recurring->amount, 0, ',', ' ').' XOF',
                        'due_date' => $dueDate->toDateString(),
                        'is_read' => false,
                    ]);
                    $generated++;
                }
            }

            // Si la date d'échéance est passée, calculer la prochaine
            if (now()->gt($dueDate)) {
                if ($recurring->auto_create_transaction) {
                    $alreadyCreated = FinanceTransaction::where('finance_recurring_id', $recurring->id)
                        ->whereDate('transaction_date', $dueDate->toDateString())
                        ->exists();

                    if (!$alreadyCreated) {
                        FinanceTransaction::create([
                            'finance_category_id' => $recurring->finance_category_id,
                            'type' => $recurring->type,
                            'amount' => $recurring->amount,
                            'currency' => 'XOF',
                            'amount_xof' => $recurring->amount,
                            'exchange_rate' => 1,
                            'label' => $recurring->label,
                            'notes' => 'Générée automatiquement',
                            'transaction_date' => $dueDate->toDateString(),
                            'is_recurring_instance' => true,
                            'finance_recurring_id' => $recurring->id,
                        ]);
                    }
                }

                $recurring->update([
                    'last_generated_date' => $dueDate->toDateString(),
                    'next_due_date' => $recurring->calculateNextDueDate($dueDate)->toDateString(),
                ]);
            }
        }

        $this->info("Finances check done: {$generated} notification(s) générée(s).");
        return Command::SUCCESS;
    }
}
