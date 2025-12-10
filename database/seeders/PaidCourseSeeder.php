<?php

namespace Database\Seeders;

use App\Models\PaidCourse;
use App\Models\CourseChapter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PaidCourseSeeder extends Seeder
{
    /**
     * Exécuter le seeder pour créer des cours payants complets
     */
    public function run(): void
    {
        // Supprimer tous les cours payants existants (et leurs chapitres via cascade)
        $this->command->info('🧹 Suppression des cours payants existants...');
        DB::table('course_chapters')->delete();
        DB::table('paid_courses')->delete();
        $this->command->info('✅ Anciens cours supprimés');

        $courses = [
            [
                'title' => 'Formation Complète Laravel 11',
                'description' => 'Maîtrisez Laravel 11 de A à Z : routes, contrôleurs, modèles, migrations, relations Eloquent, authentification, API REST, tests et déploiement. Devenez un expert du framework PHP le plus populaire.',
                'content' => 'Cette formation complète vous permettra de maîtriser le framework Laravel 11, le framework PHP le plus populaire au monde. Vous apprendrez à créer des applications web modernes, sécurisées et performantes.

## Pourquoi Laravel ?

Laravel est le framework PHP le plus utilisé au monde, choisi par des millions de développeurs pour sa simplicité, sa puissance et sa documentation exceptionnelle. Avec cette formation, vous maîtriserez tous les aspects essentiels du framework.

## Ce que vous allez apprendre

- Architecture MVC et structure Laravel
- Routes, contrôleurs et middleware
- Eloquent ORM et relations
- Authentification et autorisation
- API REST complètes
- Tests automatisés
- Déploiement en production',
                'cover_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&h=600&fit=crop',
                'cover_type' => 'external',
                'price' => 50000,
                'currency' => 'XOF',
                'discount_price' => 35000,
                'discount_start' => now(),
                'discount_end' => now()->addDays(30),
                'status' => 'published',
                'duration_hours' => 40,
                'what_you_learn' => [
                    'Créer des applications web avec Laravel 11',
                    'Gérer les bases de données avec Eloquent ORM',
                    'Implémenter l\'authentification et l\'autorisation',
                    'Développer des API REST complètes',
                    'Écrire des tests automatisés',
                    'Déployer des applications en production',
                    'Optimiser les performances',
                    'Sécuriser vos applications'
                ],
                'requirements' => [
                    'Connaissances de base en PHP',
                    'Compréhension du HTML/CSS',
                    'Notions de base de données',
                    'Environnement de développement configuré'
                ],
                'chapters' => [
                    [
                        'title' => 'Introduction à Laravel 11',
                        'description' => 'Découvrez Laravel, son écosystème et installez votre premier projet',
                        'content' => 'Dans ce chapitre, nous allons découvrir Laravel 11, comprendre son architecture et installer notre premier projet.

## Installation de Laravel

Laravel utilise Composer pour la gestion des dépendances. Nous verrons comment installer Laravel via Composer et créer un nouveau projet.

## Structure d\'un projet Laravel

Un projet Laravel suit une architecture MVC (Model-View-Controller) bien définie. Nous explorerons chaque dossier et son rôle dans l\'application.

## Artisan CLI

Artisan est l\'outil en ligne de commande de Laravel. Nous apprendrons les commandes essentielles pour développer efficacement.',
                        'duration_minutes' => 90
                    ],
                    [
                        'title' => 'Routes et Contrôleurs',
                        'description' => 'Maîtrisez le système de routage et la création de contrôleurs',
                        'content' => 'Les routes sont le point d\'entrée de votre application Laravel. Nous verrons comment définir des routes, créer des contrôleurs et organiser votre code.

## Définition des routes

Laravel offre plusieurs façons de définir des routes : dans le fichier routes/web.php, routes/api.php, ou via des route groups.

## Contrôleurs

Les contrôleurs organisent la logique de votre application. Nous créerons des contrôleurs et apprendrons les meilleures pratiques.

## Middleware

Les middleware permettent d\'exécuter du code avant ou après une requête. Nous verrons comment créer et utiliser des middleware personnalisés.',
                        'duration_minutes' => 120
                    ],
                    [
                        'title' => 'Base de données et Eloquent ORM',
                        'description' => 'Apprenez à gérer les bases de données avec les migrations et Eloquent',
                        'content' => 'Laravel facilite grandement la gestion des bases de données grâce aux migrations et à Eloquent ORM.

## Migrations

Les migrations sont comme un contrôle de version pour votre base de données. Nous créerons des migrations pour définir la structure de nos tables.

## Modèles Eloquent

Eloquent est l\'ORM de Laravel. Nous apprendrons à créer des modèles, définir des relations et effectuer des requêtes complexes.

## Relations Eloquent

Les relations permettent de lier vos modèles entre eux. Nous verrons hasMany, belongsTo, manyToMany et bien plus.',
                        'duration_minutes' => 180
                    ],
                    [
                        'title' => 'Authentification et Autorisation',
                        'description' => 'Implémentez un système complet d\'authentification et de gestion des rôles',
                        'content' => 'Laravel fournit un système d\'authentification complet prêt à l\'emploi. Nous verrons comment l\'utiliser et le personnaliser.

## Authentification Laravel

Laravel Breeze et Laravel Jetstream sont des packages qui fournissent une authentification complète. Nous explorerons ces solutions.

## Gestion des rôles

Nous créerons un système de rôles et permissions pour contrôler l\'accès aux différentes parties de votre application.

## API Tokens

Pour les API, nous utiliserons Sanctum pour générer des tokens d\'authentification sécurisés.',
                        'duration_minutes' => 150
                    ],
                    [
                        'title' => 'API REST avec Laravel',
                        'description' => 'Créez des API RESTful complètes et professionnelles',
                        'content' => 'Laravel excelle dans la création d\'API REST. Nous créerons une API complète avec validation, transformation et pagination.

## Création d\'API RESTful

Nous définirons des routes API, créerons des contrôleurs API et implémenterons les méthodes CRUD.

## Validation des données

Laravel offre un système de validation puissant. Nous verrons comment valider les données entrantes.

## Resources API

Les API Resources permettent de transformer vos modèles en JSON de manière cohérente.',
                        'duration_minutes' => 160
                    ],
                    [
                        'title' => 'Tests et Déploiement',
                        'description' => 'Apprenez à tester votre application et la déployer en production',
                        'content' => 'Les tests sont essentiels pour maintenir la qualité de votre code. Nous verrons comment écrire des tests avec PHPUnit.

## Tests unitaires

Nous écriremos des tests unitaires pour tester les fonctions individuelles de votre application.

## Tests fonctionnels

Les tests fonctionnels testent le comportement complet de votre application.

## Déploiement

Nous verrons comment déployer votre application Laravel sur un serveur de production avec les meilleures pratiques.',
                        'duration_minutes' => 140
                    ]
                ]
            ],
            [
                'title' => 'Formation React Avancé avec TypeScript',
                'description' => 'Développez des applications React modernes avec TypeScript, hooks avancés, context API, Redux, tests et optimisations. Créez des interfaces utilisateur performantes et maintenables.',
                'content' => 'Formation complète sur React avec TypeScript pour créer des applications frontend performantes et maintenables. Maîtrisez les concepts avancés de React et TypeScript.',
                'cover_image' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=800&h=600&fit=crop',
                'cover_type' => 'external',
                'price' => 45000,
                'currency' => 'XOF',
                'status' => 'published',
                'duration_hours' => 35,
                'what_you_learn' => [
                    'Maîtriser React avec TypeScript',
                    'Utiliser les hooks avancés',
                    'Gérer l\'état avec Redux',
                    'Créer des composants réutilisables',
                    'Tester vos composants',
                    'Optimiser les performances'
                ],
                'requirements' => [
                    'Connaissances en JavaScript ES6+',
                    'Notions de base en HTML/CSS',
                    'Node.js installé'
                ],
                'chapters' => [
                    [
                        'title' => 'Introduction à React et TypeScript',
                        'description' => 'Découvrez React et TypeScript, configurez votre environnement',
                        'content' => 'Nous commencerons par comprendre React et TypeScript, puis configurerons notre environnement de développement.',
                        'duration_minutes' => 60
                    ],
                    [
                        'title' => 'Composants et Props avec TypeScript',
                        'description' => 'Créez vos premiers composants typés avec TypeScript',
                        'content' => 'Apprenez à créer des composants React typés avec TypeScript pour une meilleure maintenabilité.',
                        'duration_minutes' => 90
                    ],
                    [
                        'title' => 'Hooks Avancés',
                        'description' => 'Maîtrisez useState, useEffect, useContext et les hooks personnalisés',
                        'content' => 'Les hooks sont la façon moderne de gérer l\'état et les effets de bord dans React.',
                        'duration_minutes' => 120
                    ],
                    [
                        'title' => 'Gestion d\'état avec Redux',
                        'description' => 'Implémentez Redux pour gérer l\'état global de votre application',
                        'content' => 'Redux permet de gérer l\'état de manière prévisible dans vos applications React.',
                        'duration_minutes' => 150
                    ],
                    [
                        'title' => 'Tests avec Jest et React Testing Library',
                        'description' => 'Écrivez des tests complets pour vos composants React',
                        'content' => 'Les tests garantissent la qualité et la maintenabilité de votre code.',
                        'duration_minutes' => 100
                    ]
                ]
            ],
            [
                'title' => 'Formation Flutter pour le Mobile',
                'description' => 'Développez des applications mobiles cross-platform avec Flutter et Dart. Créez des apps iOS et Android avec un seul code source, des performances natives et une UI magnifique.',
                'content' => 'Formation complète sur Flutter pour le développement mobile cross-platform. Créez des applications natives pour iOS et Android avec un seul code.',
                'cover_image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=800&h=600&fit=crop',
                'cover_type' => 'external',
                'price' => 48000,
                'currency' => 'XOF',
                'status' => 'published',
                'duration_hours' => 38,
                'what_you_learn' => [
                    'Créer des applications Flutter',
                    'Utiliser les widgets Flutter',
                    'Gérer l\'état avec Provider',
                    'Accéder aux APIs',
                    'Publier sur les stores',
                    'Optimiser les performances'
                ],
                'requirements' => [
                    'Connaissances de base en programmation',
                    'Flutter SDK installé',
                    'Émulateur ou appareil mobile'
                ],
                'chapters' => [
                    [
                        'title' => 'Introduction à Flutter et Dart',
                        'description' => 'Découvrez Flutter, Dart et configurez votre environnement',
                        'content' => 'Nous commencerons par comprendre Flutter, le langage Dart, et configurerons notre environnement de développement.',
                        'duration_minutes' => 75
                    ],
                    [
                        'title' => 'Widgets et UI Flutter',
                        'description' => 'Maîtrisez les widgets Flutter pour créer des interfaces utilisateur',
                        'content' => 'Flutter utilise un système de widgets pour construire l\'interface utilisateur. Nous explorerons les widgets essentiels.',
                        'duration_minutes' => 120
                    ],
                    [
                        'title' => 'Gestion d\'état avec Provider',
                        'description' => 'Apprenez à gérer l\'état de votre application avec Provider',
                        'content' => 'Provider est une solution simple et efficace pour gérer l\'état dans Flutter.',
                        'duration_minutes' => 100
                    ],
                    [
                        'title' => 'Navigation et Routing',
                        'description' => 'Implémentez la navigation entre les écrans de votre application',
                        'content' => 'La navigation est essentielle dans toute application mobile. Nous verrons comment naviguer entre les écrans.',
                        'duration_minutes' => 90
                    ],
                    [
                        'title' => 'Accès aux APIs et données',
                        'description' => 'Connectez votre application à des APIs REST',
                        'content' => 'Nous apprendrons à faire des requêtes HTTP, parser le JSON et gérer les erreurs.',
                        'duration_minutes' => 110
                    ],
                    [
                        'title' => 'Publication sur les stores',
                        'description' => 'Publiez votre application sur Google Play et App Store',
                        'content' => 'Nous verrons comment préparer et publier votre application sur les stores officiels.',
                        'duration_minutes' => 85
                    ]
                ]
            ],
            [
                'title' => 'Formation Python pour la Data Science',
                'description' => 'Maîtrisez Python pour l\'analyse de données avec Pandas, NumPy, Matplotlib, Scikit-learn et plus encore. Devenez un expert en data science et machine learning.',
                'content' => 'Formation complète sur Python pour la science des données et le machine learning. Apprenez à analyser, visualiser et modéliser des données.',
                'cover_image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop',
                'cover_type' => 'external',
                'price' => 55000,
                'currency' => 'XOF',
                'discount_price' => 40000,
                'discount_start' => now()->addDays(7),
                'discount_end' => now()->addDays(37),
                'status' => 'published',
                'duration_hours' => 45,
                'what_you_learn' => [
                    'Manipuler les données avec Pandas',
                    'Effectuer des analyses statistiques',
                    'Créer des visualisations avec Matplotlib',
                    'Implémenter des modèles de machine learning',
                    'Nettoyer et préparer les données',
                    'Présenter vos résultats'
                ],
                'requirements' => [
                    'Connaissances de base en programmation',
                    'Notions de mathématiques',
                    'Python 3.x installé'
                ],
                'chapters' => [
                    [
                        'title' => 'Introduction à Python pour la Data Science',
                        'description' => 'Découvrez l\'écosystème Python pour la data science',
                        'content' => 'Nous explorerons les bibliothèques essentielles : NumPy, Pandas, Matplotlib, et Scikit-learn.',
                        'duration_minutes' => 60
                    ],
                    [
                        'title' => 'NumPy : Calculs Numériques',
                        'description' => 'Maîtrisez NumPy pour les calculs numériques efficaces',
                        'content' => 'NumPy est la base de l\'écosystème Python pour la data science. Nous apprendrons les tableaux NumPy et leurs opérations.',
                        'duration_minutes' => 120
                    ],
                    [
                        'title' => 'Pandas : Manipulation de Données',
                        'description' => 'Utilisez Pandas pour manipuler et analyser des données',
                        'content' => 'Pandas est l\'outil principal pour manipuler des données structurées. Nous verrons les DataFrames et leurs opérations.',
                        'duration_minutes' => 180
                    ],
                    [
                        'title' => 'Visualisation avec Matplotlib et Seaborn',
                        'description' => 'Créez des visualisations professionnelles de vos données',
                        'content' => 'La visualisation est essentielle pour comprendre et présenter vos données. Nous créerons des graphiques avec Matplotlib et Seaborn.',
                        'duration_minutes' => 150
                    ],
                    [
                        'title' => 'Machine Learning avec Scikit-learn',
                        'description' => 'Implémentez des modèles de machine learning',
                        'content' => 'Scikit-learn offre des outils puissants pour le machine learning. Nous créerons et évaluerons des modèles.',
                        'duration_minutes' => 200
                    ],
                    [
                        'title' => 'Projet Final : Analyse Complète',
                        'description' => 'Appliquez toutes vos connaissances dans un projet complet',
                        'content' => 'Nous réaliserons un projet complet d\'analyse de données du début à la fin.',
                        'duration_minutes' => 180
                    ]
                ]
            ],
            [
                'title' => 'Formation Vue.js 3 Composition API',
                'description' => 'Découvrez Vue.js 3 avec la Composition API, Pinia, Vue Router, TypeScript et les meilleures pratiques. Créez des applications Vue.js modernes et performantes.',
                'content' => 'Formation complète sur Vue.js 3 avec la nouvelle Composition API. Apprenez à créer des applications Vue.js modernes et maintenables.',
                'cover_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&h=600&fit=crop',
                'cover_type' => 'external',
                'price' => 35000,
                'currency' => 'XOF',
                'status' => 'published',
                'duration_hours' => 25,
                'what_you_learn' => [
                    'Maîtriser Vue.js 3',
                    'Utiliser la Composition API',
                    'Gérer l\'état avec Pinia',
                    'Créer des routes avec Vue Router',
                    'Intégrer TypeScript',
                    'Tester vos composants'
                ],
                'requirements' => [
                    'Connaissances en JavaScript',
                    'Notions de base en HTML/CSS',
                    'Node.js installé'
                ],
                'chapters' => [
                    [
                        'title' => 'Introduction à Vue.js 3',
                        'description' => 'Découvrez Vue.js 3 et ses nouveautés',
                        'content' => 'Nous explorerons Vue.js 3, ses nouvelles fonctionnalités et la Composition API.',
                        'duration_minutes' => 60
                    ],
                    [
                        'title' => 'Composition API',
                        'description' => 'Maîtrisez la Composition API de Vue.js 3',
                        'content' => 'La Composition API est la nouvelle façon d\'écrire des composants Vue.js. Nous apprendrons setup(), ref(), reactive(), et computed().',
                        'duration_minutes' => 120
                    ],
                    [
                        'title' => 'Pinia : Gestion d\'état',
                        'description' => 'Utilisez Pinia pour gérer l\'état global',
                        'content' => 'Pinia est le nouveau système de gestion d\'état recommandé pour Vue.js 3.',
                        'duration_minutes' => 100
                    ],
                    [
                        'title' => 'Vue Router',
                        'description' => 'Implémentez la navigation avec Vue Router',
                        'content' => 'Vue Router permet de créer des applications à page unique avec navigation.',
                        'duration_minutes' => 90
                    ],
                    [
                        'title' => 'TypeScript avec Vue.js',
                        'description' => 'Intégrez TypeScript dans vos projets Vue.js',
                        'content' => 'TypeScript apporte la sécurité de type à Vue.js. Nous verrons comment l\'intégrer.',
                        'duration_minutes' => 110
                    ]
                ]
            ]
        ];

        foreach ($courses as $courseData) {
            // Extraire les chapitres
            $chapters = $courseData['chapters'] ?? [];
            unset($courseData['chapters']);

            // Générer un slug unique
            if (!isset($courseData['slug'])) {
                $courseData['slug'] = Str::slug($courseData['title']);
                
                $originalSlug = $courseData['slug'];
                $counter = 1;
                while (PaidCourse::where('slug', $courseData['slug'])->exists()) {
                    $courseData['slug'] = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }

            // Générer des statistiques aléatoires pour les cours publiés
            if ($courseData['status'] === 'published') {
                $courseData['students_count'] = rand(10, 500);
                $courseData['rating'] = round(rand(35, 50) / 10, 1);
                $courseData['reviews_count'] = rand(5, 150);
            } else {
                $courseData['students_count'] = 0;
                $courseData['rating'] = 0;
                $courseData['reviews_count'] = 0;
            }

            // Créer le cours
            $course = PaidCourse::create($courseData);

            // Créer les chapitres
            foreach ($chapters as $index => $chapterData) {
                CourseChapter::create([
                    'paid_course_id' => $course->id,
                    'title' => $chapterData['title'],
                    'description' => $chapterData['description'] ?? null,
                    'content' => $chapterData['content'] ?? null,
                    'order' => $index + 1,
                    'duration_minutes' => $chapterData['duration_minutes'] ?? null,
                ]);
            }

            $this->command->info("✅ Cours créé : {$course->title} ({$course->id}) avec " . count($chapters) . " chapitres");
        }

        $totalChapters = CourseChapter::count();
        $this->command->info("🎉 " . count($courses) . " cours payants créés avec succès !");
        $this->command->info("📚 Total de {$totalChapters} chapitres créés !");
    }
}
