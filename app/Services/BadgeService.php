<?php

namespace App\Services;

use App\Models\User;
use App\Models\Badge;
use App\Models\UserBadge;
use App\Models\FormationProgress;
use App\Models\ExerciseProgress;
use App\Models\QuizResult;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BadgeService
{
    /**
     * Vérifier et attribuer les badges à un utilisateur
     */
    public function checkAndAwardBadges(User $user): array
    {
        $awardedBadges = [];

        // Vérifier tous les badges actifs
        $badges = Badge::active()->get();

        foreach ($badges as $badge) {
            // Si l'utilisateur a déjà ce badge, on passe
            if ($user->hasBadge($badge->code)) {
                continue;
            }

            // Vérifier si l'utilisateur remplit les conditions
            if ($this->checkBadgeRequirement($user, $badge)) {
                $this->awardBadge($user, $badge);
                $awardedBadges[] = $badge;
            }
        }

        return $awardedBadges;
    }

    /**
     * Vérifier si un utilisateur remplit les conditions d'un badge
     */
    protected function checkBadgeRequirement(User $user, Badge $badge): bool
    {
        switch ($badge->type) {
            case 'formation':
                return $this->checkFormationBadge($user, $badge);
            case 'exercise':
                return $this->checkExerciseBadge($user, $badge);
            case 'quiz':
                return $this->checkQuizBadge($user, $badge);
            case 'streak':
                return $this->checkStreakBadge($user, $badge);
            case 'special':
                return $this->checkSpecialBadge($user, $badge);
            default:
                return false;
        }
    }

    /**
     * Vérifier les badges de formation
     */
    protected function checkFormationBadge(User $user, Badge $badge): bool
    {
        $requirementType = $badge->requirement_type;
        $requirementValue = $badge->requirement_value;

        switch ($requirementType) {
            case 'count':
                $completedFormations = FormationProgress::where('user_id', $user->id)
                    ->where('progress_percentage', 100)
                    ->count();
                return $completedFormations >= $requirementValue;
            
            case 'first':
                return FormationProgress::where('user_id', $user->id)
                    ->where('progress_percentage', 100)
                    ->exists();
            
            default:
                return false;
        }
    }

    /**
     * Vérifier les badges d'exercices
     */
    protected function checkExerciseBadge(User $user, Badge $badge): bool
    {
        $requirementType = $badge->requirement_type;
        $requirementValue = $badge->requirement_value;

        switch ($requirementType) {
            case 'count':
                $completedExercises = ExerciseProgress::where('user_id', $user->id)
                    ->where('completed', true)
                    ->count();
                return $completedExercises >= $requirementValue;
            
            case 'first':
                return ExerciseProgress::where('user_id', $user->id)
                    ->where('completed', true)
                    ->exists();
            
            default:
                return false;
        }
    }

    /**
     * Vérifier les badges de quiz
     */
    protected function checkQuizBadge(User $user, Badge $badge): bool
    {
        $requirementType = $badge->requirement_type;
        $requirementValue = $badge->requirement_value;

        switch ($requirementType) {
            case 'count':
                $quizCount = QuizResult::where('user_id', $user->id)->count();
                return $quizCount >= $requirementValue;
            
            case 'score':
                // Calculer le pourcentage à partir de correct_answers et total_questions
                $quizCount = QuizResult::where('user_id', $user->id)
                    ->whereRaw('(correct_answers * 100.0 / NULLIF(total_questions, 0)) >= ?', [$requirementValue])
                    ->count();
                return $quizCount >= ($badge->requirement_value ?? 1);
            
            default:
                return false;
        }
    }

    /**
     * Vérifier les badges de streak (jours consécutifs)
     */
    protected function checkStreakBadge(User $user, Badge $badge): bool
    {
        $requirementValue = $badge->requirement_value ?? 7;
        
        // Calculer le streak actuel
        $streak = $this->calculateStreak($user);
        
        return $streak >= $requirementValue;
    }

    /**
     * Calculer le streak d'un utilisateur (jours consécutifs d'activité)
     */
    protected function calculateStreak(User $user): int
    {
        $activities = $user->activities()
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('Y-m-d');
            });

        $streak = 0;
        $currentDate = Carbon::now();

        while ($activities->has($currentDate->format('Y-m-d'))) {
            $streak++;
            $currentDate->subDay();
        }

        return $streak;
    }

    /**
     * Vérifier les badges spéciaux
     */
    protected function checkSpecialBadge(User $user, Badge $badge): bool
    {
        $code = $badge->code;

        switch ($code) {
            case 'first_formation':
                return FormationProgress::where('user_id', $user->id)
                    ->where('progress_percentage', 100)
                    ->exists();
            
            case 'first_exercise':
                return ExerciseProgress::where('user_id', $user->id)
                    ->where('completed', true)
                    ->exists();
            
            case 'first_quiz':
                return QuizResult::where('user_id', $user->id)->exists();
            
            default:
                return false;
        }
    }

    /**
     * Attribuer un badge à un utilisateur
     */
    public function awardBadge(User $user, Badge $badge, array $metadata = []): UserBadge
    {
        // Vérifier si l'utilisateur a déjà ce badge
        if ($user->hasBadge($badge->code)) {
            return $user->userBadges()->where('badge_id', $badge->id)->first();
        }

        // Créer le badge utilisateur
        $userBadge = UserBadge::create([
            'user_id' => $user->id,
            'badge_id' => $badge->id,
            'earned_at' => now(),
            'metadata' => $metadata,
        ]);

        // Invalider le cache du dashboard
        \App\Http\Controllers\ProfileController::clearCache($user->id);

        return $userBadge;
    }
}


