<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database {--full : Créer une sauvegarde complète avec fichiers}';
    protected $description = 'Backup automatique de la base de données (à exécuter quotidiennement)';

    /**
     * Nombre de sauvegardes à conserver
     */
    private const BACKUP_RETENTION_DAILY = 7;   // 7 jours
    private const BACKUP_RETENTION_WEEKLY = 4;  // 4 semaines
    private const BACKUP_RETENTION_MONTHLY = 6; // 6 mois

    public function handle()
    {
        $this->info('Démarrage de la sauvegarde...');
        
        try {
            $connection = config('database.default');
            $config = config("database.connections.{$connection}");
            
            if ($config['driver'] !== 'mysql') {
                $this->error('Cette commande ne supporte que MySQL pour le moment.');
                return 1;
            }
            
            $database = $config['database'];
            $username = $config['username'];
            $password = $config['password'];
            $host = $config['host'];
            $port = $config['port'] ?? 3306;
            
            // Créer le dossier backups s'il n'existe pas
            $backupsPath = storage_path('app/backups');
            if (!file_exists($backupsPath)) {
                mkdir($backupsPath, 0755, true);
            }
            
            // Nom du fichier avec timestamp
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "backup_{$database}_{$timestamp}.sql";
            $path = "{$backupsPath}/{$filename}";
            
            // Commande mysqldump avec options optimisées
            $command = sprintf(
                'mysqldump -h %s -P %s -u %s -p%s --single-transaction --quick --lock-tables=false --routines --triggers %s > %s 2>&1',
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($path)
            );
            
            // Exécuter la commande
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0 && file_exists($path)) {
                // Vérifier la taille du fichier
                $fileSize = filesize($path);
                if ($fileSize === 0) {
                    $this->error('Le fichier de sauvegarde est vide.');
                    unlink($path);
                    return 1;
                }
                
                // Compresser le fichier
                $compressedPath = $path . '.gz';
                $this->info('Compression de la sauvegarde...');
                $data = file_get_contents($path);
                file_put_contents($compressedPath, gzencode($data, 9));
                
                // Supprimer le fichier non compressé
                unlink($path);
                
                $compressedSize = filesize($compressedPath);
                $this->info("Sauvegarde créée avec succès: " . basename($compressedPath));
                $this->info("Taille: " . $this->formatBytes($compressedSize));
                
                // Sauvegarder les fichiers si option --full
                if ($this->option('full')) {
                    $this->backupFiles($timestamp);
                }
                
                // Nettoyer les anciennes sauvegardes
                $this->cleanupOldBackups();
                
                // Logger le succès
                Log::info('Backup database réussi', [
                    'filename' => basename($compressedPath),
                    'size' => $compressedSize,
                ]);
                
                return 0;
            } else {
                $error = implode("\n", $output);
                $this->error('Erreur lors de la création de la sauvegarde: ' . $error);
                
                // Logger l'erreur
                Log::error('Échec du backup database', [
                    'error' => $error,
                    'return_code' => $returnCode,
                ]);
                
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('Erreur: ' . $e->getMessage());
            Log::error('Exception lors du backup', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 1;
        }
    }
    
    /**
     * Sauvegarder les fichiers importants
     */
    private function backupFiles(string $timestamp): void
    {
        $this->info('Sauvegarde des fichiers...');
        
        $filesToBackup = [
            storage_path('app/public') => 'storage',
            base_path('.env') => 'env',
        ];
        
        $backupsPath = storage_path('app/backups');
        $filesBackupPath = "{$backupsPath}/files_{$timestamp}.tar.gz";
        
        $files = [];
        foreach ($filesToBackup as $source => $name) {
            if (file_exists($source)) {
                $files[] = $source;
            }
        }
        
        if (!empty($files)) {
            $tarCommand = sprintf(
                'tar -czf %s %s 2>&1',
                escapeshellarg($filesBackupPath),
                implode(' ', array_map('escapeshellarg', $files))
            );
            
            exec($tarCommand, $output, $returnCode);
            
            if ($returnCode === 0) {
                $this->info("Fichiers sauvegardés: " . basename($filesBackupPath));
            } else {
                $this->warn("Erreur lors de la sauvegarde des fichiers: " . implode("\n", $output));
            }
        }
    }
    
    /**
     * Nettoyer les anciennes sauvegardes selon la stratégie de rétention
     */
    private function cleanupOldBackups(): void
    {
        $backupsPath = storage_path('app/backups');
        if (!is_dir($backupsPath)) {
            return;
        }
        
        $files = glob($backupsPath . '/backup_*.sql.gz');
        
        if (count($files) <= self::BACKUP_RETENTION_DAILY) {
            return;
        }
        
        // Trier par date de modification (plus récent en premier)
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        $now = time();
        $deleted = 0;
        
        foreach ($files as $index => $file) {
            $fileTime = filemtime($file);
            $daysOld = ($now - $fileTime) / 86400;
            
            // Garder les 7 derniers jours
            if ($index < self::BACKUP_RETENTION_DAILY) {
                continue;
            }
            
            // Garder une sauvegarde par semaine pendant 4 semaines
            if ($daysOld <= 28 && date('w', $fileTime) === '0') {
                continue;
            }
            
            // Garder une sauvegarde par mois pendant 6 mois
            if ($daysOld <= 180 && date('j', $fileTime) === '1') {
                continue;
            }
            
            // Supprimer les autres
            unlink($file);
            $deleted++;
        }
        
        if ($deleted > 0) {
            $this->info("{$deleted} ancienne(s) sauvegarde(s) supprimée(s).");
        }
    }
    
    /**
     * Formater les bytes en format lisible
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

