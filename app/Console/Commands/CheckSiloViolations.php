<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobArticle;

class CheckSiloViolations extends Command
{
    protected $signature = 'articles:check-silo
                            {--fix : Supprimer automatiquement les liens hors-silo}
                            {--limit=100 : Nombre d\'articles à analyser}';

    protected $description = 'Détecter (et optionnellement corriger) les violations de siloing dans les articles';

    // Liens autorisés depuis les articles /emplois
    private array $allowedPrefixes = [
        '/emplois',
        '/documents',
        '#',
    ];

    // Liens internes interdits (hors silo)
    private array $forbiddenPrefixes = [
        '/formations',
        '/exercices',
        '/epreuves',
        '/forum',
        '/dashboard',
    ];

    public function handle(): int
    {
        $fix   = $this->option('fix');
        $limit = (int) $this->option('limit');

        $this->info($fix ? '🔧 Détection ET correction des violations silo...' : '🔍 Détection des violations de siloing...');
        $this->newLine();

        $articles = JobArticle::whereNotNull('content')
            ->select('id', 'title', 'slug', 'content')
            ->limit($limit)
            ->get();

        $violations = [];

        foreach ($articles as $article) {
            $found = $this->findViolations($article->content);
            if (!empty($found)) {
                $violations[] = [
                    'id'     => $article->id,
                    'title'  => $article->title,
                    'slug'   => $article->slug,
                    'links'  => $found,
                    'model'  => $article,
                ];
            }
        }

        if (empty($violations)) {
            $this->info("✅ Aucune violation de siloing détectée sur {$articles->count()} articles analysés.");
            return 0;
        }

        $this->warn("⚠️  " . count($violations) . " article(s) avec violations de siloing :\n");

        foreach ($violations as $v) {
            $this->line("  📄 [#{$v['id']}] {$v['title']}");
            $this->line("     Slug : /emplois/article/{$v['slug']}");
            foreach ($v['links'] as $link) {
                $this->line("     ❌ Lien hors silo : {$link}");
            }
            $this->newLine();

            if ($fix) {
                $newContent = $this->removeViolationLinks($v['model']->content);
                $v['model']->update(['content' => $newContent]);
                $this->info("     ✅ Liens supprimés de l'article #{$v['id']}");
            }
        }

        if (!$fix) {
            $this->warn("Relancez avec --fix pour supprimer automatiquement ces liens.");
        }

        return 0;
    }

    private function findViolations(string $content): array
    {
        $violations = [];

        // Chercher tous les liens markdown et HTML
        preg_match_all('/href=["\']([^"\']+)["\']|\[([^\]]+)\]\(([^)]+)\)/', $content, $matches);

        $links = array_merge(
            array_filter($matches[1]),
            array_filter($matches[3])
        );

        foreach ($links as $link) {
            $link = trim($link);
            // Ignorer les liens externes et ancres
            if (str_starts_with($link, 'http') || str_starts_with($link, 'mailto') || $link === '#') {
                continue;
            }
            foreach ($this->forbiddenPrefixes as $prefix) {
                if (str_starts_with($link, $prefix)) {
                    $violations[] = $link;
                    break;
                }
            }
        }

        return array_unique($violations);
    }

    private function removeViolationLinks(string $content): string
    {
        foreach ($this->forbiddenPrefixes as $prefix) {
            // Supprimer les liens markdown : [texte](/formations/...) → texte
            $content = preg_replace(
                '/\[([^\]]+)\]\(' . preg_quote($prefix, '/') . '[^)]*\)/',
                '$1',
                $content
            );
            // Supprimer les liens HTML : <a href="/formations/...">texte</a> → texte
            $content = preg_replace(
                '/<a\s[^>]*href=["\']' . preg_quote($prefix, '/') . '[^"\']*["\'][^>]*>(.*?)<\/a>/is',
                '$1',
                $content
            );
        }
        return $content;
    }
}
