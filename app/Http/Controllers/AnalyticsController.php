<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EventTrackingService;
use Illuminate\Support\Facades\Session;

/**
 * Contrôleur pour recevoir les événements analytics depuis le frontend
 */
class AnalyticsController extends Controller
{
    /**
     * Tracker un événement
     */
    public function trackEvent(Request $request)
    {
        $validated = $request->validate([
            'event_type' => 'required|string|max:50',
            'event_name' => 'required|string|max:255',
            'page_url' => 'required|string|max:500',
            'page_title' => 'nullable|string|max:255',
            'element_id' => 'nullable|string|max:255',
            'element_class' => 'nullable|string|max:255',
            'element_text' => 'nullable|string|max:500',
            'metadata' => 'nullable|array',
        ]);

        EventTrackingService::trackEvent($validated, $request);

        return response()->json(['success' => true]);
    }

    /**
     * Tracker un clic pour heatmap
     */
    public function trackHeatmap(Request $request)
    {
        $validated = $request->validate([
            'page_url' => 'required|string|max:500',
            'page_title' => 'nullable|string|max:255',
            'x_position' => 'required|integer|min:0',
            'y_position' => 'required|integer|min:0',
            'viewport_width' => 'required|integer|min:0',
            'viewport_height' => 'required|integer|min:0',
            'element_selector' => 'nullable|string|max:500',
            'element_type' => 'nullable|string|max:50',
            'interaction_type' => 'nullable|string|in:click,hover,scroll',
            'scroll_depth' => 'nullable|integer|min:0|max:100',
        ]);

        EventTrackingService::trackHeatmapClick($validated, $request);

        return response()->json(['success' => true]);
    }

    /**
     * Tracker une étape de funnel
     */
    public function trackFunnelStep(Request $request)
    {
        $validated = $request->validate([
            'funnel_id' => 'required|exists:analytics_funnels,id',
            'step_number' => 'required|integer|min:1',
            'completed' => 'nullable|boolean',
        ]);

        EventTrackingService::trackFunnelStep(
            $validated['funnel_id'],
            $validated['step_number'],
            $validated['completed'] ?? false
        );

        return response()->json(['success' => true]);
    }

    /**
     * Obtenir le variant A/B pour un test
     */
    public function getABTestVariant(Request $request, int $testId)
    {
        $sessionId = Session::getId();
        $variant = EventTrackingService::assignABTestVariant($testId, $sessionId, auth()->id());
        
        return response()->json(['variant' => $variant]);
    }

    /**
     * Marquer une conversion pour un test A/B
     */
    public function markABTestConversion(Request $request)
    {
        $validated = $request->validate([
            'test_id' => 'required|exists:ab_tests,id',
        ]);

        $sessionId = Session::getId();
        $converted = EventTrackingService::markABTestConversion(
            $validated['test_id'],
            $sessionId
        );

        return response()->json(['success' => $converted]);
    }
}
