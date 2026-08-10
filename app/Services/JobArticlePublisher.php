<?php

namespace App\Services;

use App\Exceptions\DuplicateArticleException;
use App\Models\JobArticle;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Str;

/**
 * Logique de création d'un JobArticle partagée entre les points d'entrée
 * externes (API Sanctum /api/articles, endpoint MCP /api/mcp). N'est PAS
 * utilisée par Admin\JobArticleController, qui reste indépendant.
 */
class JobArticlePublisher
{
    /**
     * Mots trop génériques au secteur "emploi au Sénégal" pour servir de signal de
     * similarité (sinon deux articles sur des entreprises différentes se
     * ressembleraient déjà à cause du vocabulaire commun du domaine).
     */
    private const GENERIC_WORDS = [
        'comment', 'postuler', 'candidature', 'candidater', 'spontanee', 'spontanément',
        'recrute', 'recrutement', 'recrutent', 'emploi', 'emplois', 'offre', 'offres',
        'opportunite', 'opportunites', 'carriere', 'carrieres', 'poste', 'postes', 'chez',
        'deposer', 'denicher', 'decrocher', 'obtenir', 'senegal', 'dakar', 'guide',
        'conseils', 'astuces', 'etapes', 'travail', 'travailler', 'entreprise',
        '2024', '2025', '2026', '2027', '2028',
    ];

    /**
     * Seuil (indice de Jaccard sur les mots significatifs du titre) au-delà duquel
     * deux titres sont considérés comme le même sujet.
     */
    private const SIMILARITY_THRESHOLD = 0.5;

    /**
     * @param array{
     *   title: string, content: string, category_id: int,
     *   slug?: ?string, excerpt?: ?string, cover_image_url?: ?string,
     *   meta_title?: ?string, meta_description?: ?string, meta_keywords?: string|array|null,
     *   status?: ?string, is_sponsored?: ?bool, is_featured?: ?bool, published_at?: ?string,
     *   allow_duplicate?: ?bool
     * } $data
     *
     * @throws UniqueConstraintViolationException
     * @throws DuplicateArticleException si un article très proche existe déjà
     *         dans la même catégorie et que allow_duplicate n'est pas passé à true.
     */
    public function publish(array $data): JobArticle
    {
        if (empty($data['allow_duplicate']) && !empty($data['category_id'])) {
            $similar = $this->findSimilarArticle($data['title'], (int) $data['category_id']);
            if ($similar !== null) {
                throw new DuplicateArticleException($similar['article'], $similar['similarity']);
            }
        }

        $slug = !empty($data['slug']) ? $data['slug'] : $this->uniqueSlug($data['title']);

        $coverImage = $data['cover_image_url'] ?? null;
        $coverType  = $coverImage ? 'external' : ($data['cover_type'] ?? 'external');

        $metaKeywords = null;
        if (!empty($data['meta_keywords'])) {
            $metaKeywords = is_array($data['meta_keywords'])
                ? $data['meta_keywords']
                : array_map('trim', explode(',', $data['meta_keywords']));
        }

        $status = $data['status'] ?? 'draft';

        $publishedAt = null;
        if ($status === 'published') {
            $publishedAt = isset($data['published_at'])
                ? \Carbon\Carbon::parse($data['published_at'])
                : now();
        }

        $attributes = [
            'title'            => $data['title'],
            'slug'             => $slug,
            'content'          => $data['content'],
            'excerpt'          => $data['excerpt'] ?? null,
            'category_id'      => $data['category_id'],
            'status'           => $status,
            'cover_image'      => $coverImage,
            'cover_type'       => $coverType,
            'meta_title'       => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords'    => $metaKeywords,
            'is_sponsored'     => $data['is_sponsored'] ?? false,
            'is_featured'      => $data['is_featured'] ?? false,
            'published_at'     => $publishedAt,
        ];

        $attributes['seo_score']         = $this->calculateSeoScore($attributes);
        $attributes['readability_score'] = $this->calculateReadabilityScore($attributes['content']);

        return JobArticle::create($attributes);
    }

