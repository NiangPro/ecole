<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobArticle;

class CheckArticleImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:check-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier les images des articles pour le partage Facebook';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Vérification des images des articles...');
        $this->newLine();

        $articles = JobArticle::where('status', 'published')
            ->select('id', 'title', 'slug', 'cover_image', 'cover_type')
            ->get();

        $total = $articles->count();
        $withImages = 0;
        $withoutImages = 0;
        $invalidImages = 0;

        $this->table(
            ['ID', 'Titre', 'Cover Image', 'Cover Type', 'Statut'],
            $articles->map(function ($article) use (&$withImages, &$withoutImages, &$invalidImages) {
                $hasImage = !empty($article->cover_image) && trim($article->cover_image) !== '';
                
                if (!$hasImage) {
                    $withoutImages++;
                    $status = '❌ Pas d\'image';
                } elseif ($article->cover_type === 'external') {
                    $imageUrl = trim($article->cover_image);
                    if (!preg_match('/^https?:\/\//i', $imageUrl)) {
                        $invalidImages++;
                        $status = '⚠️ URL invalide (manque http://)';
                    } else {
                        $withImages++;
                        $status = '✅ Image externe valide';
                    }
                } else {
                    $withImages++;
                    $status = '✅ Image interne';
                }

                return [
                    $article->id,
                    substr($article->title, 0, 40) . '...',
                    $hasImage ? substr($article->cover_image, 0, 50) . '...' : 'NULL',
                    $article->cover_type ?? 'NULL',
                    $status
                ];
            })->toArray()
        );

        $this->newLine();
        $this->info("Résumé :");
        $this->line("  Total d'articles : {$total}");
        $this->line("  Articles avec images valides : {$withImages}");
        $this->line("  Articles sans images : {$withoutImages}");
        $this->line("  Articles avec URLs invalides : {$invalidImages}");

        if ($invalidImages > 0) {
            $this->newLine();
            $this->warn("⚠️  {$invalidImages} article(s) ont des URLs d'images invalides.");
            $this->warn("   Les URLs doivent commencer par http:// ou https://");
        }

        return 0;
    }
}

