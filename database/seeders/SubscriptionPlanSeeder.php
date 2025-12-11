<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Str;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Exécuter le seeder des plans d'abonnement
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'description' => 'Accédez à tous les contenus premium de la plateforme',
                'price' => 5000,
                'currency' => 'XOF',
                'billing_period' => 'monthly',
                'duration_days' => 30,
                'features' => [
                    'Accès à tous les cours premium',
                    'Certificats téléchargeables',
                    'Support prioritaire',
                    'Sans publicités',
                    'Contenu exclusif',
                    'Accès aux formations avancées',
                ],
                'is_active' => true,
                'order' => 1,
                'badge' => null,
                'is_featured' => false,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'Pour les développeurs sérieux qui veulent aller plus loin',
                'price' => 10000,
                'currency' => 'XOF',
                'billing_period' => 'monthly',
                'duration_days' => 30,
                'features' => [
                    'Tout Premium inclus',
                    'Coaching personnalisé',
                    'Projets pratiques',
                    'Accès communauté VIP',
                    'Webinaires exclusifs',
                    'Code reviews',
                    'Mentorat individuel',
                ],
                'is_active' => true,
                'order' => 2,
                'badge' => 'Populaire',
                'is_featured' => true,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'Solution complète pour les équipes et entreprises',
                'price' => 25000,
                'currency' => 'XOF',
                'billing_period' => 'monthly',
                'duration_days' => 30,
                'features' => [
                    'Tout Pro inclus',
                    'Formation sur mesure',
                    'Support dédié',
                    'Licence multi-utilisateurs',
                    'API personnalisée',
                    'Rapports d\'avancement',
                    'Formation en entreprise',
                    'Gestion centralisée',
                ],
                'is_active' => true,
                'order' => 3,
                'badge' => null,
                'is_featured' => false,
            ],
            [
                'name' => 'Premium Annuel',
                'slug' => 'premium-yearly',
                'description' => 'Abonnement Premium avec économie de 2 mois',
                'price' => 50000,
                'currency' => 'XOF',
                'billing_period' => 'yearly',
                'duration_days' => 365,
                'features' => [
                    'Tout Premium inclus',
                    'Économisez 2 mois',
                    'Certificats téléchargeables',
                    'Support prioritaire',
                    'Sans publicités',
                    'Contenu exclusif',
                ],
                'is_active' => true,
                'order' => 4,
                'badge' => 'Économie',
                'is_featured' => false,
            ],
            [
                'name' => 'Pro Annuel',
                'slug' => 'pro-yearly',
                'description' => 'Abonnement Pro avec économie de 2 mois',
                'price' => 100000,
                'currency' => 'XOF',
                'billing_period' => 'yearly',
                'duration_days' => 365,
                'features' => [
                    'Tout Pro inclus',
                    'Économisez 2 mois',
                    'Coaching personnalisé',
                    'Projets pratiques',
                    'Accès communauté VIP',
                    'Webinaires exclusifs',
                ],
                'is_active' => true,
                'order' => 5,
                'badge' => 'Économie',
                'is_featured' => false,
            ],
        ];

        foreach ($plans as $planData) {
            // Vérifier si le plan existe déjà
            $existingPlan = SubscriptionPlan::where('slug', $planData['slug'])->first();
            
            if (!$existingPlan) {
                SubscriptionPlan::create($planData);
                $this->command->info("Plan '{$planData['name']}' créé avec succès.");
            } else {
                $this->command->warn("Plan '{$planData['name']}' existe déjà, ignoré.");
            }
        }

        $this->command->info('Seeder des plans d\'abonnement terminé !');
    }
}
