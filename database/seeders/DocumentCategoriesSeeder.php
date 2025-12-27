<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentCategory;
use Illuminate\Support\Str;

class DocumentCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Guides et Tutoriels',
                'slug' => 'guides-tutoriels',
                'description' => 'Guides pratiques et tutoriels détaillés pour apprendre et maîtriser différentes technologies.',
                'icon' => 'fas fa-book',
                'image' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=600&h=400&fit=crop',
                'image_type' => 'external',
                'is_active' => true,
                'order' => 1
            ],
            [
                'name' => 'Templates et Modèles',
                'slug' => 'templates-modeles',
                'description' => 'Templates prêts à l\'emploi pour accélérer votre développement.',
                'icon' => 'fas fa-file-code',
                'image' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=600&h=400&fit=crop',
                'image_type' => 'external',
                'is_active' => true,
                'order' => 2
            ],
            [
                'name' => 'Documentation Technique',
                'slug' => 'documentation-technique',
                'description' => 'Documentation complète et références techniques pour développeurs.',
                'icon' => 'fas fa-file-alt',
                'image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=600&h=400&fit=crop',
                'image_type' => 'external',
                'is_active' => true,
                'order' => 3
            ],
            [
                'name' => 'Ressources Design',
                'slug' => 'ressources-design',
                'description' => 'Ressources graphiques, icônes, et éléments de design pour vos projets.',
                'icon' => 'fas fa-palette',
                'image' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=600&h=400&fit=crop',
                'image_type' => 'external',
                'is_active' => true,
                'order' => 4
            ],
            [
                'name' => 'E-books et Livres',
                'slug' => 'ebooks-livres',
                'description' => 'Livres électroniques et guides complets sur le développement web et la programmation.',
                'icon' => 'fas fa-book-reader',
                'image' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=600&h=400&fit=crop',
                'image_type' => 'external',
                'is_active' => true,
                'order' => 5
            ],
            [
                'name' => 'Outils et Scripts',
                'slug' => 'outils-scripts',
                'description' => 'Scripts utilitaires et outils pour automatiser vos tâches de développement.',
                'icon' => 'fas fa-tools',
                'image' => 'https://images.unsplash.com/photo-1551650975-87deedd944c3?w=600&h=400&fit=crop',
                'image_type' => 'external',
                'is_active' => true,
                'order' => 6
            ]
        ];

        foreach ($categories as $category) {
            DocumentCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
