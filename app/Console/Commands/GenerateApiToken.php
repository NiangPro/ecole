<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateApiToken extends Command
{
    protected $signature   = 'token:generate {email : Adresse e-mail du compte utilisateur} {--name= : Nom du token (défaut: agent-api)}';
    protected $description = 'Génère un Personal Access Token Sanctum pour un utilisateur donné';

    public function handle(): int
    {
        $email = $this->argument('email');
        $name  = $this->option('name') ?: 'agent-api';

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("Aucun utilisateur trouvé pour l'adresse : {$email}");
            return self::FAILURE;
        }

        if (! $user->isAdmin()) {
            $this->warn("Attention : cet utilisateur n'est pas administrateur (role={$user->role}).");
            if (! $this->confirm('Continuer quand même ?')) {
                return self::FAILURE;
            }
        }

        $token = $user->createToken($name, ['article:create']);

        $this->newLine();
        $this->line('<fg=green;options=bold>✓ Token généré avec succès</>');
        $this->table(
            ['Champ', 'Valeur'],
            [
                ['Utilisateur', $user->name . ' <' . $user->email . '>'],
                ['Nom du token', $name],
                ['Abilities', 'article:create'],
            ]
        );
        $this->newLine();
        $this->line('<fg=yellow>TOKEN (copier maintenant, ne sera plus affiché) :</>');
        $this->line('<fg=cyan>' . $token->plainTextToken . '</>');
        $this->newLine();
        $this->line('<fg=gray>Utilisation :</> curl -H "Authorization: Bearer ' . $token->plainTextToken . '" ...');
        $this->newLine();

        return self::SUCCESS;
    }
}
