<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';
    protected $description = 'Backup the database automatically';

    public function handle()
    {
        try {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port', 3306);
            
            $filename = 'backup_' . $database . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
            $path = storage_path('app/backups/' . $filename);
            
            // Créer le dossier backups s'il n'existe pas
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }
            
            // Commande mysqldump
            $command = sprintf(
                'mysqldump -h %s -P %s -u %s -p%s %s > %s',
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($path)
            );
            
            // Exécuter la commande
            exec($command . ' 2>&1', $output, $returnCode);
            
            if ($returnCode === 0 && file_exists($path)) {
                // Compresser le fichier de sauvegarde
                $compressedPath = $path . '.gz';
                $data = file_get_contents($path);
                file_put_contents($compressedPath, gzencode($data, 9));
                
                // Supprimer le fichier non compressé
                unlink($path);
                
                // Garder seulement les 10 dernières sauvegardes
                $this->cleanupOldBackups();
                
                $this->info('Sauvegarde créée avec succès: ' . basename($compressedPath));
                return 0;
            } else {
                $this->error('Erreur lors de la création de la sauvegarde: ' . implode("\n", $output));
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('Erreur: ' . $e->getMessage());
            return 1;
        }
    }
    
    private function cleanupOldBackups()
    {
        $backupsPath = storage_path('app/backups');
        if (!is_dir($backupsPath)) {
            return;
        }
        
        $files = glob($backupsPath . '/backup_*.sql.gz');
        
        if (count($files) > 10) {
            // Trier par date de modification (plus ancien en premier)
            usort($files, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            
            // Supprimer les fichiers les plus anciens
            $filesToDelete = array_slice($files, 0, count($files) - 10);
            foreach ($filesToDelete as $file) {
                unlink($file);
            }
        }
    }
}

