<?php

namespace App\Console\Commands;

use App\Models\JobArticle;
use App\Services\ExternalImageRehoster;
use Illuminate\Console\Command;

class RehostExternalArticleImages extends Command
{
    /**
     * Correctif rétroactif (audit SEO/perf) : réhéberge localement les images
     * d'articles actuellement hébergées chez des tiers (Google Images thumbnails,
     * actu.rts.sn, msf.fr, etc.) plutôt que de dépendre d'un lien externe.
     *
     * Usage :
     *   php artisan articles:rehost-images                 # tous les articles cover_type=external
     *   php artisan articles:rehost-images --slug=mon-slug  # un seul article
     */
    protected $signature = 'articles:rehost-images {--slug= : Ne traiter qu\'un seul article par son slug}';

    protected $description = "Réhéberge localement les images d'articles actuellement hébergées en externe";

    public function handle(): int
    {
        $query = JobArticle::where('cover_type', 'external')->whereNotNull('cover_image');

        if ($slug = $this->option('slug')) {
            $query->where('slug', $slug);
        }

        $articles = $query->get();

        if ($articles->isEmpty()) {
            $this->info('Aucun article avec une image externe à réhéberger.');
            return self::SUCCESS;
        }

        $success = 0;
        $failed = 0;

        foreach ($articles as $article) {
            $rehosted = ExternalImageRehoster::rehost($article->cover_image);

            if ($rehosted) {
                $article->update([
                    'cover_image' => $rehosted,
                    'cover_type' => 'internal',
                ]);
                $success++;
                $this->line("✓ {$article->slug}");
            } else {
                $failed++;
                $this->warn("✗ {$article->slug} — échec (URL : {$article->cover_image})");
            }
        }

        $this->info("Terminé : {$success} image(s) réhébergée(s), {$failed} échec(s).");

        return self::SUCCESS;
    }
}
