<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class DownloadRateLimiting
{
    /**
     * Configuration des limites de téléchargement
     */
    private const DOWNLOAD_LIMITS = [
        'per_hour' => 20,           // 20 téléchargements par heure
        'per_day' => 100,           // 100 téléchargements par jour
        'per_file_hour' => 10,      // 10 téléchargements du même fichier par heure
        'burst_threshold' => 10,    // 10 téléchargements rapides = détection bot
        'burst_window' => 60,       // Dans une fenêtre de 60 secondes
    ];

    /**
     * Traiter les requêtes de téléchargement
     */
    public function handle(Request $request, Closure $next): Response
    {
        $identifier = $this->resolveIdentifier($request);
        $fileId = $request->route('id') ?? 'unknown';

        // Vérifier si l'utilisateur est bloqué temporairement
        if ($this->isBlockedForBotActivity($identifier)) {
            return $this->blockResponse($request, 'Activité suspecte détectée. Réessayez plus tard.');
        }

        // Vérifier le dépassement par heure
        $hourKey = "dl_hour:{$identifier}";
        if (RateLimiter::tooManyAttempts($hourKey, self::DOWNLOAD_LIMITS['per_hour'])) {
            $this->logSuspiciousActivity($request, 'download_limit_hour_exceeded', $identifier);
            return $this->blockResponse($request, 'Limite de téléchargement dépassée. Réessayez dans 1 heure.');
        }

        // Vérifier le dépassement par jour
        $dayKey = "dl_day:{$identifier}";
        if (RateLimiter::tooManyAttempts($dayKey, self::DOWNLOAD_LIMITS['per_day'])) {
            $this->logSuspiciousActivity($request, 'download_limit_day_exceeded', $identifier);
            return $this->blockResponse($request, 'Limite de téléchargement quotidienne dépassée. Réessayez demain.');
        }

        // Vérifier le dépassement par fichier
        $fileKey = "dl_file:{$identifier}:{$fileId}";
        if (RateLimiter::tooManyAttempts($fileKey, self::DOWNLOAD_LIMITS['per_file_hour'])) {
            $this->logSuspiciousActivity($request, 'download_limit_file_exceeded', $identifier);
            return $this->blockResponse($request, 'Vous avez déjà téléchargé ce fichier plusieurs fois. Réessayez dans 1 heure.');
        }

        // Détecteur de pattern bot (burst)
        $burstKey = "dl_burst:{$identifier}";
        $burstCount = RateLimiter::attempts($burstKey);

        if ($burstCount >= self::DOWNLOAD_LIMITS['burst_threshold']) {
            $this->blockBotActivity($identifier, $request);
            return $this->blockResponse($request, 'Activité anormale détectée. Accès refusé temporairement.');
        }

        // Incrémenter les compteurs
        RateLimiter::hit($hourKey, 60 * 60);      // 1 heure
        RateLimiter::hit($dayKey, 24 * 60 * 60);  // 1 jour
        RateLimiter::hit($fileKey, 60 * 60);      // 1 heure
        RateLimiter::hit($burstKey, self::DOWNLOAD_LIMITS['burst_window']);

        $response = $next($request);

        $remaining = max(0, self::DOWNLOAD_LIMITS['per_hour'] - RateLimiter::attempts($hourKey));

        $response->headers->set('X-Download-Limit', self::DOWNLOAD_LIMITS['per_hour']);
        $response->headers->set('X-Download-Remaining', $remaining);
        $response->headers->set('X-Download-Reset', ceil((RateLimiter::availableIn($hourKey) + time()) / 60));

        return $response;
    }

    /**
     * Résoudre l'identifiant unique du client
     */
    private function resolveIdentifier(Request $request): string
    {
        $ip = $this->getClientIp($request);
        $identifier = $ip;

        // Ajouter l'ID utilisateur si authentifié
        if ($user = $request->user()) {
            $identifier = "{$ip}|{$user->id}";
        }

        return Str::lower(hash('sha256', $identifier));
    }

    /**
     * Obtenir l'IP réelle du client (proxy-aware)
     */
    private function getClientIp(Request $request): string
    {
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        }
        return $request->ip() ?? '0.0.0.0';
    }

    /**
     * Vérifier si l'identifiant est bloqué pour activité bot
     */
    private function isBlockedForBotActivity(string $identifier): bool
    {
        $blockKey = "dl_blocked:{$identifier}";
        return \Cache::get($blockKey) === true;
    }

    /**
     * Bloquer l'identifiant pour activité bot (24 heures)
     */
    private function blockBotActivity(string $identifier, Request $request): void
    {
        $blockKey = "dl_blocked:{$identifier}";
        \Cache::put($blockKey, true, 24 * 60 * 60);

        $this->logSuspiciousActivity($request, 'bot_activity_detected', $identifier);
    }

    /**
     * Logger l'activité suspecte
     */
    private function logSuspiciousActivity(Request $request, string $reason, string $identifier): void
    {
        \Log::warning('Download security: suspicious activity', [
            'identifier' => $identifier,
            'reason' => $reason,
            'ip' => $this->getClientIp($request),
            'user_id' => auth()->id(),
            'file_id' => $request->route('id'),
            'user_agent' => substr($request->userAgent(), 0, 100),
            'timestamp' => now(),
        ]);

        // Enregistrer dans l'audit de sécurité si la table existe
        try {
            \App\Models\SecurityAudit::log('download_limit_exceeded', \App\Models\SecurityAudit::SEVERITY_HIGH, [
                'identifier' => $identifier,
                'reason' => $reason,
                'ip' => $this->getClientIp($request),
            ]);
        } catch (\Exception $e) {
            // Ignorer si la table n'existe pas
        }
    }

    /**
     * Réponse de blocage :
     * - navigation navigateur → redirect arrière avec flash
     * - requête AJAX/JSON → JSON 429
     */
    private function blockResponse(Request $request, string $message): Response
    {
        if (!$request->expectsJson() && !$request->ajax()) {
            return redirect()->back()
                ->with('download_error', $message);
        }

        return response()->json([
            'message' => $message,
            'error'   => 'download_limit_exceeded',
            'status'  => 429,
        ], 429)
            ->header('Retry-After', 3600)
            ->header('X-RateLimit-Limit', self::DOWNLOAD_LIMITS['per_hour'])
            ->header('X-RateLimit-Remaining', 0);
    }
}
