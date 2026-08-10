<?php

namespace App\Console\Commands;

use App\Services\ImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Audit (et, avec --apply, compression) des images déjà stockées sur le disque
 * 'public' — couvertures d'articles (job-covers), de cours (courses), catégories
 * (category-images), etc.
 *
 * Contexte : l'audit du 27/07/2026 a chiffré 3,4 Mo d'économies possibles sur les
 * images du site (ex. couverture d'article PNG de 1,4 Mo jamais redimensionnée).
 * ImageOptimizer::optimize() traite déjà les nouveaux uploads (voir
 * JobArticleController, PaidCourseController, ExternalImageRehoster) ; cette
 * commande rattrape le stock existant.
 *
 * Par défaut, ne fait qu'un état des lieux (aucune écriture). Ajouter --apply
 * pour recompresser réellement les fichiers en place (même nom, même extension —
 * aucune colonne cover_image en base à mettre à jour).
 *
 * À exécuter directement sur le serveur de production (le stockage local de dev
 * ne contient pas les mêmes fichiers que la prod) :
 *   php artisan images:audit
 *   php artisan images:audit --apply
 */
class AuditImages extends Command
{
    protected $signature = 'images:audit
                            {--apply : Recompresse réellement les fichiers trouvés (sinon, rapport seul)}
                            {--folders=job-covers,courses,category-images : Dossiers du disque public à scanner, séparés par des virgules}
                            {--min-kb=150 : Ne signaler que les fichiers dépassant ce poids (Ko)}';

    protected $description = "Audite (et optionnellement compresse) les images déjà stockées sur le disque public";

    public function handle(): int
    {
        $apply = (bool) $this->option('apply');
        $minBytes = (int) $this->option('min-kb') * 1024;
        $folders = array_filter(array_map('trim', explode(',', (string) $this->option('folders'))));

        $disk = Storage::disk('public');
        $rows = [];
        $totalBefore = 0;
        $totalSaved = 0;

        foreach ($folders as $folder) {
            if (!$disk->exists($folder)) {
                continue;
            }

            foreach ($disk->files($folder) as $relativePath) {
                $absolutePath = $disk->path($relativePath);
                $size = @filesize($absolutePath);
                if ($size === false || $size < $minBytes) {
                    continue;
                }

                $totalBefore += $size;
                $saved = 0;

                if ($apply) {
                    if (ImageOptimizer::optimize($absolutePath)) {
                        $newSize = filesize($absolutePath);
                        $saved = $size - $newSize;
                        $totalSaved += $saved;
                    }
                }

                $rows[] = [
                    $relativePath,
                    number_format($size / 1024, 0) . ' Ko',
                    $apply ? ($saved > 0 ? '-' . number_format($saved / 1024, 0) . ' Ko' : '—') : '',
                ];
            }
        }

        if (empty($rows)) {
            $this->info('Aucune image dépassant ' . ($minBytes / 1024) . ' Ko dans : ' . implode(', ', $folders));
            return self::SUCCESS;
        }

        $this->table(['Fichier', 'Poids', $apply ? 'Gain' : ''], $rows);
        $this->newLine();
        $this->info(count($rows) . ' image(s) au-delà du seuil, ' . number_format($totalBefore / 1024 / 1024, 2) . ' Mo au total.');

        if ($apply) {
            $this->info('Compressées en place : ' . number_format($totalSaved / 1024 / 1024, 2) . ' Mo économisés.');
        } else {
            $this->comment('Mode rapport seul — relancer avec --apply pour compresser réellement ces fichiers.');
        }

        return self::SUCCESS;
    }
}
