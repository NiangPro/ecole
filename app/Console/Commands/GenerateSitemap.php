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
        
        $baseUrl = config('app.url', 'http://localhost:8000');
        
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
        
        // Générer les entrées XML
        foreach ($pages as $page) {
            $sitemap .= '  <url>' . PHP_EOL;
            $sitemap .= '    <loc>' . $baseUrl . $page['url'] . '</loc>' . PHP_EOL;
            $sitemap .= '    <lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL;
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
