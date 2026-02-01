<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use App\Models\JobArticle;

class FeaturedArticlesSeeder extends Seeder
{
    /**
     * Marque des articles publiés comme "vedettes" pour la page d'accueil et la section Articles vedettes.
     * Prend les 8 articles les plus récents (par date de publication) et les met en avant.
     */
    public function run(): void
    {
        // Remettre tous les articles en non-vedette pour repartir à zéro
        JobArticle::query()->update(['is_featured' => false]);

        // Sélectionner les 8 articles publiés les plus récents pour les mettre en vedette
        $nombreVedettes = 8;
        $idsVedettes = JobArticle::published()
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit($nombreVedettes)
            ->pluck('id');

        if ($idsVedettes->isEmpty()) {
            $this->command?->warn('Aucun article publié trouvé. Exécutez d\'abord JobArticlesSeeder.');
            return;
        }

        JobArticle::whereIn('id', $idsVedettes)->update(['is_featured' => true]);

        Cache::forget('featured_articles');
        Cache::forget('homepage_view_fr');
        Cache::forget('homepage_view_en');

        $this->command?->info(
            $idsVedettes->count() . ' article(s) marqué(s) comme vedette(s).'
        );
    }
}
