<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogErrors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Logger les erreurs HTTP (4xx, 5xx)
        if ($response->getStatusCode() >= 400) {
            $context = [
                'status_code' => $response->getStatusCode(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'user_id' => auth()->id(),
            ];

            if ($response->getStatusCode() >= 500) {
                // Erreurs serveur critiques
                Log::error('Server Error', $context);
            } elseif ($response->getStatusCode() >= 400) {
                // Erreurs client
                Log::warning('Client Error', $context);
            }
        }

        return $response;
    }
}
