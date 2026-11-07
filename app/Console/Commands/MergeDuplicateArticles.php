<?php

namespace App\Console\Commands;

use App\Http\Middleware\PageCache;
use App\Models\JobArticle;
use App\Models\JobArticleRedirect;
use App\Support\DuplicateArticleFinder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Audit SEO-03 — fusion des articles en double.
 *
 * Par défaut : SIMULATION, rien n'est modifié. Avec --appliquer : dans chaque groupe, l'article
 * gardé est choisi par DuplicateArticleFinder::keeper() ; les autres sont ARCHIVÉS (jamais
 * supprimés) et leur slug renvoie une 301 vers l'article gardé (JobArticleRedirect).
 *
 * Retour arrière : le rapport (--rapport) liste chaque article archivé avec son statut d'origine.
 * Remettre ce statut et supprimer la ligne de job_article_redirects correspondante.
 */
class MergeDuplicateArticles extends Command
{
    protected $signature = 'articles:fusionner-doublons
                            {--appliquer : Exécute réellement la fusion (sinon simulation)}
                            {--force : Ne demande pas de confirmation (avec --appliquer)}
                            {--rapport= : Écrit le plan / le résultat en Markdown dans ce fichier}';

    protected $description = 'Archive les doublons d\'articles et les redirige en 301 vers l\'article gardé (simulation par défaut)';

    public function handle(): int
    {
        $groups = DuplicateArticleFinder::groups();
        $redirected = JobArticleRedirect::map();
        $apply = (bool) $this->option('appliquer');

        $plan = [];
        foreach ($groups as $group) {
            $keeper = DuplicateArticleFinder::keeper($group['articles']);
            foreach ($group['articles'] as $article) {
                if ($article->id === $keeper->id || isset($redirected[$article->slug])) {
                    continue; // l'article gardé, ou un doublon déjà redirigé
                }
                $plan[] = ['old' => $article, 'keeper' => $keeper, 'raison' => $group['raison']];
            }
        }

        if ($plan === []) {
            $this->info('Aucun doublon à fusionner.');

            return self::SUCCESS;
        }

        $this->info(($apply ? 'Fusion' : 'SIMULATION (rien n\'est modifié)') . ' — ' . count($plan) . ' article(s) à archiver et rediriger :');
        $this->table(
            ['id archivé', 'slug archivé', 'statut actuel', 'vues', '→ id gardé', 'slug gardé', 'vues gardé'],
            array_map(fn ($p) => [$p['old']->id, $p['old']->slug, $p['old']->status, $p['old']->views, $p['keeper']->id, $p['keeper']->slug, $p['keeper']->views], $plan)
        );

        if (! $apply) {
            $this->writeReport($plan, false);
            $this->line('');
            $this->comment('Pour appliquer : php artisan articles:fusionner-doublons --appliquer --rapport=storage/app/fusion-doublons.md');

            return self::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm('Archiver ces articles et créer les redirections 301 ?', false)) {
            $this->warn('Annulé, rien n\'a été modifié.');

            return self::FAILURE;
        }

        DB::transaction(function () use ($plan) {
            foreach ($plan as $p) {
                JobArticleRedirect::updateOrCreate(['old_slug' => $p['old']->slug], ['new_slug' => $p['keeper']->slug]);
                // save() via le modèle : vide le cache de l'article, des listes et du sitemap.
                $old = JobArticle::find($p['old']->id);
                $old->status = 'archived';
                $old->save();
            }
        });
        PageCache::flush();

        $this->writeReport($plan, true);
        $this->info(count($plan) . ' article(s) archivé(s), redirections 301 créées, cache de page vidé.');

        return self::SUCCESS;
    }

    private function writeReport(array $plan, bool $applied): void
    {
        if (! $path = $this->option('rapport')) {
            return;
        }

        $md = [
            '# Fusion des doublons — ' . now()->format('d/m/Y H:i') . ($applied ? '' : ' (SIMULATION)'),
            '',
            $applied
                ? 'Articles archivés et redirigés en 301. Retour arrière : remettre le « statut d\'origine » et supprimer la ligne `old_slug` de `job_article_redirects`.'
                : 'Simulation : rien n\'a été modifié. Règle : publié, puis URL sans suffixe -N, puis le plus vu, puis le plus ancien.',
            '',
            '| id archivé | slug archivé | statut d\'origine | vues | id gardé | slug gardé |',
            '|---|---|---|---|---|---|',
        ];
        foreach ($plan as $p) {
            $md[] = '| ' . implode(' | ', array_map(fn ($v) => str_replace('|', '\|', (string) $v), [
                $p['old']->id, $p['old']->slug, $p['old']->status, $p['old']->views, $p['keeper']->id, $p['keeper']->slug,
            ])) . ' |';
        }

        file_put_contents($path, implode(PHP_EOL, $md) . PHP_EOL);
        $this->info("Rapport écrit dans $path");
    }
}