    /**
     * Cherche, dans la même catégorie, un article existant (hors archivés) dont le
     * titre partage trop de mots significatifs avec le nouveau titre. Heuristique
     * volontairement simple (comparaison de titres, pas de contenu) pour rester
     * rapide sur un appel API synchrone.
     *
     * @return array{article: JobArticle, similarity: float}|null
     */
    private function findSimilarArticle(string $title, int $categoryId): ?array
    {
        $newTokens = $this->significantTokens($title);
        if (empty($newTokens)) {
            return null;
        }

        $best = null;

        JobArticle::where('category_id', $categoryId)
            ->where('status', '!=', 'archived')
            ->select('id', 'title')
            ->chunk(200, function ($articles) use ($newTokens, &$best) {
                foreach ($articles as $article) {
                    $tokens = $this->significantTokens($article->title);
                    if (empty($tokens)) {
                        continue;
                    }

                    $intersection = count(array_intersect($newTokens, $tokens));
                    $union = count(array_unique(array_merge($newTokens, $tokens)));
                    $similarity = $union > 0 ? $intersection / $union : 0;

                    if ($similarity >= self::SIMILARITY_THRESHOLD && ($best === null || $similarity > $best['similarity'])) {
                        $best = ['article' => $article, 'similarity' => $similarity];
                    }
                }
            });

        return $best;
    }

    /**
     * Extrait les mots significatifs d'un titre : minuscules, accents retirés,
     * ponctuation retirée, mots de moins de 3 lettres et vocabulaire générique du
     * secteur (voir GENERIC_WORDS) exclus. Ce qui reste est essentiellement le nom
     * de l'entreprise/l'entité et les mots distinctifs du sujet.
     *
     * @return string[]
     */
    private function significantTokens(string $title): array
    {
        $normalized = strtolower($title);
        $normalized = strtr($normalized, [
            'à' => 'a', 'â' => 'a', 'ä' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'î' => 'i', 'ï' => 'i',
            'ô' => 'o', 'ö' => 'o',
            'ù' => 'u', 'û' => 'u', 'ü' => 'u',
            'ç' => 'c',
        ]);
        $normalized = preg_replace('/[^a-z0-9\s]/', ' ', $normalized) ?? '';
        $words = array_filter(preg_split('/\s+/', $normalized) ?: []);

        $tokens = array_filter($words, fn ($word) => strlen($word) >= 3 && !in_array($word, self::GENERIC_WORDS, true));

        return array_values(array_unique($tokens));
    }

    public function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 1;

        while (JobArticle::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    private function calculateSeoScore(array $data): int
    {
        $score    = 0;
        $maxScore = 100;

        if (!empty($data['title']) && strlen($data['title']) >= 30 && strlen($data['title']) <= 60) {
            $score += 20;
        } elseif (!empty($data['title'])) {
            $score += 10;
        }

        if (!empty($data['meta_title']) && strlen($data['meta_title']) >= 30 && strlen($data['meta_title']) <= 60) {
            $score += 15;
        } elseif (!empty($data['meta_title'])) {
            $score += 7;
        }

        if (!empty($data['meta_description']) && strlen($data['meta_description']) >= 120 && strlen($data['meta_description']) <= 160) {
            $score += 15;
        } elseif (!empty($data['meta_description'])) {
            $score += 7;
        }

        if (!empty($data['meta_keywords'])) {
            $keywords = is_array($data['meta_keywords']) ? $data['meta_keywords'] : explode(',', $data['meta_keywords']);
            if (count($keywords) >= 3 && count($keywords) <= 10) {
                $score += 10;
            } else {
                $score += 5;
            }
        }

        if (!empty($data['content'])) {
            $wordCount = str_word_count(strip_tags($data['content']));
            if ($wordCount >= 300) {
                $score += 20;
            } elseif ($wordCount >= 150) {
                $score += 10;
            } else {
                $score += 5;
            }
        }

        if (!empty($data['excerpt']) && strlen($data['excerpt']) >= 100) {
            $score += 10;
        } elseif (!empty($data['excerpt'])) {
            $score += 5;
        }

        if (!empty($data['cover_image'])) {
            $score += 10;
        }

        return min($score, $maxScore);
    }

    private function calculateReadabilityScore(?string $content): int
    {
        if (empty($content)) {
            return 0;
        }

        $text           = strip_tags($content);
        $words          = str_word_count($text);
        $sentences      = preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $sentenceCount  = count($sentences);
        $paragraphs     = preg_split('/\n\s*\n/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $paragraphCount = count($paragraphs);

        if ($sentenceCount === 0 || $words === 0) {
            return 0;
        }

        $avgWordsPerSentence      = $words / $sentenceCount;
        $avgSentencesPerParagraph = $paragraphCount > 0 ? $sentenceCount / $paragraphCount : 0;

        $score = 100;

        if ($avgWordsPerSentence > 20) {
            $score -= 20;
        } elseif ($avgWordsPerSentence > 15) {
            $score -= 10;
        }

        if ($avgWordsPerSentence >= 10 && $avgWordsPerSentence <= 15) {
            $score += 10;
        }

        if ($avgSentencesPerParagraph > 5) {
            $score -= 15;
        }

        if ($paragraphCount >= 3 && $paragraphCount <= 10) {
            $score += 10;
        }

        return max(0, min(100, $score));
    }
}
