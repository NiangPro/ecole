<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            [
                'title' => 'CodAfrik - Leader Tech Sénégal',
                'description' => 'Plateforme technologique leader au Sénégal depuis 2017. Développement de solutions web innovantes, applications mobiles, et formations certifiantes. Plus de 150 projets réalisés avec 98% de satisfaction client. Expertise en Laravel, React, Vue.js, Flutter et technologies cloud natives.',
                'icon' => 'fas fa-code',
                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1200&h=630&fit=crop',
                'image_type' => 'external',
                'link_url' => 'https://codafrik.com/',
                'order' => 1,
                'is_visible' => true,
            ],
            [
                'title' => 'Gestunews - Actualités Sénégal',
                'description' => 'Plateforme d\'actualités fiables au Sénégal. Source d\'informations complètes sur l\'emploi, les concours, les bourses d\'études, les formations et les opportunités professionnelles. Interface moderne et intuitive permettant aux utilisateurs de rester informés des dernières actualités du pays.',
                'icon' => 'fas fa-newspaper',
                'image' => 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=1200&h=630&fit=crop',
                'image_type' => 'external',
                'link_url' => 'https://gestunews.com/',
                'order' => 2,
                'is_visible' => true,
            ],
            [
                'title' => 'Elfecky Immobilier - Groupe Foncier',
                'description' => 'Plateforme immobilière complète pour la gestion foncière et immobilière au Sénégal. Vente de terrains, coopérative d\'habitat, gestion immobilière et régularisation foncière. Interface multilingue (Français/Anglais) avec système de recherche avancé et gestion de propriétés.',
                'icon' => 'fas fa-building',
                'image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1200&h=630&fit=crop',
                'image_type' => 'external',
                'link_url' => 'https://agneimmobilier.com/',
                'order' => 3,
                'is_visible' => true,
            ],
            [
                'title' => 'Graphiquenseignes - Communication Visuelle',
                'description' => 'Plateforme spécialisée dans la communication visuelle et la signalétique. Services de création graphique, design de logos, enseignes publicitaires et solutions de branding. Interface moderne permettant la présentation de portfolios et la gestion de projets de communication visuelle.',
                'icon' => 'fas fa-paint-brush',
                'image' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=1200&h=630&fit=crop',
                'image_type' => 'external',
                'link_url' => 'https://graphiquenseignes.com/',
                'order' => 4,
                'is_visible' => true,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::updateOrCreate(
                ['link_url' => $achievement['link_url']],
                $achievement
            );
        }

        $this->command->info('Réalisations créées avec succès !');
    }
}
