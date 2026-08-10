<?php

namespace App\Console\Commands;

use App\Services\ArticlePublishingHealthCheck;
use Illuminate\Console\Command;

/**
 * Vérification de santé à lancer avant toute session de publication automatisée
 * d'articles (voir ArticlePublishingHealthCheck pour le contexte de l'incident
 * du 27/07/2026). Utile en ligne de commande / cron sur le serveur, en
 * complément de GET /api/mcp/health côté agent externe.
 */
class ArticlesHealthCheck extends Command
{
    protected $signature = 'articles:health-check';

    protected $description = "Vérifie que job_articles est accessible en écriture avant une session de publication automatisée";

    public function handle(ArticlePublishingHealthCheck $healthCheck): int
    {
        $result = $healthCheck->run();

        if ($result['ok']) {
            $this->info('✅ job_articles accessible en écriture — publication possible.');
            return self::SUCCESS;
        }

        $this->error('❌ ' . $result['error']);
        return self::FAILURE;
    }
}
