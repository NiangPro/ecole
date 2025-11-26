<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceWwwRedirect
{
    /**
     * Handle an incoming request.
     * Force la redirection vers www.niangprogrammeur.com en production
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ne s'applique qu'en production
        if (config('app.env') !== 'production') {
            return $next($request);
        }

        $host = $request->getHost();
        
        // Si l'URL est niangprogrammeur.com (sans www), rediriger vers www.niangprogrammeur.com
        if ($host === 'niangprogrammeur.com') {
            $url = $request->getScheme() . '://www.niangprogrammeur.com' . $request->getRequestUri();
            return redirect($url, 301);
        }

        return $next($request);
    }
}
