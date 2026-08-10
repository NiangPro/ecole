<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceNotification;

class FinanceNotificationController extends Controller
{
    public function index()
    {
        $notifications = FinanceNotification::with('recurring.category')
            ->orderBy('is_read')
            ->orderBy('due_date')
            ->paginate(20);

        return view('admin.finances.notifications.index', compact('notifications'));
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
