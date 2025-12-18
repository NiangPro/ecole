<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QuizResult;
use App\Models\UserActivity;
use App\Http\Controllers\Concerns\LocaleTrait;

class QuizController extends Controller
{
    use LocaleTrait;

    /**
     * Liste des langages disponibles pour les quiz
     */
    public function index()
    {
        $locale = $this->ensureLocale();
        
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
            ['name' => trans('app.formations.languages.go'), 'slug' => 'go', 'icon' => 'fab fa-golang', 'color' => 'blue', 'questions' => 20],
            ['name' => trans('app.formations.languages.swift'), 'slug' => 'swift', 'icon' => 'fab fa-swift', 'color' => 'orange', 'questions' => 20],
            ['name' => trans('app.formations.languages.perl'), 'slug' => 'perl', 'icon' => 'fas fa-code', 'color' => 'blue', 'questions' => 20],
            ['name' => trans('app.formations.languages.typescript'), 'slug' => 'typescript', 'icon' => 'fab fa-js-square', 'color' => 'blue', 'questions' => 20],
            ['name' => trans('app.formations.languages.rust'), 'slug' => 'rust', 'icon' => 'fab fa-rust', 'color' => 'black', 'questions' => 20],
            ['name' => trans('app.formations.languages.ruby'), 'slug' => 'ruby', 'icon' => 'fas fa-gem', 'color' => 'red', 'questions' => 20],
            ['name' => trans('app.formations.languages.cybersecurite'), 'slug' => 'cybersecurite', 'icon' => 'fas fa-shield-alt', 'color' => 'orange', 'questions' => 20],
            ['name' => trans('app.formations.languages.data-science'), 'slug' => 'data-science', 'icon' => 'fas fa-chart-line', 'color' => 'blue', 'questions' => 20],
            ['name' => trans('app.formations.languages.big-data'), 'slug' => 'big-data', 'icon' => 'fas fa-database', 'color' => 'purple', 'questions' => 20],
        ];
        
        return view('quiz', compact('languages'));
    }

    /**
     * Afficher le quiz pour un langage donné
     */
    public function language($language)
    {
        $this->ensureLocale();
        
        $questions = $this->getQuizQuestions($language);
        
        if (empty($questions)) {
            abort(404);
        }
        
        $translatedQuestions = $this->translateQuizQuestions($language, $questions);
        
        return view('quiz-language', compact('language'))->with('questions', $translatedQuestions);
    }

    /**
     * Soumettre les réponses du quiz
     */
    public function submit(Request $request, $language)
    {
        $this->ensureLocale();
        
        $questions = $this->getQuizQuestions($language);
        $translatedQuestions = $this->translateQuizQuestions($language, $questions);
        $answers = $request->input('answers', []);
        
        $score = 0;
        $total = count($translatedQuestions);
        $results = [];
        
        foreach ($translatedQuestions as $index => $question) {
            $userAnswer = $answers[$index] ?? null;
            $isCorrect = $userAnswer == $question['correct'];
            
            if ($isCorrect) {
                $score++;
            }
            
            $results[] = [
                'question' => $question['question'],
                'userAnswer' => $userAnswer,
                'correctAnswer' => $question['correct'],
                'isCorrect' => $isCorrect,
                'options' => $question['options']
            ];
        }
        
        $percentage = ($score / $total) * 100;
        
        // Enregistrer le résultat du quiz si l'utilisateur est connecté
        if (Auth::check()) {
            $user = Auth::user();
            
            $correctAnswers = $score;
            $wrongAnswers = $total - $score;
            
            $quizResult = QuizResult::create([
                'user_id' => $user->id,
                'quiz_id' => $language,
                'language' => $language,
                'score' => $score,
                'total_questions' => $total,
                'correct_answers' => $correctAnswers,
                'wrong_answers' => $wrongAnswers,
                'answers' => $results,
                'completed_at' => now(),
            ]);
            
            UserActivity::log(
                $user->id,
                'quiz',
                'Quiz complété : ' . ucfirst($language),
                "quiz/{$language}",
                [
                    'score' => $score,
                    'total_questions' => $total,
                    'percentage' => round($percentage, 2),
                    'language' => $language,
                ]
            );
            
            \App\Http\Controllers\ProfileController::clearCache($user->id);
        }
        
        // Stocker les résultats en session pour éviter la re-soumission
        session([
            'quiz_results_' . $language => [
                'score' => $score,
                'total' => $total,
                'percentage' => $percentage,
                'results' => $results
            ]
        ]);
        
        return redirect()->route('quiz.result', $language);
    }

    /**
     * Afficher les résultats du quiz
     */
    public function result($language)
    {
        $this->ensureLocale();
        
        $sessionKey = 'quiz_results_' . $language;
        $quizData = session($sessionKey);
        
        if (!$quizData) {
            return redirect()->route('quiz.language', $language)
                ->with('error', trans('app.quiz.result.no_results'));
        }
        
        session()->forget($sessionKey);
        
        $translatedResults = [];
        foreach ($quizData['results'] as $result) {
            $translatedResults[] = $result;
        }
        
        return view('quiz-result', [
            'language' => $language,
            'score' => $quizData['score'],
            'total' => $quizData['total'],
            'percentage' => $quizData['percentage'],
            'results' => $translatedResults
        ]);
    }

    // ============================================
    // Méthodes privées
    // ============================================

    /**
     * Traduire les questions du quiz
     */
    private function translateQuizQuestions($language, $questions)
    {
        $translatedQuestions = [];
        
        foreach ($questions as $index => $question) {
            $questionId = $index + 1;
            
            $translation = trans("quiz.{$language}.{$questionId}", [], app()->getLocale());
            
            if (is_array($translation) && isset($translation['question']) && isset($translation['options'])) {
                $translatedQuestions[] = [
                    'question' => $translation['question'],
                    'options' => array_values($translation['options']),
                    'correct' => $question['correct']
                ];
            } else {
                $translatedQuestions[] = [
                    'question' => $question['question'],
                    'options' => $question['options'],
                    'correct' => $question['correct']
                ];
            }
        }
        
        return $translatedQuestions;
    }

    /**
     * Obtenir les questions du quiz pour un langage
     * NOTE: Cette méthode est très longue
     * TODO: Extraire dans un service ou fichier séparé
     */
    private function getQuizQuestions($language)
    {
        // Déléguer temporairement à PageController
        // TODO: Extraire cette méthode dans un service dédié
        $pageController = new \App\Http\Controllers\PageController();
        $reflection = new \ReflectionClass($pageController);
        $method = $reflection->getMethod('getQuizQuestions');
        $method->setAccessible(true);
        return $method->invoke($pageController, $language);
    }
}

