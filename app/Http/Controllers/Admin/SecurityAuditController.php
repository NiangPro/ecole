<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityAudit;
use Illuminate\Http\Request;

class SecurityAuditController extends Controller
{
    /**
     * Afficher la liste des audits de sécurité
     */
    public function index(Request $request)
    {
        $query = SecurityAudit::with('user')
            ->orderBy('created_at', 'desc');

        // Filtres
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $audits = $query->paginate(50);

        // Statistiques
        $stats = [
            'total' => SecurityAudit::count(),
            'critical' => SecurityAudit::where('severity', 'critical')->count(),
            'high' => SecurityAudit::where('severity', 'high')->count(),
            'today' => SecurityAudit::whereDate('created_at', today())->count(),
            'last_24h' => SecurityAudit::where('created_at', '>=', now()->subHours(24))->count(),
        ];

        // Top IPs suspectes
        $topIps = SecurityAudit::selectRaw('ip_address, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('ip_address')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.security-audit.index', compact('audits', 'stats', 'topIps'));
    }

    /**
     * Afficher les détails d'un audit
     */
    public function show(SecurityAudit $audit)
    {
        $audit->load('user');
        return view('admin.security-audit.show', compact('audit'));
    }

    /**
     * Exporter les audits en CSV
     */
    public function export(Request $request)
    {
        $query = SecurityAudit::query();

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $audits = $query->orderBy('created_at', 'desc')->get();

        $filename = 'security_audits_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($audits) {
            $file = fopen('php://output', 'w');
            
            // En-têtes
            fputcsv($file, [
                'ID',
                'Type d\'événement',
                'Sévérité',
                'IP',
                'Utilisateur',
                'Route',
                'Méthode',
                'Message',
                'Date',
            ]);

            // Données
            foreach ($audits as $audit) {
                fputcsv($file, [
                    $audit->id,
                    $audit->event_type,
                    $audit->severity,
                    $audit->ip_address,
                    $audit->user ? $audit->user->email : 'Anonyme',
                    $audit->route,
                    $audit->method,
                    $audit->message,
                    $audit->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

