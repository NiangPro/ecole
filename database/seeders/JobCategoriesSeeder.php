<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class JobCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Offres d\'emploi',
                'slug' => 'offres-emploi',
                'description' => 'Découvrez les meilleures offres d\'emploi au Sénégal dans tous les secteurs d\'activité.',
                'icon' => 'fas fa-briefcase',
                'image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=600&h=400&fit=crop',
                'image_type' => 'external',
                'is_active' => true,
                'order' => 1
            ],
            [
                'name' => 'Bourses d\'études',
                'slug' => 'bourses-etudes',
                'description' => 'Toutes les opportunités de bourses d\'études disponibles au Sénégal et à l\'étranger.',
                'icon' => 'fas fa-graduation-cap',
                'image' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=600&h=400&fit=crop',
                'image_type' => 'external',
                'is_active' => true,
                'order' => 2
            ],
            [
                'name' => 'Candidature spontanée',
                'slug' => 'candidature-spontanee',
                'description' => 'Conseils et guides pour réussir votre candidature spontanée au Sénégal.',
                'icon' => 'fas fa-paper-plane',
                'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&h=400&fit=crop',
                'image_type' => 'external',
                'is_active' => true,
                'order' => 3
            ],
            [
                'name' => 'Opportunités professionnelles',
                'slug' => 'opportunites-professionnelles',
                'description' => 'Stages, formations professionnelles et opportunités de carrière au Sénégal.',
                'icon' => 'fas fa-star',
                'image' => 'https://images.unsplash.com/photo-1522202176988-ff73e2a17864?w=600&h=400&fit=crop',
                'image_type' => 'external',
                'is_active' => true,
                'order' => 4
            ],
            [
                'name' => 'Conseils carrière',
                'slug' => 'conseils-carriere',
                'description' => 'Astuces et conseils pour développer votre carrière professionnelle au Sénégal.',
                'icon' => 'fas fa-lightbulb',
                'image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=600&h=400&fit=crop',
                'image_type' => 'external',
                'is_active' => true,
                'order' => 5
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }
    }
}
