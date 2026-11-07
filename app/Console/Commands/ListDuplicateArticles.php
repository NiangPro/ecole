<?php

namespace App\Console\Commands;

use App\Models\JobArticle;
use App\Models\JobArticleRedirect;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use App\Support\DuplicateArticleFinder;

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
        $redirected = JobArticleRedirect::map();
        $groups = DuplicateArticleFinder::groups();

        if ($groups === []) {
            $this->info('Aucun doublon trouvé.');

            return self::SUCCESS;
        }

        $md = ["# Articles en double — " . now()->format('d/m/Y H:i'), '',
            'Généré par `php artisan articles:doublons` (lecture seule, rien n\'a été modifié).', '',
            'Colonne « À garder » : choix de `articles:fusionner-doublons` (publié, puis URL sans suffixe -N, puis le plus vu, puis le plus ancien).',
            'Colonne « Redirigé » : le slug renvoie déjà une 301 (JobArticleRedirect).', ''];

        $n = 0;
        foreach ($groups as $group) {
            $n++;
            /** @var Collection $items */
            $items = $group['articles'];
            $keeper = DuplicateArticleFinder::keeper($items);
            $rows = $items->map(fn ($a) => [
                $a->id === $keeper->id ? 'oui' : '',
                $a->id,
                $a->slug,
                $a->status,
                optional($a->published_at ?? $a->created_at)->format('Y-m-d'),
                $a->views,
                isset($redirected[$a->slug]) ? '→ ' . $redirected[$a->slug] : '',
            ])->all();

            $this->line('');
            $this->info("Groupe $n — {$group['raison']} : « {$items->first()->title} »");
            $this->table(['À garder', 'id', 'slug', 'statut', 'date', 'vues', 'Redirigé'], $rows);

            $md[] = "## Groupe $n — {$group['raison']}";
            $md[] = '';
            $md[] = '« ' . str_replace('|', '\|', $items->first()->title) . ' »';
            $md[] = '';
            $md[] = '| À garder | id | slug | statut | date | vues | Redirigé |';
            $md[] = '|---|---|---|---|---|---|---|';
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
