<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\FormationProgress;
use App\Models\ExerciseProgress;
use App\Models\QuizResult;
use App\Models\UserActivity;
use App\Models\UserGoal;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DashboardTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Création des données de test pour le tableau de bord...');
        
        // Récupérer ou créer l'utilisateur de test
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Utilisateur Test',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'is_active' => true,
            ]
        );
        
        $this->command->info("✅ Utilisateur trouvé/créé : {$user->email}");
        
        // Supprimer les anciennes données de test pour cet utilisateur
        FormationProgress::where('user_id', $user->id)->delete();
        ExerciseProgress::where('user_id', $user->id)->delete();
        QuizResult::where('user_id', $user->id)->delete();
        UserActivity::where('user_id', $user->id)->delete();
        UserGoal::where('user_id', $user->id)->delete();
        
        $this->command->info('🧹 Anciennes données supprimées');
        
        // 1. Créer des progressions de formations
        $formations = ['html5', 'css3', 'javascript', 'php', 'bootstrap', 'git', 'python'];
        $formationProgresses = [];
        
        foreach ($formations as $index => $formation) {
            $percentage = [100, 75, 50, 45, 30, 20, 0][$index]; // Varier les pourcentages
            $completedSections = $percentage > 0 ? range(1, (int)($percentage / 10)) : [];
            
            $progress = FormationProgress::create([
                'user_id' => $user->id,
                'formation_slug' => $formation,
                'progress_percentage' => $percentage,
                'completed_sections' => $completedSections,
                'time_spent_minutes' => rand(60, 600),
                'started_at' => Carbon::now()->subDays(rand(1, 30)),
                'completed_at' => $percentage === 100 ? Carbon::now()->subDays(rand(1, 10)) : null,
            ]);
            
            $formationProgresses[] = $progress;
            $this->command->info("  ✓ Formation {$formation}: {$percentage}%");
        }
        
        // 2. Créer des progressions d'exercices
        $languages = ['html5', 'css3', 'javascript', 'php', 'python'];
        $exerciseCount = 0;
        
        foreach ($languages as $language) {
            $exercisesCount = rand(3, 8);
            for ($i = 1; $i <= $exercisesCount; $i++) {
                $isCompleted = rand(0, 1);
                
                ExerciseProgress::create([
                    'user_id' => $user->id,
                    'exercise_id' => "ex-{$language}-{$i}",
                    'language' => $language,
                    'completed' => $isCompleted,
                    'score' => $isCompleted ? rand(80, 100) : 0,
                    'time_spent_seconds' => $isCompleted ? rand(120, 600) : 0,
                    'code_submitted' => $isCompleted ? "// Code de l'exercice {$i}" : null,
                    'completed_at' => $isCompleted ? Carbon::now()->subDays(rand(1, 20)) : null,
                ]);
                $exerciseCount++;
            }
        }
        
        $this->command->info("  ✓ {$exerciseCount} exercices créés");
        
        // 3. Créer des résultats de quiz
        $quizLanguages = ['html5', 'css3', 'javascript', 'php', 'python', 'bootstrap'];
        $quizCount = 0;
        
        foreach ($quizLanguages as $language) {
            $totalQuestions = rand(15, 20);
            $correctAnswers = rand(10, $totalQuestions);
            $wrongAnswers = $totalQuestions - $correctAnswers;
            $score = $correctAnswers;
            
            QuizResult::create([
                'user_id' => $user->id,
                'quiz_id' => $language,
                'language' => $language,
                'score' => $score,
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctAnswers,
                'wrong_answers' => $wrongAnswers,
                'answers' => [],
                'time_spent_seconds' => rand(300, 1200),
                'completed_at' => Carbon::now()->subDays(rand(1, 25)),
            ]);
            $quizCount++;
        }
        
        $this->command->info("  ✓ {$quizCount} quiz créés");
        
        // 4. Créer des activités récentes (30 derniers jours)
        $activityTypes = ['formation', 'exercise', 'quiz'];
        $activityCount = 0;
        
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays($i);
            $type = $activityTypes[array_rand($activityTypes)];
            
            if ($type === 'formation') {
                $formation = $formations[array_rand($formations)];
                UserActivity::create([
                    'user_id' => $user->id,
                    'activity_type' => 'formation',
                    'activity_name' => 'Formation : ' . ucfirst($formation),
                    'activity_slug' => "formations/{$formation}",
                    'activity_data' => [
                        'formation_slug' => $formation,
                        'progress_percentage' => rand(10, 100),
                    ],
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            } elseif ($type === 'exercise') {
                $language = $languages[array_rand($languages)];
                UserActivity::create([
                    'user_id' => $user->id,
                    'activity_type' => 'exercise',
                    'activity_name' => 'Exercice complété : ' . ucfirst($language),
                    'activity_slug' => "exercices/{$language}/1",
                    'activity_data' => [
                        'language' => $language,
                        'score' => rand(80, 100),
                    ],
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            } else {
                $language = $quizLanguages[array_rand($quizLanguages)];
                UserActivity::create([
                    'user_id' => $user->id,
                    'activity_type' => 'quiz',
                    'activity_name' => 'Quiz complété : ' . ucfirst($language),
                    'activity_slug' => "quiz/{$language}",
                    'activity_data' => [
                        'language' => $language,
                        'score' => rand(70, 100),
                        'percentage' => rand(70, 100),
                    ],
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
            $activityCount++;
        }
        
        $this->command->info("  ✓ {$activityCount} activités créées");
        
        // 5. Créer des objectifs utilisateur
        $goals = [
            [
                'goal_type' => 'formation',
                'title' => 'Terminer la formation PHP',
                'description' => 'Compléter 100% de la formation PHP',
                'target_value' => 100,
                'current_value' => 45,
                'unit' => '%',
                'deadline' => Carbon::now()->addDays(30),
                'completed' => false,
            ],
            [
                'goal_type' => 'exercise',
                'title' => 'Compléter 20 exercices',
                'description' => 'Terminer 20 exercices en JavaScript',
                'target_value' => 20,
                'current_value' => 12,
                'unit' => 'exercices',
                'deadline' => Carbon::now()->addDays(45),
                'completed' => false,
            ],
            [
                'goal_type' => 'quiz',
                'title' => 'Obtenir 80% en moyenne aux quiz',
                'description' => 'Maintenir une moyenne de 80% sur tous les quiz',
                'target_value' => 80,
                'current_value' => 75,
                'unit' => '%',
                'deadline' => Carbon::now()->addDays(60),
                'completed' => false,
            ],
            [
                'goal_type' => 'formation',
                'title' => 'Terminer la formation HTML5',
                'description' => 'Compléter 100% de la formation HTML5',
                'target_value' => 100,
                'current_value' => 100,
                'unit' => '%',
                'deadline' => Carbon::now()->subDays(5),
                'completed' => true,
                'completed_at' => Carbon::now()->subDays(5),
            ],
        ];
        
        foreach ($goals as $goalData) {
            UserGoal::create(array_merge([
                'user_id' => $user->id,
            ], $goalData));
        }
        
        $this->command->info("  ✓ " . count($goals) . " objectifs créés");
        
        // Résumé
        $this->command->info('');
        $this->command->info('✅ Données de test créées avec succès !');
        $this->command->info('');
        $this->command->info('📊 Résumé :');
        $this->command->info("   • Formations : " . count($formationProgresses));
        $this->command->info("   • Exercices : {$exerciseCount}");
        $this->command->info("   • Quiz : {$quizCount}");
        $this->command->info("   • Activités : {$activityCount}");
        $this->command->info("   • Objectifs : " . count($goals));
        $this->command->info('');
        $this->command->info('🔑 Identifiants de connexion :');
        $this->command->info("   Email : test@example.com");
        $this->command->info("   Mot de passe : password123");
        $this->command->info('');
        $this->command->info('🌐 Accédez au tableau de bord : /profile');
    }
}
