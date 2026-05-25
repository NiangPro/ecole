<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Statistic;
use App\Services\UserAgentParser;
use App\Services\GeoIPService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Foundation\Application;
use Carbon\Carbon;

class TrackVisit
{
    protected Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle(Request $request, Closure $next)
    {
        // Ne pas tracker les routes admin, assets, et API
        if (!$request->is('admin/*') &&
            !$request->is('css/*') &&
            !$request->is('js/*') &&
            !$request->is('images/*') &&
            !$request->is('api/*') &&
            $request->method() === 'GET') {

            $cacheKey = 'visit_' . md5($request->ip() . $request->path() . Carbon::today()->format('Y-m-d'));

            if (!Cache::has($cacheKey)) {
                Cache::put($cacheKey, true, 3600);

                // Stocker les données dans l'attribut de la requête pour le terminate()
                $request->attributes->set('_track_visit', [
                    'full_url'   => $request->fullUrl(),
                    'page_title' => $this->getPageTitle($request),
                    'ip'         => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'referer'    => $request->header('referer'),
                ]);
            }
        }

        return $next($request);
    }

    /**
     * Exécuté après l'envoi de la réponse au client (même process, pas de queue).
     * Compatible PHP-FPM via fastcgi_finish_request().
     */
    public function terminate(Request $request, $response): void
    {
        $donnees = $request->attributes->get('_track_visit');

        if ($donnees === null) {
            return;
        }

        try {
            $userAgentData = UserAgentParser::parse($donnees['user_agent']);
            $country       = GeoIPService::getCountry($donnees['ip']);

            Statistic::create([
                'page_url'   => $donnees['full_url'],
                'page_title' => $donnees['page_title'],
                'ip_address' => $donnees['ip'],
                'user_agent' => $donnees['user_agent'],
                'referer'    => $donnees['referer'],
                'country'    => $country,
                'browser'    => $userAgentData['browser'],
                'device'     => $userAgentData['device'],
                'visit_date' => Carbon::today(),
            ]);
        } catch (\Exception $e) {
            \Log::warning('TrackVisit: ' . $e->getMessage());
        }
    }

    private function getPageTitle(Request $request): string
    {
        $path = $request->path();

        $titles = [
            '/'                      => 'Accueil',
            'about'                  => 'À propos',
            'formations/html5'       => 'Formation HTML5',
            'formations/css3'        => 'Formation CSS3',
            'formations/javascript'  => 'Formation JavaScript',
            'formations/php'         => 'Formation PHP',
            'formations/bootstrap'   => 'Formation Bootstrap',
            'formations/git'         => 'Formation Git',
            'formations/wordpress'   => 'Formation WordPress',
            'formations/ia'          => 'Formation IA',
        ];

        return $titles[$path] ?? ucfirst($path);
    }
}
