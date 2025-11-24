<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BingUrlSubmissionService;

class SubmitUrlsToBing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bing:submit-urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Soumet toutes les URLs (formations, exercices, quiz, articles) à Bing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = new BingUrlSubmissionService();

        if (!$service->isConfigured()) {
            $this->error('Clé API Bing non configurée. Veuillez la configurer dans les paramètres admin.');
            return 1;
        }

        $this->info('Récupération des URLs à soumettre...');
        $urls = $service->getAllUrlsToSubmit();
        
        $this->info("Nombre d'URLs à soumettre : " . count($urls));

        if ($this->confirm('Voulez-vous continuer ?', true)) {
            $this->info('Soumission des URLs à Bing...');
            $result = $service->submitUrls($urls);

            if ($result['success']) {
                $this->info($result['message']);
                $this->info("Total : {$result['total']} | Soumises : {$result['submitted']} | Échecs : {$result['failed']}");
            } else {
                $this->error($result['message']);
                if (!empty($result['errors'])) {
                    foreach ($result['errors'] as $error) {
                        $this->error("  - $error");
                    }
                }
                return 1;
            }
        }

        return 0;
    }
}
