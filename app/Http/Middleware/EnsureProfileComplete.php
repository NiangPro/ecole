<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bloque l'accès aux certificats et cours payants tant que l'utilisateur
 * n'a pas complété les sections "Informations personnelles" et
 * "Informations complémentaires" de son profil (bio exclue).
 */
class EnsureProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && !$user->hasCompletedRequiredProfile()) {
            return redirect()
                ->route('dashboard.profile')
                ->with('error', 'Veuillez compléter votre profil (informations personnelles et complémentaires) pour accéder à cette page.');
        }

        return $next($request);
    }
}
