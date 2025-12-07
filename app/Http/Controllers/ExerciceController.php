<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ExerciseProgress;
use App\Models\UserActivity;
use App\Http\Controllers\Concerns\LocaleTrait;

class ExerciceController extends Controller
{
    use LocaleTrait;

    /**
     * Liste des langages disponibles pour les exercices
     */
    public function index()
    {
        $locale = $this->ensureLocale();
        view()->share('currentLocale', $locale);
        
        $languages = [
            ['name' => trans('app.formations.languages.html5'), 'slug' => 'html5', 'icon' => 'fab fa-html5', 'color' => 'orange', 'exercises' => 25],
            ['name' => trans('app.formations.languages.css3'), 'slug' => 'css3', 'icon' => 'fab fa-css3-alt', 'color' => 'blue', 'exercises' => 30],
            ['name' => trans('app.formations.languages.javascript'), 'slug' => 'javascript', 'icon' => 'fab fa-js', 'color' => 'yellow', 'exercises' => 35],
            ['name' => trans('app.formations.languages.php'), 'slug' => 'php', 'icon' => 'fab fa-php', 'color' => 'purple', 'exercises' => 28],
            ['name' => trans('app.formations.languages.bootstrap'), 'slug' => 'bootstrap', 'icon' => 'fab fa-bootstrap', 'color' => 'purple', 'exercises' => 20],
            ['name' => trans('app.formations.languages.git'), 'slug' => 'git', 'icon' => 'fab fa-git-alt', 'color' => 'red', 'exercises' => 15],
            ['name' => trans('app.formations.languages.wordpress'), 'slug' => 'wordpress', 'icon' => 'fab fa-wordpress', 'color' => 'blue', 'exercises' => 18],
            ['name' => trans('app.formations.languages.ia'), 'slug' => 'ia', 'icon' => 'fas fa-robot', 'color' => 'green', 'exercises' => 12],
            ['name' => trans('app.formations.languages.python'), 'slug' => 'python', 'icon' => 'fab fa-python', 'color' => 'blue', 'exercises' => 22],
            ['name' => trans('app.formations.languages.java'), 'slug' => 'java', 'icon' => 'fab fa-java', 'color' => 'orange', 'exercises' => 25],
            ['name' => trans('app.formations.languages.sql'), 'slug' => 'sql', 'icon' => 'fas fa-database', 'color' => 'blue', 'exercises' => 20],
            ['name' => trans('app.formations.languages.c'), 'slug' => 'c', 'icon' => 'fab fa-c', 'color' => 'gray', 'exercises' => 18],
            ['name' => trans('app.formations.languages.cpp'), 'slug' => 'cpp', 'icon' => 'fab fa-cuttlefish', 'color' => 'blue', 'exercises' => 20],
            ['name' => trans('app.formations.languages.csharp'), 'slug' => 'csharp', 'icon' => 'fab fa-microsoft', 'color' => 'green', 'exercises' => 22],
            ['name' => trans('app.formations.languages.dart'), 'slug' => 'dart', 'icon' => 'fas fa-feather-alt', 'color' => 'blue', 'exercises' => 18],
        ];
        
        $response = response()->view('exercices', compact('languages'));
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        $response->headers->set('X-Locale', $locale);
        
        return $response;
    }

    /**
     * Liste des exercices pour un langage donné
     */
    public function language($language)
    {
        $this->ensureLocale();
        
        $allExercises = $this->getExercisesByLanguage($language);
        
        // Varier les exercices par utilisateur (basé sur IP + session)
        $userIdentifier = md5(request()->ip() . session()->getId() . $language);
        $exercises = !empty($allExercises) ? $this->getVariedExercises($allExercises, $userIdentifier) : [];
        
        // Récupérer la progression de l'utilisateur si connecté - Optimisé avec eager loading
        $exerciseProgress = collect();
        if (Auth::check()) {
            $exerciseProgress = ExerciseProgress::where('user_id', Auth::id())
                ->where('language', $language)
                ->get()
                ->keyBy(function($progress) {
                    return $progress->exercise_id;
                });
        }
        
        return view('exercices-language', compact('language', 'exercises', 'exerciseProgress'));
    }

