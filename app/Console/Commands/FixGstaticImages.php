<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobArticle;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FixGstaticImages extends Command
{
    protected $signature = 'articles:fix-gstatic-images
                            {--dry-run : Simuler sans modifier la base de données}
                            {--limit=50 : Nombre maximum d\'articles à traiter par exécution}';

    protected $description = 'Télécharger et re-héberger les images gstatic/googleusercontent instables';

    private string $targetDir = 'public/images/articles';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $limit  = (int) $this->option('limit');

        $this->info($dryRun ? '🔍 Mode simulation (aucune modification)' : '🔄 Correction des images gstatic...');
        $this->newLine();

        $articles = JobArticle::whereNotNull('cover_image')
            ->where(function ($q) {
                $q->where('cover_image', 'like', '%gstatic%')
                  ->orWhere('cover_image', 'like', '%googleusercontent%');
            })
            ->select('id', 'title', 'slug', 'cover_image', 'cover_type')
            ->limit($limit)
            ->get();

        if ($articles->isEmpty()) {
            $this->info('✅ Aucune image gstatic trouvée. Rien à corriger.');
            return 0;
        }

        $this->info("📋 {$articles->count()} article(s) avec images gstatic/googleusercontent (limite: {$limit})");
        $this->newLine();

        $targetPath = public_path('images/articles');
        if (!$dryRun && !is_dir($targetPath)) {
            mkdir($targetPath, 0755, true);
        }

        $fixed   = 0;
        $failed  = 0;
        $skipped = 0;

        $bar = $this->output->createProgressBar($articles->count());
        $bar->start();

        foreach ($articles as $article) {
            $bar->advance();

            $imageUrl = trim($article->cover_image);

            // Déterminer l'extension depuis l'URL ou fallback jpg
            $ext = $this->guessExtension($imageUrl);
            $filename = 'article-' . $article->id . '-' . Str::slug(substr($article->slug, 0, 40)) . '.' . $ext;
            $localPath = $targetPath . '/' . $filename;
            $publicPath = '/images/articles/' . $filename;

            if ($dryRun) {
                $this->newLine();
                $this->line("  [#{$article->id}] {$article->title}");
                $this->line("    URL: " . Str::limit($imageUrl, 80));
                $this->line("    → Cible: {$publicPath}");
                $skipped++;
                continue;
            }

            // Déjà téléchargée lors d'un run précédent
            if (file_exists($localPath)) {
                $article->update(['cover_image' => $publicPath, 'cover_type' => 'external']);
                $fixed++;
                continue;
            }

            try {
                $response = Http::timeout(15)
                    ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; NiangProgrammeur-Bot/1.0)'])
                    ->get($imageUrl);

                if ($response->successful() && strlen($response->body()) > 1000) {
                    file_put_contents($localPath, $response->body());
                    $article->update(['cover_image' => $publicPath, 'cover_type' => 'external']);
                    $fixed++;
                } else {
                    // Image inaccessible : on vide le champ pour éviter un lien cassé
                    $article->update(['cover_image' => null, 'cover_type' => null]);
                    $failed++;
                }
            } catch (\Throwable $e) {
                $article->update(['cover_image' => null, 'cover_type' => null]);
                $failed++;
            }
        }

        $bar->finish();
        $this->newLine(2);

        if ($dryRun) {
            $this->info("📊 Simulation terminée : {$articles->count()} articles seraient traités.");
            $this->line("   Relancez sans --dry-run pour appliquer les corrections.");
        } else {
            $this->info("✅ Terminé :");
            $this->line("   Images téléchargées et re-hébergées : {$fixed}");
            $this->line("   Images inaccessibles (champ vidé)   : {$failed}");

            $remaining = JobArticle::whereNotNull('cover_image')
                ->where(function ($q) {
                    $q->where('cover_image', 'like', '%gstatic%')
                      ->orWhere('cover_image', 'like', '%googleusercontent%');
                })->count();

            if ($remaining > 0) {
                $this->warn("   ⚠️  {$remaining} articles encore avec images gstatic. Relancez la commande.");
            } else {
                $this->info("   🎉 Toutes les images gstatic ont été corrigées !");
            }
        }

        return 0;
    }

    private function guessExtension(string $url): string
    {
        // Essayer d'extraire l'extension de l'URL
        $path = parse_url($url, PHP_URL_PATH);
        if ($path) {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                return $ext === 'jpeg' ? 'jpg' : $ext;
            }
        }
        // Les thumbnails gstatic sont presque toujours des JPEG
        return 'jpg';
    }
}
