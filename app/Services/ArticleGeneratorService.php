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
     * Recherche des articles récents sur le web
     */
    private function searchRecentArticles(int $count = 10): array
    {
        $articles = [];

        // Essayer d'abord avec NewsAPI
        if ($this->newsApiKey) {
            try {
                $response = Http::timeout(10)->get('https://newsapi.org/v2/everything', [
                    'q' => 'emploi recrutement travail Sénégal',
                    'language' => 'fr',
                    'sortBy' => 'publishedAt',
                    'pageSize' => min($count, 100),
                    'apiKey' => $this->newsApiKey
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['articles'])) {
                        foreach ($data['articles'] as $article) {
                            if (!empty($article['title']) && !empty($article['content'])) {
                                $articles[] = [
                                    'title' => $article['title'],
                                    'content' => $article['content'] ?? $article['description'] ?? '',
                                    'source' => $article['source']['name'] ?? 'Source inconnue',
                                    'url' => $article['url'] ?? '',
                                    'publishedAt' => $article['publishedAt'] ?? now(),
                                    'image' => $article['urlToImage'] ?? null
                                ];
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Erreur NewsAPI: ' . $e->getMessage());
            }
        }

        // Si pas assez d'articles, utiliser Google News RSS
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

        // Si le contenu est trop court, l'enrichir
        if (mb_strlen($content) < 500) {
            $content = $this->enrichContent($content, $category);
        }

        // Structurer le contenu avec des titres
        $content = $this->structureContent($content);

        // Optimiser pour le SEO
        $content = $this->optimizeContentForSeo($content, $category);

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
     * Enrichit le contenu s'il est trop court
     */
    private function enrichContent(string $content, Category $category): string
    {
        $enrichments = [
            "\n\n## Opportunités de Carrière\n\n",
            "Le secteur de l'emploi au Sénégal offre de nombreuses opportunités pour les candidats qualifiés. ",
            "\n\n## Comment Postuler\n\n",
            "Pour postuler à ces offres, il est recommandé de préparer un CV détaillé et une lettre de motivation adaptée. ",
            "\n\n## Compétences Requises\n\n",
            "Les employeurs recherchent généralement des candidats avec des compétences spécifiques et une expérience pertinente. ",
        ];

        $enriched = $content;
        foreach ($enrichments as $enrichment) {
            $enriched .= $enrichment;
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
                $query = urlencode('business office work ' . $category->name);
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

        // Fallback: utiliser une image placeholder
        return 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=800&h=600&fit=crop';
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

