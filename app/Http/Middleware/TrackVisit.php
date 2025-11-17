<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Statistic;
use App\Services\UserAgentParser;
use App\Services\GeoIPService;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class TrackVisit
{
    public function handle(Request $request, Closure $next)
    {
        // Ne pas tracker les routes admin, assets, et API
        if (!$request->is('admin/*') && 
            !$request->is('css/*') && 
            !$request->is('js/*') && 
            !$request->is('images/*') &&
            !$request->is('api/*') &&
            $request->method() === 'GET') {
            
            // Utiliser une queue pour les statistiques afin de ne pas bloquer la réponse
            // Pour l'instant, on utilise un cache pour limiter les écritures
            $cacheKey = 'visit_' . md5($request->ip() . $request->path() . Carbon::today()->format('Y-m-d'));
            
            if (!Cache::has($cacheKey)) {
                // Mettre en cache pendant 1 heure pour éviter les doublons
                Cache::put($cacheKey, true, 3600);
                
                // Exécuter de manière asynchrone si possible, sinon en arrière-plan
                try {
                    $userAgentData = UserAgentParser::parse($request->userAgent());
                    $country = GeoIPService::getCountry($request->ip());
                    
                    // Créer la statistique
                    Statistic::create([
                        'page_url' => $request->fullUrl(),
                        'page_title' => $this->getPageTitle($request),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'referer' => $request->header('referer'),
                        'country' => $country,
                        'browser' => $userAgentData['browser'],
                        'device' => $userAgentData['device'],
                        'visit_date' => Carbon::today(),
                    ]);
                } catch (\Exception $e) {
                    // Ignorer les erreurs de tracking pour ne pas bloquer la requête
                    \Log::warning('Erreur lors du tracking de visite: ' . $e->getMessage());
                }
            }
        }
        
        return $next($request);
    }
    
    private function getPageTitle($request)
    {
        $path = $request->path();
        
        $titles = [
            '/' => 'Accueil',
            'about' => 'À propos',
            'formations/html5' => 'Formation HTML5',
            'formations/css3' => 'Formation CSS3',
            'formations/javascript' => 'Formation JavaScript',
            'formations/php' => 'Formation PHP',
            'formations/bootstrap' => 'Formation Bootstrap',
            'formations/git' => 'Formation Git',
            'formations/wordpress' => 'Formation WordPress',
            'formations/ia' => 'Formation IA',
        ];
        
        return $titles[$path] ?? ucfirst($path);
    }
}
