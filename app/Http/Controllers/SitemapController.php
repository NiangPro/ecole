<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobArticle;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index()
    {
        // Détecter l'URL de base (production ou local)
        $baseUrl = config('app.env') === 'production' 
            ? 'https://niangprogrammeur.com' 
            : (request()->getSchemeAndHttpHost());
        
        // Cache du sitemap index pendant 1 heure (3600 secondes)
        // Le cache est invalidé automatiquement quand un article est créé/modifié/supprimé
        $sitemap = Cache::remember('sitemap_index_' . md5($baseUrl), 3600, function () use ($baseUrl) {
            $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
            $sitemap .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
            
            // Calculer la date de dernière modification des articles
            $lastArticleUpdate = Cache::remember('sitemap_articles_lastmod', 3600, function () {
                try {
                    $lastArticle = JobArticle::where('status', 'published')
                        ->whereNotNull('published_at')
                        ->orderBy('updated_at', 'desc')
                        ->first();
                    if ($lastArticle && $lastArticle->updated_at) {
                        return $lastArticle->updated_at->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    // Ignorer si la table n'existe pas
                }
                return now()->format('Y-m-d');
            });
            
            $sitemap .= '  <sitemap>' . PHP_EOL;
            $sitemap .= '    <loc>' . htmlspecialchars($baseUrl . '/sitemap-pages.xml', ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
            $sitemap .= '    <lastmod>' . now()->format('Y-m-d') . '</lastmod>' . PHP_EOL;
            $sitemap .= '  </sitemap>' . PHP_EOL;
            
            $sitemap .= '  <sitemap>' . PHP_EOL;
            $sitemap .= '    <loc>' . htmlspecialchars($baseUrl . '/sitemap-articles.xml', ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
            $sitemap .= '    <lastmod>' . ($lastArticleUpdate ?? now()->format('Y-m-d')) . '</lastmod>' . PHP_EOL;
            $sitemap .= '  </sitemap>' . PHP_EOL;
            
            $sitemap .= '</sitemapindex>';
            
            return $sitemap;
        });
        
        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }
    
    public function pages()
    {
        // Détecter l'URL de base (production ou local)
        $baseUrl = config('app.env') === 'production' 
            ? 'https://niangprogrammeur.com' 
            : (request()->getSchemeAndHttpHost());
        
        // Cache du sitemap pages pendant 6 heures (21600 secondes)
        // Les pages statiques changent rarement
        $sitemap = Cache::remember('sitemap_pages_' . md5($baseUrl), 21600, function () use ($baseUrl) {
            $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
            $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL;
        
        // Pages principales avec dates de modification dynamiques
        $pages = [
            ['url' => '/', 'priority' => '1.0', 'changefreq' => 'daily', 'lastmod' => now()->format('Y-m-d')],
            ['url' => '/about', 'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => now()->subMonths(1)->format('Y-m-d')],
            ['url' => '/contact', 'priority' => '0.8', 'changefreq' => 'monthly', 'lastmod' => now()->subMonths(1)->format('Y-m-d')],
            ['url' => '/faq', 'priority' => '0.6', 'changefreq' => 'monthly', 'lastmod' => now()->subMonths(1)->format('Y-m-d')],
            ['url' => '/privacy-policy', 'priority' => '0.5', 'changefreq' => 'yearly', 'lastmod' => now()->subYear()->format('Y-m-d')],
            ['url' => '/legal', 'priority' => '0.5', 'changefreq' => 'yearly', 'lastmod' => now()->subYear()->format('Y-m-d')],
            ['url' => '/terms', 'priority' => '0.5', 'changefreq' => 'yearly', 'lastmod' => now()->subYear()->format('Y-m-d')],
            ['url' => '/exercices', 'priority' => '0.9', 'changefreq' => 'weekly', 'lastmod' => now()->format('Y-m-d')],
            ['url' => '/quiz', 'priority' => '0.9', 'changefreq' => 'weekly', 'lastmod' => now()->format('Y-m-d')],
        ];
        
        // Pages de formations (toutes les formations)
        $formations = [
            '/formations',
            '/formations/html5',
            '/formations/css3',
            '/formations/javascript',
            '/formations/php',
            '/formations/bootstrap',
            '/formations/git',
            '/formations/wordpress',
            '/formations/ia',
            '/formations/python',
            '/formations/java',
            '/formations/sql',
            '/formations/c',
            '/formations/cpp',
            '/formations/csharp',
            '/formations/dart',
        ];
        
        foreach ($formations as $formation) {
            $pages[] = ['url' => $formation, 'priority' => '0.9', 'changefreq' => 'weekly', 'lastmod' => now()->format('Y-m-d')];
        }
        
        // Pages Exercices par langue
        $exercicesLanguages = ['html5', 'css3', 'javascript', 'php', 'bootstrap', 'python', 'java', 'sql', 'c', 'git', 'wordpress', 'ia', 'cpp', 'csharp', 'dart'];
        foreach ($exercicesLanguages as $lang) {
            $pages[] = ['url' => '/exercices/' . $lang, 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => now()->format('Y-m-d')];
        }
        
        // Pages Quiz par langue
        $quizLanguages = ['html5', 'css3', 'javascript', 'php', 'bootstrap', 'python', 'java', 'sql', 'c', 'git', 'wordpress', 'ia', 'cpp', 'csharp', 'dart'];
        foreach ($quizLanguages as $lang) {
            $pages[] = ['url' => '/quiz/' . $lang, 'priority' => '0.8', 'changefreq' => 'weekly', 'lastmod' => now()->format('Y-m-d')];
        }
        
        // Pages Emplois
        $pages[] = ['url' => '/emplois', 'priority' => '0.9', 'changefreq' => 'daily', 'lastmod' => now()->format('Y-m-d')];
        $pages[] = ['url' => '/emplois/offres', 'priority' => '0.8', 'changefreq' => 'daily', 'lastmod' => now()->format('Y-m-d')];
        $pages[] = ['url' => '/emplois/bourses', 'priority' => '0.8', 'changefreq' => 'daily', 'lastmod' => now()->format('Y-m-d')];
        $pages[] = ['url' => '/emplois/candidature-spontanee', 'priority' => '0.8', 'changefreq' => 'daily', 'lastmod' => now()->format('Y-m-d')];
        $pages[] = ['url' => '/emplois/opportunites', 'priority' => '0.8', 'changefreq' => 'daily', 'lastmod' => now()->format('Y-m-d')];
        $pages[] = ['url' => '/emplois/concours', 'priority' => '0.8', 'changefreq' => 'daily', 'lastmod' => now()->format('Y-m-d')];
        
        // Pages catégories d'emplois
        try {
            $categories = Category::where('is_active', true)->get();
            foreach ($categories as $category) {
                $pages[] = ['url' => '/emplois/categorie/' . $category->slug, 'priority' => '0.7', 'changefreq' => 'daily', 'lastmod' => now()->format('Y-m-d')];
            }
        } catch (\Exception $e) {
            // Ignorer si la table n'existe pas
        }
        
        // Générer les entrées XML
        foreach ($pages as $page) {
            $sitemap .= '  <url>' . PHP_EOL;
            $url = $baseUrl . ($page['url'] === '/' ? '' : $page['url']);
            $sitemap .= '    <loc>' . htmlspecialchars($url, ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
            $sitemap .= '    <lastmod>' . ($page['lastmod'] ?? now()->format('Y-m-d')) . '</lastmod>' . PHP_EOL;
            $sitemap .= '    <changefreq>' . $page['changefreq'] . '</changefreq>' . PHP_EOL;
            $sitemap .= '    <priority>' . $page['priority'] . '</priority>' . PHP_EOL;
            $sitemap .= '  </url>' . PHP_EOL;
        }
        
            $sitemap .= '</urlset>';
            
            return $sitemap;
        });
        
        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=21600');
    }
    
    public function articles()
    {
        // Détecter l'URL de base (production ou local)
        $baseUrl = config('app.env') === 'production' 
            ? 'https://niangprogrammeur.com' 
            : (request()->getSchemeAndHttpHost());
        
        // Cache du sitemap articles pendant 1 heure (3600 secondes)
        // Le cache est invalidé automatiquement quand un article est créé/modifié/supprimé
        $sitemap = Cache::remember('sitemap_articles_' . md5($baseUrl), 3600, function () use ($baseUrl) {
            $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
            $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">' . PHP_EOL;
            
            try {
            $articles = JobArticle::where('status', 'published')
                ->whereNotNull('published_at')
                ->with('category')
                ->orderBy('published_at', 'desc')
                ->limit(50000) // Limite Google : 50 000 URLs max par sitemap
                ->get();
            
            foreach ($articles as $article) {
                $sitemap .= '  <url>' . PHP_EOL;
                $url = $baseUrl . '/emplois/article/' . $article->slug;
                $sitemap .= '    <loc>' . htmlspecialchars($url, ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
                $sitemap .= '    <lastmod>' . ($article->updated_at ? $article->updated_at->format('Y-m-d\TH:i:s+00:00') : ($article->published_at ? $article->published_at->format('Y-m-d\TH:i:s+00:00') : now()->format('Y-m-d\TH:i:s+00:00'))) . '</lastmod>' . PHP_EOL;
                $sitemap .= '    <changefreq>weekly</changefreq>' . PHP_EOL;
                // Priorité dynamique basée sur la fraîcheur du contenu
                $priority = $article->published_at && $article->published_at->gt(now()->subDays(7)) ? '0.9' : '0.8';
                $sitemap .= '    <priority>' . $priority . '</priority>' . PHP_EOL;
                
                // Image pour l'article (important pour AdSense et SEO)
                if ($article->cover_image) {
                    $imageUrl = $article->cover_type === 'internal' 
                        ? $baseUrl . '/' . \Illuminate\Support\Facades\Storage::url($article->cover_image)
                        : $article->cover_image;
                    // S'assurer que l'URL de l'image est absolue
                    if (!preg_match('/^https?:\/\//', $imageUrl)) {
                        $imageUrl = $baseUrl . '/' . ltrim($imageUrl, '/');
                    }
                    $sitemap .= '    <image:image>' . PHP_EOL;
                    $sitemap .= '      <image:loc>' . htmlspecialchars($imageUrl, ENT_XML1, 'UTF-8') . '</image:loc>' . PHP_EOL;
                    $sitemap .= '      <image:title>' . htmlspecialchars($article->title, ENT_XML1, 'UTF-8') . '</image:title>' . PHP_EOL;
                    if ($article->excerpt) {
                        $caption = strip_tags($article->excerpt);
                        $sitemap .= '      <image:caption>' . htmlspecialchars(mb_substr($caption, 0, 200), ENT_XML1, 'UTF-8') . '</image:caption>' . PHP_EOL;
                    } elseif ($article->meta_description) {
                        $sitemap .= '      <image:caption>' . htmlspecialchars(mb_substr($article->meta_description, 0, 200), ENT_XML1, 'UTF-8') . '</image:caption>' . PHP_EOL;
                    }
                    $sitemap .= '      <image:license>' . htmlspecialchars($baseUrl, ENT_XML1, 'UTF-8') . '</image:license>' . PHP_EOL;
                    $sitemap .= '    </image:image>' . PHP_EOL;
                }
                
                // News sitemap (pour Google News) - Articles publiés dans les 2 derniers jours
                if ($article->published_at && $article->published_at->gt(now()->subDays(2))) {
                    $sitemap .= '    <news:news>' . PHP_EOL;
                    $sitemap .= '      <news:publication>' . PHP_EOL;
                    $sitemap .= '        <news:name>NiangProgrammeur</news:name>' . PHP_EOL;
                    $sitemap .= '        <news:language>fr</news:language>' . PHP_EOL;
                    $sitemap .= '      </news:publication>' . PHP_EOL;
                    $sitemap .= '      <news:publication_date>' . $article->published_at->format('Y-m-d\TH:i:s+00:00') . '</news:publication_date>' . PHP_EOL;
                    $sitemap .= '      <news:title>' . htmlspecialchars($article->title, ENT_XML1, 'UTF-8') . '</news:title>' . PHP_EOL;
                    if ($article->meta_keywords && is_array($article->meta_keywords)) {
                        $keywords = implode(', ', array_slice($article->meta_keywords, 0, 10));
                        $sitemap .= '      <news:keywords>' . htmlspecialchars(mb_substr($keywords, 0, 200), ENT_XML1, 'UTF-8') . '</news:keywords>' . PHP_EOL;
                    } elseif ($article->excerpt) {
                        $sitemap .= '      <news:keywords>' . htmlspecialchars(mb_substr(strip_tags($article->excerpt), 0, 200), ENT_XML1, 'UTF-8') . '</news:keywords>' . PHP_EOL;
                    }
                    $sitemap .= '      <news:geo_locations>SN</news:geo_locations>' . PHP_EOL;
                    $sitemap .= '    </news:news>' . PHP_EOL;
                }
                
                $sitemap .= '  </url>' . PHP_EOL;
                }
            } catch (\Exception $e) {
                // Ignorer si la table n'existe pas encore
            }
            
            $sitemap .= '</urlset>';
            
            return $sitemap;
        });
        
        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}

