<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobArticle;
use App\Models\Category;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $sitemap .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
        
        // Forcer l'URL de production pour le sitemap
        // Toujours utiliser le domaine de production pour le sitemap
        $baseUrl = 'https://niangprogrammeur.com';
        
        $sitemap .= '  <sitemap>' . PHP_EOL;
        $sitemap .= '    <loc>' . htmlspecialchars($baseUrl . '/sitemap-pages.xml', ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
        $sitemap .= '    <lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL;
        $sitemap .= '  </sitemap>' . PHP_EOL;
        
        $sitemap .= '  <sitemap>' . PHP_EOL;
        $sitemap .= '    <loc>' . htmlspecialchars($baseUrl . '/sitemap-articles.xml', ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
        $sitemap .= '    <lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL;
        $sitemap .= '  </sitemap>' . PHP_EOL;
        
        $sitemap .= '</sitemapindex>';
        
        return response($sitemap, 200)->header('Content-Type', 'application/xml');
    }
    
    public function pages()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . PHP_EOL;
        
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
        
        // Générer les entrées XML
        foreach ($pages as $page) {
            $sitemap .= '  <url>' . PHP_EOL;
            $url = $baseUrl . ($page['url'] === '/' ? '' : $page['url']);
            $sitemap .= '    <loc>' . htmlspecialchars($url, ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
            $sitemap .= '    <lastmod>' . date('Y-m-d') . '</lastmod>' . PHP_EOL;
            $sitemap .= '    <changefreq>' . $page['changefreq'] . '</changefreq>' . PHP_EOL;
            $sitemap .= '    <priority>' . $page['priority'] . '</priority>' . PHP_EOL;
            $sitemap .= '  </url>' . PHP_EOL;
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200)->header('Content-Type', 'application/xml');
    }
    
    public function articles()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">' . PHP_EOL;
        
        // Forcer l'URL de production pour le sitemap
        // Toujours utiliser le domaine de production pour le sitemap
        $baseUrl = 'https://niangprogrammeur.com';
        
        try {
            $articles = JobArticle::where('status', 'published')
                ->whereNotNull('published_at')
                ->with('category')
                ->orderBy('published_at', 'desc')
                ->get();
            
            foreach ($articles as $article) {
                $sitemap .= '  <url>' . PHP_EOL;
                $url = $baseUrl . '/emplois/article/' . $article->slug;
                $sitemap .= '    <loc>' . htmlspecialchars($url, ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
                $sitemap .= '    <lastmod>' . ($article->updated_at ? $article->updated_at->format('Y-m-d\TH:i:s+00:00') : date('Y-m-d\TH:i:s+00:00')) . '</lastmod>' . PHP_EOL;
                $sitemap .= '    <changefreq>weekly</changefreq>' . PHP_EOL;
                $sitemap .= '    <priority>0.8</priority>' . PHP_EOL;
                
                // Image pour l'article
                if ($article->cover_image) {
                    $imageUrl = $article->cover_type === 'internal' 
                        ? $baseUrl . '/' . \Illuminate\Support\Facades\Storage::url($article->cover_image)
                        : $article->cover_image;
                    $sitemap .= '    <image:image>' . PHP_EOL;
                    $sitemap .= '      <image:loc>' . htmlspecialchars($imageUrl, ENT_XML1, 'UTF-8') . '</image:loc>' . PHP_EOL;
                    $sitemap .= '      <image:title>' . htmlspecialchars($article->title, ENT_XML1, 'UTF-8') . '</image:title>' . PHP_EOL;
                    if ($article->excerpt) {
                        $sitemap .= '      <image:caption>' . htmlspecialchars(substr(strip_tags($article->excerpt), 0, 200), ENT_XML1, 'UTF-8') . '</image:caption>' . PHP_EOL;
                    }
                    $sitemap .= '    </image:image>' . PHP_EOL;
                }
                
                // News sitemap (pour Google News)
                if ($article->published_at && $article->published_at->gt(now()->subDays(2))) {
                    $sitemap .= '    <news:news>' . PHP_EOL;
                    $sitemap .= '      <news:publication>' . PHP_EOL;
                    $sitemap .= '        <news:name>NiangProgrammeur</news:name>' . PHP_EOL;
                    $sitemap .= '        <news:language>fr</news:language>' . PHP_EOL;
                    $sitemap .= '      </news:publication>' . PHP_EOL;
                    $sitemap .= '      <news:publication_date>' . $article->published_at->format('Y-m-d\TH:i:s+00:00') . '</news:publication_date>' . PHP_EOL;
                    $sitemap .= '      <news:title>' . htmlspecialchars($article->title, ENT_XML1, 'UTF-8') . '</news:title>' . PHP_EOL;
                    if ($article->excerpt) {
                        $sitemap .= '      <news:keywords>' . htmlspecialchars(substr(strip_tags($article->excerpt), 0, 200), ENT_XML1, 'UTF-8') . '</news:keywords>' . PHP_EOL;
                    }
                    $sitemap .= '    </news:news>' . PHP_EOL;
                }
                
                $sitemap .= '  </url>' . PHP_EOL;
            }
        } catch (\Exception $e) {
            // Ignorer si la table n'existe pas encore
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200)->header('Content-Type', 'application/xml');
    }
}

