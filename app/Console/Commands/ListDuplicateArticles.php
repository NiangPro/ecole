<?php

namespace App\Console\Commands;

use App\Models\JobArticle;
use App\Models\JobArticleRedirect;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

/**
 * Audit SEO-03 — LECTURE SEULE : liste les articles en double (même titre, ou slug
 * « -2 », « -3 »… dont l'article de base existe). Ne modifie rien en base.
 */
class ListDuplicateArticles extends Command
{
    protected $signature = 'articles:doublons
                            {--md= : Écrit aussi le résultat en Markdown dans ce fichier}';

    protected $description = 'Liste (sans rien modifier) les articles au titre identique ou au slug -2, -3… d\'un article existant';

    public function handle(): int
    {
        $articles = JobArticle::query()
            ->select('id', 'title', 'slug', 'status', 'published_at', 'created_at', 'views')
            ->orderBy('id')
            ->get();

        $bySlug = $articles->keyBy('slug');
        $redirected = JobArticleRedirect::map();
        $groups = [];

        // 1. Titres identiques (insensible à la casse et aux espaces).
        foreach ($articles->groupBy(fn ($a) => mb_strtolower(trim(preg_replace('/\s+/', ' ', $a->title)))) as $title => $items) {
            if ($items->count() > 1) {
                $groups['titre:' . $title] = ['raison' => 'Titre identique', 'articles' => $items];
            }
        }

        // 2. Slug « base-N » dont « base » existe.
        foreach ($articles as $article) {
            if (preg_match('/^(.+)-(\d{1,2})$/', $article->slug, $m) && $bySlug->has($m[1])) {
                $base = $bySlug->get($m[1]);
                $key = 'titre:' . mb_strtolower(trim(preg_replace('/\s+/', ' ', $base->title)));
                if (isset($groups[$key]) && $groups[$key]['articles']->contains('id', $article->id)) {
                    continue; // déjà listé avec le même titre
                }
                $slugKey = 'slug:' . $m[1];
                $groups[$slugKey] ??= ['raison' => 'Slug suffixé (-N) d\'un article existant', 'articles' => collect([$base])];
                $groups[$slugKey]['articles']->push($article);
            }
        }

        if ($groups === []) {
            $this->info('Aucun doublon trouvé.');

            return self::SUCCESS;
        }

        $md = ["# Articles en double — " . now()->format('d/m/Y H:i'), '',
            'Généré par `php artisan articles:doublons` (lecture seule, rien n\'a été modifié).', '',
            'Colonne « Redirigé » : le slug renvoie déjà une 301 (JobArticleRedirect).', ''];

        $n = 0;
        foreach ($groups as $group) {
            $n++;
            /** @var Collection $items */
            $items = $group['articles']->sortBy('id')->values();
            $rows = $items->map(fn ($a) => [
                $a->id,
                $a->slug,
                $a->status,
                optional($a->published_at ?? $a->created_at)->format('Y-m-d'),
                $a->views,
                isset($redirected[$a->slug]) ? '→ ' . $redirected[$a->slug] : '',
            ])->all();

            $this->line('');
            $this->info("Groupe $n — {$group['raison']} : « {$items->first()->title} »");
            $this->table(['id', 'slug', 'statut', 'date', 'vues', 'Redirigé'], $rows);

            $md[] = "## Groupe $n — {$group['raison']}";
            $md[] = '';
            $md[] = '« ' . str_replace('|', '\|', $items->first()->title) . ' »';
            $md[] = '';
            $md[] = '| id | slug | statut | date | vues | Redirigé |';
            $md[] = '|---|---|---|---|---|---|';
            foreach ($rows as $r) {
                $md[] = '| ' . implode(' | ', array_map(fn ($v) => str_replace('|', '\|', (string) $v), $r)) . ' |';
            }
            $md[] = '';
        }

        $this->line('');
        $this->info("$n groupe(s) de doublons.");

        if ($path = $this->option('md')) {
            file_put_contents($path, implode(PHP_EOL, $md) . PHP_EOL);
            $this->info("Écrit dans $path");
        }

        return self::SUCCESS;
    }
}
