<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ajoute des en-têtes HTTP de cache agressifs pour les pages publiques statiques.
 *
 * Stratégie :
 *  - Les pages d'articles (emplois, admin-docs) : stale-while-revalidate 60 s,
 *    max-age 5 min  → le navigateur sert depuis son cache immédiatement puis
 *    revalide en arrière-plan. Idéal pour 3G/4G.
 *  - Les pages de liste / index : max-age 2 min (contenu qui change plus souvent).
 *  - Les assets statiques (JS, CSS, images) sont gérés par Vite/serveur web,
 *    ce middleware cible uniquement les réponses HTML.
 *  - Les routes authentifiées et les POST reçoivent no-store.
 */
class HttpCacheHeaders
{
    public function handle(Request $request, Closure $next, string $profile = 'article'): Response
    {
        $response = $next($request);

        // Ne jamais cacher les requêtes non-GET / non-HEAD ou les réponses d'erreur
        if (!$request->isMethodCacheable() || $response->getStatusCode() >= 400) {
            return $response;
        }

        // Ne jamais cacher si l'utilisateur est authentifié
        if ($request->user()) {
            $response->headers->set('Cache-Control', 'no-store, private');
            return $response;
        }

        // Ne pas écraser si la réponse pose déjà ses propres en-têtes de cache
        if ($response->headers->hasCacheControlDirective('no-store')
            || $response->headers->hasCacheControlDirective('private')) {
            return $response;
        }

        match ($profile) {
            'article' => $response->headers->set(
                'Cache-Control',
                'public, max-age=300, stale-while-revalidate=60, stale-if-error=3600'
            ),
            'list' => $response->headers->set(
                'Cache-Control',
                'public, max-age=120, stale-while-revalidate=30, stale-if-error=1800'
            ),
            default => null,
        };

        // Vary: Accept-Encoding pour les CDN/Infomaniak
        $response->headers->set('Vary', 'Accept-Encoding');

        return $response;
    }
}
