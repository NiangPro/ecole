<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AdsTxtController;

class UpdateAdsTxt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ads:update-txt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mettre à jour le fichier ads.txt avec le Publisher ID AdSense configuré';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mise à jour du fichier ads.txt...');
        
        try {
            AdsTxtController::updateFile();
            $this->info('✓ Fichier ads.txt mis à jour avec succès !');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('✗ Erreur lors de la mise à jour du fichier ads.txt : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
