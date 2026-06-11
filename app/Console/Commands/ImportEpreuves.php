<?php

namespace App\Console\Commands;

use App\Models\Epreuve;
use App\Models\EpreuveMatiere;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class ImportEpreuves extends Command
{
    /**
     * Import en masse de PDF d'épreuves : les métadonnées (examen, matière,
     * année, série, type) sont détectées depuis le nom de chaque fichier.
     *
     * Exemples :
     *   php artisan epreuves:import ~/Downloads/epreuves --dry-run
     *   php artisan epreuves:import ~/Downloads/epreuves --draft
     */
    protected $signature = 'epreuves:import
                            {path : Dossier contenant les fichiers PDF (parcouru récursivement)}
                            {--dry-run : Afficher ce qui serait importé sans rien créer}
                            {--draft : Importer en brouillon au lieu de publier directement}
                            {--exam= : Forcer l\'examen pour tout le dossier (cfee, bfem, bac, bts, cap)}
                            {--level= : Forcer la classe pour tout le dossier (ex : 3eme, terminale)}
                            {--force : Importer même les fichiers dont aucune métadonnée n\'est détectée}';

    protected $description = 'Importe en masse des PDF d\'épreuves avec détection automatique des métadonnées';

    public function handle(): int
    {
        $path = rtrim($this->argument('path'), '/');
        if (!is_dir($path)) {
            $this->error("Dossier introuvable : {$path}");
            return self::FAILURE;
        }

        $dryRun = $this->option('dry-run');
        $status = $this->option('draft') ? 'draft' : 'published';

        $forcedExam = $this->option('exam');
        if ($forcedExam && !isset(Epreuve::EXAMS[$forcedExam])) {
            $this->error("Examen inconnu : {$forcedExam} (valeurs : " . implode(', ', array_keys(Epreuve::EXAMS)) . ')');
            return self::FAILURE;
        }
        $forcedLevel = $this->option('level');
        if ($forcedLevel && !isset(Epreuve::flatLevels()[$forcedLevel])) {
            $this->error("Classe inconnue : {$forcedLevel} (valeurs : " . implode(', ', array_keys(Epreuve::flatLevels())) . ')');
            return self::FAILURE;
        }

        $finder = (new Finder())->files()->in($path)->name('*.pdf')->sortByName();
        $total = iterator_count($finder);

        if ($total === 0) {
            $this->warn('Aucun fichier PDF trouvé dans ce dossier.');
            return self::SUCCESS;
        }

        $this->info(($dryRun ? '[DRY-RUN] ' : '') . "{$total} PDF trouvé(s) dans {$path}");
        $this->newLine();

        $imported = 0;
        $skipped = 0;
        $incomplete = 0;
        $rows = [];

        foreach ($finder as $file) {
            $fileName = $file->getFilename();
            $baseName = $file->getFilenameWithoutExtension();

            $title = $this->makeTitle($baseName);
            $slug = Str::slug($baseName);

            if (Epreuve::where('slug', $slug)->exists()) {
                $rows[] = [$fileName, '—', '—', '—', '—', 'IGNORÉ (existe déjà)'];
                $skipped++;
                continue;
            }

            $guess = Epreuve::guessMetadata($fileName);
            if ($forcedExam) {
                $guess['exam'] = $forcedExam;
            }
            if ($forcedLevel) {
                $guess['level'] = $forcedLevel;
            }

            // Sans la moindre métadonnée détectée, le document serait inclassable
            $hasMeta = isset($guess['exam']) || isset($guess['level']) || isset($guess['matiere_id']);
            if (!$hasMeta && !$this->option('force')) {
                $rows[] = [$fileName, '—', '—', '—', '—', 'IGNORÉ (rien détecté, utilisez --force)'];
                $incomplete++;
                continue;
            }

            $matiereName = isset($guess['matiere_id'])
                ? EpreuveMatiere::find($guess['matiere_id'])?->name
                : null;

            $examOrLevel = isset($guess['exam'])
                ? Epreuve::EXAMS[$guess['exam']]
                : (isset($guess['level']) ? Epreuve::flatLevels()[$guess['level']] : '—');

            $rows[] = [
                $fileName,
                $examOrLevel,
                $matiereName ?? '—',
                $guess['year'] ?? '—',
                Epreuve::TYPES[$guess['type'] ?? 'epreuve'],
                $dryRun ? 'À IMPORTER' : 'IMPORTÉ',
            ];

            if (!$dryRun) {
                $storedPath = Storage::disk('public')->putFileAs(
                    'epreuves',
                    $file->getRealPath(),
                    $slug . '.pdf'
                );

                Epreuve::create(array_merge([
                    'title' => $title,
                    'slug' => $slug,
                    'type' => 'epreuve',
                    'file_path' => $storedPath,
                    'file_name' => $fileName,
                    'file_size' => $file->getSize(),
                    'status' => $status,
                ], $guess));
            }

            $imported++;
        }

        $this->table(['Fichier', 'Examen/Classe', 'Matière', 'Année', 'Type', 'Résultat'], $rows);

        $this->newLine();
        $this->info(($dryRun ? '[DRY-RUN] ' : '') . "{$imported} importé(s), {$skipped} doublon(s) ignoré(s), {$incomplete} sans métadonnées.");

        if ($incomplete > 0) {
            $this->comment('Astuce : renommez les fichiers ignorés (ex : "bac-2024-mathematiques-serie-s2.pdf") ou relancez avec --force puis complétez-les dans l\'admin.');
        }
        if (!$dryRun && $imported > 0) {
            $this->comment('Pensez à vider le cache des sitemaps : php artisan cache:clear');
        }

        return self::SUCCESS;
    }

    /**
     * Transforme un nom de fichier en titre lisible.
     * "bac-2024-mathematiques-serie-s2" → "Bac 2024 Mathematiques Serie S2"
     */
    private function makeTitle(string $baseName): string
    {
        $title = trim(preg_replace('/\s+/', ' ', str_replace(['_', '-', '.'], ' ', $baseName)));
        $title = Str::title($title);

        // Remettre les sigles d'examens en majuscules
        foreach (array_keys(Epreuve::EXAMS) as $exam) {
            $title = preg_replace('/\b' . ucfirst($exam) . '\b/', strtoupper($exam), $title);
        }

        return $title;
    }
}
