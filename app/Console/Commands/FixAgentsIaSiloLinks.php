<?php

namespace App\Console\Commands;

use App\Models\JobArticle;
use Illuminate\Console\Command;

class FixAgentsIaSiloLinks extends Command
{
    /**
     * Correctif ponctuel (audit SEO juin-juillet 2026) : l'article "Les 7 meilleurs
     * agents IA pour développeurs en 2026" (silo Carrière/Emplois) mentionne dans son
     * contenu deux items topiquement rattachés au silo Éducation Tech ("Formation
     * JavaScript gratuite pour débutants", "Apprendre PHP et Laravel en Afrique") —
     * en base ce sont actuellement de simples mentions texte (pas de <a href>), mais
     * si elles ont pu être des liens vers /formations/javascript et /formations/php
     * à un moment donné (ou le redeviennent via un futur éditeur WYSIWYG), ce
     * correctif les remplace par des liens internes du même silo (Emplois).
     */
    protected $signature = 'articles:fix-agents-ia-silo';

    protected $description = 'Remplace les liens/mentions hors-silo dans l\'article "Agents IA" par des liens Emplois';

    public function handle(): int
    {
        $article = JobArticle::where('slug', 'les-7-meilleurs-agents-ia-pour-developpeurs-en-2026')->first();

        if (!$article) {
            $this->error('Article introuvable (slug: les-7-meilleurs-agents-ia-pour-developpeurs-en-2026).');
            return self::FAILURE;
        }

        $content = $article->content;
        $original = $content;

        $replacements = [
            // Cas 1 : mention texte simple (état actuel constaté en production)
            '/(<li>)\s*→\s*Formation JavaScript gratuite pour débutants\s*(<\/li>)/u'
                => '$1<a href="https://www.niangprogrammeur.com/emplois/offres?category=conseils-carriere">→ Conseils carrière pour développeurs</a>$2',
            '/(<li>)\s*→\s*Apprendre PHP et Laravel en Afrique\s*(<\/li>)/u'
                => '$1<a href="https://www.niangprogrammeur.com/emplois/offres?category=opportunites-professionnelles">→ Opportunités professionnelles tech au Sénégal</a>$2',
            // Cas 2 : si un lien vers /formations/javascript ou /formations/php existe déjà
            '/<a\s+[^>]*href="[^"]*\/formations\/javascript"[^>]*>.*?<\/a>/is'
                => '<a href="https://www.niangprogrammeur.com/emplois/offres?category=conseils-carriere">→ Conseils carrière pour développeurs</a>',
            '/<a\s+[^>]*href="[^"]*\/formations\/php"[^>]*>.*?<\/a>/is'
                => '<a href="https://www.niangprogrammeur.com/emplois/offres?category=opportunites-professionnelles">→ Opportunités professionnelles tech au Sénégal</a>',
        ];

        foreach ($replacements as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }

        if ($content === $original) {
            $this->warn('Aucune occurrence trouvée à remplacer — le contenu ne correspond à aucun des motifs attendus. Vérifiez manuellement.');
            return self::FAILURE;
        }

        $article->content = $content;
        $article->save();

        $this->info('Liens hors-silo remplacés avec succès dans l\'article "Agents IA".');

        return self::SUCCESS;
    }
}
