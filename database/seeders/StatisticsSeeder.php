<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Statistic;
use Carbon\Carbon;

class StatisticsSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            ['url' => 'http://localhost:8000/', 'title' => 'Accueil'],
            ['url' => 'http://localhost:8000/formations/html5', 'title' => 'Formation HTML5'],
            ['url' => 'http://localhost:8000/formations/css3', 'title' => 'Formation CSS3'],
            ['url' => 'http://localhost:8000/formations/javascript', 'title' => 'Formation JavaScript'],
            ['url' => 'http://localhost:8000/formations/php', 'title' => 'Formation PHP'],
            ['url' => 'http://localhost:8000/formations/bootstrap', 'title' => 'Formation Bootstrap'],
            ['url' => 'http://localhost:8000/formations/git', 'title' => 'Formation Git'],
            ['url' => 'http://localhost:8000/formations/wordpress', 'title' => 'Formation WordPress'],
            ['url' => 'http://localhost:8000/formations/ia', 'title' => 'Formation IA'],
            ['url' => 'http://localhost:8000/about', 'title' => 'À propos'],
        ];

        $ips = [
            '192.168.1.1',
            '192.168.1.2',
            '192.168.1.3',
            '192.168.1.4',
            '192.168.1.5',
            '10.0.0.1',
            '10.0.0.2',
            '172.16.0.1',
        ];

        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X)',
        ];

        // Générer des statistiques pour les 60 derniers jours
        for ($i = 60; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Nombre de visites aléatoire par jour (entre 20 et 150)
            $visitsCount = rand(20, 150);
            
            for ($j = 0; $j < $visitsCount; $j++) {
                $page = $pages[array_rand($pages)];
                
                Statistic::create([
                    'page_url' => $page['url'],
                    'page_title' => $page['title'],
                    'ip_address' => $ips[array_rand($ips)],
                    'user_agent' => $userAgents[array_rand($userAgents)],
                    'referer' => rand(0, 1) ? 'https://google.com' : null,
                    'visit_date' => $date,
                ]);
            }
        }
    }
}
