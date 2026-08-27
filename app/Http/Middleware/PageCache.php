<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cache HTML complet des pages publiques dans le cache fichier.
 * Réduit le TTFB de ~440ms à ~30ms sur les hits de cache.
 *
 * Comportement :
 *  - GET uniquement, utilisateurs non authentifiés
 *  - Clé = hash de l'URL complète (query string inclus)
 *  - Le token CSRF est normalisé → __CSRF__ avant stockage
 *    et injecté au moment de servir (évite le problème de token partagé)
 *  - TTL configurable via le paramètre de route (défaut : 300 s)
 */
class PageCache
{
    public function handle(Request $request, Closure $next, int $ttl = 300): Response
    {
        // Conditions pour bypasser le cache
        if (
            $request->method() !== 'GET' ||
            auth()->check() ||
            $request->has('preview') ||
            session()->has('errors')
        ) {
            return $next($request);
        }

        // La locale effective (session 'language', paramètre `lang`) est déterminée par
        // LocaleTrait::ensureLocale() dans le contrôleur, APRÈS ce middleware. On la
        // déduit ici avec la même règle pour que la clé de cache en tienne compte —
        // sinon une réponse rendue en anglais peut être mise en cache sous l'URL
        // canonique et servie à tous les visiteurs suivants (dont Googlebot) jusqu'à
        // expiration du TTL, quelle que soit leur propre locale.
        $locale = $request->get('lang', session('language', 'fr'));
        if (!in_array($locale, ['fr', 'en'], true)) {
            $locale = 'fr';
        }

        $cacheKey = 'pcache_' . $locale . '_' . md5($request->fullUrl());

        // Servir depuis le cache si disponible
        if (Cache::has($cacheKey)) {
            $html = Cache::get($cacheKey);
            // Injecter le CSRF token de la session courante
            $html = str_replace('__CSRF__', csrf_token(), $html);
            return response($html, 200, ['Content-Type' => 'text/html; charset=UTF-8'])
                ->header('X-Page-Cache', 'HIT');
        }

        // Exécuter la requête normalement
        $response = $next($request);

        // Ne cacher que les 200 OK HTML
        if (
            $response->getStatusCode() === 200 &&
            str_contains($response->headers->get('Content-Type', ''), 'text/html')
        ) {
            $html = $response->getContent();

            // Normaliser le CSRF token avant de stocker
            // (remplace le token de la session courante par le placeholder)
            $token = csrf_token();
            if ($token) {
                $html = str_replace($token, '__CSRF__', $html);
            }

            Cache::put($cacheKey, $html, $ttl);
            $response->headers->set('X-Page-Cache', 'MISS');
        }

        return $response;
    }

    /**
     * Vider le cache d'une URL spécifique (toutes locales confondues, cf. clé
     * préfixée par la locale dans handle()).
     */
    public static function forget(string $url): void
    {
        foreach (['fr', 'en'] as $locale) {
            Cache::forget('pcache_' . $locale . '_' . md5($url));
        }
    }

    /**
     * Vider tous les caches de pages (utile après un déploiement ou modification).
     * Fonctionne uniquement avec le driver 'file'.
     */
    public static function flush(): void
    {
        Cache::flush();
    }
}
