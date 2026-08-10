<?php

namespace App\Console\Commands;

use App\Models\JobArticle;
use Illuminate\Console\Command;

/**
 * Édition ciblée et *reviewable* de l'année mentionnée dans le corps de texte
 * des guides « evergreen » dont le titre/meta a déjà été passé de 2025 à 2026
 * via l'admin, mais dont le champ `content` (HTML) mentionne encore l'ancienne
 * année dans le H1 et/ou les premiers paragraphes.
 *
 * Par défaut la commande tourne en DRY-RUN : elle n'écrit rien et se contente
 * d'afficher un diff avant/après de chaque occurrence, avec son contexte, pour
 * relecture. L'écriture en base n'a lieu qu'avec --apply. Ce n'est donc PAS une
 * migration de masse : les IDs sont explicites et chaque changement est montré.
 *
 * Exemples :
 *   php artisan articles:fix-evergreen-year                 # dry-run, IDs par défaut
 *   php artisan articles:fix-evergreen-year --apply         # applique après relecture
 *   php artisan articles:fix-evergreen-year --ids=6 --apply # un seul article
 */
class FixEvergreenYear extends Command
{
    protected $signature = 'articles:fix-evergreen-year
        {--ids=6,27,20,25 : IDs job_articles à traiter (guides evergreen)}
        {--from=2025 : Année à remplacer}
        {--to=2026 : Année de remplacement}
        {--fields=title,meta_title,excerpt,meta_description,content : Champs à inspecter}
        {--apply : Écrit réellement les changements en base (sinon dry-run)}';

    protected $description = "Met à jour l'année (2025→2026) dans le corps des guides evergreen, avec diff avant/après et dry-run par défaut";

    public function handle(): int
    {
        $ids = collect(explode(',', (string) $this->option('ids')))
            ->map(fn ($id) => (int) trim($id))
            ->filter()
            ->all();
        $from = (string) $this->option('from');
        $to = (string) $this->option('to');
        $fields = collect(explode(',', (string) $this->option('fields')))
            ->map(fn ($f) => trim($f))
            ->filter()
            ->all();
        $apply = (bool) $this->option('apply');

        if (empty($ids)) {
            $this->error('Aucun ID fourni (--ids).');
            return self::FAILURE;
        }

        $this->line($apply
            ? "<fg=yellow>MODE APPLY — les changements seront écrits en base.</>"
            : "<fg=cyan>MODE DRY-RUN — aucune écriture. Utilisez --apply pour appliquer.</>");
        $this->line("Remplacement : « $from » → « $to »");
        $this->newLine();

        $totalOccurrences = 0;
        $totalArticles = 0;

        foreach ($ids as $id) {
            /** @var JobArticle|null $article */
            $article = JobArticle::find($id);

            if (! $article) {
                $this->warn("#$id : introuvable — ignoré.");
                continue;
            }

            $this->line("<options=bold>#{$id} — {$article->title}</>");

            $articleChanged = false;
            $articleOccurrences = 0;

            foreach ($fields as $field) {
                $original = (string) ($article->{$field} ?? '');
                if ($original === '' || ! str_contains($original, $from)) {
                    continue;
                }

                $count = substr_count($original, $from);
                $articleOccurrences += $count;
                $this->line("  <fg=magenta>[$field]</> — $count occurrence(s) :");

                // Affiche chaque occurrence avec ~50 caractères de contexte de part et d'autre.
                foreach ($this->contexts($original, $from, $to) as $ctx) {
                    $this->line("    - avant : …{$ctx['before']}…");
                    $this->line("      après : …{$ctx['after']}…");
                }

                if ($apply) {
                    $article->{$field} = str_replace($from, $to, $original);
                    $articleChanged = true;
                }
            }

            if ($articleOccurrences === 0) {
                $this->line("  <fg=green>Aucune mention de « {$from} » — rien à faire.</>");
            } else {
                $totalOccurrences += $articleOccurrences;
                $totalArticles++;
            }

            if ($apply && $articleChanged) {
                $article->save();
                $this->line("  <fg=green>✔ Enregistré.</>");
            }

            $this->newLine();
        }

        $this->line("Résumé : $totalOccurrences occurrence(s) sur $totalArticles article(s).");
        if (! $apply && $totalOccurrences > 0) {
            $this->line("<fg=cyan>Relancez avec --apply pour appliquer ces changements.</>");
        }

        return self::SUCCESS;
    }

    /**
     * Extrait le contexte (avant/après) de chaque occurrence de $from, en
     * surlignant le fragment remplacé, pour une relecture visuelle du diff.
     *
     * @return array<int, array{before: string, after: string}>
     */
    private function contexts(string $text, string $from, string $to): array
    {
        $out = [];
        $offset = 0;
        $pad = 50;

        while (($pos = strpos($text, $from, $offset)) !== false) {
            $start = max(0, $pos - $pad);
            $before = substr($text, $start, $pos - $start);
            $after = substr($text, $pos + strlen($from), $pad);
            // Nettoie les retours à la ligne pour un affichage sur une ligne.
            $before = trim(preg_replace('/\s+/', ' ', $before));
            $after = trim(preg_replace('/\s+/', ' ', $after));

            $out[] = [
                'before' => "{$before}<fg=red>{$from}</>{$after}",
                'after' => "{$before}<fg=green>{$to}</>{$after}",
            ];
            $offset = $pos + strlen($from);
        }

        return $out;
    }
}
