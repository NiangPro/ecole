<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de rate limiting avancé avec limites différenciées selon les routes
 */
class AdvancedRateLimiting
{
    /**
     * Configuration des limites par type de route
     */
    private const LIMITS = [
        'auth' => [
            'max_attempts' => 5,
            'decay_minutes' => 15,
            'lockout_minutes' => 30,
        ],
        'api' => [
            'max_attempts' => 60,
            'decay_minutes' => 1,
        ],
        'contact' => [
            'max_attempts' => 3,
            'decay_minutes' => 60,
        ],
        'comment' => [
            'max_attempts' => 10,
            'decay_minutes' => 15,
        ],
        'newsletter' => [
            'max_attempts' => 5,
            'decay_minutes' => 60,
        ],
        'search' => [
            'max_attempts' => 30,
            'decay_minutes' => 1,
        ],
        'default' => [
            'max_attempts' => 100,
            'decay_minutes' => 1,
        ],
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $type = 'default'): Response
    {
        $config = self::LIMITS[$type] ?? self::LIMITS['default'];
        
        // Créer une clé unique basée sur l'IP et l'utilisateur
        $key = $this->resolveRequestSignature($request, $type);
        
        // Vérifier si l'utilisateur est bloqué
        if (RateLimiter::tooManyAttempts($key, $config['max_attempts'])) {
            $seconds = RateLimiter::availableIn($key);
            
            // Logger la tentative bloquée
            \Log::warning('Rate limit exceeded', [
                'ip' => $request->ip(),
                'user_id' => auth()->id(),
                'route' => $request->route()?->getName(),
                'type' => $type,
                'seconds_remaining' => $seconds,
            ]);

            // Enregistrer dans l'audit de sécurité
            try {
                \App\Models\SecurityAudit::log('rate_limit_exceeded', \App\Models\SecurityAudit::SEVERITY_MEDIUM, [
                    'response_code' => 429,
                    'message' => 'Limite de taux dépassée',
                    'metadata' => [
                        'type' => $type,
                        'seconds_remaining' => $seconds,
                    ],
                ]);
            } catch (\Exception $e) {
                // Ignorer si la table n'existe pas encore
            }
            
            return response()->json([
                'message' => 'Trop de tentatives. Veuillez réessayer dans ' . ceil($seconds / 60) . ' minute(s).',
                'retry_after' => $seconds,
            ], 429)
                ->header('Retry-After', $seconds)
                ->header('X-RateLimit-Limit', $config['max_attempts'])
                ->header('X-RateLimit-Remaining', 0);
        }
        
        // Incrémenter le compteur
        RateLimiter::hit($key, $config['decay_minutes'] * 60);
        
        // Ajouter les headers de rate limit à la réponse
        $response = $next($request);
        
        $remaining = $config['max_attempts'] - RateLimiter::attempts($key);
        
        return $response
            ->header('X-RateLimit-Limit', $config['max_attempts'])
            ->header('X-RateLimit-Remaining', max(0, $remaining));
    }
    
    /**
     * Résoudre la signature de la requête pour le rate limiting
     */
    protected function resolveRequestSignature(Request $request, string $type): string
    {
        $identifier = $request->ip();
        
        // Ajouter l'ID utilisateur si authentifié pour un rate limiting plus précis
        if ($user = $request->user()) {
            $identifier .= '|' . $user->id;
        }
        
        // Ajouter le type de route
        $identifier .= '|' . $type;
        
        // Ajouter le nom de la route si disponible
        if ($route = $request->route()) {
            $identifier .= '|' . $route->getName();
        }
        
        return Str::lower($identifier);
    }
}

