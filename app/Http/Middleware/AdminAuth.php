<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérifier si le compte est actif
        if (!Auth::user()->is_active) {
            Auth::logout();
            return redirect()->route('admin.login')->with('error', 'Votre compte a été désactivé.');
        }

        // Pour les utilisateurs normaux, rediriger vers le dashboard si ils tentent d'accéder à des routes non autorisées
        if (!Auth::user()->isAdmin()) {
            $allowedRoutes = ['admin.dashboard', 'admin.comments.index', 'admin.comments.approve', 'admin.comments.reject', 
                            'admin.jobs.categories.index', 'admin.jobs.articles.index', 'admin.jobs.articles.show', 
                            'admin.jobs.articles.edit', 'admin.jobs.articles.create', 'admin.jobs.articles.store', 
                            'admin.jobs.articles.update', 'admin.jobs.seeder.index', 'admin.profile', 'admin.profile.update', 
                            'admin.logout', 'admin.comments.whatsapp-link', 'admin.comments.email-link'];
            
            $currentRoute = $request->route()->getName();
            
            if (!in_array($currentRoute, $allowedRoutes)) {
                return redirect()->route('admin.dashboard')->with('error', 'Accès refusé. Vous n\'avez pas les permissions nécessaires.');
            }
        }

        return $next($request);
    }
}
