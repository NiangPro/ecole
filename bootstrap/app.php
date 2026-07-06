<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // L'hébergement (LWS comme Infomaniak) place un reverse proxy devant
        // Apache : il ajoute l'en-tête X-Forwarded-* et, sans cette ligne,
        // $request->ip() renvoie l'IP du proxy pour TOUS les visiteurs. Ils
        // partagent alors un unique compteur de throttle → erreurs 429 en masse.
        // On fait confiance au proxy pour lire la vraie IP du client. Trust « * »
        // est sûr ici car l'application n'est joignable QUE via le proxy de
        // l'hébergeur (impossible de l'atteindre en direct pour usurper l'en-tête).
        // Volontairement inconditionnel (pas via env) pour survivre à config:cache.
        $middleware->trustProxies(at: '*');

        // Middleware pour forcer www en production
        $middleware->web(prepend: [
            \App\Http\Middleware\ForceWwwRedirect::class,
            \App\Http\Middleware\EnhancedCsrfProtection::class,
        ]);
        
        $middleware->web(append: [
            \App\Http\Middleware\TrackVisit::class,
            \App\Http\Middleware\CheckMaintenanceMode::class,
        ]);
        
        // Alias pour les middlewares
        $middleware->alias([
            'admin'               => \App\Http\Middleware\AdminAuth::class,
            'rate.limit'          => \App\Http\Middleware\AdvancedRateLimiting::class,
            'download_rate_limit' => \App\Http\Middleware\DownloadRateLimiting::class,
            'http.cache'          => \App\Http\Middleware\HttpCacheHeaders::class,
            'page.cache'          => \App\Http\Middleware\PageCache::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
