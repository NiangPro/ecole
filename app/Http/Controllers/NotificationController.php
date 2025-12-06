<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Obtenir les notifications non lues
     */
    public function unread()
    {
        if (!Auth::check()) {
            return response()->json(['notifications' => [], 'count' => 0]);
        }

        $notifications = Notification::getUnread(Auth::id(), 10);
        $count = Notification::countUnread(Auth::id());

        return response()->json([
            'notifications' => $notifications,
            'count' => $count
        ]);
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false], 401);
        }

        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false], 401);
        }

        Notification::markAllAsRead(Auth::id());

        return response()->json(['success' => true]);
    }

    /**
     * Liste complète des notifications
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $notifications = Auth::user()
            ->notifications()
            ->paginate(20);

        return view('dashboard.notifications', compact('notifications'));
    }
}
