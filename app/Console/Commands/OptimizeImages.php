<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OptimizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:optimize 
                            {--path=public/images : Chemin des images à optimiser}
                            {--quality=85 : Qualité WebP (1-100)}
                            {--force : Forcer la reconversion même si le WebP existe}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convertit les images en WebP pour améliorer les performances';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->option('path');
        $quality = (int) $this->option('quality');
        $force = $this->option('force');

        if (!is_dir($path)) {
            $this->error("Le chemin {$path} n'existe pas.");
            return 1;
        }

        $this->info("Optimisation des images dans {$path}...");
        $this->newLine();

        $images = $this->findImages($path);
        $converted = 0;
        $skipped = 0;
        $failed = 0;

        $bar = $this->output->createProgressBar(count($images));
        $bar->start();

        foreach ($images as $image) {
            $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $image);

            // Vérifier si le WebP existe déjà
            if (file_exists($webpPath) && !$force) {
                $skipped++;
                $bar->advance();
                continue;
            }

            // Convertir en WebP
            if ($this->convertToWebp($image, $webpPath, $quality)) {
                $converted++;
            } else {
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Conversion terminée !");
        $this->table(
            ['Statut', 'Nombre'],
            [
                ['Converties', $converted],
                ['Ignorées', $skipped],
                ['Échecs', $failed],
            ]
        );

        return 0;
    }

    /**
     * Trouver toutes les images dans un répertoire
     */
    private function findImages(string $path): array
    {
        $images = [];
        $files = File::allFiles($path);

        foreach ($files as $file) {
            $extension = strtolower($file->getExtension());
            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $images[] = $file->getPathname();
            }
        }

        return $images;
    }

    /**
     * Convertir une image en WebP
     */
    private function convertToWebp(string $sourcePath, string $destinationPath, int $quality): bool
    {
        // Créer le répertoire de destination si nécessaire
        $destinationDir = dirname($destinationPath);
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        // Détecter le type d'image
        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) {
            return false;
        }

        $mimeType = $imageInfo['mime'];

        // Charger l'image selon son type
        switch ($mimeType) {
            case 'image/jpeg':
                if (!function_exists('imagecreatefromjpeg')) {
                    return false;
                }
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                if (!function_exists('imagecreatefrompng')) {
                    return false;
                }
                $image = imagecreatefrompng($sourcePath);
                // Préserver la transparence
                imagealphablending($image, false);
                imagesavealpha($image, true);
                break;
            default:
                return false;
        }

        if (!$image) {
            return false;
        }

        // Convertir en WebP
        $result = imagewebp($image, $destinationPath, $quality);
        imagedestroy($image);

        return $result;
    }
}

