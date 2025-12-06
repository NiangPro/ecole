<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de protection CSRF renforcée
 */
class EnhancedCsrfProtection
{
    /**
     * Routes exclues de la vérification CSRF (API publiques, webhooks, etc.)
     * Note: Ce middleware complète le CSRF de Laravel, il ne le remplace pas
     */
    protected $except = [
        'api/webhooks/*',
        'api/public/*',
        'logout', // La route logout peut avoir une session détruite
        'admin/login', // Laisser Laravel gérer le CSRF pour le login admin
        'login', // Laisser Laravel gérer le CSRF pour le login utilisateur
        'register', // Laisser Laravel gérer le CSRF pour l'inscription
    ];

    /**
     * Handle an incoming request.
     * 
     * Note: Ce middleware complète le CSRF de Laravel en ajoutant des vérifications supplémentaires.
     * Il ne remplace pas le middleware CSRF de Laravel qui est déjà actif.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si la route est exclue
        if ($this->inExceptArray($request)) {
            return $next($request);
        }

        // Vérifier les méthodes qui nécessitent CSRF
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            // Vérifier l'origine de la requête (Referer) - vérification supplémentaire
            // On ne bloque pas si le referer est absent pour les formulaires normaux
            // mais on log pour surveillance
            if (!$this->isValidOrigin($request) && $request->header('referer')) {
                // Si un referer est présent mais invalide, c'est suspect
                Log::warning('Invalid request origin detected', [
                    'ip' => $request->ip(),
                    'referer' => $request->header('referer'),
                    'host' => $request->getHost(),
                    'user_id' => auth()->id(),
                    'url' => $request->fullUrl(),
                ]);

                // Enregistrer dans l'audit de sécurité (sévérité moyenne car peut être un faux positif)
                try {
                    \App\Models\SecurityAudit::log('invalid_origin', \App\Models\SecurityAudit::SEVERITY_MEDIUM, [
                        'response_code' => 403,
                        'message' => 'Origine de requête invalide détectée',
                        'metadata' => [
                            'referer' => $request->header('referer'),
                            'host' => $request->getHost(),
                        ],
                    ]);
                } catch (\Exception $e) {
                    // Ignorer si la table n'existe pas encore
                }

                // Ne pas bloquer, juste logger (le CSRF de Laravel gère déjà la sécurité)
                // return response()->json([
                //     'message' => 'Origine de la requête invalide.',
                //     'error' => 'invalid_origin',
                // ], 403);
            }

            // Vérifier le header X-Requested-With pour les requêtes AJAX (juste pour log)
            if ($request->ajax() && !$request->hasHeader('X-Requested-With')) {
                Log::info('AJAX request without X-Requested-With header', [
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl(),
                    'user_id' => auth()->id(),
                ]);
            }
        }

        return $next($request);
    }

    /**
     * Vérifier si les tokens CSRF correspondent
     * Note: Cette méthode n'est plus utilisée car Laravel gère déjà le CSRF
     * Elle est conservée pour référence mais n'est pas appelée
     */
    protected function tokensMatch(Request $request): bool
    {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
        $sessionToken = Session::token();

        // Vérifier que les deux tokens existent
        if (!$token || !$sessionToken) {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    /**
     * Vérifier si l'origine de la requête est valide
     * Retourne true si valide ou si pas de referer (pour ne pas bloquer les formulaires normaux)
     */
    protected function isValidOrigin(Request $request): bool
    {
        $referer = $request->header('referer');
        $host = $request->getHost();

        // Autoriser les requêtes sans referer (formulaires normaux, certains navigateurs)
        if (!$referer) {
            return true; // Ne pas bloquer si pas de referer
        }

        // Extraire le host du referer
        $refererHost = parse_url($referer, PHP_URL_HOST);

        if (!$refererHost) {
            return true; // Si on ne peut pas parser, ne pas bloquer
        }

        // Vérifier que le host correspond
        return $refererHost === $host 
            || $refererHost === 'www.' . $host 
            || $host === 'www.' . $refererHost;
    }

    /**
     * Déterminer si la requête est dans le tableau d'exceptions
     */
    protected function inExceptArray(Request $request): bool
    {
        foreach ($this->except as $except) {
            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }
}

