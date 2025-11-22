<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml file';

    public function handle()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
        
        // Forcer l'URL de production pour le sitemap
        // Toujours utiliser le domaine de production pour le sitemap
        $baseUrl = 'https://niangprogrammeur.com';
        
        // Pages principales
        $pages = [
            ['url' => '/', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => '/about', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => '/contact', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => '/faq', 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['url' => '/privacy-policy', 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['url' => '/legal', 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['url' => '/terms', 'priority' => '0.5', 'changefreq' => 'yearly'],
        ];
        
        // Pages de formations
        $formations = [
            '/formations/html5',
            '/formations/css3',
            '/formations/javascript',
            '/formations/php',
            '/formations/bootstrap',
            '/formations/git',
            '/formations/wordpress',
            '/formations/ia',
        ];
        
        foreach ($formations as $formation) {
            $pages[] = ['url' => $formation, 'priority' => '0.9', 'changefreq' => 'weekly'];
        }
        
        // Pages Emplois
        $pages[] = ['url' => '/emplois', 'priority' => '0.9', 'changefreq' => 'daily'];
        $pages[] = ['url' => '/emplois/offres', 'priority' => '0.8', 'changefreq' => 'daily'];
        $pages[] = ['url' => '/emplois/bourses', 'priority' => '0.8', 'changefreq' => 'daily'];
        $pages[] = ['url' => '/emplois/candidature-spontanee', 'priority' => '0.8', 'changefreq' => 'daily'];
        $pages[] = ['url' => '/emplois/opportunites', 'priority' => '0.8', 'changefreq' => 'daily'];
        
        // Articles d'emplois publiés
        try {
            $articles = \App\Models\JobArticle::where('status', 'published')
                ->whereNotNull('published_at')
                ->orderBy('published_at', 'desc')
                ->get();
            
            foreach ($articles as $article) {
                $pages[] = [
                    'url' => '/emplois/article/' . $article->slug,
                    'priority' => '0.7',
                    'changefreq' => 'weekly',
                    'lastmod' => $article->updated_at ? $article->updated_at->format('Y-m-d') : date('Y-m-d')
                ];
            }
        } catch (\Exception $e) {
            // Ignorer si la table n'existe pas encore
        }
        
        // Générer les entrées XML
        foreach ($pages as $page) {
            $sitemap .= '  <url>' . PHP_EOL;
            // S'assurer que l'URL est correctement formatée
            $url = $baseUrl . ($page['url'] === '/' ? '' : $page['url']);
            $sitemap .= '    <loc>' . htmlspecialchars($url, ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
            $sitemap .= '    <lastmod>' . (isset($page['lastmod']) ? $page['lastmod'] : date('Y-m-d')) . '</lastmod>' . PHP_EOL;
            $sitemap .= '    <changefreq>' . $page['changefreq'] . '</changefreq>' . PHP_EOL;
            $sitemap .= '    <priority>' . $page['priority'] . '</priority>' . PHP_EOL;
            $sitemap .= '  </url>' . PHP_EOL;
        }
        
        $sitemap .= '</urlset>';
        
        // Sauvegarder le fichier
        file_put_contents(public_path('sitemap.xml'), $sitemap);
        
        $this->info('Sitemap generated successfully at public/sitemap.xml');
        
        return 0;
    }
}
