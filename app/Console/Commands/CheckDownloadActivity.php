<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DownloadLog;

class CheckDownloadActivity extends Command
{
    protected $signature = 'downloads:check-suspicious {--hours=24 : Number of hours to check}';
    protected $description = 'Check for suspicious download activity';

    public function handle()
    {
        $hours = $this->option('hours');
        $suspicious = DownloadLog::getSuspiciousActivity($hours);

        if ($suspicious->isEmpty()) {
            $this->info('✓ Aucune activité suspecte détectée.');
            return 0;
        }

        $this->warn("⚠️  Activités suspectes détectées au cours des {$hours} dernières heures :");
        $this->newLine();

        $this->table(
            ['Identifiant (hash)', 'Tentatives', 'Dernière tentative'],
            $suspicious->map(fn($item) => [
                substr($item->identifier_hash, 0, 16) . '...',
                $item->count,
                $item->last_attempt->format('Y-m-d H:i:s'),
            ])->toArray()
        );

        return 0;
    }
}
