<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ad;
use Illuminate\Support\Str;

class AdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Publicité Yobali
        Ad::updateOrCreate(
            ['name' => 'Yobali - Facturation Simple'],
            [
                'description' => 'La facturation simple et rapide pour entrepreneurs et commerçants. Créez des factures professionnelles en 3 clics.',
                'image' => 'https://yobali.com/images/logo.png',
                'image_type' => 'external',
                'link_url' => 'https://yobali.com/',
                'position' => 'content',
                'location' => 'homepage_after_exercises',
                'status' => 'active',
                'order' => 1,
                'start_date' => null,
                'end_date' => null,
                'clicks' => 0,
                'impressions' => 0,
            ]
        );

        // Publicité Semplio
        Ad::updateOrCreate(
            ['name' => 'Semplio SAAS - Gestion d\'Entreprise'],
            [
                'description' => 'Simplifiez la gestion de votre entreprise. Un outil complet pour gérer vos ventes, produits, factures, dépenses et plus encore.',
                'image' => 'https://semplio.com/images/logo.png',
                'image_type' => 'external',
                'link_url' => 'https://semplio.com/',
                'position' => 'content',
                'location' => 'homepage_after_exercises',
                'status' => 'active',
                'order' => 2,
                'start_date' => null,
                'end_date' => null,
                'clicks' => 0,
                'impressions' => 0,
            ]
        );
    }
}
