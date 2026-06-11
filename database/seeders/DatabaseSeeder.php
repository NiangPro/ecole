<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Créer l'utilisateur de test s'il n'existe pas déjà
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password123'),
            ]
        );
        
        // Créer l'utilisateur admin
        $this->call(AdminUserSeeder::class);

        // Matières des épreuves & corrigés
        $this->call(EpreuveMatieresSeeder::class);
        
        // Seeders pour les emplois
        $this->call([
            JobCategoriesSeeder::class,
            JobArticlesSeeder::class,
            SponsoredArticleSeeder::class,
            FeaturedArticlesSeeder::class,
        ]);
        
        // Seeder pour les badges
        $this->call(BadgeSeeder::class);
        
        // Seeder pour les cours payants
        $this->call(PaidCourseSeeder::class);
        
        // Seeder pour les plans d'abonnement
        $this->call(SubscriptionPlanSeeder::class);
        
        // Seeder pour les catégories de documents
        $this->call(DocumentCategoriesSeeder::class);
        
        // Seeder pour les documents
        $this->call(DocumentSeeder::class);
        
        // Seeder pour les paramètres WhatsApp
        $this->call(WhatsAppSettingsSeeder::class);
        
        // Seeder pour les formations gratuites
        $this->call(FormationSeeder::class);
        
        // Seeder pour les catégories de forum
        $this->call(ForumCategorySeeder::class);
    }
}
