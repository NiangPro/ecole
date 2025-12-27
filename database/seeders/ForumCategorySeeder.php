<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ForumCategory;

class ForumCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Général',
                'slug' => 'general',
                'description' => 'Discussions générales sur le développement web et la programmation',
                'icon' => 'fas fa-comments',
                'color' => '#06b6d4',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'HTML & CSS',
                'slug' => 'html-css',
                'description' => 'Questions et discussions sur HTML5, CSS3, Flexbox, Grid, etc.',
                'icon' => 'fab fa-html5',
                'color' => '#e34c26',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'JavaScript',
                'slug' => 'javascript',
                'description' => 'Discussions sur JavaScript, ES6+, frameworks et bibliothèques',
                'icon' => 'fab fa-js',
                'color' => '#f0db4f',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'PHP & Laravel',
                'slug' => 'php-laravel',
                'description' => 'Questions sur PHP, Laravel, architecture MVC, etc.',
                'icon' => 'fab fa-php',
                'color' => '#8993be',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Bases de données',
                'slug' => 'bases-de-donnees',
                'description' => 'MySQL, PostgreSQL, MongoDB, requêtes SQL, optimisation',
                'icon' => 'fas fa-database',
                'color' => '#336791',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Outils & Technologies',
                'slug' => 'outils-technologies',
                'description' => 'Git, Docker, APIs, outils de développement, etc.',
                'icon' => 'fas fa-tools',
                'color' => '#14b8a6',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Aide & Support',
                'slug' => 'aide-support',
                'description' => 'Besoin d\'aide ? Posez vos questions ici',
                'icon' => 'fas fa-question-circle',
                'color' => '#f59e0b',
                'order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Projets & Portfolio',
                'slug' => 'projets-portfolio',
                'description' => 'Partagez vos projets, demandez des retours et montrez votre portfolio',
                'icon' => 'fas fa-project-diagram',
                'color' => '#8b5cf6',
                'order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            ForumCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('Catégories du forum créées avec succès !');
    }
}
