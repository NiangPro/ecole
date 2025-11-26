<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobArticle;
use App\Models\Category;

class CheckAdSenseRequirements extends Command
{
    protected $signature = 'adsense:check';
    protected $description = 'Vérifie les exigences pour l\'acceptation Google AdSense';

    public function handle()
    {
        $this->info('=== VÉRIFICATION DES EXIGENCES GOOGLE ADSENSE ===');
        $this->newLine();

        // 1. Vérifier le nombre d'articles
        $publishedArticles = JobArticle::where('status', 'published')->count();
        $this->info("1. Articles publiés : {$publishedArticles}");
        
        if ($publishedArticles >= 30) {
            $this->line("   ✅ Suffisant (minimum 30 requis)");
        } else {
            $this->error("   ❌ Insuffisant (minimum 30 requis, {$publishedArticles} actuellement)");
        }
        $this->newLine();

        // 2. Vérifier la longueur moyenne des articles
        $avgLengthResult = JobArticle::where('status', 'published')
            ->selectRaw('AVG(CHAR_LENGTH(content)) as avg_length')
            ->first();
        $avgLength = round($avgLengthResult->avg_length ?? 0);
        $this->info("2. Longueur moyenne des articles : {$avgLength} caractères");
        
        if ($avgLength >= 2500) { // ~500 mots
            $this->line("   ✅ Articles de bonne longueur (minimum 500 mots recommandé)");
        } else {
            $this->warn("   ⚠️  Essayez d'augmenter la longueur des articles (minimum 500 mots recommandé)");
        }
        $this->newLine();

        // 3. Vérifier les catégories actives
        $activeCategories = Category::where('is_active', true)->count();
        $this->info("3. Catégories actives : {$activeCategories}");
        $this->line("   ✅ OK");
        $this->newLine();

        // 4. Vérifier les articles récents (30 derniers jours)
        $recentArticles = JobArticle::where('status', 'published')
            ->whereDate('published_at', '>=', now()->subDays(30))
            ->count();
        $this->info("4. Articles publiés dans les 30 derniers jours : {$recentArticles}");
        
        if ($recentArticles >= 5) {
            $this->line("   ✅ Bon rythme de publication");
        } else {
            $this->warn("   ⚠️  Essayez de publier plus régulièrement (5+ articles par mois recommandé)");
        }
        $this->newLine();

        // 5. Vérifier les vues totales
        $totalViews = JobArticle::where('status', 'published')->sum('views');
        $this->info("5. Vues totales : {$totalViews}");
        $this->line("   ✅ OK");
        $this->newLine();

        // 6. Vérifier les pages légales
        $this->info("6. Pages légales :");
        $privacyExists = file_exists(resource_path('views/privacy-policy.blade.php'));
        $legalExists = file_exists(resource_path('views/legal.blade.php'));
        $termsExists = file_exists(resource_path('views/terms.blade.php'));
        
        $this->line("   - Mentions légales : " . ($legalExists ? "✅" : "❌"));
        $this->line("   - Politique de confidentialité : " . ($privacyExists ? "✅" : "❌"));
        $this->line("   - Conditions d'utilisation : " . ($termsExists ? "✅" : "❌"));
        
        if ($privacyExists && $legalExists && $termsExists) {
            $this->line("   ✅ Toutes les pages existent");
        } else {
            $this->error("   ❌ Certaines pages manquent");
        }
        $this->newLine();

        // 7. Vérifier sitemap et robots.txt
        $this->info("7. Fichiers SEO :");
        $sitemapExists = file_exists(public_path('sitemap.xml'));
        $robotsExists = file_exists(public_path('robots.txt'));
        $this->line("   - Sitemap.xml : " . ($sitemapExists ? "✅" : "❌"));
        $this->line("   - Robots.txt : " . ($robotsExists ? "✅" : "❌"));
        $this->newLine();

        // 8. Résumé
        $this->info("=== RÉSUMÉ ===");
        
        $issues = [];
        $warnings = [];
        
        if ($publishedArticles < 30) {
            $issues[] = "Publiez au moins " . (30 - $publishedArticles) . " articles supplémentaires";
        }
        if ($avgLength < 2500) {
            $warnings[] = "Augmentez la longueur des articles (minimum 500 mots recommandé)";
        }
        if ($recentArticles < 5) {
            $warnings[] = "Publiez plus régulièrement (5+ articles par mois recommandé)";
        }
        if (!$privacyExists || !$legalExists || !$termsExists) {
            $issues[] = "Complétez toutes les pages légales";
        }

        if (empty($issues) && empty($warnings)) {
            $this->info("✅ Votre site semble prêt pour AdSense !");
            $this->newLine();
            $this->line("Actions recommandées :");
            $this->line("1. Continuez à publier du contenu régulièrement");
            $this->line("2. Optimisez vos articles pour le SEO");
            $this->line("3. Partagez vos articles sur les réseaux sociaux");
            $this->line("4. Testez la vitesse avec PageSpeed Insights");
            $this->line("5. Attendez patiemment la réponse d'AdSense (1-14 jours)");
        } else {
            if (!empty($issues)) {
                $this->error("❌ Actions requises :");
                foreach ($issues as $issue) {
                    $this->line("   - {$issue}");
                }
                $this->newLine();
            }
            if (!empty($warnings)) {
                $this->warn("⚠️  Améliorations recommandées :");
                foreach ($warnings as $warning) {
                    $this->line("   - {$warning}");
                }
            }
        }

        return 0;
    }
}

