<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cache HTML complet des pages publiques (réduit le TTFB de ~440 ms à ~30 ms sur un HIT).
 *
 * Règles (audit CACHE-01 — le HTML ne doit jamais passer d'un visiteur à l'autre) :
 *  - lecture/écriture seulement pour un GET d'un visiteur NON connecté, SANS cookie de
 *    session existant (premier passage) et sans message flash / erreurs de validation ;
 *  - jamais de mise en cache d'une réponse non-200, non HTML, ou qui pose un cookie
 *    applicatif (les cookies de session et XSRF-TOKEN, posés plus haut dans la pile par
 *    StartSession / VerifyCsrfToken pour chaque visiteur, ne sont pas dans le HTML) ;
 *  - la clé inclut la langue réellement utilisée pour le rendu ;
 *  - certaines URL ne sont jamais mises en cache (formulaires, compte, admin…) ;
 *  - le jeton CSRF est remplacé par __CSRF__ avant stockage et le jeton de la session du
 *    visiteur est réinjecté à chaque HIT : le HTML stocké ne contient aucun jeton valide.
 *
 * Vidage : `php artisan page-cache:clear`, et automatiquement à chaque enregistrement
 * ou suppression d'un article (JobArticle).
 */
class PageCache
{
    /** URL jamais mises en cache, même si la route porte le middleware. */
    public const EXCLUDED_PATHS = [
        'login', 'register', 'logout', 'password/*', 'forgot-password', 'reset-password*',
        'contact', 'donations', 'donations/*', 'payment/*',
        'forum', 'forum/*', 'newsletter/*', 'language/*', 'search', 'search/*',
        'admin', 'admin/*', 'dashboard', 'dashboard/*', 'profile', 'profile/*',
        'api/*',
    ];

    private const VERSION_KEY = 'pcache_version';

    public function handle(Request $request, Closure $next, int $ttl = 300): Response
    {
        if (! self::isCacheableRequest($request)) {
            $response = $next($request);
            $response->headers->set('X-Page-Cache', 'BYPASS');

            return $response;
        }

        // Sans cookie de session, la langue ne peut venir que du paramètre `lang`
        // (même règle que LocaleTrait::ensureLocale()).
        $locale = $request->query('lang', 'fr');
        if (! in_array($locale, ['fr', 'en'], true)) {
            $locale = 'fr';
        }

        $cacheKey = self::key($request->fullUrl(), $locale);

        $html = Cache::get($cacheKey);
        if (is_string($html)) {
            return response(self::injectToken($html), 200, [
                'Content-Type' => 'text/html; charset=UTF-8',
                'X-Page-Cache' => 'HIT',
            ]);
        }

        $response = $next($request);

        if ($this->isStorable($response, $locale)) {
            Cache::put($cacheKey, self::stripToken($response->getContent()), $ttl);
            $response->headers->set('X-Page-Cache', 'MISS');
        } else {
            $response->headers->set('X-Page-Cache', 'BYPASS');
        }

        return $response;
    }

    /**
     * Une page peut-elle être servie depuis / écrite dans un cache partagé entre visiteurs ?
     * Utilisé aussi par FormationController (cache HTML des formations).
     */
    public static function isCacheableRequest(Request $request): bool
    {
        if (! $request->isMethod('GET') || $request->has('preview')) {
            return false;
        }

        // Visiteur déjà connu (cookie de session) ou connecté : rendu dédié, jamais partagé.
        if ($request->cookies->has(config('session.cookie')) || auth()->check()) {
            return false;
        }

        if ($request->hasSession()) {
            $session = $request->session();
            if ($session->has('errors') || ! empty($session->get('_flash.old', []))) {
                return false;
            }
        }

        return ! $request->is(...self::EXCLUDED_PATHS);
    }

    private function isStorable(Response $response, string $locale): bool
    {
        return $response->getStatusCode() === 200
            && str_contains((string) $response->headers->get('Content-Type', ''), 'text/html')
            // Un cookie posé par le contrôleur (langue, préférence…) = réponse personnalisée.
            && $response->headers->getCookies() === []
            && Cookie::getQueuedCookies() === []
            // Rendu dans une autre langue que celle de la clé (ex. locale modifiée en cours de
            // route) : on ne stocke pas plutôt que de servir la mauvaise langue.
            && app()->getLocale() === $locale
            && ! auth()->check();
    }

    /** Remplace le jeton CSRF de la session courante par un marqueur avant stockage. */
    public static function stripToken(string $html): string
    {
        $token = csrf_token();

        return $token ? str_replace($token, '__CSRF__', $html) : $html;
    }

    /** Réinjecte le jeton CSRF de la session du visiteur courant. */
    public static function injectToken(string $html): string
    {
        return str_replace('__CSRF__', (string) csrf_token(), $html);
    }

    private static function key(string $url, string $locale): string
    {
        return 'pcache_v' . self::version() . '_' . $locale . '_' . md5($url);
    }

    /** Version courante des clés ; à inclure dans toute autre clé de cache HTML (formations…). */
    public static function version(): int
    {
        return (int) Cache::get(self::VERSION_KEY, 1);
    }

    /**
     * Vider le cache d'une URL précise (toutes langues).
     */
    public static function forget(string $url): void
    {
        foreach (['fr', 'en'] as $locale) {
            Cache::forget(self::key($url, $locale));
        }
    }

    /**
     * Invalider toutes les pages en cache. On change de « version » de clé au lieu de
     * Cache::flush(), qui viderait aussi les compteurs de throttle, les réglages, etc.
     * Les anciennes entrées expirent d'elles-mêmes (TTL de quelques minutes).
     */
    public static function flush(): void
    {
        Cache::forever(self::VERSION_KEY, (int) Cache::get(self::VERSION_KEY, 1) + 1);
    }
}
