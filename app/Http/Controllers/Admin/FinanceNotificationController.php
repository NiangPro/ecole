<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceNotification;
use App\Models\FinanceRecurring;

class FinanceNotificationController extends Controller
{
    public function index()
    {
        $notifications = FinanceNotification::with('recurring.category')
            ->orderBy('is_read')
            ->orderBy('due_date')
            ->paginate(20);

        // Une échéance dépassée (ex. cron finance:check-reminders qui n'est pas encore
        // passé, ou en panne depuis plusieurs jours) est reprogrammée directement à la
        // prochaine date future dès qu'on consulte cette page, plutôt que de rester
        // affichée en retard indéfiniment.
        FinanceRecurring::where('is_active', true)
            ->where('next_due_date', '<', today())
            ->get()
            ->each->rescheduleIfOverdue();

        // Fenêtre resserrée à 2 jours sur cette page (contrairement aux 30 jours
        // du dashboard) : ici on ne veut voir que les échéances vraiment imminentes.
        $upcomingRecurrings = FinanceRecurring::where('is_active', true)
            ->where('next_due_date', '<=', now()->addDays(2))
            ->orderBy('next_due_date')
            ->with('category')
            ->get();

        return view('admin.finances.notifications.index', compact('notifications', 'upcomingRecurrings'));
    }

    public function markRead(FinanceNotification $notification)
    {
        $notification->markAsRead();
        return back()->with('success', 'Notification marquée comme lue.');
    }

    public function markAllRead()
    {
        FinanceNotification::where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
        return back()->with('success', 'Toutes les notifications ont été lues.');
    }
}
