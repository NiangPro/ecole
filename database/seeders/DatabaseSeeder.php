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

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        // Créer l'utilisateur admin
        $this->call(AdminUserSeeder::class);
        
        // Seeders pour les emplois
        $this->call([
            JobCategoriesSeeder::class,
            JobArticlesSeeder::class,
            SponsoredArticleSeeder::class,
        ]);
        
        // Seeder pour les badges
        $this->call(BadgeSeeder::class);
        
        // Seeder pour les cours payants
        $this->call(PaidCourseSeeder::class);
    }
}
