<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsEvent;
use App\Models\AnalyticsFunnel;
use App\Models\AnalyticsHeatmap;
use App\Models\AbTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Contrôleur admin pour le dashboard analytics
 */
class AnalyticsController extends Controller
{
    /**
     * Obtenir les tests A/B actifs pour une URL
     */
    public function getActiveABTests(Request $request)
    {
        $url = $request->get('url');
        
        $tests = AbTest::where('is_active', true)
            ->where('target_url', $url)
            ->where(function($query) {
                $query->whereNull('started_at')
                      ->orWhere('started_at', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('ended_at')
                      ->orWhere('ended_at', '>=', now());
            })
            ->get(['id', 'name', 'test_type', 'variants']);
        
        return response()->json($tests);
    }

    /**
     * Afficher le dashboard analytics
     */
    public function index(Request $request)
    {
        $period = $request->get('period', '7days'); // 7days, 30days, 90days, all
        
        $startDate = match($period) {
            '7days' => Carbon::now()->subDays(7),
            '30days' => Carbon::now()->subDays(30),
            '90days' => Carbon::now()->subDays(90),
            default => Carbon::now()->subYear(),
        };
        
        // Statistiques générales
        $stats = [
            'total_events' => AnalyticsEvent::where('created_at', '>=', $startDate)->count(),
            'total_page_views' => AnalyticsEvent::where('event_type', 'page_view')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'total_clicks' => AnalyticsEvent::where('event_type', 'click')
                ->where('created_at', '>=', $startDate)
                ->count(),
            'unique_visitors' => AnalyticsEvent::where('created_at', '>=', $startDate)
                ->distinct('session_id')
                ->count('session_id'),
            'unique_users' => AnalyticsEvent::where('created_at', '>=', $startDate)
                ->whereNotNull('user_id')
                ->distinct('user_id')
                ->count('user_id'),
        ];
        
        // Événements par type
        $eventsByType = AnalyticsEvent::where('created_at', '>=', $startDate)
            ->select('event_type', DB::raw('count(*) as count'))
            ->groupBy('event_type')
            ->orderBy('count', 'desc')
            ->get();
        
        // Pages les plus visitées
        $topPages = AnalyticsEvent::where('event_type', 'page_view')
            ->where('created_at', '>=', $startDate)
            ->select('page_url', 'page_title', DB::raw('count(*) as views'))
            ->groupBy('page_url', 'page_title')
            ->orderBy('views', 'desc')
            ->limit(10)
            ->get();
        
        // Événements par jour
        $eventsByDay = AnalyticsEvent::where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        
        // Devices
        $devices = AnalyticsEvent::where('created_at', '>=', $startDate)
            ->select('device_type', DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->get();
        
        // Navigateurs
        $browsers = AnalyticsEvent::where('created_at', '>=', $startDate)
            ->select('browser', DB::raw('count(*) as count'))
            ->groupBy('browser')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();
        
        // Funnels actifs
        $funnels = AnalyticsFunnel::where('is_active', true)->with('conversions')->get();
        
        // Tests A/B actifs
        $abTests = AbTest::where('is_active', true)->with('assignments')->get();
        
        return view('admin.analytics.index', compact(
            'stats',
            'eventsByType',
            'topPages',
            'eventsByDay',
            'devices',
            'browsers',
            'funnels',
            'abTests',
            'period'
        ));
    }

    /**
     * Afficher les détails d'un funnel
     */
    public function showFunnel($id)
    {
        $funnel = AnalyticsFunnel::with('conversions')->findOrFail($id);
        
        return view('admin.analytics.funnel', compact('funnel'));
    }

    /**
     * Afficher les détails d'un test A/B
     */
    public function showABTest($id)
    {
        $test = AbTest::with('assignments')->findOrFail($id);
        
        return view('admin.analytics.ab-test', compact('test'));
    }

    /**
     * Afficher les heatmaps pour une URL
     */
    public function showHeatmap(Request $request)
    {
        $url = $request->get('url');
        
        if (!$url) {
            $urls = AnalyticsHeatmap::select('page_url', DB::raw('count(*) as clicks'))
                ->groupBy('page_url')
                ->orderBy('clicks', 'desc')
                ->limit(20)
                ->get();
            
            return view('admin.analytics.heatmaps', compact('urls'));
        }
        
        $heatmapData = AnalyticsHeatmap::where('page_url', $url)
            ->where('interaction_type', 'click')
            ->get();
        
        return view('admin.analytics.heatmap-detail', compact('heatmapData', 'url'));
    }
}
