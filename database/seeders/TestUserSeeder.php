<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier si l'utilisateur existe déjà
        $existingUser = User::where('email', 'test@example.com')->first();
        
        if (!$existingUser) {
            $user = User::create([
                'name' => 'Utilisateur Test',
                'email' => 'test@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'is_active' => true,
            ]);
            
            $this->command->info('✅ Utilisateur de test créé avec succès!');
            $this->command->info('   Email: test@example.com');
            $this->command->info('   Mot de passe: password123');
        } else {
            $this->command->warn('⚠️  L\'utilisateur test@example.com existe déjà.');
        }
    }
}
