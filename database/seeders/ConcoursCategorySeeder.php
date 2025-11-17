<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class ConcoursCategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::updateOrCreate(
            ['slug' => 'concours'],
            [
                'name' => 'Concours',
                'slug' => 'concours',
                'description' => 'Tous les concours publics et privés au Sénégal : fonction publique, entreprises, institutions.',
                'icon' => 'fas fa-trophy',
                'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop',
                'image_type' => 'external',
                'is_active' => true,
                'order' => 6
            ]
        );
    }
}

