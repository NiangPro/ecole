<?php

namespace App\Support;

use App\Models\JobArticle;
use Illuminate\Support\Collection;

/**
 * Détection des articles en double (audit SEO-03) : même titre (casse et espaces ignorés),
 * ou slug « base-N » dont l'article « base » existe. Partagé par articles:doublons
 * (lecture seule) et articles:fusionner-doublons.
 */
class DuplicateArticleFinder
{
    /**
     * @return array<int, array{raison: string, articles: Collection}>
     */
    public static function groups(): array
    {
        $articles = JobArticle::query()
            ->select('id', 'title', 'slug', 'status', 'published_at', 'created_at', 'views')
            ->orderBy('id')
            ->get();

        $bySlug = $articles->keyBy('slug');
        $norm = fn (string $title) => mb_strtolower(trim(preg_replace('/\s+/', ' ', $title)));
        $groups = [];

        // 1. Titres identiques.
        foreach ($articles->groupBy(fn ($a) => $norm($a->title)) as $title => $items) {
            if ($items->count() > 1) {
                $groups['titre:' . $title] = ['raison' => 'Titre identique', 'articles' => $items->values()];
            }
        }

        // 2. Slug « base-N » dont « base » existe.
        foreach ($articles as $article) {
            if (preg_match('/^(.+)-(\d{1,2})$/', $article->slug, $m) && $bySlug->has($m[1])) {
                $base = $bySlug->get($m[1]);
                $key = 'titre:' . $norm($base->title);
                if (isset($groups[$key]) && $groups[$key]['articles']->contains('id', $article->id)) {
                    continue; // déjà dans le groupe du même titre
                }
                $slugKey = 'slug:' . $m[1];
                $groups[$slugKey] ??= ['raison' => 'Slug suffixé (-N) d\'un article existant', 'articles' => collect([$base])];
                $groups[$slugKey]['articles']->push($article);
            }
        }

        return array_values(array_map(function ($g) {
            $g['articles'] = $g['articles']->unique('id')->sortBy('id')->values();

            return $g;
        }, $groups));
    }

    /**
     * Article à garder dans un groupe : publié d'abord, puis URL sans suffixe -N (celle que
     * les moteurs connaissent), puis le plus vu, puis le plus ancien.
     */
    public static function keeper(Collection $articles): JobArticle
    {
        $rank = fn (JobArticle $a) => [
            $a->status === 'published',
            ! self::isSuffixed($a),
            (int) $a->views,
            -$a->id,
        ];

        return $articles->sort(fn (JobArticle $a, JobArticle $b) => $rank($b) <=> $rank($a))->first();
    }

    private static function isSuffixed(JobArticle $article): bool
    {
        return preg_match('/-\d{1,2}$/', $article->slug) === 1;
    }
}