    /**
     * Détail d'un exercice
     */
    public function detail($language, $id)
    {
        $this->ensureLocale();
        
        $allExercises = $this->getExercisesByLanguage($language);
        $userIdentifier = md5(request()->ip() . session()->getId() . $language);
        $variedExercises = $this->getVariedExercises($allExercises, $userIdentifier);
        
        $exercise = null;
        foreach ($variedExercises as $ex) {
            if (isset($ex['display_index']) && $ex['display_index'] == $id) {
                if (isset($ex['original_index'])) {
                    $originalIndex = $ex['original_index'];
                    $exercise = $this->getExerciseDetail($language, $originalIndex);
                    if ($exercise) {
                        $exercise['display_index'] = $id;
                    }
                } else {
                    $exerciseTitle = $ex['title'];
                    $originalIndex = $this->findExerciseIndexByTitle($language, $exerciseTitle);
                    
                    if ($originalIndex) {
                        $exercise = $this->getExerciseDetail($language, $originalIndex);
                        if ($exercise) {
                            $exercise['display_index'] = $id;
                        }
                    }
                }
                break;
            }
        }
        
        if (!$exercise) {
            abort(404, 'Exercice non trouvé pour le langage: ' . $language . ' et l\'ID: ' . $id);
        }
        
        $totalExercises = count($variedExercises);
        
        return view('exercice-detail', compact('language', 'id', 'exercise', 'totalExercises'));
    }

    /**
     * Soumettre une solution d'exercice
     */
    public function submit(Request $request, $language, $id)
    {
        $allExercises = $this->getExercisesByLanguage($language);
        $userIdentifier = md5(request()->ip() . session()->getId() . $language);
        $variedExercises = $this->getVariedExercises($allExercises, $userIdentifier);
        
        $exercise = null;
        $originalIndex = null;
        
        foreach ($variedExercises as $ex) {
            if (isset($ex['display_index']) && $ex['display_index'] == $id) {
                $exerciseTitle = $ex['title'];
                $originalIndex = $this->findExerciseIndexByTitle($language, $exerciseTitle);
                
                if ($originalIndex) {
                    $exercise = $this->getExerciseDetail($language, $originalIndex);
                }
                break;
            }
        }
        
        if (!$exercise) {
            return response()->json(['correct' => false, 'message' => 'Exercice non trouvé']);
        }
        
        $userCode = $request->input('code');
        $isCorrect = $this->checkAnswer($exercise, $userCode);
        
        // Enregistrer la progression et l'activité si l'utilisateur est connecté
        if (Auth::check() && $isCorrect) {
            $user = Auth::user();
            $realExerciseId = $originalIndex ?? $id;
            
            $exerciseProgress = ExerciseProgress::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'exercise_id' => $realExerciseId,
                    'language' => $language,
                ],
                [
                    'completed' => false,
                    'score' => 0,
                    'time_spent_seconds' => 0,
                ]
            );
            
