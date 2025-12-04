<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            // Badges spéciaux
            [
                'code' => 'first_formation',
                'name' => 'Premier Pas',
                'description' => 'Complétez votre première formation',
                'icon' => 'fa-star',
                'color' => '#fbbf24',
                'type' => 'special',
                'requirement_type' => 'first',
                'requirement_value' => 1,
                'order' => 1,
            ],
            [
                'code' => 'first_exercise',
                'name' => 'Premier Exercice',
                'description' => 'Complétez votre premier exercice',
                'icon' => 'fa-code',
                'color' => '#06b6d4',
                'type' => 'special',
                'requirement_type' => 'first',
                'requirement_value' => 1,
                'order' => 2,
            ],
            [
                'code' => 'first_quiz',
                'name' => 'Premier Quiz',
                'description' => 'Passez votre premier quiz',
                'icon' => 'fa-question-circle',
                'color' => '#10b981',
                'type' => 'special',
                'requirement_type' => 'first',
                'requirement_value' => 1,
                'order' => 3,
            ],

            // Badges de formations
            [
                'code' => 'formation_5',
                'name' => 'Étudiant Assidu',
                'description' => 'Complétez 5 formations',
                'icon' => 'fa-graduation-cap',
                'color' => '#8b5cf6',
                'type' => 'formation',
                'requirement_type' => 'count',
                'requirement_value' => 5,
                'order' => 10,
            ],
            [
                'code' => 'formation_10',
                'name' => 'Expert en Formations',
                'description' => 'Complétez 10 formations',
                'icon' => 'fa-trophy',
                'color' => '#f59e0b',
                'type' => 'formation',
                'requirement_type' => 'count',
                'requirement_value' => 10,
                'order' => 11,
            ],

            // Badges d'exercices
            [
                'code' => 'exercise_10',
                'name' => 'Débutant',
                'description' => 'Complétez 10 exercices',
                'icon' => 'fa-code',
                'color' => '#06b6d4',
                'type' => 'exercise',
                'requirement_type' => 'count',
                'requirement_value' => 10,
                'order' => 20,
            ],
            [
                'code' => 'exercise_50',
                'name' => 'Pratiquant',
                'description' => 'Complétez 50 exercices',
                'icon' => 'fa-code',
                'color' => '#3b82f6',
                'type' => 'exercise',
                'requirement_type' => 'count',
                'requirement_value' => 50,
                'order' => 21,
            ],
            [
                'code' => 'exercise_100',
                'name' => 'Maître du Code',
                'description' => 'Complétez 100 exercices',
                'icon' => 'fa-code',
                'color' => '#8b5cf6',
                'type' => 'exercise',
                'requirement_type' => 'count',
                'requirement_value' => 100,
                'order' => 22,
            ],

            // Badges de quiz
            [
                'code' => 'quiz_10',
                'name' => 'Quiz Master',
                'description' => 'Passez 10 quiz avec un score de 80% ou plus',
                'icon' => 'fa-question-circle',
                'color' => '#10b981',
                'type' => 'quiz',
                'requirement_type' => 'score',
                'requirement_value' => 80,
                'order' => 30,
            ],
            [
                'code' => 'quiz_20',
                'name' => 'Grand Maître des Quiz',
                'description' => 'Passez 20 quiz avec un score de 80% ou plus',
                'icon' => 'fa-trophy',
                'color' => '#f59e0b',
                'type' => 'quiz',
                'requirement_type' => 'score',
                'requirement_value' => 80,
                'order' => 31,
            ],

            // Badges de streak
            [
                'code' => 'streak_7',
                'name' => 'Semaine Parfaite',
                'description' => '7 jours consécutifs d\'activité',
                'icon' => 'fa-fire',
                'color' => '#ef4444',
                'type' => 'streak',
                'requirement_type' => 'days',
                'requirement_value' => 7,
                'order' => 40,
            ],
            [
                'code' => 'streak_30',
                'name' => 'Mois Parfait',
                'description' => '30 jours consécutifs d\'activité',
                'icon' => 'fa-fire',
                'color' => '#dc2626',
                'type' => 'streak',
                'requirement_type' => 'days',
                'requirement_value' => 30,
                'order' => 41,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['code' => $badge['code']],
                $badge
            );
        }
    }
}
