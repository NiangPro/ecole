<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Hébergement LWS derrière un reverse proxy : sans ceci, $request->ip()
        // renvoie l'IP du proxy pour tous les visiteurs, qui partagent alors
        // les mêmes compteurs de throttle (erreurs 429 "Too Many Requests")
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
            'admin' => \App\Http\Middleware\AdminAuth::class,
            'rate.limit' => \App\Http\Middleware\AdvancedRateLimiting::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
