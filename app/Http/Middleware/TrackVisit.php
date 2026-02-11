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
                Cache::put($cacheKey, true, 3600);

                $donnees = [
                    'full_url' => $request->fullUrl(),
                    'page_title' => $this->getPageTitle($request),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'referer' => $request->header('referer'),
                ];

                // Exécuter après l'envoi de la réponse pour ne pas bloquer (GeoIP = appel HTTP externe)
                dispatch(function () use ($donnees) {
                    try {
                        $userAgentData = UserAgentParser::parse($donnees['user_agent']);
                        $country = GeoIPService::getCountry($donnees['ip']);
                        Statistic::create([
                            'page_url' => $donnees['full_url'],
                            'page_title' => $donnees['page_title'],
                            'ip_address' => $donnees['ip'],
                            'user_agent' => $donnees['user_agent'],
                            'referer' => $donnees['referer'],
                            'country' => $country,
                            'browser' => $userAgentData['browser'],
                            'device' => $userAgentData['device'],
                            'visit_date' => Carbon::today(),
                        ]);
                    } catch (\Exception $e) {
                        \Log::warning('Erreur lors du tracking de visite: ' . $e->getMessage());
                    }
                })->afterResponse();
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
