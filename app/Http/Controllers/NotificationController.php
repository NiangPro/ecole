<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Obtenir les notifications non lues (optimisé)
     */
    public function unread()
    {
        if (!Auth::check()) {
            return response()->json(['notifications' => [], 'count' => 0]);
        }

        $userId = Auth::id();
        
        // Base query réutilisable
        $baseQuery = Notification::where('user_id', $userId)
            ->where('is_read', false);
        
        // Récupérer les notifications
        $notifications = (clone $baseQuery)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'link' => $notification->link,
                    'icon' => $notification->icon ?? 'fa-bell',
                    'color' => $notification->color ?? '#64748b',
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'created_at_iso' => $notification->created_at->toISOString(),
                ];
            });
        
        // Compter le total (requête séparée mais optimisée avec index)
        $count = $baseQuery->count();

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
