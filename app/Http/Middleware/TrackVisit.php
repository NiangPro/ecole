<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Statistic;
use App\Services\UserAgentParser;
use App\Services\GeoIPService;
use Carbon\Carbon;

class TrackVisit
{
    public function handle(Request $request, Closure $next)
    {
        // Ne pas tracker les routes admin et assets
        if (!$request->is('admin/*') && !$request->is('css/*') && !$request->is('js/*') && !$request->is('images/*')) {
            $userAgentData = UserAgentParser::parse($request->userAgent());
            $country = GeoIPService::getCountry($request->ip());
            
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
