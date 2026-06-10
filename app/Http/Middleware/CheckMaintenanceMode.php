<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    private const BYPASS_PREFIXES = [
        '/admin',
        '/login',
        '/logout',
        '/language',
        '/maintenance',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // Laisser passer les routes admin / auth sans vérification
        foreach (self::BYPASS_PREFIXES as $prefix) {
            if (str_starts_with($request->getPathInfo(), $prefix)) {
                return $next($request);
            }
        }

        $settings = Cache::remember('site_settings', 3600, fn() => \App\Models\SiteSetting::first());

        if (!$settings || !$settings->maintenance_mode) {
            return $next($request);
        }

        // Les admins passent toujours
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        // Tout le monde d'autre voit la page de maintenance
        return response()
            ->view('maintenance', ['settings' => $settings], 503)
            ->header('Retry-After', 3600);
    }
}
