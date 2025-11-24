<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Langues supportées
        $supportedLocales = ['fr', 'en'];
        
        // Récupérer la langue depuis la session ou utiliser 'fr' par défaut
        $locale = Session::get('locale', 'fr');
        
        // Vérifier que la langue est supportée
        if (!in_array($locale, $supportedLocales)) {
            $locale = 'fr';
        }
        
        // Définir la locale de l'application
        App::setLocale($locale);
        
        return $next($request);
    }
}
