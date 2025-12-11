<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\FormationProgress;
use App\Models\ExerciseProgress;
use App\Models\QuizResult;
use App\Models\UserActivity;
use App\Models\UserGoal;
use App\Models\CoursePurchase;
use App\Models\PaidCourse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Détermine la locale à utiliser (français ou anglais)
     */
    private function ensureLocale()
    {
        // Récupérer la langue depuis la session ou le paramètre de requête
        $locale = Session::get('language', 'fr');
        
        // Vérifier si un paramètre de langue est passé dans l'URL
        if (request()->has('lang')) {
            $locale = request()->get('lang');
            Session::put('language', $locale);
        }
        
        // Valider que la locale est supportée
        if (!in_array($locale, ['fr', 'en'])) {
            $locale = 'fr';
        }
        
        // Définir la locale
        App::setLocale($locale);
        \Illuminate\Support\Facades\Lang::setLocale($locale);
        config(['app.locale' => $locale]);
        config(['app.fallback_locale' => 'fr']);
        
        return $locale;
    }
    
    /**
     * Invalider le cache du dashboard pour un utilisateur
     */
    public static function clearCache($userId)
    {
        // Invalider le cache pour toutes les locales
        Cache::forget("dashboard_data_user_{$userId}_locale_fr");
        Cache::forget("dashboard_data_user_{$userId}_locale_en");
        Cache::forget("recommendations_user_{$userId}_locale_fr");
        Cache::forget("recommendations_user_{$userId}_locale_en");
    }
    /**
     * Données communes pour toutes les pages du dashboard
     * Optimisé avec cache et requêtes efficaces
     */
    private function getCommonData()
    {
        $user = Auth::user();
        $locale = app()->getLocale(); // Inclure la locale dans la clé de cache
        $cacheKey = "dashboard_data_user_{$user->id}_locale_{$locale}";
        $cacheDuration = 300; // 5 minutes
        
        // Utiliser le cache pour les données qui changent peu
        return Cache::remember($cacheKey, $cacheDuration, function () use ($user) {
            // Progression des formations - limité aux 50 dernières
            $formationProgress = FormationProgress::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
                ->limit(50)
                ->get();
            
            // Progression des exercices - limité aux 100 derniers
            $exerciseProgress = ExerciseProgress::where('user_id', $user->id)
                ->orderBy('completed_at', 'desc')
                ->limit(100)
                ->get();
            
            // Résultats des quiz - limité aux 50 derniers
            $quizResults = QuizResult::where('user_id', $user->id)
                ->orderBy('completed_at', 'desc')
                ->limit(50)
                ->get();
            
            // Activités récentes - limité aux 20 dernières
            $recentActivities = UserActivity::where('user_id', $user->id)
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();
            
            // Objectifs - tous (généralement peu nombreux)
            $goals = UserGoal::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
            ->get();
            
            // Statistiques
            $stats = $this->calculateStats($user, $formationProgress, $exerciseProgress, $quizResults);
            
            // Données pour graphiques
            $chartData = $this->prepareChartData($user, $formationProgress, $exerciseProgress, $quizResults);
            
            return [
                'user' => $user,
                'formationProgress' => $formationProgress,
                'exerciseProgress' => $exerciseProgress,
                'quizResults' => $quizResults,
                'recentActivities' => $recentActivities,
                'goals' => $goals,
                'stats' => $stats,
                'chartData' => $chartData,
            ];
        });
    }
    
    /**
     * Vue d'ensemble (Overview)
     */
    public function overview()
    {
        // Définir la locale AVANT tout traitement
        $locale = $this->ensureLocale();
        
        // Partager la locale avec toutes les vues
        view()->share('currentLocale', $locale);
        
        $data = $this->getCommonData();
        
        // Cache séparé pour les recommandations (changent moins souvent)
        $user = Auth::user();
        $recommendationsCacheKey = "recommendations_user_{$user->id}_locale_{$locale}";
        $data['recommendations'] = Cache::remember($recommendationsCacheKey, 600, function () use ($data) {
            return $this->generateRecommendations(
                $data['user'],
                $data['formationProgress'],
                $data['exerciseProgress'],
                $data['quizResults']
            );
        });
        
        return view('dashboard.overview', $data);
    }
    
    /**
     * Page Formations avec filtres et tri
     */
    public function formations(Request $request)
    {
        // Définir la locale AVANT tout traitement
        $this->ensureLocale();
        
        $data = $this->getCommonData();
        
        // Filtres
        $query = $data['formationProgress'];
        
        // Filtre par statut
        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query = $query->where('progress_percentage', 100);
            } elseif ($request->status === 'ongoing') {
                $query = $query->where('progress_percentage', '<', 100)->where('progress_percentage', '>', 0);
            } elseif ($request->status === 'not_started') {
                $query = $query->where('progress_percentage', 0);
            }
        }
        
        // Recherche
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query = $query->filter(function($progress) use ($search) {
                return str_contains(strtolower($progress->formation_slug), $search);
            });
        }
        
        // Tri
        $sortBy = $request->get('sort', 'updated_at');
        $sortOrder = $request->get('order', 'desc');
        
        if ($sortBy === 'name') {
            $query = $query->sortBy('formation_slug', SORT_REGULAR, $sortOrder === 'desc');
        } elseif ($sortBy === 'progress') {
            $query = $query->sortBy('progress_percentage', SORT_NUMERIC, $sortOrder === 'desc');
        } else {
            $query = $query->sortBy('updated_at', SORT_REGULAR, $sortOrder === 'desc');
        }
        
        $data['formationProgress'] = $query->values();
        $data['filters'] = $request->only(['status', 'search', 'sort', 'order']);
        
        return view('dashboard.formations', $data);
    }
    
    /**
     * Page Exercices avec filtres et tri
     */
    public function exercices(Request $request)
    {
        // Définir la locale AVANT tout traitement
        $this->ensureLocale();
        
        $data = $this->getCommonData();
        
        // Filtres
        $query = $data['exerciseProgress'];
        
        // Filtre par statut
        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query = $query->where('completed', true);
            } elseif ($request->status === 'in_progress') {
                $query = $query->where('completed', false);
            }
        }
        
        // Filtre par langage
        if ($request->filled('language')) {
            $query = $query->where('language', $request->language);
        }
        
        // Recherche
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query = $query->filter(function($exercise) use ($search) {
                return str_contains(strtolower($exercise->language), $search) ||
                       str_contains(strtolower((string)$exercise->exercise_id), $search);
            });
        }
        
        // Tri
        $sortBy = $request->get('sort', 'completed_at');
        $sortOrder = $request->get('order', 'desc');
        
        if ($sortBy === 'score') {
            $query = $query->sortBy('score', SORT_NUMERIC, $sortOrder === 'desc');
        } elseif ($sortBy === 'language') {
            $query = $query->sortBy('language', SORT_REGULAR, $sortOrder === 'desc');
        } else {
            $query = $query->sortBy('completed_at', SORT_REGULAR, $sortOrder === 'desc');
        }
        
        $data['exerciseProgress'] = $query->values();
        $data['filters'] = $request->only(['status', 'language', 'search', 'sort', 'order']);
        
        return view('dashboard.exercices', $data);
    }
    
    /**
     * Page Quiz avec filtres et tri
     */
    public function quiz(Request $request)
    {
        // Définir la locale AVANT tout traitement
        $this->ensureLocale();
        
        $data = $this->getCommonData();
        $user = Auth::user();
        
        // Liste des langages disponibles (identique à PageController::quiz())
        $languages = [
            ['name' => trans('app.formations.languages.html5'), 'slug' => 'html5', 'icon' => 'fab fa-html5', 'color' => 'orange', 'questions' => 20],
            ['name' => trans('app.formations.languages.css3'), 'slug' => 'css3', 'icon' => 'fab fa-css3-alt', 'color' => 'blue', 'questions' => 20],
            ['name' => trans('app.formations.languages.javascript'), 'slug' => 'javascript', 'icon' => 'fab fa-js', 'color' => 'yellow', 'questions' => 20],
            ['name' => trans('app.formations.languages.php'), 'slug' => 'php', 'icon' => 'fab fa-php', 'color' => 'purple', 'questions' => 20],
            ['name' => trans('app.formations.languages.bootstrap'), 'slug' => 'bootstrap', 'icon' => 'fab fa-bootstrap', 'color' => 'purple', 'questions' => 15],
            ['name' => trans('app.formations.languages.git'), 'slug' => 'git', 'icon' => 'fab fa-git-alt', 'color' => 'red', 'questions' => 15],
            ['name' => trans('app.formations.languages.wordpress'), 'slug' => 'wordpress', 'icon' => 'fab fa-wordpress', 'color' => 'blue', 'questions' => 15],
            ['name' => trans('app.formations.languages.ia'), 'slug' => 'ia', 'icon' => 'fas fa-robot', 'color' => 'green', 'questions' => 15],
            ['name' => trans('app.formations.languages.python'), 'slug' => 'python', 'icon' => 'fab fa-python', 'color' => 'blue', 'questions' => 20],
            ['name' => trans('app.formations.languages.java'), 'slug' => 'java', 'icon' => 'fab fa-java', 'color' => 'orange', 'questions' => 20],
            ['name' => trans('app.formations.languages.sql'), 'slug' => 'sql', 'icon' => 'fas fa-database', 'color' => 'blue', 'questions' => 20],
            ['name' => trans('app.formations.languages.c'), 'slug' => 'c', 'icon' => 'fab fa-c', 'color' => 'gray', 'questions' => 20],
            ['name' => trans('app.formations.languages.cpp'), 'slug' => 'cpp', 'icon' => 'fab fa-cuttlefish', 'color' => 'blue', 'questions' => 20],
            ['name' => trans('app.formations.languages.csharp'), 'slug' => 'csharp', 'icon' => 'fab fa-microsoft', 'color' => 'green', 'questions' => 20],
            ['name' => trans('app.formations.languages.dart'), 'slug' => 'dart', 'icon' => 'fas fa-feather-alt', 'color' => 'blue', 'questions' => 20],
        ];
        
        // Enrichir chaque langage avec les statistiques de l'utilisateur
        $allQuizResults = $data['quizResults'];
        $languagesWithStats = [];
        
        foreach ($languages as $lang) {
            $langResults = $allQuizResults->where('language', $lang['slug']);
            $bestResult = $langResults->sortByDesc(function($result) {
                return $result->percentage;
            })->first();
            
            $languagesWithStats[] = [
                'name' => $lang['name'],
                'slug' => $lang['slug'],
                'icon' => $lang['icon'],
                'color' => $lang['color'],
                'questions' => $lang['questions'],
                'completed' => $langResults->count() > 0,
                'attempts' => $langResults->count(),
                'best_score' => $bestResult ? $bestResult->percentage : null,
                'last_completed' => $bestResult ? $bestResult->completed_at : null,
            ];
        }
        
        // Filtres pour l'historique
        $query = $data['quizResults'];
        
        // Filtre par langage
        if ($request->filled('language')) {
            $query = $query->where('language', $request->language);
        }
        
        // Filtre par score
        if ($request->filled('score_min')) {
            $scoreMin = $request->score_min;
            $query = $query->filter(function($result) use ($scoreMin) {
                return $result->percentage >= $scoreMin;
            });
        }
        
        // Recherche
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query = $query->filter(function($result) use ($search) {
                return str_contains(strtolower($result->language ?? ''), $search) ||
                       str_contains(strtolower($result->quiz_id ?? ''), $search);
            });
        }
        
        // Tri
        $sortBy = $request->get('sort', 'completed_at');
        $sortOrder = $request->get('order', 'desc');
        
        if ($sortBy === 'score') {
            $query = $query->sortBy(function($result) {
                return $result->percentage;
            }, SORT_NUMERIC, $sortOrder === 'desc');
        } elseif ($sortBy === 'language') {
            $query = $query->sortBy('language', SORT_REGULAR, $sortOrder === 'desc');
        } else {
            $query = $query->sortBy('completed_at', SORT_REGULAR, $sortOrder === 'desc');
        }
        
        $data['quizResults'] = $query->values();
        $data['filters'] = $request->only(['language', 'score_min', 'search', 'sort', 'order']);
        $data['languages'] = $languagesWithStats;
        
        return view('dashboard.quiz', $data);
    }
    
    /**
     * Page Objectifs
     */
    public function goals()
    {
        // Définir la locale AVANT tout traitement
        $this->ensureLocale();
        
        $data = $this->getCommonData();
        return view('dashboard.goals', $data);
    }
    
    /**
     * Page Activités avec filtres et tri
     */
    public function activities(Request $request)
    {
        // Définir la locale AVANT tout traitement
        $this->ensureLocale();
        
        $data = $this->getCommonData();
        
        // Construire la requête avec filtres
        $query = UserActivity::where('user_id', $data['user']->id)
            ->select('id', 'user_id', 'activity_type', 'activity_name', 'activity_slug', 'activity_data', 'created_at');
        
        // Filtre par type
        if ($request->filled('type')) {
            $query->where('activity_type', $request->type);
        }
        
        // Filtre par période
        if ($request->filled('period')) {
            $period = $request->period;
            if ($period === '7days') {
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
            } elseif ($period === '30days') {
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
            } elseif ($period === '3months') {
                $query->where('created_at', '>=', Carbon::now()->subMonths(3));
            }
        }
        
        // Recherche
        if ($request->filled('search')) {
            $query->where('activity_name', 'like', '%' . $request->search . '%');
        }
        
        // Tri
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $data['recentActivities'] = $query->paginate(20)->withQueryString();
        $data['filters'] = $request->only(['type', 'period', 'search', 'sort', 'order']);
        
        return view('dashboard.activities', $data);
    }
    
    /**
     * Page Statistiques
     */
    public function statistics()
    {
        // Définir la locale AVANT tout traitement
        $this->ensureLocale();
        
        $data = $this->getCommonData();
        return view('dashboard.statistics', $data);
    }
    
    /**
     * Page Paramètres (dépréciée - redirige vers profil)
     */
    public function settings()
    {
        return redirect()->route('dashboard.profile');
    }
    
    /**
     * Page Profil
     */
    public function profile()
    {
        // Définir la locale AVANT tout traitement
        $this->ensureLocale();
        
        $data = $this->getCommonData();
        return view('dashboard.profile', $data);
    }
    
    /**
     * Mise à jour du profil
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        // Si c'est un changement de mot de passe
        if ($request->filled('current_password') && $request->filled('password')) {
            $request->validate([
                'current_password' => 'required',
                'password' => 'required|min:8|confirmed',
            ]);
            
            // Vérifier le mot de passe actuel
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.'])->withInput();
            }
            
            // Mettre à jour le mot de passe
            $user->password = Hash::make($request->password);
            $user->save();
            
            // Invalider le cache
            self::clearCache($user->id);
            
            return redirect()->route('dashboard.profile')->with('success', 'Mot de passe modifié avec succès !');
        }
        
        // Sinon, mise à jour des informations personnelles
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',
            'phone_country' => 'nullable|string|max:2',
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        } else {
            $user->phone = null;
        }
        $user->save();
        
        // Invalider le cache
        self::clearCache($user->id);
        
        return redirect()->route('dashboard.profile')->with('success', 'Profil mis à jour avec succès !');
    }
    
    /**
     * Calcul des statistiques
     */
    private function calculateStats($user, $formationProgress, $exerciseProgress, $quizResults)
    {
        $totalTimeMinutes = $formationProgress->sum('time_spent_minutes') +
            ($exerciseProgress->sum('time_spent_seconds') / 60) +
            ($quizResults->sum('time_spent_seconds') / 60);

        $completedFormations = $formationProgress->where('progress_percentage', 100)->count();
        $completedExercises = $exerciseProgress->where('completed', true)->count();
        $quizCount = $quizResults->count();
        $averageQuizScore = $quizResults->count() > 0
            ? round($quizResults->avg('correct_answers'), 2)
            : 0;
        $averageCompletion = $formationProgress->count() > 0
            ? round($formationProgress->avg('progress_percentage'), 2)
            : 0;
        $ongoingFormations = $formationProgress->where('progress_percentage', '<', 100)->count();
        $totalExerciseScore = $exerciseProgress->where('completed', true)->sum('score');

        return [
            'total_time_minutes' => round($totalTimeMinutes),
            'total_time_hours' => round($totalTimeMinutes / 60, 1),
            'completed_formations' => $completedFormations,
            'ongoing_formations' => $ongoingFormations,
            'total_formations' => $formationProgress->count(),
            'completed_exercises' => $completedExercises,
            'total_exercises' => $exerciseProgress->count(),
            'quiz_count' => $quizCount,
            'average_quiz_score' => $averageQuizScore,
            'average_completion' => $averageCompletion,
            'total_exercise_score' => $totalExerciseScore,
        ];
    }
    
    /**
     * Préparation des données pour les graphiques
     * Optimisé pour éviter les requêtes N+1
     */
    private function prepareChartData($user, $formationProgress, $exerciseProgress, $quizResults)
    {
        $days = 30;
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();
        
        // Une seule requête pour toutes les activités des 30 derniers jours
        $activitiesByDate = UserActivity::where('user_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();
        
        $progressionOverTime = [];
        $labels = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            $labels[] = $date->format('d/m');
            $progressionOverTime[] = $activitiesByDate[$dateKey] ?? 0;
        }

        $activityDistribution = [
            'formations' => $formationProgress->count(),
            'exercises' => $exerciseProgress->count(),
            'quiz' => $quizResults->count(),
        ];

        $formationProgressData = [];
        $formationLabels = [];
        foreach ($formationProgress->take(10) as $progress) {
            $formationLabels[] = ucfirst(str_replace('-', ' ', $progress->formation_slug));
            $formationProgressData[] = $progress->progress_percentage;
        }

        $quizScores = [];
        $quizLabels = [];
        foreach ($quizResults->take(10) as $result) {
            $quizLabels[] = ucfirst($result->quiz_id ?? $result->language ?? 'Quiz');
            $quizScores[] = $result->percentage ?? 0;
        }

        return [
            'progression_over_time' => [
                'labels' => $labels,
                'data' => $progressionOverTime,
            ],
            'activity_distribution' => [
                'labels' => array_keys($activityDistribution),
                'data' => array_values($activityDistribution),
            ],
            'formation_progress' => [
                'labels' => $formationLabels,
                'data' => $formationProgressData,
            ],
            'quiz_scores' => [
                'labels' => $quizLabels,
                'data' => $quizScores,
            ],
        ];
    }
    
    /**
     * Génération de recommandations
     */
    /**
     * Génération de recommandations intelligentes et personnalisées
     */
    private function generateRecommendations($user, $formationProgress, $exerciseProgress, $quizResults)
    {
        $recommendations = [];
        
        // Toutes les formations disponibles avec leurs prérequis
        $allFormations = [
            'html5' => ['prerequis' => [], 'categorie' => 'frontend'],
            'css3' => ['prerequis' => ['html5'], 'categorie' => 'frontend'],
            'javascript' => ['prerequis' => ['html5'], 'categorie' => 'frontend'],
            'bootstrap' => ['prerequis' => ['html5', 'css3'], 'categorie' => 'frontend'],
            'php' => ['prerequis' => [], 'categorie' => 'backend'],
            'python' => ['prerequis' => [], 'categorie' => 'backend'],
            'java' => ['prerequis' => [], 'categorie' => 'backend'],
            'sql' => ['prerequis' => [], 'categorie' => 'database'],
            'c' => ['prerequis' => [], 'categorie' => 'system'],
            'git' => ['prerequis' => [], 'categorie' => 'tools'],
            'wordpress' => ['prerequis' => ['php', 'html5', 'css3'], 'categorie' => 'cms'],
            'ia' => ['prerequis' => ['python'], 'categorie' => 'advanced'],
        ];
        
        $startedFormations = $formationProgress->pluck('formation_slug')->toArray();
        $completedFormations = $formationProgress->where('progress_percentage', 100)->pluck('formation_slug')->toArray();
        
        // 1. Recommandation : Formations en cours à continuer
        $ongoing = $formationProgress->where('progress_percentage', '>', 0)
            ->where('progress_percentage', '<', 100)
            ->sortByDesc('updated_at')
            ->take(3);

        if ($ongoing->count() > 0) {
            $recommendations[] = [
                'type' => 'continue',
                'title' => trans('app.profile.dashboard.overview.recommendations.continue_title'),
                'description' => trans_choice('app.profile.dashboard.overview.recommendations.continue_description', $ongoing->count(), ['count' => $ongoing->count()]),
                'items' => $ongoing->pluck('formation_slug')->toArray(),
                'priority' => 'high',
                'icon' => 'fa-book-open',
            ];
        }
        
        // 2. Recommandation : Formations complémentaires basées sur les prérequis
        $recommendedFormations = [];
        foreach ($completedFormations as $completed) {
            foreach ($allFormations as $slug => $info) {
                if (!in_array($slug, $startedFormations) && 
                    in_array($completed, $info['prerequis'])) {
                    $recommendedFormations[] = $slug;
                }
            }
        }
        $recommendedFormations = array_unique($recommendedFormations);
        
        if (count($recommendedFormations) > 0) {
            $recommendations[] = [
                'type' => 'formation',
                'title' => trans('app.profile.dashboard.overview.recommendations.complementary_title'),
                'description' => trans('app.profile.dashboard.overview.recommendations.complementary_description'),
                'items' => array_slice($recommendedFormations, 0, 4),
                'priority' => 'medium',
                'icon' => 'fa-graduation-cap',
            ];
        }
        
        // 3. Recommandation : Nouvelles formations pour débuter
        $notStarted = array_diff(array_keys($allFormations), $startedFormations);
        if (count($notStarted) > 0 && count($startedFormations) === 0) {
            $recommendations[] = [
                'type' => 'formation',
                'title' => trans('app.profile.dashboard.overview.recommendations.start_learning_title'),
                'description' => trans('app.profile.dashboard.overview.recommendations.start_learning_description'),
                'items' => array_slice($notStarted, 0, 3),
                'priority' => 'high',
                'icon' => 'fa-rocket',
            ];
        } elseif (count($notStarted) > 0) {
            $recommendations[] = [
                'type' => 'formation',
                'title' => trans('app.profile.dashboard.overview.recommendations.explore_title'),
                'description' => trans_choice('app.profile.dashboard.overview.recommendations.explore_description', count($notStarted), ['count' => count($notStarted)]),
                'items' => array_slice($notStarted, 0, 3),
                'priority' => 'low',
                'icon' => 'fa-compass',
            ];
        }
        
        // 4. Recommandation : Exercices basés sur les formations complétées
        $completedExerciseLanguages = $exerciseProgress->where('completed', true)
            ->pluck('language')
            ->unique()
            ->toArray();
        
        $formationToLanguage = [
            'html5' => 'html',
            'css3' => 'css',
            'javascript' => 'javascript',
            'php' => 'php',
            'python' => 'python',
            'java' => 'java',
            'c' => 'c',
        ];
        
        $suggestedLanguages = [];
        foreach ($completedFormations as $formation) {
            if (isset($formationToLanguage[$formation])) {
                $lang = $formationToLanguage[$formation];
                if (!in_array($lang, $completedExerciseLanguages)) {
                    $suggestedLanguages[] = $lang;
                }
            }
        }
        
        if (count($suggestedLanguages) > 0) {
            $recommendations[] = [
                'type' => 'exercise',
                'title' => trans('app.profile.dashboard.overview.recommendations.practice_exercises_title'),
                'description' => trans('app.profile.dashboard.overview.recommendations.practice_exercises_description'),
                'items' => array_slice($suggestedLanguages, 0, 3),
                'priority' => 'medium',
                'icon' => 'fa-code',
            ];
        } elseif (count($completedExerciseLanguages) > 0) {
            $recommendations[] = [
                'type' => 'exercise',
                'title' => trans('app.profile.dashboard.overview.recommendations.keep_practicing_title'),
                'description' => trans('app.profile.dashboard.overview.recommendations.keep_practicing_description'),
                'items' => array_slice($completedExerciseLanguages, 0, 3),
                'priority' => 'low',
                'icon' => 'fa-dumbbell',
            ];
        }
        
        // 5. Recommandation : Quiz pour tester les connaissances
        $quizLanguages = $quizResults->pluck('language')->unique()->toArray();
        $lowScores = $quizResults->filter(function($result) {
                return $result->percentage < 70;
            })
            ->pluck('language')
            ->unique()
            ->toArray();
        
        if (count($lowScores) > 0) {
            $recommendations[] = [
                'type' => 'quiz',
                'title' => trans('app.profile.dashboard.overview.recommendations.improve_quiz_title'),
                'description' => trans('app.profile.dashboard.overview.recommendations.improve_quiz_description'),
                'items' => array_slice($lowScores, 0, 3),
                'priority' => 'high',
                'icon' => 'fa-redo',
            ];
        } elseif (count($quizLanguages) > 0) {
            $recommendations[] = [
                'type' => 'quiz',
                'title' => trans('app.profile.dashboard.overview.recommendations.test_knowledge_title'),
                'description' => trans('app.profile.dashboard.overview.recommendations.test_knowledge_description'),
                'items' => array_slice($quizLanguages, 0, 3),
                'priority' => 'medium',
                'icon' => 'fa-question-circle',
            ];
        } else {
            $recommendations[] = [
                'type' => 'quiz',
                'title' => trans('app.profile.dashboard.overview.recommendations.start_quiz_title'),
                'description' => trans('app.profile.dashboard.overview.recommendations.start_quiz_description'),
                'items' => ['html', 'css', 'javascript'],
                'priority' => 'medium',
                'icon' => 'fa-clipboard-check',
            ];
        }
        
        // 6. Recommandation : Objectifs non complétés (utiliser les données déjà chargées si disponibles)
        // Note: Les objectifs sont déjà chargés dans getCommonData, mais on peut les passer en paramètre
        // Pour l'instant, on fait une requête optimisée
        $incompleteGoals = UserGoal::where('user_id', $user->id)
            ->where('completed', false)
            ->orderBy('deadline', 'asc')
            ->limit(3)
            ->pluck('title')
            ->toArray();
        
        if (count($incompleteGoals) > 0) {
            $recommendations[] = [
                'type' => 'goal',
                'title' => 'Objectifs à compléter',
                'description' => 'Vous avez ' . count($incompleteGoals) . ' objectif(s) en cours. Concentrez-vous sur leur réalisation.',
                'items' => $incompleteGoals,
                'priority' => 'high',
                'icon' => 'fa-bullseye',
            ];
        }
        
        // Trier par priorité (high > medium > low)
        $priorityOrder = ['high' => 1, 'medium' => 2, 'low' => 3];
        usort($recommendations, function($a, $b) use ($priorityOrder) {
            return ($priorityOrder[$a['priority']] ?? 3) <=> ($priorityOrder[$b['priority']] ?? 3);
        });
        
        return array_slice($recommendations, 0, 5); // Limiter à 5 recommandations
    }

    /**
     * Afficher les cours payants de l'utilisateur
     */
    public function paidCourses()
    {
        $this->ensureLocale();
        $user = Auth::user();
        
        // Récupérer les cours achetés
        $purchases = CoursePurchase::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with(['course' => function($query) {
                $query->with('chapters');
            }])
            ->orderBy('purchased_at', 'desc')
            ->get();

        // Si l'utilisateur a un abonnement premium, ajouter tous les cours publiés
        if ($user->hasActivePremium()) {
            $premiumCourses = PaidCourse::where('status', 'published')
                ->with('chapters')
                ->whereNotIn('id', $purchases->pluck('paid_course_id'))
                ->get();
            
            // Créer des "purchases" virtuelles pour les cours premium
            foreach ($premiumCourses as $course) {
                $purchases->push((object)[
                    'id' => null,
                    'paid_course_id' => $course->id,
                    'status' => 'completed',
                    'purchased_at' => null,
                    'course' => $course,
                ]);
            }
        }

        // Pagination manuelle
        $perPage = 12;
        $currentPage = request()->get('page', 1);
        $items = $purchases->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $purchases = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $purchases->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $pageTitle = 'Mes Cours Payants';
        $pageDescription = 'Accédez à tous vos cours payants achetés';

        return view('dashboard.paid-courses', compact('purchases', 'pageTitle', 'pageDescription'))
            ->with('layout', 'dashboard.layout');
    }

    /**
     * Traiter le contenu markdown et convertir les blocs de code en HTML
     */
    private function processMarkdownContent($content)
    {
        if (empty($content)) {
            return $content;
        }

        // Détecter les blocs markdown ```langue\ncode\n```
        // Pattern amélioré pour gérer les cas avec ou sans saut de ligne après le langage
        $pattern = '/```(\w+)?\s*\n?([\s\S]*?)```/';
        
        return preg_replace_callback($pattern, function($matches) {
            $language = !empty($matches[1]) ? trim($matches[1]) : 'text';
            
            // Mapper les alias de langages vers les noms Prism.js
            $languageMap = [
                'js' => 'javascript',
                'py' => 'python',
                'rb' => 'ruby',
                'sh' => 'bash',
                'yml' => 'yaml',
                'md' => 'markdown',
            ];
            
            $language = strtolower($language);
            if (isset($languageMap[$language])) {
                $language = $languageMap[$language];
            }
            
            $code = trim($matches[2]);
            $codeId = 'code-' . uniqid();
            
            // Échapper le code pour l'HTML (mais garder les sauts de ligne)
            $escapedCode = htmlspecialchars($code, ENT_QUOTES, 'UTF-8');
            
            // Générer le HTML du bloc de code
            // IMPORTANT: Highlight.js nécessite <pre><code class="language-xxx"> pour fonctionner
            return sprintf(
                '<div class="code-box" data-language="%s" style="position: relative;">
                    <button class="copy-code-btn" onclick="copyCodeToClipboard(this, document.getElementById(\'%s\'))" title="Copier le code" style="position: absolute; top: 10px; right: 80px; background: #04AA6D; color: white; border: none; padding: 2px 10px; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: bold; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; z-index: 10; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); white-space: nowrap; height: auto; line-height: 1.4;">
                        <i class="fas fa-copy" style="margin-right: 5px; font-size: 12px;"></i>
                        <span>Copier</span>
                    </button>
                    <pre style="margin: 0; padding: 0; background: transparent !important;"><code id="%s" class="language-%s">%s</code></pre>
                </div>',
                htmlspecialchars($language, ENT_QUOTES, 'UTF-8'),
                $codeId,
                $codeId,
                htmlspecialchars($language, ENT_QUOTES, 'UTF-8'),
                $escapedCode
            );
        }, $content);
    }

    /**
     * Afficher un cours payant avec ses chapitres
     */
    public function showPaidCourse($courseId)
    {
        $this->ensureLocale();
        $user = Auth::user();
        
        // Vérifier que l'utilisateur a acheté ce cours ou a un abonnement premium
        $purchase = CoursePurchase::where('user_id', $user->id)
            ->where('paid_course_id', $courseId)
            ->where('status', 'completed')
            ->first();

        // Si pas d'achat, vérifier l'abonnement premium
        if (!$purchase && !$user->hasActivePremium()) {
            abort(403, 'Vous devez acheter ce cours ou avoir un abonnement premium pour y accéder.');
        }

        $course = PaidCourse::with(['chapters' => function($query) {
            $query->orderBy('order');
        }])->findOrFail($courseId);

        // Traiter le contenu de chaque chapitre pour convertir les blocs markdown
        foreach ($course->chapters as $chapter) {
            if (!empty($chapter->content)) {
                $chapter->content = $this->processMarkdownContent($chapter->content);
            }
        }

        $pageTitle = $course->title;
        $pageDescription = 'Suivez votre progression dans ce cours';

        return view('dashboard.paid-course-show', compact('course', 'purchase', 'pageTitle', 'pageDescription'))
            ->with('layout', 'dashboard.layout');
    }
    
    /**
     * Afficher les abonnements de l'utilisateur
     */
    public function subscriptions()
    {
        $this->ensureLocale();
        $user = Auth::user();
        
        $subscriptions = $user->subscriptions()
            ->with('payment')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $activeSubscription = $user->subscriptions()
            ->where('status', 'active')
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->first();
        
        $pageTitle = 'Mes Abonnements';
        $pageDescription = 'Gérez vos abonnements premium';
        
        return view('dashboard.subscriptions', compact('subscriptions', 'activeSubscription', 'pageTitle', 'pageDescription'));
    }
}
