<?php

namespace App\Services;

use App\Models\AnalyticsEvent;
use App\Models\AnalyticsHeatmap;
use App\Models\AnalyticsFunnel;
use App\Models\AnalyticsFunnelConversion;
use App\Models\AbTest;
use App\Models\AbTestAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Service pour le tracking des événements analytics
 */
class EventTrackingService
{
    /**
     * Tracker un événement
     */
    public static function trackEvent(array $data, ?Request $request = null): AnalyticsEvent
    {
        $request = $request ?? request();
        
        // Obtenir ou créer un session_id
        $sessionId = Session::getId();
        
        // Détecter le device
        $userAgent = $request->userAgent();
        $deviceType = self::detectDeviceType($userAgent);
        $browser = self::detectBrowser($userAgent);
        $os = self::detectOS($userAgent);
        
        return AnalyticsEvent::create([
            'user_id' => auth()->id(),
            'session_id' => $sessionId,
            'event_type' => $data['event_type'] ?? 'custom',
            'event_name' => $data['event_name'] ?? 'Unknown',
            'page_url' => $data['page_url'] ?? $request->fullUrl(),
            'page_title' => $data['page_title'] ?? null,
            'element_id' => $data['element_id'] ?? null,
            'element_class' => $data['element_class'] ?? null,
            'element_text' => $data['element_text'] ?? null,
            'metadata' => $data['metadata'] ?? [],
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent,
            'referrer' => $request->header('referer'),
            'device_type' => $deviceType,
            'browser' => $browser,
            'os' => $os,
            'country' => $data['country'] ?? null,
            'city' => $data['city'] ?? null,
        ]);
    }

    /**
     * Tracker un clic pour heatmap
     */
    public static function trackHeatmapClick(array $data, ?Request $request = null): AnalyticsHeatmap
    {
        $request = $request ?? request();
        $sessionId = Session::getId();
        
        return AnalyticsHeatmap::create([
            'page_url' => $data['page_url'] ?? $request->fullUrl(),
            'page_title' => $data['page_title'] ?? null,
            'session_id' => $sessionId,
            'user_id' => auth()->id(),
            'x_position' => $data['x_position'] ?? 0,
            'y_position' => $data['y_position'] ?? 0,
            'viewport_width' => $data['viewport_width'] ?? 1920,
            'viewport_height' => $data['viewport_height'] ?? 1080,
            'element_selector' => $data['element_selector'] ?? null,
            'element_type' => $data['element_type'] ?? null,
            'interaction_type' => $data['interaction_type'] ?? 'click',
            'scroll_depth' => $data['scroll_depth'] ?? null,
        ]);
    }

    /**
     * Tracker une étape de funnel
     */
    public static function trackFunnelStep(int $funnelId, int $stepNumber, bool $completed = false): AnalyticsFunnelConversion
    {
        $sessionId = Session::getId();
        $funnel = AnalyticsFunnel::findOrFail($funnelId);
        
        $conversion = AnalyticsFunnelConversion::firstOrNew([
            'funnel_id' => $funnelId,
            'session_id' => $sessionId,
            'user_id' => auth()->id(),
        ]);
        
        if (!$conversion->exists) {
            $conversion->started_at = now();
        }
        
        $conversion->step_reached = max($conversion->step_reached ?? 0, $stepNumber);
        $conversion->completed = $completed || $conversion->completed;
        
        if ($completed && !$conversion->completed_at) {
            $conversion->completed_at = now();
            $conversion->time_to_complete = $conversion->started_at->diffInSeconds(now());
        }
        
        $conversion->save();
        
        return $conversion;
    }

    /**
     * Assigner un variant A/B à un utilisateur
     */
    public static function assignABTestVariant(int $testId, string $sessionId, ?int $userId = null): string
    {
        // Vérifier si déjà assigné
        $existing = AbTestAssignment::where('test_id', $testId)
            ->where('session_id', $sessionId)
            ->first();
        
        if ($existing) {
            return $existing->variant;
        }
        
        // Obtenir le test
        $test = AbTest::findOrFail($testId);
        
        if (!$test->isCurrentlyActive()) {
            return $test->variants[0]['name'] ?? 'A';
        }
        
        // Assigner aléatoirement un variant
        $variants = $test->variants;
        $variant = $variants[array_rand($variants)];
        
        AbTestAssignment::create([
            'test_id' => $testId,
            'session_id' => $sessionId,
            'user_id' => $userId,
            'variant' => $variant['name'],
        ]);
        
        return $variant['name'];
    }

    /**
     * Marquer une conversion pour un test A/B
     */
    public static function markABTestConversion(int $testId, string $sessionId): bool
    {
        $assignment = AbTestAssignment::where('test_id', $testId)
            ->where('session_id', $sessionId)
            ->first();
        
        if ($assignment && !$assignment->converted) {
            $assignment->converted = true;
            $assignment->converted_at = now();
            $assignment->save();
            return true;
        }
        
        return false;
    }

    /**
     * Détecter le type de device
     */
    protected static function detectDeviceType(string $userAgent): string
    {
        if (preg_match('/mobile|android|iphone|ipad|ipod|blackberry|iemobile|opera mini/i', $userAgent)) {
            if (preg_match('/tablet|ipad|playbook|silk/i', $userAgent)) {
                return 'tablet';
            }
            return 'mobile';
        }
        return 'desktop';
    }

    /**
     * Détecter le navigateur
     */
    protected static function detectBrowser(string $userAgent): string
    {
        if (preg_match('/chrome/i', $userAgent) && !preg_match('/edg/i', $userAgent)) {
            return 'Chrome';
        } elseif (preg_match('/edg/i', $userAgent)) {
            return 'Edge';
        } elseif (preg_match('/firefox/i', $userAgent)) {
            return 'Firefox';
        } elseif (preg_match('/safari/i', $userAgent) && !preg_match('/chrome/i', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/opera|opr/i', $userAgent)) {
            return 'Opera';
        }
        return 'Unknown';
    }

    /**
     * Détecter l'OS
     */
    protected static function detectOS(string $userAgent): string
    {
        if (preg_match('/windows/i', $userAgent)) {
            return 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            return 'macOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            return 'Linux';
        } elseif (preg_match('/android/i', $userAgent)) {
            return 'Android';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            return 'iOS';
        }
        return 'Unknown';
    }
}

