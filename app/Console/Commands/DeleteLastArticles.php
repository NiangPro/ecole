<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobArticle;

class DeleteLastArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:delete-last {count=7 : Nombre d\'articles à supprimer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprime les N derniers articles créés';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->argument('count');
        
        // Récupérer les N derniers articles
        $articles = JobArticle::orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->take($count)
            ->get();
        
        if ($articles->isEmpty()) {
            $this->info('Aucun article à supprimer.');
            return 0;
        }
        
        // Afficher les articles qui vont être supprimés
        $this->info("Les {$count} derniers articles à supprimer :");
        $this->newLine();
        
        $tableData = [];
        foreach ($articles as $article) {
            $tableData[] = [
                'ID' => $article->id,
                'Titre' => $article->title,
                'Créé le' => $article->created_at->format('d/m/Y H:i'),
                'Statut' => $article->status,
            ];
        }
        
        $this->table(['ID', 'Titre', 'Créé le', 'Statut'], $tableData);
        $this->newLine();
        
        // Demander confirmation
        if (!$this->confirm("Êtes-vous sûr de vouloir supprimer ces {$count} articles ?", true)) {
            $this->info('Suppression annulée.');
            return 0;
        }
        
        // Supprimer les articles
        $deleted = 0;
        foreach ($articles as $article) {
            try {
                $article->delete();
                $deleted++;
                $this->line("✓ Article supprimé : {$article->title} (ID: {$article->id})");
            } catch (\Exception $e) {
                $this->error("✗ Erreur lors de la suppression de l'article ID {$article->id}: {$e->getMessage()}");
            }
        }
        
        $this->newLine();
        $this->info("✅ {$deleted} article(s) supprimé(s) avec succès !");
        
        return 0;
    }
}
