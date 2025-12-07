<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\JobArticle;
use App\Models\Category;

class CacheWarmup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warmup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Précharge le cache avec les données fréquemment utilisées';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Préchargement du cache...');
        $this->newLine();

        // Cache des articles récents
        $this->info('Caching des articles récents...');
        $latestJobs = JobArticle::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(9)
            ->get();
        Cache::remember('homepage.latest_jobs', 3600, function () use ($latestJobs) {
            return $latestJobs;
        });

        // Cache des catégories
        $this->info('Caching des catégories...');
        $categories = Category::withCount('articles')->get();
        Cache::remember('categories.all', 7200, function () use ($categories) {
            return $categories;
        });

        // Cache des statistiques
        $this->info('Caching des statistiques...');
        Cache::remember('stats.total_articles', 3600, function () {
            return JobArticle::where('status', 'published')->count();
        });

        Cache::remember('stats.total_categories', 7200, function () {
            return Category::count();
        });

        // Cache des articles populaires
        $this->info('Caching des articles populaires...');
        $popularArticles = JobArticle::where('status', 'published')
            ->orderBy('views', 'desc')
            ->take(10)
            ->get();
        Cache::remember('articles.popular', 1800, function () use ($popularArticles) {
            return $popularArticles;
        });

        $this->newLine();
        $this->info('Cache préchargé avec succès !');

        return 0;
    }
}

