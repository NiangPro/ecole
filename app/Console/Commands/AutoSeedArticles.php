<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobArticle;
use App\Models\Category;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AutoSeedArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:seed 
                            {--count=5 : Nombre d\'articles à créer}
                            {--category= : ID de la catégorie (optionnel)}
                            {--days=3 : Nombre de jours dans le passé pour published_at}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crée automatiquement des articles d\'emploi avec des données réalistes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = (int) $this->option('count');
        $categoryId = $this->option('category');
        $daysAgo = (int) $this->option('days');

        $this->info("🚀 Démarrage de la création de {$count} article(s)...");

        // Récupérer les catégories actives
        if ($categoryId) {
            $category = Category::find($categoryId);
            if (!$category || !$category->is_active) {
                $this->error("❌ Catégorie ID {$categoryId} introuvable ou inactive.");
                return 1;
            }
            $categories = collect([$category]);
        } else {
            $categories = Category::where('is_active', true)->get();
            if ($categories->isEmpty()) {
                $this->error("❌ Aucune catégorie active trouvée. Veuillez créer des catégories d'abord.");
                return 1;
            }
        }

        // Images d'exemple
        $images = [
            'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=1200&h=630&fit=crop',
            'https://images.unsplash.com/photo-1551434678-e076c223a692?w=1200&h=630&fit=crop',
            'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=1200&h=630&fit=crop',
            'https://images.unsplash.com/photo-1552664730-d307ca884978?w=1200&h=630&fit=crop',
            'https://images.unsplash.com/photo-1556761175-4b46a572b786?w=1200&h=630&fit=crop',
        ];

        // Templates d'articles
        $templates = [
            [
                'title_prefix' => 'Recrutement',
                'title_suffix' => 'Opportunités au Sénégal',
                'content_template' => '<h2>Le Marché de l\'Emploi au Sénégal</h2><p>Le secteur recrute activement des professionnels qualifiés pour renforcer ses équipes.</p><h3>Postes Disponibles</h3><ul><li><strong>Poste 1</strong> : Description du poste</li><li><strong>Poste 2</strong> : Description du poste</li></ul><h3>Compétences Requises</h3><p>Les candidats doivent posséder une solide expérience et des compétences techniques avérées.</p>',
            ],
            [
                'title_prefix' => 'Offres d\'Emploi',
                'title_suffix' => 'Carrières au Sénégal',
                'content_template' => '<h2>Opportunités de Carrière</h2><p>Rejoignez une équipe dynamique et développez vos compétences dans un environnement stimulant.</p><h3>Avantages</h3><ul><li>Environnement de travail moderne</li><li>Formation continue</li><li>Évolution de carrière</li></ul>',
            ],
            [
                'title_prefix' => 'Emploi',
                'title_suffix' => 'Recrutement Actif',
                'content_template' => '<h2>Rejoignez Notre Équipe</h2><p>Nous recherchons des talents pour accompagner notre croissance.</p><h3>Profil Recherché</h3><p>Nous cherchons des profils expérimentés avec une forte motivation.</p>',
            ],
        ];

        $created = 0;
        $imageIndex = 0;

        for ($i = 0; $i < $count; $i++) {
            $category = $categories[$i % $categories->count()];
            $template = $templates[$i % count($templates)];

            // Générer un titre unique
            $title = "{$template['title_prefix']} {$category->name} : {$template['title_suffix']} " . date('Y');
            $slug = Str::slug($title) . '-' . time() . '-' . ($i + 1);

            // Générer un excerpt
            $excerpt = "Découvrez les meilleures opportunités d'emploi dans le secteur {$category->name} au Sénégal. Postes disponibles avec des conditions attractives.";

            // Générer le contenu
            $content = str_replace('{category}', $category->name, $template['content_template']);

            // Générer des mots-clés
            $keywords = [
                strtolower($category->name),
                'emploi Sénégal',
                'recrutement',
                'offres d\'emploi',
                'carrière',
            ];

            // Date de publication (dans le passé)
            $publishedAt = Carbon::now()->subDays(rand(0, $daysAgo));

            try {
                JobArticle::create([
                    'title' => $title,
                    'slug' => $slug,
                    'excerpt' => $excerpt,
                    'content' => $content,
                    'category_id' => $category->id,
                    'cover_image' => $images[$imageIndex % count($images)],
                    'cover_type' => 'external',
                    'status' => 'published',
                    'published_at' => $publishedAt,
                    'meta_title' => $title,
                    'meta_description' => $excerpt,
                    'meta_keywords' => $keywords,
                    'seo_score' => rand(85, 100),
                    'readability_score' => rand(80, 100),
                    'views' => rand(10, 200),
                    'created_at' => $publishedAt,
                    'updated_at' => $publishedAt,
                ]);

                $created++;
                $this->info("✅ Article créé : {$title}");
                $imageIndex++;
            } catch (\Exception $e) {
                $this->error("❌ Erreur lors de la création de l'article : " . $e->getMessage());
            }
        }

        $this->info("\n🎉 {$created} article(s) créé(s) avec succès !");
        return 0;
    }
}

