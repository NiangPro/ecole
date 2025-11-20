<?php

namespace App\Services;

use App\Models\JobArticle;
use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ArticleGeneratorService
{
    private $newsApiKey;
    private $unsplashApiKey;
    private $openaiApiKey;

    public function __construct()
    {
        $this->newsApiKey = config('services.newsapi.key', env('NEWS_API_KEY'));
        $this->unsplashApiKey = config('services.unsplash.key', env('UNSPLASH_API_KEY'));
        $this->openaiApiKey = config('services.openai.key', env('OPENAI_API_KEY'));
    }

    /**
     * Génère des articles à partir d'articles récents trouvés sur le web
     */
    public function generateArticles(int $count, ?int $categoryId = null, int $days = 3): array
    {
        $created = [];
        $errors = [];

        // Rechercher des articles récents
        $recentArticles = $this->searchRecentArticles($count * 2); // Chercher plus pour avoir du choix

        if (empty($recentArticles)) {
            throw new \Exception('Aucun article récent trouvé. Vérifiez votre connexion internet et les clés API. Le système peut fonctionner avec Google News RSS même sans clés API.');
        }

        // S'assurer qu'on a assez d'articles
        if (count($recentArticles) < $count) {
            Log::warning('Moins d\'articles trouvés que demandé', [
                'demandé' => $count,
                'trouvé' => count($recentArticles)
            ]);
        }

        // Obtenir les catégories
        if ($categoryId) {
            $category = Category::find($categoryId);
            if (!$category) {
                throw new \Exception('Catégorie introuvable.');
            }
            $categories = collect([$category]);
        } else {
            $categories = Category::where('is_active', true)->get();
        }

        if ($categories->isEmpty()) {
            throw new \Exception('Aucune catégorie active trouvée.');
        }

        $categoryIndex = 0;
        $categoryCount = $categories->count();

        foreach (array_slice($recentArticles, 0, $count) as $index => $article) {
            try {
                // Sélectionner une catégorie (rotation)
                $category = $categories[$categoryIndex % $categoryCount];
                $categoryIndex++;

                // Reformuler l'article avec optimisation SEO
                $reformulated = $this->reformulateArticle($article, $category);

                // Récupérer une image illustrative
                $imageUrl = $this->getIllustrativeImage($reformulated['title'], $category);

                // Calculer les scores SEO et visibilité
                $seoScore = $this->calculateSeoScore($reformulated);
                $readabilityScore = $this->calculateReadabilityScore($reformulated['content']);

                // Créer l'article
                $jobArticle = JobArticle::create([
                    'category_id' => $category->id,
                    'title' => $reformulated['title'],
                    'slug' => Str::slug($reformulated['title']) . '-' . time() . '-' . rand(1000, 9999),
                    'excerpt' => $reformulated['excerpt'],
                    'content' => $reformulated['content'],
                    'cover_image' => $imageUrl,
                    'cover_type' => 'external',
                    'meta_title' => $reformulated['meta_title'] ?? $reformulated['title'],
                    'meta_description' => $reformulated['meta_description'] ?? $reformulated['excerpt'],
                    'meta_keywords' => $reformulated['keywords'] ?? [],
                    'seo_score' => $seoScore,
                    'readability_score' => $readabilityScore,
                    'status' => 'published',
                    'views' => 0,
                    'published_at' => Carbon::now()->subDays($days)->addMinutes(rand(0, 1440)),
                ]);

                $created[] = $jobArticle;
                
                // Petite pause pour éviter de surcharger les APIs
                usleep(500000); // 0.5 seconde
                
            } catch (\Exception $e) {
                Log::error('Erreur lors de la génération d\'article', [
                    'error' => $e->getMessage(),
                    'article_index' => $index
                ]);
                $errors[] = $e->getMessage();
            }
        }

        return [
            'created' => $created,
            'errors' => $errors,
            'count' => count($created)
        ];
    }

    /**
     * Recherche des articles récents sur les sites sénégalais spécifiques
     */
    private function searchRecentArticles(int $count = 10): array
    {
        $articles = [];

        // Sites sénégalais à scraper
        $sites = [
            [
                'url' => 'https://directiondesbourses.sn/',
                'name' => 'Direction des Bourses',
                'type' => 'bourses'
            ],
            [
                'url' => 'https://concoursn.com/',
                'name' => 'Concoursn',
                'type' => 'concours'
            ],
            [
                'url' => 'https://www.sgee-sn.org/',
                'name' => 'SGEE',
                'type' => 'bourses'
            ],
            [
                'url' => 'https://www.emploidakar.com/',
                'name' => 'Emploi Dakar',
                'type' => 'emploi'
            ],
            [
                'url' => 'https://www.emploisenegal.com/',
                'name' => 'Emplois Sénégal',
                'type' => 'emploi'
            ],
            [
                'url' => 'https://www.guichetjeunesse.sn/',
                'name' => 'Guichet Jeunesse',
                'type' => 'emploi'
            ],
            [
                'url' => 'https://senegalservices.sn/',
                'name' => 'Sénégal Services',
                'type' => 'services'
            ],
            [
                'url' => 'https://samabac.sn/',
                'name' => 'SAMABAC',
                'type' => 'concours'
            ],
            [
                'url' => 'https://guindima.sn/',
                'name' => 'Guindima',
                'type' => 'emploi'
            ]
        ];

        // Scraper chaque site avec pause entre les requêtes
        foreach ($sites as $index => $site) {
            if (count($articles) >= $count) {
                break;
            }

            // Pause entre les requêtes pour ne pas surcharger les serveurs
            if ($index > 0) {
                sleep(2); // 2 secondes entre chaque site
            }

            try {
                $siteArticles = $this->scrapeSite($site, $count - count($articles));
                $articles = array_merge($articles, $siteArticles);
            } catch (\Exception $e) {
                Log::warning("Erreur lors du scraping de {$site['name']}: " . $e->getMessage());
            }
        }

        // Si pas assez d'articles, essayer Google News RSS en dernier recours
        if (count($articles) < $count) {
            try {
                $rssArticles = $this->fetchGoogleNewsRSS($count - count($articles));
                $articles = array_merge($articles, $rssArticles);
            } catch (\Exception $e) {
                Log::warning('Erreur Google News RSS: ' . $e->getMessage());
            }
        }

        return array_slice($articles, 0, $count);
    }

    /**
     * Scrape un site spécifique pour extraire les articles
     */
    private function scrapeSite(array $site, int $maxArticles = 5): array
    {
        $articles = [];

        try {
            $response = Http::timeout(20)
                ->retry(2, 1000) // 2 tentatives avec 1 seconde de délai
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'fr-FR,fr;q=0.9,en;q=0.8',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'Connection' => 'keep-alive',
                    'Upgrade-Insecure-Requests' => '1',
                ])
                ->get($site['url']);

            if (!$response->successful()) {
                Log::warning("Échec de la requête HTTP pour {$site['name']}: Status " . $response->status());
                return $articles;
            }

            $html = $response->body();
            
            if (empty($html) || mb_strlen($html) < 500) {
                Log::warning("Contenu HTML trop court ou vide pour {$site['name']}");
                return $articles;
            }
            
            // Utiliser DOMDocument pour parser le HTML
            libxml_use_internal_errors(true);
            $dom = new \DOMDocument('1.0', 'UTF-8');
            
            // Convertir l'encodage si nécessaire
            if (!mb_check_encoding($html, 'UTF-8')) {
                $html = mb_convert_encoding($html, 'UTF-8', 'auto');
            }
            
            @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
            libxml_clear_errors();
            
            $xpath = new \DOMXPath($dom);

            // Stratégies de scraping selon le site
            switch ($site['name']) {
                case 'Direction des Bourses':
                    $articles = $this->scrapeDirectionBourses($xpath, $site, $maxArticles);
                    break;
                case 'Concoursn':
                    $articles = $this->scrapeConcoursn($xpath, $site, $maxArticles);
                    break;
                case 'SGEE':
                    $articles = $this->scrapeSGEE($xpath, $site, $maxArticles);
                    break;
                case 'Emploi Dakar':
                case 'Emplois Sénégal':
                case 'Guindima':
                    $articles = $this->scrapeEmploiSites($xpath, $site, $maxArticles);
                    break;
                case 'Guichet Jeunesse':
                    $articles = $this->scrapeGuichetJeunesse($xpath, $site, $maxArticles);
                    break;
                case 'Sénégal Services':
                    $articles = $this->scrapeSenegalServices($xpath, $site, $maxArticles);
                    break;
                case 'SAMABAC':
                    $articles = $this->scrapeSAMABAC($xpath, $site, $maxArticles);
                    break;
                default:
                    $articles = $this->scrapeGeneric($xpath, $site, $maxArticles);
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::warning("Erreur de connexion pour {$site['name']}: " . $e->getMessage());
        } catch (\Exception $e) {
            Log::warning("Erreur lors du scraping de {$site['name']}: " . $e->getMessage() . " | Trace: " . $e->getTraceAsString());
        }

        return $articles;
    }

    /**
     * Scrape Direction des Bourses
     */
    private function scrapeDirectionBourses(\DOMXPath $xpath, array $site, int $maxArticles): array
    {
        $articles = [];
        
        // Chercher les articles/communiqués - plusieurs stratégies
        $queries = [
            "//article",
            "//div[contains(@class, 'post')]",
            "//div[contains(@class, 'article')]",
            "//div[contains(@class, 'communique')]",
            "//h2[contains(text(), 'bourse') or contains(text(), 'communiqué')]",
            "//h3[contains(text(), 'bourse') or contains(text(), 'communiqué')]",
            "//div[contains(@class, 'content')]//h2",
            "//div[contains(@class, 'content')]//h3"
        ];
        
        $allNodes = [];
        foreach ($queries as $query) {
            $nodes = $xpath->query($query);
            foreach ($nodes as $node) {
                $allNodes[] = $node;
            }
        }
        
        // Dédupliquer et trier
        $uniqueNodes = [];
        $seenTitles = [];
        foreach ($allNodes as $node) {
            $title = trim($node->textContent);
            $titleKey = mb_strtolower($title);
            
            if (!isset($seenTitles[$titleKey]) && mb_strlen($title) >= 20 && mb_strlen($title) <= 200) {
                // Filtrer les éléments non pertinents
                if (stripos($title, 'menu') === false && 
                    stripos($title, 'navigation') === false &&
                    stripos($title, 'footer') === false &&
                    stripos($title, 'header') === false) {
                    $uniqueNodes[] = $node;
                    $seenTitles[$titleKey] = true;
                }
            }
        }
        
        foreach ($uniqueNodes as $index => $node) {
            if (count($articles) >= $maxArticles) break;
            
            $title = trim($node->textContent);
            
            // Chercher le contenu associé
            $content = $this->extractContentFromNode($xpath, $node);
            
            if (empty($content) || mb_strlen($content) < 100) {
                // Générer un contenu plus riche et varié basé sur le titre
                $content = $this->generateRichContentFromTitle($title, 'bourses');
            }
            
            $articles[] = [
                'title' => $title,
                'content' => mb_substr($content, 0, 1000),
                'source' => $site['name'],
                'url' => $site['url'],
                'publishedAt' => now()->subDays(rand(0, 7)),
                'image' => null
            ];
        }
        
        return $articles;
    }
    
    /**
     * Extrait le contenu d'un nœud et ses éléments enfants
     */
    private function extractContentFromNode(\DOMXPath $xpath, \DOMNode $node): string
    {
        $content = '';
        
        // Chercher dans le parent
        $parent = $node->parentNode;
        if ($parent) {
            $contentNodes = $xpath->query(".//p | .//div[contains(@class, 'content')] | .//div[contains(@class, 'description')] | .//div[contains(@class, 'text')]", $parent);
            foreach ($contentNodes as $contentNode) {
                $text = trim($contentNode->textContent);
                if (mb_strlen($text) > 50) {
                    $content .= $text . ' ';
                }
            }
        }
        
        // Si pas de contenu, chercher dans les éléments suivants
        if (empty($content)) {
            $nextSibling = $node->nextSibling;
            $maxDepth = 5;
            $depth = 0;
            
            while ($nextSibling && $depth < $maxDepth) {
                if ($nextSibling->nodeType === XML_ELEMENT_NODE) {
                    $text = trim($nextSibling->textContent);
                    if (mb_strlen($text) > 100) {
                        $content = $text;
                        break;
                    }
                }
                $nextSibling = $nextSibling->nextSibling;
                $depth++;
            }
        }
        
        return trim($content);
    }

    /**
     * Scrape Concoursn
     */
    private function scrapeConcoursn(\DOMXPath $xpath, array $site, int $maxArticles): array
    {
        $articles = [];
        
        // Chercher les offres de recrutement et concours - plusieurs stratégies
        $queries = [
            "//article",
            "//div[contains(@class, 'post')]",
            "//div[contains(@class, 'job')]",
            "//div[contains(@class, 'offre')]",
            "//h2[contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'recrute') or contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'concours')]",
            "//h3[contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), 'recrute')]",
            "//a[contains(@href, 'recrutement') or contains(@href, 'concours')]"
        ];
        
        $allNodes = [];
        foreach ($queries as $query) {
            try {
                $nodes = $xpath->query($query);
                foreach ($nodes as $node) {
                    $allNodes[] = $node;
                }
            } catch (\Exception $e) {
                // Ignorer les erreurs de requête XPath
            }
        }
        
        // Dédupliquer
        $uniqueNodes = [];
        $seenTitles = [];
        foreach ($allNodes as $node) {
            $title = trim($node->textContent);
            $titleKey = mb_strtolower($title);
            
            if (!isset($seenTitles[$titleKey]) && mb_strlen($title) >= 15 && mb_strlen($title) <= 200) {
                if (stripos($title, 'menu') === false && 
                    stripos($title, 'navigation') === false &&
                    stripos($title, 'accueil') === false) {
                    $uniqueNodes[] = $node;
                    $seenTitles[$titleKey] = true;
                }
            }
        }
        
        foreach ($uniqueNodes as $index => $node) {
            if (count($articles) >= $maxArticles) break;
            
            $title = trim($node->textContent);
            
            // Chercher le contenu
            $content = $this->extractContentFromNode($xpath, $node);
            
            if (empty($content) || mb_strlen($content) < 100) {
                // Générer un contenu plus riche et varié basé sur le titre
                $content = $this->generateRichContentFromTitle($title, 'concours');
            }
            
            $articles[] = [
                'title' => $title,
                'content' => mb_substr($content, 0, 1000),
                'source' => $site['name'],
                'url' => $site['url'],
                'publishedAt' => now()->subDays(rand(0, 14)),
                'image' => null
            ];
        }
        
        return $articles;
    }

    /**
     * Scrape SGEE
     */
    private function scrapeSGEE(\DOMXPath $xpath, array $site, int $maxArticles): array
    {
        $articles = [];
        
        // Chercher les actualités et communiqués
        $nodes = $xpath->query("//div[contains(@class, 'post')] | //article | //h2 | //h3 | //div[contains(@class, 'actualite')]");
        
        foreach ($nodes as $index => $node) {
            if (count($articles) >= $maxArticles) break;
            
            $title = trim($node->textContent);
            if (mb_strlen($title) < 20 || mb_strlen($title) > 200) continue;
            
            // Filtrer les titres non pertinents
            if (stripos($title, 'menu') !== false || 
                stripos($title, 'navigation') !== false ||
                stripos($title, 'footer') !== false) {
                continue;
            }
            
            // Générer un contenu plus riche
            $content = $this->generateRichContentFromTitle($title, 'bourses');
            if (empty($content)) {
                $content = "Actualité du Service de Gestion des Etudiants Sénégalais à l'Étranger. " . $title;
            }
            
            $articles[] = [
                'title' => $title,
                'content' => mb_substr($content, 0, 1000),
                'source' => $site['name'],
                'url' => $site['url'],
                'publishedAt' => now()->subDays(rand(0, 10)),
                'image' => null
            ];
        }
        
        return $articles;
    }

    /**
     * Scrape sites d'emploi génériques
     */
    private function scrapeEmploiSites(\DOMXPath $xpath, array $site, int $maxArticles): array
    {
        $articles = [];
        
        // Chercher les offres d'emploi
        $nodes = $xpath->query("//div[contains(@class, 'job')] | //div[contains(@class, 'offre')] | //article | //h2 | //h3 | //a[contains(@href, 'emploi') or contains(@href, 'recrutement')]");
        
        foreach ($nodes as $index => $node) {
            if (count($articles) >= $maxArticles) break;
            
            $title = trim($node->textContent);
            if (mb_strlen($title) < 15 || mb_strlen($title) > 200) continue;
            
            // Filtrer les liens de navigation
            if (stripos($title, 'accueil') !== false || 
                stripos($title, 'contact') !== false ||
                stripos($title, 'menu') !== false) {
                continue;
            }
            
            // Générer un contenu plus riche si pas assez de contenu extrait
            if (empty($content) || mb_strlen($content) < 100) {
                $content = $this->generateRichContentFromTitle($title, 'emploi');
            }
            if (empty($content)) {
                $content = "Offre d'emploi au Sénégal. " . $title;
            }
            
            // Chercher plus de détails
            $parent = $node->parentNode;
            if ($parent) {
                $details = $xpath->query(".//p | .//div[contains(@class, 'description')]", $parent);
                foreach ($details as $detail) {
                    $text = trim($detail->textContent);
                    if (mb_strlen($text) > 50) {
                        $content .= ' ' . $text;
                    }
                }
            }
            
            $articles[] = [
                'title' => $title,
                'content' => mb_substr($content, 0, 1000),
                'source' => $site['name'],
                'url' => $site['url'],
                'publishedAt' => now()->subDays(rand(0, 30)),
                'image' => null
            ];
        }
        
        return $articles;
    }

    /**
     * Scrape Guichet Jeunesse
     */
    private function scrapeGuichetJeunesse(\DOMXPath $xpath, array $site, int $maxArticles): array
    {
        return $this->scrapeEmploiSites($xpath, $site, $maxArticles);
    }

    /**
     * Scrape Sénégal Services
     */
    private function scrapeSenegalServices(\DOMXPath $xpath, array $site, int $maxArticles): array
    {
        return $this->scrapeEmploiSites($xpath, $site, $maxArticles);
    }

    /**
     * Scrape SAMABAC
     */
    private function scrapeSAMABAC(\DOMXPath $xpath, array $site, int $maxArticles): array
    {
        return $this->scrapeConcoursn($xpath, $site, $maxArticles);
    }

    /**
     * Scrape générique pour sites non spécifiques
     */
    private function scrapeGeneric(\DOMXPath $xpath, array $site, int $maxArticles): array
    {
        $articles = [];
        
        // Chercher les titres et articles
        $nodes = $xpath->query("//h1 | //h2 | //h3 | //article | //div[contains(@class, 'post')] | //div[contains(@class, 'article')]");
        
        foreach ($nodes as $index => $node) {
            if (count($articles) >= $maxArticles) break;
            
            $title = trim($node->textContent);
            if (mb_strlen($title) < 20 || mb_strlen($title) > 200) continue;
            
            // Filtrer les éléments de navigation
            $skipWords = ['menu', 'navigation', 'footer', 'header', 'accueil', 'contact', 'à propos'];
            $shouldSkip = false;
            foreach ($skipWords as $word) {
                if (stripos($title, $word) !== false) {
                    $shouldSkip = true;
                    break;
                }
            }
            if ($shouldSkip) continue;
            
            // Générer un contenu plus riche
            $content = $this->generateRichContentFromTitle($title, $site['type'] ?? 'general');
            if (empty($content)) {
                $content = "Information du site {$site['name']}. " . $title;
            }
            
            $articles[] = [
                'title' => $title,
                'content' => mb_substr($content, 0, 1000),
                'source' => $site['name'],
                'url' => $site['url'],
                'publishedAt' => now()->subDays(rand(0, 15)),
                'image' => null
            ];
        }
        
        return $articles;
    }

    /**
     * Récupère des articles depuis Google News RSS
     */
    private function fetchGoogleNewsRSS(int $count = 10): array
    {
        $articles = [];
        
        try {
            $url = 'https://news.google.com/rss/search?q=emploi+recrutement+Sénégal&hl=fr&gl=SN&ceid=SN:fr';
            $response = Http::timeout(10)->get($url);
            
            if ($response->successful()) {
                $xml = simplexml_load_string($response->body());
                
                if ($xml) {
                    $items = $xml->xpath('//item');
                    foreach (array_slice($items, 0, $count) as $item) {
                        $articles[] = [
                            'title' => (string) $item->title,
                            'content' => strip_tags((string) $item->description),
                            'source' => 'Google News',
                            'url' => (string) $item->link,
                            'publishedAt' => isset($item->pubDate) ? (string) $item->pubDate : now(),
                            'image' => null
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('Erreur lors de la récupération Google News RSS: ' . $e->getMessage());
        }

        return $articles;
    }

    /**
     * Reformule un article avec optimisation SEO
     */
    private function reformulateArticle(array $article, Category $category): array
    {
        $originalTitle = $article['title'];
        $originalContent = $article['content'];

        // Reformuler le titre (plus accrocheur et SEO-friendly)
        $title = $this->reformulateTitle($originalTitle, $category);

        // Reformuler le contenu
        $content = $this->reformulateContent($originalContent, $category);

        // Générer un extrait
        $excerpt = $this->generateExcerpt($content);

        // Générer les métadonnées SEO
        $metaTitle = $this->generateMetaTitle($title);
        $metaDescription = $this->generateMetaDescription($excerpt);
        $keywords = $this->extractKeywords($title, $content, $category);

        return [
            'title' => $title,
            'content' => $content,
            'excerpt' => $excerpt,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'keywords' => $keywords
        ];
    }

    /**
     * Reformule le titre pour le rendre plus accrocheur et SEO-friendly
     */
    private function reformulateTitle(string $title, Category $category): string
    {
        // Nettoyer le titre
        $title = trim($title);
        $title = preg_replace('/\s+/', ' ', $title);

        // Ajouter le contexte Sénégal si pas présent
        if (stripos($title, 'Sénégal') === false && stripos($title, 'Senegal') === false) {
            $title = $title . ' au Sénégal';
        }

        // Optimiser la longueur (50-60 caractères idéal pour SEO)
        if (mb_strlen($title) > 65) {
            $title = mb_substr($title, 0, 62) . '...';
        }

        // Capitaliser correctement
        $title = mb_convert_case($title, MB_CASE_TITLE, 'UTF-8');

        return $title;
    }

    /**
     * Reformule le contenu avec optimisation SEO
     */
    private function reformulateContent(string $content, Category $category): string
    {
        // Nettoyer le contenu
        $content = strip_tags($content);
        $content = preg_replace('/\s+/', ' ', $content);
        $content = trim($content);

        // Si le contenu est trop court ou trop générique, l'enrichir avec du contenu varié
        if (mb_strlen($content) < 500 || $this->isGenericContent($content)) {
            $content = $this->enrichContent($content, $category);
        }

        // Ajouter de la variété dans la reformulation
        $content = $this->addVarietyToContent($content, $category);

        // Structurer le contenu avec des titres
        $content = $this->structureContent($content);

        // Optimiser pour le SEO
        $content = $this->optimizeContentForSeo($content, $category);

        return $content;
    }

    /**
     * Vérifie si le contenu est trop générique
     */
    private function isGenericContent(string $content): bool
    {
        $genericPhrases = [
            'pour plus d\'informations',
            'consultez le site',
            'offre d\'emploi ou concours',
            'informations sur les',
        ];
        
        $contentLower = mb_strtolower($content);
        foreach ($genericPhrases as $phrase) {
            if (stripos($contentLower, $phrase) !== false && mb_strlen($content) < 300) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Ajoute de la variété au contenu
     */
    private function addVarietyToContent(string $content, Category $category): string
    {
        // Phrases d'introduction variées
        $introductions = [
            "Le Sénégal offre de nombreuses opportunités dans ce domaine.",
            "Cette opportunité s'inscrit dans le cadre du développement du secteur au Sénégal.",
            "Les candidats intéressés peuvent postuler pour cette offre.",
            "Cette annonce concerne les opportunités disponibles au Sénégal.",
            "Le marché de l'emploi sénégalais présente cette opportunité.",
            "Cette offre s'adresse aux candidats qualifiés au Sénégal.",
        ];
        
        // Phrases de conclusion variées
        $conclusions = [
            "Les candidats doivent être motivés et posséder les compétences requises.",
            "Cette opportunité représente une excellente chance de développement professionnel.",
            "Les conditions d'éligibilité sont détaillées dans l'annonce complète.",
            "Les postulants doivent respecter les critères établis.",
            "Cette offre est ouverte à tous les candidats éligibles.",
            "Les modalités de candidature sont précisées dans l'annonce.",
        ];
        
        // Ajouter une introduction variée si le contenu est court
        if (mb_strlen($content) < 400) {
            $intro = $introductions[array_rand($introductions)];
            $content = $intro . ' ' . $content;
        }
        
        // Ajouter une conclusion variée
        $conclusion = $conclusions[array_rand($conclusions)];
        $content .= ' ' . $conclusion;
        
        return $content;
    }

    /**
     * Structure le contenu avec des titres H2 et H3
     */
    private function structureContent(string $content): string
    {
        $sentences = preg_split('/(?<=[.!?])\s+/', $content);
        $paragraphs = [];
        $currentParagraph = [];

        foreach ($sentences as $sentence) {
            $currentParagraph[] = $sentence;
            
            // Créer un paragraphe tous les 3-4 phrases
            if (count($currentParagraph) >= 3) {
                $paragraphs[] = '<p>' . implode(' ', $currentParagraph) . '</p>';
                $currentParagraph = [];
            }
        }

        // Ajouter le dernier paragraphe
        if (!empty($currentParagraph)) {
            $paragraphs[] = '<p>' . implode(' ', $currentParagraph) . '</p>';
        }

        // Ajouter des titres H2 tous les 2-3 paragraphes
        $structured = '';
        $paragraphCount = 0;
        
        foreach ($paragraphs as $index => $paragraph) {
            if ($paragraphCount === 0 && $index > 0) {
                $structured .= '<h2>Informations Importantes</h2>';
            }
            $structured .= $paragraph;
            $paragraphCount++;
            
            if ($paragraphCount >= 3) {
                $paragraphCount = 0;
            }
        }

        return $structured ?: '<p>' . $content . '</p>';
    }

    /**
     * Enrichit le contenu s'il est trop court avec du contenu varié
     */
    private function enrichContent(string $content, Category $category): string
    {
        // Sections variées selon le type de contenu
        $sections = [];
        
        // Détecter le type de contenu
        $contentLower = mb_strtolower($content);
        
        if (stripos($contentLower, 'bourse') !== false) {
            $sections[] = [
                'title' => '## Critères d\'Éligibilité',
                'content' => 'Les bourses sont attribuées selon des critères spécifiques incluant les performances académiques, la situation sociale et les besoins du secteur. Les candidats doivent remplir les conditions requises pour être éligibles.'
            ];
            $sections[] = [
                'title' => '## Processus de Candidature',
                'content' => 'Le processus de candidature nécessite la préparation de documents administratifs complets. Les dossiers doivent être déposés dans les délais impartis avec toutes les pièces justificatives requises.'
            ];
        } elseif (stripos($contentLower, 'emploi') !== false || stripos($contentLower, 'recrutement') !== false) {
            $sections[] = [
                'title' => '## Profil Recherché',
                'content' => 'Le profil recherché correspond à un candidat possédant les compétences techniques et les qualités personnelles nécessaires pour le poste. Une expérience dans le domaine est généralement appréciée.'
            ];
            $sections[] = [
                'title' => '## Modalités de Candidature',
                'content' => 'Les candidats intéressés doivent soumettre leur dossier de candidature comprenant un CV détaillé, une lettre de motivation et les documents justificatifs requis. Les modalités précises sont indiquées dans l\'annonce complète.'
            ];
        } elseif (stripos($contentLower, 'concours') !== false) {
            $sections[] = [
                'title' => '## Conditions de Participation',
                'content' => 'Le concours est ouvert aux candidats remplissant les conditions d\'âge, de diplôme et d\'expérience requises. Les modalités d\'inscription et les épreuves sont détaillées dans le règlement du concours.'
            ];
            $sections[] = [
                'title' => '## Calendrier du Concours',
                'content' => 'Les dates importantes du concours incluent la période d\'inscription, les dates des épreuves écrites et orales, ainsi que la publication des résultats. Les candidats doivent respecter ces échéances.'
            ];
        } else {
            // Sections génériques
            $sections[] = [
                'title' => '## Informations Importantes',
                'content' => 'Cette opportunité s\'adresse aux candidats qualifiés et motivés. Les détails complets sont disponibles dans l\'annonce officielle.'
            ];
            $sections[] = [
                'title' => '## Comment Postuler',
                'content' => 'Pour postuler, les candidats doivent suivre les instructions fournies dans l\'annonce. Il est important de respecter les délais et de fournir tous les documents requis.'
            ];
        }
        
        // Ajouter 1-2 sections aléatoirement pour varier
        $selectedSections = array_rand($sections, min(2, count($sections)));
        if (!is_array($selectedSections)) {
            $selectedSections = [$selectedSections];
        }
        
        $enriched = $content;
        foreach ($selectedSections as $index) {
            $section = $sections[$index];
            $enriched .= "\n\n" . $section['title'] . "\n\n" . $section['content'];
        }

        return $enriched;
    }

    /**
     * Optimise le contenu pour le SEO
     */
    private function optimizeContentForSeo(string $content, Category $category): string
    {
        // S'assurer que les mots-clés importants sont présents
        $keywords = ['Sénégal', 'emploi', 'recrutement', 'travail', 'carrière'];
        
        foreach ($keywords as $keyword) {
            if (stripos($content, $keyword) === false) {
                $content = '<p>Découvrez les meilleures opportunités d\'emploi au Sénégal.</p>' . $content;
                break;
            }
        }

        return $content;
    }

    /**
     * Génère un extrait à partir du contenu
     */
    private function generateExcerpt(string $content): string
    {
        $excerpt = strip_tags($content);
        $excerpt = preg_replace('/\s+/', ' ', $excerpt);
        $excerpt = trim($excerpt);

        // Limiter à 160 caractères (optimal pour SEO)
        if (mb_strlen($excerpt) > 160) {
            $excerpt = mb_substr($excerpt, 0, 157) . '...';
        }

        return $excerpt;
    }

    /**
     * Génère un meta title optimisé
     */
    private function generateMetaTitle(string $title): string
    {
        // Limiter à 60 caractères
        if (mb_strlen($title) > 60) {
            return mb_substr($title, 0, 57) . '...';
        }
        return $title;
    }

    /**
     * Génère une meta description optimisée
     */
    private function generateMetaDescription(string $excerpt): string
    {
        // Limiter à 160 caractères
        if (mb_strlen($excerpt) > 160) {
            return mb_substr($excerpt, 0, 157) . '...';
        }
        return $excerpt;
    }

    /**
     * Extrait les mots-clés du contenu
     */
    private function extractKeywords(string $title, string $content, Category $category): array
    {
        $keywords = ['emploi', 'recrutement', 'Sénégal', 'travail', 'carrière'];
        
        // Ajouter le nom de la catégorie
        if ($category) {
            $keywords[] = strtolower($category->name);
        }

        // Extraire des mots importants du titre et du contenu
        $text = $title . ' ' . strip_tags($content);
        $words = str_word_count($text, 1, 'àáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ');
        
        // Filtrer les mots communs
        $stopWords = ['le', 'la', 'les', 'un', 'une', 'des', 'de', 'du', 'et', 'ou', 'à', 'pour', 'avec', 'sans', 'sur', 'dans'];
        $importantWords = array_filter($words, function($word) use ($stopWords) {
            return mb_strlen($word) > 4 && !in_array(strtolower($word), $stopWords);
        });

        $keywords = array_merge($keywords, array_slice(array_unique($importantWords), 0, 5));

        return array_slice(array_unique($keywords), 0, 10);
    }

    /**
     * Récupère une image illustrative depuis Unsplash
     */
    private function getIllustrativeImage(string $title, Category $category): string
    {
        // Essayer d'abord Unsplash
        if ($this->unsplashApiKey) {
            try {
                // Générer des mots-clés variés basés sur le titre et la catégorie
                $keywords = $this->extractImageKeywords($title, $category);
                $query = urlencode($keywords);
                
                $response = Http::timeout(10)->get('https://api.unsplash.com/photos/random', [
                    'query' => $query,
                    'orientation' => 'landscape',
                    'client_id' => $this->unsplashApiKey
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['urls']['regular'])) {
                        return $data['urls']['regular'];
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Erreur Unsplash API: ' . $e->getMessage());
            }
        }

        // Fallback: utiliser des images placeholder variées basées sur le titre
        return $this->getVariedPlaceholderImage($title, $category);
    }

    /**
     * Extrait des mots-clés pour la recherche d'image
     */
    private function extractImageKeywords(string $title, Category $category): string
    {
        $keywords = [];
        
        // Mots-clés basés sur le titre
        $titleLower = mb_strtolower($title);
        
        if (stripos($titleLower, 'bourse') !== false || stripos($titleLower, 'boursier') !== false) {
            $keywords[] = 'education student scholarship';
        }
        if (stripos($titleLower, 'emploi') !== false || stripos($titleLower, 'recrutement') !== false || stripos($titleLower, 'travail') !== false) {
            $keywords[] = 'business office work professional';
        }
        if (stripos($titleLower, 'concours') !== false) {
            $keywords[] = 'competition exam test';
        }
        if (stripos($titleLower, 'formation') !== false || stripos($titleLower, 'formation') !== false) {
            $keywords[] = 'training learning education';
        }
        if (stripos($titleLower, 'santé') !== false || stripos($titleLower, 'medecin') !== false) {
            $keywords[] = 'healthcare medical hospital';
        }
        if (stripos($titleLower, 'ingénieur') !== false || stripos($titleLower, 'technique') !== false) {
            $keywords[] = 'engineering technology';
        }
        if (stripos($titleLower, 'enseignant') !== false || stripos($titleLower, 'professeur') !== false) {
            $keywords[] = 'teacher education classroom';
        }
        
        // Ajouter le nom de la catégorie
        if ($category) {
            $categoryName = mb_strtolower($category->name);
            $keywords[] = $categoryName;
        }
        
        // Mots-clés par défaut
        if (empty($keywords)) {
            $keywords[] = 'business professional Senegal';
        }
        
        return implode(' ', array_unique($keywords));
    }

    /**
     * Génère une image placeholder variée basée sur le titre
     */
    private function getVariedPlaceholderImage(string $title, Category $category): string
    {
        // Liste d'images Unsplash variées (gratuites, pas besoin d'API)
        $images = [
            // Business/Emploi
            'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=800&h=600&fit=crop',
            
            // Éducation/Bourses
            'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=800&h=600&fit=crop',
            
            // Concours/Examens
            'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=600&fit=crop',
            
            // Général
            'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1551434678-e076c223a692?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1556761175-4b46a572b786?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&h=600&fit=crop',
        ];
        
        // Utiliser un hash du titre pour sélectionner une image de manière déterministe mais variée
        $hash = crc32($title . ($category ? $category->name : ''));
        $index = abs($hash) % count($images);
        
        return $images[$index];
    }

    /**
     * Génère un contenu riche et varié à partir d'un titre
     */
    private function generateRichContentFromTitle(string $title, string $type = 'general'): string
    {
        $titleLower = mb_strtolower($title);
        $content = '';
        
        // Contenu varié selon le type
        if ($type === 'bourses' || stripos($titleLower, 'bourse') !== false) {
            $variations = [
                "Les bourses d'études au Sénégal représentent une opportunité importante pour les étudiants méritants. ",
                "Le système de bourses sénégalais permet à de nombreux étudiants de poursuivre leurs études dans de bonnes conditions. ",
                "Les allocations d'études sont attribuées selon des critères précis incluant les performances académiques et la situation sociale. ",
            ];
            $content = $variations[array_rand($variations)];
            $content .= "Cette annonce concerne " . $title . ". Les candidats éligibles peuvent bénéficier de cette opportunité pour leur parcours académique. ";
            $content .= "Les modalités d'attribution et les conditions d'éligibilité sont détaillées dans l'annonce officielle.";
            
        } elseif ($type === 'concours' || stripos($titleLower, 'concours') !== false) {
            $variations = [
                "Les concours publics au Sénégal offrent des opportunités de carrière dans la fonction publique. ",
                "Ce concours permet aux candidats qualifiés d'accéder à des postes dans l'administration sénégalaise. ",
                "Les épreuves de sélection sont organisées pour recruter les meilleurs profils selon les besoins du service public. ",
            ];
            $content = $variations[array_rand($variations)];
            $content .= "L'annonce " . $title . " est ouverte aux candidats remplissant les conditions requises. ";
            $content .= "Les inscriptions se font selon un calendrier précis et les épreuves sont organisées dans différents centres au Sénégal.";
            
        } elseif (stripos($titleLower, 'emploi') !== false || stripos($titleLower, 'recrutement') !== false) {
            $variations = [
                "Le marché de l'emploi au Sénégal présente de nombreuses opportunités pour les candidats qualifiés. ",
                "Cette offre d'emploi s'adresse aux professionnels possédant les compétences recherchées. ",
                "Les entreprises sénégalaises recrutent activement pour renforcer leurs équipes et développer leurs activités. ",
            ];
            $content = $variations[array_rand($variations)];
            $content .= "L'offre " . $title . " concerne un poste nécessitant des qualifications spécifiques. ";
            $content .= "Les candidats intéressés doivent soumettre leur dossier de candidature avec un CV détaillé et une lettre de motivation.";
            
        } else {
            $variations = [
                "Cette opportunité au Sénégal s'adresse aux candidats qualifiés et motivés. ",
                "Le Sénégal offre de nombreuses possibilités de développement professionnel et académique. ",
                "Cette annonce concerne une opportunité importante pour les candidats éligibles au Sénégal. ",
            ];
            $content = $variations[array_rand($variations)];
            $content .= $title . " représente une chance pour les candidats de progresser dans leur parcours. ";
            $content .= "Les détails complets et les modalités de candidature sont disponibles dans l'annonce officielle.";
        }
        
        return $content;
    }

    /**
     * Calcule le score SEO
     */
    private function calculateSeoScore(array $article): int
    {
        $score = 0;

        // Titre optimisé (10 points)
        $titleLength = mb_strlen($article['title']);
        if ($titleLength >= 30 && $titleLength <= 60) {
            $score += 10;
        } elseif ($titleLength >= 20 && $titleLength <= 70) {
            $score += 7;
        } else {
            $score += 5;
        }

        // Meta description (10 points)
        if (!empty($article['meta_description'])) {
            $metaLength = mb_strlen($article['meta_description']);
            if ($metaLength >= 120 && $metaLength <= 160) {
                $score += 10;
            } elseif ($metaLength >= 100 && $metaLength <= 180) {
                $score += 7;
            } else {
                $score += 5;
            }
        }

        // Mots-clés (10 points)
        if (!empty($article['keywords']) && count($article['keywords']) >= 5) {
            $score += 10;
        } elseif (!empty($article['keywords'])) {
            $score += 7;
        }

        // Longueur du contenu (20 points)
        $contentLength = mb_strlen(strip_tags($article['content']));
        if ($contentLength >= 1000) {
            $score += 20;
        } elseif ($contentLength >= 500) {
            $score += 15;
        } elseif ($contentLength >= 300) {
            $score += 10;
        } else {
            $score += 5;
        }

        // Structure avec titres (20 points)
        if (strpos($article['content'], '<h2>') !== false) {
            $score += 20;
        } elseif (strpos($article['content'], '<h3>') !== false) {
            $score += 15;
        } else {
            $score += 10;
        }

        // Présence de mots-clés importants (20 points)
        $importantKeywords = ['Sénégal', 'emploi', 'recrutement', 'travail'];
        $content = strtolower($article['content']);
        $foundKeywords = 0;
        foreach ($importantKeywords as $keyword) {
            if (stripos($content, strtolower($keyword)) !== false) {
                $foundKeywords++;
            }
        }
        $score += ($foundKeywords / count($importantKeywords)) * 20;

        // Présence d'image (10 points)
        // (sera vérifié lors de la création de l'article)

        return min(100, $score);
    }

    /**
     * Calcule le score de lisibilité
     */
    private function calculateReadabilityScore(string $content): int
    {
        $text = strip_tags($content);
        $sentences = preg_split('/[.!?]+/', $text);
        $words = str_word_count($text);
        $syllables = $this->countSyllables($text);

        if (count($sentences) == 0 || $words == 0) {
            return 50;
        }

        // Indice de Flesch adapté pour le français
        $avgSentenceLength = $words / count($sentences);
        $avgSyllablesPerWord = $syllables / $words;

        // Score simplifié (0-100)
        $score = 100;
        
        // Pénaliser les phrases trop longues
        if ($avgSentenceLength > 25) {
            $score -= 20;
        } elseif ($avgSentenceLength > 20) {
            $score -= 10;
        }

        // Pénaliser les mots trop complexes
        if ($avgSyllablesPerWord > 2.5) {
            $score -= 20;
        } elseif ($avgSyllablesPerWord > 2.0) {
            $score -= 10;
        }

        // Bonus pour la structure
        if (strpos($content, '<h2>') !== false || strpos($content, '<h3>') !== false) {
            $score += 10;
        }

        return max(0, min(100, $score));
    }

    /**
     * Compte les syllabes approximativement
     */
    private function countSyllables(string $text): int
    {
        $text = strtolower($text);
        $vowels = 'aeiouyàáâãäåæèéêëìíîïòóôõöùúûüý';
        $syllables = 0;
        
        for ($i = 0; $i < mb_strlen($text); $i++) {
            $char = mb_substr($text, $i, 1);
            if (mb_strpos($vowels, $char) !== false) {
                $syllables++;
            }
        }

        return max(1, $syllables);
    }
}

