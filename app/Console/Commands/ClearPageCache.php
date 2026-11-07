<?php

namespace App\Console\Commands;

use App\Http\Middleware\PageCache;
use Illuminate\Console\Command;

/**
 * Vide le cache HTML des pages publiques (PageCache) sans toucher au reste du cache
 * (compteurs de throttle, réglages, etc.). À lancer après chaque déploiement.
 */
class ClearPageCache extends Command
{
    protected $signature = 'page-cache:clear';

    protected $description = 'Vide le cache HTML des pages publiques (PageCache)';

    public function handle(): int
    {
        PageCache::flush();
        $this->info('Cache de page vidé.');

        return self::SUCCESS;
    }
}
