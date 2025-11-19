<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les credentials depuis .env ou utiliser des valeurs par défaut
        $adminEmail = env('ADMIN_EMAIL', 'admin@niangprogrammeur.com');
        $adminPassword = env('ADMIN_PASSWORD', 'Admin@2025');
        $adminName = env('ADMIN_NAME', 'Administrateur');

        // Vérifier si l'admin existe déjà
        $admin = User::where('email', $adminEmail)->first();

        if ($admin) {
            // Mettre à jour le mot de passe si nécessaire
            if (!Hash::check($adminPassword, $admin->password)) {
                $admin->password = Hash::make($adminPassword);
                $admin->save();
                $this->command->info("Mot de passe admin mis à jour pour: {$adminEmail}");
            } else {
                $this->command->info("Admin existe déjà: {$adminEmail}");
            }
        } else {
            // Créer l'admin
            User::create([
                'name' => $adminName,
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $this->command->info("Admin créé avec succès: {$adminEmail}");
        }
    }
}
