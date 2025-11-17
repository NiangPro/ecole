<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $search = $request->get('search');
        $action = $request->get('action', '');
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');

        $query = AdminLog::query();

        // Recherche
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('model_type', 'like', "%{$search}%");
            });
        }

        // Filtre par action
        if ($action) {
            $query->where('action', $action);
        }

        // Tri
        $query->orderBy($sortBy, $sortOrder);

        $logs = $query->paginate(30)->withQueryString();

        // Statistiques
        $stats = [
            'total' => AdminLog::count(),
            'today' => AdminLog::whereDate('created_at', today())->count(),
            'this_week' => AdminLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => AdminLog::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
        ];

        // Actions disponibles pour le filtre
        $actions = AdminLog::select('action')->distinct()->orderBy('action')->pluck('action');

        return view('admin.logs.index', compact('logs', 'search', 'action', 'sortBy', 'sortOrder', 'stats', 'actions'));
    }
}

