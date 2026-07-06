<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DownloadLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DownloadMonitoringController extends Controller
{
    /**
     * Afficher le dashboard de monitoring des téléchargements
     */
    public function index(Request $request)
    {
        $hours = (int) $request->get('hours', 24);
        $tab = $request->get('tab', 'overview');

        $since = now()->subHours($hours);

        // Statistiques globales
        $totalDownloads = DownloadLog::where('created_at', '>=', $since)->count();
        $totalBlocked = DownloadLog::where('blocked', true)
            ->where('created_at', '>=', $since)
            ->count();
        $totalSuspicious = DownloadLog::where('is_suspicious', true)
            ->where('created_at', '>=', $since)
            ->count();

        $blockRate = $totalDownloads > 0 ? round(($totalBlocked / $totalDownloads) * 100, 2) : 0;

        // Top fichiers téléchargés
        $topFiles = DownloadLog::where('created_at', '>=', $since)
            ->groupBy('file_id')
            ->selectRaw('file_id, COUNT(*) as count, SUM(CASE WHEN blocked=1 THEN 1 ELSE 0 END) as blocked_count')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Top IPs suspectes
        $suspiciousIps = DownloadLog::where('is_suspicious', true)
            ->where('created_at', '>=', $since)
            ->groupBy('identifier_hash')
            ->selectRaw('
                identifier_hash,
                COUNT(*) as attempts,
                SUM(CASE WHEN blocked=1 THEN 1 ELSE 0 END) as blocked_count,
                MAX(created_at) as last_attempt,
                GROUP_CONCAT(DISTINCT reason SEPARATOR ", ") as reasons
            ')
            ->orderByDesc('attempts')
            ->limit(15)
            ->get()
            ->map(fn($item) => [
                'hash' => substr($item->identifier_hash, 0, 16) . '...',
                'full_hash' => $item->identifier_hash,
                'attempts' => $item->attempts,
                'blocked_count' => $item->blocked_count,
                'last_attempt' => $item->last_attempt,
                'reasons' => $item->reasons,
            ]);

        // Tendance (par heure)
        $trend = DownloadLog::where('created_at', '>=', $since)
            ->groupByRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H:00:00")')
            ->selectRaw('
                DATE_FORMAT(created_at, "%Y-%m-%d %H:00:00") as hour,
                COUNT(*) as total,
                SUM(CASE WHEN blocked=1 THEN 1 ELSE 0 END) as blocked,
                SUM(CASE WHEN is_suspicious=1 THEN 1 ELSE 0 END) as suspicious
            ')
            ->orderBy('hour', 'asc')
            ->get();

        // Raisons de blocage (répartition)
        $blockReasons = DownloadLog::where('blocked', true)
            ->where('created_at', '>=', $since)
            ->groupBy('reason')
            ->selectRaw('reason, COUNT(*) as count')
            ->orderByDesc('count')
            ->get();

        // Types de fichiers
        $fileTypes = DownloadLog::where('created_at', '>=', $since)
            ->groupBy('file_type')
            ->selectRaw('file_type, COUNT(*) as count, SUM(CASE WHEN blocked=1 THEN 1 ELSE 0 END) as blocked_count')
            ->orderByDesc('count')
            ->get();

        // Logs récents
        $recentLogs = DownloadLog::where('created_at', '>=', $since)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.downloads.monitoring', compact(
            'hours',
            'tab',
            'totalDownloads',
            'totalBlocked',
            'totalSuspicious',
            'blockRate',
            'topFiles',
            'suspiciousIps',
            'trend',
            'blockReasons',
            'fileTypes',
            'recentLogs'
        ));
    }

    /**
     * Débloquer un identifiant spécifique
     */
    public function unblock(Request $request, $hash)
    {
        $request->validate(['hash' => 'required|string']);

        // Débloquer dans le cache
        \Cache::forget("dl_blocked:{$hash}");

        \Log::info('Admin débloqué', [
            'admin_id' => auth()->id(),
            'hash' => $hash,
        ]);

        return back()->with('success', 'Identifiant débloqué avec succès');
    }

    /**
     * Supprimer les logs de téléchargement
     */
    public function clearLogs(Request $request)
    {
        $request->validate(['hours' => 'required|integer|min:1']);
        
        $hours = $request->input('hours');
        $since = now()->subHours($hours);

        $deleted = DownloadLog::where('created_at', '<', $since)->delete();

        \Log::info('Admin a supprimé les logs de téléchargement', [
            'admin_id' => auth()->id(),
            'deleted_count' => $deleted,
            'older_than_hours' => $hours,
        ]);

        return back()->with('success', "Logs supprimés: {$deleted} entrées");
    }

    /**
     * Exporter les données en CSV
     */
    public function export(Request $request)
    {
        $hours = (int) $request->get('hours', 24);
        $since = now()->subHours($hours);

        $logs = DownloadLog::where('created_at', '>=', $since)
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = "download_logs_" . now()->format('Y-m-d_His') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'User ID', 'File ID', 'File Type', 'IP', 'Blocked', 'Suspicious', 'Reason', 'Created At']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user_id,
                    $log->file_id,
                    $log->file_type,
                    $log->ip_address,
                    $log->blocked ? 'Yes' : 'No',
                    $log->is_suspicious ? 'Yes' : 'No',
                    $log->reason,
                    $log->created_at,
                ]);
            }

            fclose($file);
        };

        \Log::info('Admin a exporté les logs de téléchargement', [
            'admin_id' => auth()->id(),
            'count' => $logs->count(),
        ]);

        return response()->stream($callback, 200, $headers);
    }
}
