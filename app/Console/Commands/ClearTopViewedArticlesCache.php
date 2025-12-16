<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearTopViewedArticlesCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-top-viewed-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vider le cache des articles les plus vus dans la sidebar';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Cache::forget('top_viewed_articles_sidebar');
        $this->info('Cache des articles les plus vus vidé avec succès!');
        return 0;
    }
}

