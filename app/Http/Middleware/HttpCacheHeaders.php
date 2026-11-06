<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * En-têtes de cache HTTP des pages HTML publiques.
 *
 * Audit CACHE-01 / SEC-02 : ces pages contiennent un jeton CSRF propre au visiteur et sont
 * rendues dans la langue de sa session. Un « Cache-Control: public » laissait le proxy de
 * l'hébergeur (et tout cache partagé) servir le même HTML — même jeton, même langue — à
 * tous les visiteurs. Politique unique pour le HTML : « private, no-cache » (le navigateur
 * peut garder la page mais doit revalider ; aucun cache partagé ne la stocke).
 * La rapidité est assurée côté serveur par PageCache.
 *
 * Le paramètre $profile (article|list) est conservé pour ne pas toucher aux routes.
 */
class HttpCacheHeaders
{
    public function handle(Request $request, Closure $next, string $profile = 'article'): Response
    {
        $response = $next($request);

        if (! str_contains((string) $response->headers->get('Content-Type', ''), 'text/html')) {
            return $response;
        }

        if ($request->user() || ! $request->isMethodCacheable() || $response->getStatusCode() >= 400) {
            $response->headers->set('Cache-Control', 'no-store, private');

            return $response;
        }

        $response->headers->set('Cache-Control', 'no-cache, private');
        $response->headers->set('Vary', 'Accept-Encoding, Cookie');

        return $response;
    }
}
