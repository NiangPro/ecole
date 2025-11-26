<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SiteSetting;

class BingUrlSubmissionService
{
    private $apiKey;
    private $siteUrl;
    private $apiEndpoint = 'https://ssl.bing.com/webmaster/api.svc/json/SubmitUrlbatch';

    public function __construct()
    {
        $this->apiKey = SiteSetting::get('bing_api_key');
        // Utiliser www.niangprogrammeur.com en production
        $appUrl = config('app.url');
        if (str_contains($appUrl, 'niangprogrammeur.com')) {
            $this->siteUrl = 'https://www.niangprogrammeur.com';
        } else {
            $this->siteUrl = $appUrl;
        }
    }

    /**
     * Vérifie si la clé API est configurée
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Soumet une liste d'URLs à Bing
     * 
     * @param array $urls Liste des URLs à soumettre
     * @return array Résultat de la soumission
     */
    public function submitUrls(array $urls): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Clé API Bing non configurée. Veuillez la configurer dans les paramètres admin.',
            ];
        }

        if (empty($urls)) {
            return [
                'success' => false,
                'message' => 'Aucune URL à soumettre.',
            ];
        }

        // Bing accepte jusqu'à 10 URLs par batch
        $batches = array_chunk($urls, 10);
        $results = [
            'success' => true,
            'total' => count($urls),
            'submitted' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($batches as $batch) {
            try {
                $response = Http::timeout(30)
                    ->retry(2, 1000)
                    ->post($this->apiEndpoint . '?apikey=' . $this->apiKey, [
                        'siteUrl' => $this->siteUrl,
                        'urlList' => $batch,
                    ]);

                if ($response->successful()) {
                    $results['submitted'] += count($batch);
                    Log::info('URLs soumises à Bing avec succès', [
                        'count' => count($batch),
                        'urls' => $batch,
                    ]);
                } else {
                    $results['failed'] += count($batch);
                    $errorMessage = 'Erreur HTTP ' . $response->status() . ': ' . $response->body();
                    $results['errors'][] = $errorMessage;
                    Log::error('Erreur lors de la soumission à Bing', [
                        'status' => $response->status(),
                        'response' => $response->body(),
                        'urls' => $batch,
                    ]);
                }

                // Attendre 1 seconde entre chaque batch pour éviter le rate limiting
                if (count($batches) > 1) {
                    sleep(1);
                }
            } catch (\Exception $e) {
                $results['failed'] += count($batch);
                $results['errors'][] = $e->getMessage();
                Log::error('Exception lors de la soumission à Bing', [
                    'message' => $e->getMessage(),
                    'urls' => $batch,
                ]);
            }
        }

        if ($results['failed'] > 0) {
            $results['success'] = false;
            $results['message'] = "{$results['submitted']} URLs soumises avec succès, {$results['failed']} échecs.";
        } else {
            $results['message'] = "Toutes les {$results['submitted']} URLs ont été soumises avec succès à Bing.";
        }

        return $results;
    }

    /**
     * Récupère toutes les URLs à soumettre (pages statiques + formations/exercices/quiz + articles dynamiques)
     */
    public function getAllUrlsToSubmit(): array
    {
        $urls = [];

        // Utiliser www.niangprogrammeur.com en production
        $appUrl = config('app.url');
        if (str_contains($appUrl, 'niangprogrammeur.com')) {
            $baseUrl = 'https://www.niangprogrammeur.com';
        } else {
            $baseUrl = $appUrl;
        }
        
        // Ajouter l'URL de base
        $urls[] = $baseUrl . '/';
        
        // Pages statiques importantes (sans faq, legal, privacy-policy, terms, all-links)
        $urls[] = $baseUrl . '/about';
        $urls[] = $baseUrl . '/contact';
        
        // Pages Emplois
        $urls[] = $baseUrl . '/emplois';
        $urls[] = $baseUrl . '/emplois/offres';
        $urls[] = $baseUrl . '/emplois/bourses';
        $urls[] = $baseUrl . '/emplois/candidature-spontanee';
        $urls[] = $baseUrl . '/emplois/opportunites';
        $urls[] = $baseUrl . '/emplois/concours';
        $urls[] = $baseUrl . '/emplois/articles-recents';
        
        $languages = [
            'html5', 'css3', 'javascript', 'php', 'bootstrap', 
            'git', 'wordpress', 'ia', 'python', 'java', 'sql', 'c'
        ];

        // Page principale formations
        $urls[] = $baseUrl . '/formations';
        
        // Formations par langage
        foreach ($languages as $lang) {
            $urls[] = $baseUrl . '/formations/' . $lang;
        }

        // Page principale exercices
        $urls[] = $baseUrl . '/exercices';
        
        // Exercices par langage
        foreach ($languages as $lang) {
            $urls[] = $baseUrl . '/exercices/' . $lang;
        }

        // Page principale quiz
        $urls[] = $baseUrl . '/quiz';
        
        // Quiz par langage
        foreach ($languages as $lang) {
            $urls[] = $baseUrl . '/quiz/' . $lang;
        }

        // Calculer le nombre d'URLs fixes déjà ajoutées
        $fixedUrlsCount = count($urls);
        
        // Objectif : 100 URLs au total
        $targetTotal = 100;
        $articlesNeeded = max(0, $targetTotal - $fixedUrlsCount);
        
        // Récupérer tous les articles publiés disponibles
        $totalPublishedArticles = \App\Models\JobArticle::where('status', 'published')->count();
        
        // Prendre le minimum entre les articles nécessaires et les articles disponibles
        $articlesToTake = min($articlesNeeded, $totalPublishedArticles);
        
        // Si on a besoin de plus d'articles que disponibles, prendre tous les articles disponibles
        if ($articlesToTake > 0) {
            $recentArticles = \App\Models\JobArticle::where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->take($articlesToTake)
                ->get(['slug']);

            foreach ($recentArticles as $article) {
                $urls[] = $baseUrl . '/emplois/article/' . $article->slug;
            }
        }

        return array_unique($urls);
    }
}
