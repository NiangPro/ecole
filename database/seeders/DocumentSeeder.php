<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DocumentSeeder extends Seeder
{
    /**
     * Exécuter le seeder
     */
    public function run(): void
    {
        // Vérifier si des catégories existent, sinon en créer
        $categories = DocumentCategory::all();
        if ($categories->isEmpty()) {
            $this->command->info('Création de catégories de documents...');
            $categories = $this->createCategories();
        }

        // Vérifier si des utilisateurs existent pour les auteurs
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->warn('Aucun utilisateur trouvé. Veuillez créer au moins un utilisateur avant de lancer ce seeder.');
            return;
        }

        $this->command->info('Création de documents...');

        // Documents de formation et tutoriels
        $formationDocs = [
            [
                'title' => 'Guide Complet Laravel 11 - Débutant à Avancé',
                'description' => 'Un guide complet pour maîtriser Laravel 11, du niveau débutant au niveau avancé. Inclut des exemples pratiques, des bonnes pratiques et des cas d\'usage réels.',
                'excerpt' => 'Apprenez Laravel 11 de A à Z avec ce guide complet de 150 pages.',
                'price' => 5000,
                'discount_price' => 3500,
                'is_featured' => true,
                'tags' => ['laravel', 'php', 'framework', 'backend', 'tutoriel'],
                'file_extension' => 'pdf',
                'file_type' => 'application/pdf',
                'file_size' => 5242880, // 5 MB
            ],
            [
                'title' => 'JavaScript Moderne - ES6+ et Au-delà',
                'description' => 'Maîtrisez JavaScript moderne avec les fonctionnalités ES6+, async/await, les modules, et les dernières nouveautés du langage.',
                'excerpt' => 'Guide complet sur JavaScript moderne et les fonctionnalités avancées.',
                'price' => 4000,
                'discount_price' => null,
                'is_featured' => true,
                'tags' => ['javascript', 'es6', 'frontend', 'programmation'],
                'file_extension' => 'pdf',
                'file_type' => 'application/pdf',
                'file_size' => 4194304, // 4 MB
            ],
            [
                'title' => 'React.js - Développement d\'Applications Modernes',
                'description' => 'Créez des applications React modernes avec hooks, context API, et les meilleures pratiques de développement.',
                'excerpt' => 'Apprenez React.js pour créer des interfaces utilisateur modernes.',
                'price' => 6000,
                'discount_price' => 4500,
                'is_featured' => false,
                'tags' => ['react', 'javascript', 'frontend', 'ui', 'framework'],
                'file_extension' => 'pdf',
                'file_type' => 'application/pdf',
                'file_size' => 6291456, // 6 MB
            ],
            [
                'title' => 'Vue.js 3 - Guide Pratique',
                'description' => 'Découvrez Vue.js 3 avec la Composition API, les composables, et les nouvelles fonctionnalités.',
                'excerpt' => 'Guide pratique pour maîtriser Vue.js 3 et créer des applications réactives.',
                'price' => 4500,
                'discount_price' => null,
                'is_featured' => false,
                'tags' => ['vuejs', 'javascript', 'frontend', 'framework'],
                'file_extension' => 'pdf',
                'file_type' => 'application/pdf',
                'file_size' => 4718592, // 4.5 MB
            ],
        ];

        // Documents techniques et références
        $technicalDocs = [
            [
                'title' => 'API REST - Conception et Bonnes Pratiques',
                'description' => 'Guide complet sur la conception d\'API REST, les bonnes pratiques, la sécurité, et la documentation.',
                'excerpt' => 'Maîtrisez la conception d\'API REST professionnelles.',
                'price' => 3500,
                'discount_price' => 2500,
                'is_featured' => false,
                'tags' => ['api', 'rest', 'backend', 'architecture'],
                'file_extension' => 'pdf',
                'file_type' => 'application/pdf',
                'file_size' => 3145728, // 3 MB
            ],
            [
                'title' => 'Base de Données MySQL - Optimisation et Performance',
                'description' => 'Techniques avancées d\'optimisation MySQL, indexation, requêtes performantes, et gestion des transactions.',
                'excerpt' => 'Optimisez vos bases de données MySQL pour de meilleures performances.',
                'price' => 4000,
                'discount_price' => null,
                'is_featured' => false,
                'tags' => ['mysql', 'database', 'optimisation', 'sql'],
                'file_extension' => 'pdf',
                'file_type' => 'application/pdf',
                'file_size' => 3670016, // 3.5 MB
            ],
            [
                'title' => 'Docker et Kubernetes - Guide de Déploiement',
                'description' => 'Apprenez à containeriser vos applications avec Docker et à les orchestrer avec Kubernetes.',
                'excerpt' => 'Maîtrisez Docker et Kubernetes pour le déploiement moderne.',
                'price' => 5500,
                'discount_price' => 4000,
                'is_featured' => true,
                'tags' => ['docker', 'kubernetes', 'devops', 'deployment'],
                'file_extension' => 'pdf',
                'file_type' => 'application/pdf',
                'file_size' => 5767168, // 5.5 MB
            ],
        ];

        // Documents de design et UI/UX
        $designDocs = [
            [
                'title' => 'UI/UX Design - Principes et Méthodologies',
                'description' => 'Guide complet sur le design d\'interface utilisateur et l\'expérience utilisateur avec des exemples pratiques.',
                'excerpt' => 'Apprenez les principes fondamentaux du design UI/UX.',
                'price' => 4500,
                'discount_price' => null,
                'is_featured' => false,
                'tags' => ['design', 'ui', 'ux', 'interface'],
                'file_extension' => 'pdf',
                'file_type' => 'application/pdf',
                'file_size' => 4194304, // 4 MB
            ],
            [
                'title' => 'Figma - Maîtrise du Design Collaboratif',
                'description' => 'Apprenez à utiliser Figma pour créer des designs collaboratifs et des prototypes interactifs.',
                'excerpt' => 'Maîtrisez Figma pour le design collaboratif.',
                'price' => 3500,
                'discount_price' => 2500,
                'is_featured' => false,
                'tags' => ['figma', 'design', 'outils', 'prototype'],
                'file_extension' => 'pdf',
                'file_type' => 'application/pdf',
                'file_size' => 3145728, // 3 MB
            ],
        ];

        // Documents de sécurité
        $securityDocs = [
            [
                'title' => 'Sécurité Web - Protection contre les Vulnérabilités',
                'description' => 'Guide complet sur la sécurité web : OWASP Top 10, injection SQL, XSS, CSRF, et bonnes pratiques.',
                'excerpt' => 'Protégez vos applications web contre les vulnérabilités courantes.',
                'price' => 5000,
                'discount_price' => 3500,
                'is_featured' => true,
                'tags' => ['sécurité', 'web', 'owasp', 'protection'],
                'file_extension' => 'pdf',
                'file_type' => 'application/pdf',
                'file_size' => 5242880, // 5 MB
            ],
            [
                'title' => 'Authentification et Autorisation - JWT et OAuth 2.0',
                'description' => 'Implémentez des systèmes d\'authentification sécurisés avec JWT, OAuth 2.0, et les meilleures pratiques.',
                'excerpt' => 'Maîtrisez l\'authentification et l\'autorisation modernes.',
                'price' => 4500,
                'discount_price' => null,
                'is_featured' => false,
                'tags' => ['authentification', 'jwt', 'oauth', 'sécurité'],
                'file_extension' => 'pdf',
                'file_type' => 'application/pdf',
                'file_size' => 4194304, // 4 MB
            ],
        ];

        // Combiner tous les documents
        $allDocs = array_merge($formationDocs, $technicalDocs, $designDocs, $securityDocs);

        // Créer les documents
        foreach ($allDocs as $index => $docData) {
            // Sélectionner une catégorie aléatoire
            $category = $categories->random();
            
            // Sélectionner un auteur aléatoire
            $author = $users->random();

            // Générer le slug
            $slug = Str::slug($docData['title']);
            $baseSlug = $slug;
            $counter = 1;
            while (Document::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            // Générer le nom de fichier
            $fileName = $slug . '.' . $docData['file_extension'];
            $filePath = 'documents/' . date('Y/m') . '/' . $fileName;

            // Déterminer le statut (la plupart publiés, quelques-uns en brouillon)
            $status = $index < 8 ? 'published' : ($index < 10 ? 'draft' : 'published');
            
            // Date de publication (aléatoire dans les 30 derniers jours pour les publiés)
            $publishedAt = $status === 'published' 
                ? Carbon::now()->subDays(rand(0, 30)) 
                : null;

            // Générer une image de couverture (utiliser une URL externe ou interne)
            $coverImage = $this->getCoverImage($index);
            $coverType = $index % 3 === 0 ? 'external' : 'internal';

            // Créer le document
            Document::create([
                'title' => $docData['title'],
                'slug' => $slug,
                'description' => $docData['description'],
                'excerpt' => $docData['excerpt'],
                'category_id' => $category->id,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'file_size' => $docData['file_size'],
                'file_type' => $docData['file_type'],
                'file_extension' => $docData['file_extension'],
                'cover_image' => $coverImage,
                'cover_type' => $coverType,
                'price' => $docData['price'],
                'discount_price' => $docData['discount_price'],
                'is_featured' => $docData['is_featured'],
                'is_active' => true,
                'status' => $status,
                'download_count' => rand(0, 150),
                'sales_count' => rand(0, 100),
                'views_count' => rand(50, 500),
                'author_id' => $author->id,
                'tags' => $docData['tags'],
                'meta_title' => $docData['title'] . ' | NiangProgrammeur',
                'meta_description' => $docData['excerpt'],
                'meta_keywords' => implode(', ', $docData['tags']),
                'published_at' => $publishedAt,
            ]);

            $this->command->info("Document créé : {$docData['title']}");
        }

        $this->command->info('✅ ' . count($allDocs) . ' documents créés avec succès !');
    }

    /**
     * Créer des catégories de documents si elles n'existent pas
     */
    private function createCategories(): \Illuminate\Database\Eloquent\Collection
    {
        $categories = [
            [
                'name' => 'Formations et Tutoriels',
                'description' => 'Guides complets et tutoriels pour apprendre les technologies web',
                'icon' => 'fas fa-graduation-cap',
                'order' => 1,
            ],
            [
                'name' => 'Technique et Référence',
                'description' => 'Documents techniques, références et guides avancés',
                'icon' => 'fas fa-book',
                'order' => 2,
            ],
            [
                'name' => 'Design et UI/UX',
                'description' => 'Ressources sur le design, l\'interface utilisateur et l\'expérience utilisateur',
                'icon' => 'fas fa-palette',
                'order' => 3,
            ],
            [
                'name' => 'Sécurité',
                'description' => 'Guides sur la sécurité web et la protection des applications',
                'icon' => 'fas fa-shield-alt',
                'order' => 4,
            ],
        ];

        $createdCategories = collect();

        foreach ($categories as $catData) {
            $category = DocumentCategory::create($catData);
            $createdCategories->push($category);
            $this->command->info("Catégorie créée : {$catData['name']}");
        }

        return $createdCategories;
    }

    /**
     * Obtenir une image de couverture (URL externe ou chemin interne)
     */
    private function getCoverImage(int $index): ?string
    {
        // Images de couverture externes (Unsplash)
        $externalImages = [
            'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1504639725590-04d4ef0a1ee7?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1517180102446-f3ece451e9d8?w=800&h=600&fit=crop',
        ];

        // Images internes (chemins fictifs)
        $internalImages = [
            'documents/covers/laravel-guide.jpg',
            'documents/covers/javascript-modern.jpg',
            'documents/covers/react-apps.jpg',
            'documents/covers/vuejs-guide.jpg',
            'documents/covers/api-rest.jpg',
        ];

        // Alterner entre externe et interne
        if ($index % 2 === 0) {
            return $externalImages[$index % count($externalImages)];
        } else {
            return $internalImages[$index % count($internalImages)];
        }
    }
}

