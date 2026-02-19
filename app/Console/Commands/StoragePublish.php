<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Copie le contenu de storage/app/public vers public/storage.
 * Alternative à storage:link pour les hébergements LWS qui désactivent les symlinks.
 * Exécuter après chaque déploiement ou après upload d'images.
 */
class StoragePublish extends Command
{
    protected $signature = 'storage:publish 
        {--force : Écraser tous les fichiers existants}
        {--show : Afficher le détail des fichiers copiés}';
    protected $description = 'Copie storage/app/public vers public/storage (pour LWS sans symlink)';

    private bool $show = false;
    private int $totalInSource = 0;

    public function handle(): int
    {
        $this->show = $this->option('show');
        $source = storage_path('app/public');
        $dest = public_path('storage');

        $this->info("Source : {$source}");
        $this->info("Destination : {$dest}");
        $realSource = realpath($source);
        $realDest = file_exists($dest) ? realpath($dest) : false;
        if ($realSource && $realDest && $realSource === $realDest) {
            $this->warn('Un symlink existe : public/storage pointe vers storage/app/public.');
            $this->warn('Sur LWS, Apache peut refuser de servir les fichiers via un symlink.');
            $this->line('Remplacement du symlink par une copie réelle des fichiers...');
            if (!@unlink($dest) && !@rmdir($dest)) {
                $this->error('Impossible de supprimer le symlink. Vérifiez les permissions.');
                return 1;
            }
            if (!mkdir($dest, 0755, true)) {
                $this->error('Impossible de créer le dossier destination.');
                return 1;
            }
            $this->info('Symlink remplacé par un dossier vide. Copie en cours...');
        }
        $this->newLine();

        if (!is_dir($source)) {
            $this->error("Le dossier source n'existe pas !");
            $this->info('Créez-le avec : mkdir -p storage/app/public/document-covers storage/app/public/job-covers');
            return 1;
        }

        // Compter les fichiers dans la source
        $this->totalInSource = $this->countFiles($source);
        $this->info("Fichiers trouvés dans la source : {$this->totalInSource}");

        if ($this->totalInSource === 0) {
            $this->warn('Aucun fichier dans storage/app/public !');
            $this->line('Vérifiez que les uploads vont bien dans : storage/app/public/document-covers et job-covers');
            $this->line('Liste des sous-dossiers : ' . implode(', ', $this->listSubdirs($source)));
            return 0;
        }

        if (!file_exists($dest)) {
            if (!mkdir($dest, 0755, true)) {
                $this->error("Impossible de créer le dossier destination.");
                return 1;
            }
            $this->info("Dossier créé : {$dest}");
        }

        $copied = 0;
        $this->copyDirectory($source, $dest, $copied);

        $this->newLine();
        $this->info("Terminé. {$copied} fichier(s) copié(s) vers public/storage");

        if ($copied === 0 && $this->totalInSource > 0) {
            $this->warn('Aucune copie effectuée. Essayez avec --force pour écraser les fichiers existants.');
        }

        return 0;
    }

    private function countFiles(string $dir): int
    {
        $count = 0;
        $items = @scandir($dir);
        if ($items === false) return 0;
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            $count += is_dir($path) ? $this->countFiles($path) : 1;
        }
        return $count;
    }

    private function listSubdirs(string $dir): array
    {
        $items = @scandir($dir);
        if ($items === false) return [];
        return array_filter($items, fn($i) => $i !== '.' && $i !== '..' && is_dir($dir . DIRECTORY_SEPARATOR . $i));
    }

    private function copyDirectory(string $source, string $dest, int &$copied): void
    {
        $items = @scandir($source);
        if ($items === false) return;

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;

            $srcPath = $source . DIRECTORY_SEPARATOR . $item;
            $destPath = $dest . DIRECTORY_SEPARATOR . $item;

            if (is_dir($srcPath)) {
                if (!file_exists($destPath)) {
                    mkdir($destPath, 0755, true);
                }
                $this->copyDirectory($srcPath, $destPath, $copied);
            } else {
                $force = $this->option('force');
                $destExists = file_exists($destPath);
                $srcNewer = $destExists && (filemtime($srcPath) > filemtime($destPath));
                $differentSize = $destExists && (filesize($srcPath) !== filesize($destPath));

                $shouldCopy = $force || !$destExists || $srcNewer || $differentSize;

                if ($shouldCopy) {
                    $destDir = dirname($destPath);
                    if (!is_dir($destDir) && !mkdir($destDir, 0755, true)) {
                        $this->error("  ✗ Impossible de créer le dossier : {$destDir}");
                        continue;
                    }
                    if (!is_writable($destDir)) {
                        $this->error("  ✗ Dossier non accessible en écriture : {$destDir}");
                        continue;
                    }
                    if (copy($srcPath, $destPath)) {
                        $copied++;
                        $relativePath = str_replace(public_path(), '', $destPath);
                        if ($this->show) {
                            $this->line("  ✓ " . ltrim($relativePath, '/\\'));
                        }
                    } else {
                        $err = error_get_last();
                        $this->error("  ✗ Échec : " . basename($srcPath) . " - " . ($err['message'] ?? 'Erreur inconnue'));
                    }
                }
            }
        }
    }
}