            if ($isCorrect && !$exerciseProgress->completed) {
                $exerciseProgress->markAsCompleted(100, 0, $userCode);
                
                UserActivity::log(
                    $user->id,
                    'exercise',
                    'Exercice complété : ' . ($exercise['title'] ?? 'Exercice ' . $language),
                    "exercices/{$language}/{$id}",
                    [
                        'score' => 100,
                        'language' => $language,
                        'exercise_id' => $realExerciseId,
                        'display_index' => $id,
                        'exercise_title' => $exercise['title'] ?? null,
                    ]
                );
                
                \App\Http\Controllers\ProfileController::clearCache($user->id);
            }
        }
        
        return response()->json([
            'correct' => $isCorrect,
            'message' => $isCorrect ? 'Bravo ! Votre réponse est correcte !' : 'Pas tout à fait. Réessayez !',
            'solution' => $isCorrect ? null : $exercise['solution']
        ]);
    }

    /**
     * Exécuter du code (sandbox)
     */
    public function runCode(Request $request, $language)
    {
        // Déléguer temporairement à PageController pour éviter de dupliquer le code long
        // TODO: Extraire cette méthode dans un service dédié
        $pageController = new PageController();
        return $pageController->runCode($request, $language);
    }

    // ============================================
    // Méthodes privées (héritées de PageController)
    // TODO: Extraire ces méthodes dans un trait ou service
    // ============================================

    /**
     * Obtenir des exercices variés par utilisateur
     */
    private function getVariedExercises($allExercises, $userIdentifier)
    {
        $easyKey = trans('app.exercices.difficulty.easy');
        $mediumKey = trans('app.exercices.difficulty.medium');
        $hardKey = trans('app.exercices.difficulty.hard');
        
        $byDifficulty = [
            $easyKey => [],
            $mediumKey => [],
            $hardKey => []
        ];
        
        foreach ($allExercises as $index => $exercise) {
            $difficulty = $exercise['difficulty'] ?? $easyKey;
            
            if ($difficulty === $easyKey || $difficulty === 'Facile' || $difficulty === 'Easy') {
                $exercise['difficulty'] = $easyKey;
                $byDifficulty[$easyKey][] = array_merge($exercise, ['original_index' => $index + 1]);
            } elseif ($difficulty === $mediumKey || $difficulty === 'Moyen' || $difficulty === 'Medium') {
                $exercise['difficulty'] = $mediumKey;
                $byDifficulty[$mediumKey][] = array_merge($exercise, ['original_index' => $index + 1]);
            } elseif ($difficulty === $hardKey || $difficulty === 'Difficile' || $difficulty === 'Hard') {
                $exercise['difficulty'] = $hardKey;
                $byDifficulty[$hardKey][] = array_merge($exercise, ['original_index' => $index + 1]);
            } else {
                $exercise['difficulty'] = $easyKey;
                $byDifficulty[$easyKey][] = array_merge($exercise, ['original_index' => $index + 1]);
            }
        }
        
        $selectedExercises = [];
        
        if (count($byDifficulty[$easyKey]) > 0) {
            foreach ($byDifficulty[$easyKey] as $exercise) {
                $selectedExercises[] = $exercise;
            }
        }
        
        if (count($byDifficulty[$mediumKey]) > 0) {
            foreach ($byDifficulty[$mediumKey] as $exercise) {
                $selectedExercises[] = $exercise;
            }
        }
        
        if (count($byDifficulty[$hardKey]) > 0) {
            foreach ($byDifficulty[$hardKey] as $exercise) {
                $selectedExercises[] = $exercise;
            }
        }
        
        shuffle($selectedExercises);
        
        foreach ($selectedExercises as $index => &$exercise) {
            $exercise['display_index'] = $index + 1;
        }
        
        return $selectedExercises;
    }

    /**
     * Trouver l'index d'un exercice par son titre
     */
    private function findExerciseIndexByTitle($language, $title)
    {
        $allExercises = $this->getExercisesByLanguage($language);
        
        foreach ($allExercises as $index => $exercise) {
            if (isset($exercise['title']) && $exercise['title'] === $title) {
                $potentialId = $index + 1;
                $exerciseDetail = $this->getExerciseDetail($language, $potentialId);
                if ($exerciseDetail && isset($exerciseDetail['title']) && $exerciseDetail['title'] === $title) {
                    return $potentialId;
                }
            }
        }
        
        for ($i = 1; $i <= 20; $i++) {
            $exercise = $this->getExerciseDetail($language, $i);
            if ($exercise && isset($exercise['title']) && $exercise['title'] === $title) {
                return $i;
            }
        }
        
        return null;
    }

    /**
     * Vérifier si la réponse est correcte
     */
    private function checkAnswer($exercise, $userCode)
    {
        $userCodeNormalized = preg_replace('/\s+/', '', strtolower($userCode));
        $solutionNormalized = preg_replace('/\s+/', '', strtolower($exercise['solution']));
        
        return $userCodeNormalized === $solutionNormalized;
    }

    /**
     * Obtenir les exercices par langage
     * NOTE: Cette méthode est très longue (~6000 lignes)
     * TODO: Extraire dans un service ou fichier séparé
     */
    private function getExercisesByLanguage($language)
    {
        // Déléguer temporairement à PageController
        // TODO: Extraire cette méthode dans un service dédié
        $pageController = new PageController();
        $reflection = new \ReflectionClass($pageController);
        $method = $reflection->getMethod('getExercisesByLanguage');
        $method->setAccessible(true);
        return $method->invoke($pageController, $language);
    }

    /**
     * Obtenir le détail d'un exercice
     * NOTE: Cette méthode est très longue (~6000 lignes)
     * TODO: Extraire dans un service ou fichier séparé
     */
    private function getExerciseDetail($language, $id)
    {
        // Déléguer temporairement à PageController
        // TODO: Extraire cette méthode dans un service dédié
        $pageController = new PageController();
        $reflection = new \ReflectionClass($pageController);
        $method = $reflection->getMethod('getExerciseDetail');
        $method->setAccessible(true);
        return $method->invoke($pageController, $language, $id);
    }
}

