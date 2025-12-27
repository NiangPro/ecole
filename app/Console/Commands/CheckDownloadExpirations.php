<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;

/**
 * Commande pour vérifier et envoyer les notifications d'expiration de téléchargement
 */
class CheckDownloadExpirations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:check-expirations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie et envoie les notifications d\'expiration de téléchargement';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Vérification des expirations de téléchargement...');
        
        $count = NotificationService::checkDownloadExpirations();
        
        $this->info("{$count} notification(s) d'expiration créée(s).");
        
        return Command::SUCCESS;
    }
}
