<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Concerns\LocaleTrait;
use App\Services\RecaptchaService;

class PageController extends Controller
{
    use LocaleTrait;
    
    public function index()
    {
        // Forcer la locale AVANT tout traitement pour la traduction
        $locale = $this->ensureLocale();
        
        // Cache les 9 derniers articles publiés (15 minutes) - Optimisé avec eager loading
        $latestJobs = \Illuminate\Support\Facades\Cache::remember('latest_jobs', 900, function () {
            return \App\Models\JobArticle::where('status', 'published')
                ->where('is_sponsored', false)
                ->with(['category:id,name,slug']) // Eager loading optimisé
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'created_at', 'views')
                ->orderBy('created_at', 'desc')
                ->take(9)
                ->get();
        });
        
        // Cache les catégories actives (15 minutes) - Optimisé avec eager loading
        $categories = \Illuminate\Support\Facades\Cache::remember('active_categories_homepage', 900, function () {
            return \App\Models\Category::where('is_active', true)
                ->withCount(['publishedArticles' => function ($query) {
                    $query->where('status', 'published');
                }])
                ->orderBy('order', 'asc')
                ->orderBy('name', 'asc')
                ->get();
        });
        
        // Cache les articles sponsorisés (15 minutes) - Optimisé avec eager loading
        $sponsoredArticles = \Illuminate\Support\Facades\Cache::remember('sponsored_articles', 900, function () {
            return \App\Models\JobArticle::where('status', 'published')
                ->where('is_sponsored', true)
                ->with(['category:id,name,slug']) // Eager loading optimisé
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->take(2)
                ->get();
        });
        
        // Cache les publicités pour la position "content" (30 minutes)
        $sidebarAds = \Illuminate\Support\Facades\Cache::remember('sidebar_ads_content', 1800, function () {
            return \App\Models\Ad::active()
                ->forPosition('content')
                ->whereNull('location')
                ->orderBy('order')
                ->get();
        });
        
        // Cache les publicités pour l'emplacement "homepage_after_exercises" (30 minutes)
        $homepageAds = \Illuminate\Support\Facades\Cache::remember('homepage_ads_after_exercises', 1800, function () {
            return \App\Models\Ad::active()
                ->forLocation('homepage_after_exercises')
                ->orderBy('order')
                ->get();
        });
        
        // Cache les articles conseils et carrières pour la sidebar (15 minutes) - Triés du plus récent au plus ancien
        $careerAdviceArticles = \Illuminate\Support\Facades\Cache::remember('career_advice_articles', 900, function () {
            $conseilsCategory = \App\Models\Category::where('slug', 'conseils-carriere')->first();
            if ($conseilsCategory) {
                return \App\Models\JobArticle::where('status', 'published')
                    ->where('category_id', $conseilsCategory->id)
                    ->with('category:id,name,slug')
                    ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                    ->orderBy('published_at', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->take(9)
                    ->get();
            }
            return collect();
        });
        
        // Cache les cours payants publiés (15 minutes) - Optimisé avec eager loading
        $paidCourses = \Illuminate\Support\Facades\Cache::remember('homepage_paid_courses', 900, function () {
            return \App\Models\PaidCourse::where('status', 'published')
                ->select('id', 'title', 'slug', 'description', 'cover_image', 'cover_type', 'price', 'currency', 'discount_price', 'discount_start', 'discount_end', 'duration_hours', 'students_count', 'rating', 'reviews_count', 'created_at')
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();
        });
        
        return view('index', compact('latestJobs', 'categories', 'sponsoredArticles', 'sidebarAds', 'homepageAds', 'careerAdviceArticles', 'paidCourses'));
    }

    public function about()
    {
        $this->ensureLocale();
        $achievements = \App\Models\Achievement::visible()->ordered()->get();
        $showAchievementsSection = \App\Models\SiteSetting::get('show_achievements_section', true);
        return view('about', compact('achievements', 'showAchievementsSection'));
    }
    
    public function contact()
    {
        return view('contact');
    }
    
    public function docs()
    {
        $this->ensureLocale();
        return view('docs');
    }
    
    public function sendContact(Request $request)
    {
        // Vérifier le Honeypot
        if ($request->has('website') && !empty($request->input('website'))) {
            // Bot détecté - rejeter silencieusement
            abort(403, 'Spam détecté');
        }

        // Vérifier le temps de remplissage du formulaire
        if ($request->has('_form_time')) {
            $formTime = (float) $request->input('_form_time');
            $submitTime = microtime(true);
            $timeDiff = $submitTime - $formTime;

            // Si le formulaire a été soumis en moins de 2 secondes, c'est probablement un bot
            if ($timeDiff < 2) {
                abort(403, 'Spam détecté');
            }
        }

        // Vérifier reCAPTCHA si configuré
        $recaptchaService = new \App\Services\RecaptchaService();
        if (!empty(config('services.recaptcha.secret_key'))) {
            $recaptchaToken = $request->input('g-recaptcha-response');
            if (!$recaptchaService->verify($recaptchaToken, $request->ip())) {
                return back()->with('error', 'Vérification anti-spam échouée. Veuillez réessayer.');
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        ContactMessage::create($request->all());
        
        return back()->with('success', 'Votre message a été envoyé avec succès! Nous vous répondrons dans les plus brefs délais.');
    }

    public function faq()
    {
        return view('faq');
    }


    public function allLinks()
    {
        // Utiliser www.niangprogrammeur.com en production
        $appUrl = url('/');
        if (str_contains($appUrl, 'niangprogrammeur.com')) {
            $baseUrl = 'https://www.niangprogrammeur.com';
        } else {
            $baseUrl = $appUrl;
        }
        $localUrl = 'http://127.0.0.1:8000';
        $languages = [
            ['name' => 'HTML5', 'slug' => 'html5'],
            ['name' => 'CSS3', 'slug' => 'css3'],
            ['name' => 'JavaScript', 'slug' => 'javascript'],
            ['name' => 'PHP', 'slug' => 'php'],
            ['name' => 'Bootstrap', 'slug' => 'bootstrap'],
            ['name' => 'Git', 'slug' => 'git'],
            ['name' => 'WordPress', 'slug' => 'wordpress'],
            ['name' => 'IA', 'slug' => 'ia'],
            ['name' => 'Python', 'slug' => 'python'],
        ];
        
        // Récupérer les 49 derniers articles
        $recentArticles = \App\Models\JobArticle::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(49)
            ->get(['slug']);
        
        // Générer tous les liens (production + local)
        $allLinks = [];
        
        // URL de base
        $allLinks[] = $baseUrl . '/';
        $allLinks[] = $localUrl . '/';
        
        // Formations
        $allLinks[] = $baseUrl . '/formations';
        $allLinks[] = $localUrl . '/formations';
        foreach($languages as $lang) {
            $allLinks[] = $baseUrl . '/formations/' . $lang['slug'];
            $allLinks[] = $localUrl . '/formations/' . $lang['slug'];
        }
        
        // Exercices
        $allLinks[] = $baseUrl . '/exercices';
        $allLinks[] = $localUrl . '/exercices';
        foreach($languages as $lang) {
            $allLinks[] = $baseUrl . '/exercices/' . $lang['slug'];
            $allLinks[] = $localUrl . '/exercices/' . $lang['slug'];
        }
        
        // Quiz
        $allLinks[] = $baseUrl . '/quiz';
        $allLinks[] = $localUrl . '/quiz';
        foreach($languages as $lang) {
            $allLinks[] = $baseUrl . '/quiz/' . $lang['slug'];
            $allLinks[] = $localUrl . '/quiz/' . $lang['slug'];
        }
        
        // Articles (49 articles)
        foreach($recentArticles as $article) {
            $allLinks[] = $baseUrl . '/emplois/article/' . $article->slug;
            $allLinks[] = $localUrl . '/emplois/article/' . $article->slug;
        }
        
        $allLinksText = implode("\n", $allLinks);
        
        return view('all-links', compact('baseUrl', 'localUrl', 'languages', 'allLinksText', 'recentArticles'));
    }

    public function exercices()
    {
        // Forcer la locale AVANT tout traitement
        $locale = $this->ensureLocale();
        
        // Forcer la recompilation de la vue si nécessaire (en production, le cache peut être problématique)
        // On utilise view()->share() pour s'assurer que la locale est bien passée à la vue
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
        
        // Créer la réponse avec des headers pour empêcher le cache
        $response = response()->view('exercices', compact('languages'));
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        $response->headers->set('X-Locale', $locale);
        
        return $response;
    }

    public function exercicesLanguage($language)
    {
        // Forcer la locale
        $this->ensureLocale();
        
        $allExercises = $this->getExercisesByLanguage($language);
        
        // Varier les exercices par utilisateur (basé sur IP + session)
        $userIdentifier = md5(request()->ip() . session()->getId() . $language);
        $exercises = !empty($allExercises) ? $this->getVariedExercises($allExercises, $userIdentifier) : [];
        
        // Récupérer la progression de l'utilisateur si connecté
        $exerciseProgress = collect();
        if (Auth::check()) {
            $exerciseProgress = \App\Models\ExerciseProgress::where('user_id', Auth::id())
                ->where('language', $language)
                ->get()
                ->keyBy(function($progress) {
                    return $progress->exercise_id;
                });
        }
        
        return view('exercices-language', compact('language', 'exercises', 'exerciseProgress'));
    }
    
    /**
     * Obtenir des exercices variés par utilisateur pour tous les niveaux
     */
    private function getVariedExercises($allExercises, $userIdentifier)
    {
        // Organiser les exercices par niveau
        // Utiliser les clés de traduction pour normaliser la difficulté
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
            // Normaliser la difficulté en comparant avec les traductions
            // Normaliser la difficulté pour qu'elle corresponde toujours à la traduction actuelle
            if ($difficulty === $easyKey || $difficulty === 'Facile' || $difficulty === 'Easy') {
                $exercise['difficulty'] = $easyKey; // S'assurer que la difficulté utilise la traduction
                $byDifficulty[$easyKey][] = array_merge($exercise, ['original_index' => $index + 1]);
            } elseif ($difficulty === $mediumKey || $difficulty === 'Moyen' || $difficulty === 'Medium') {
                $exercise['difficulty'] = $mediumKey; // S'assurer que la difficulté utilise la traduction
                $byDifficulty[$mediumKey][] = array_merge($exercise, ['original_index' => $index + 1]);
            } elseif ($difficulty === $hardKey || $difficulty === 'Difficile' || $difficulty === 'Hard') {
                $exercise['difficulty'] = $hardKey; // S'assurer que la difficulté utilise la traduction
                $byDifficulty[$hardKey][] = array_merge($exercise, ['original_index' => $index + 1]);
            } else {
                // Par défaut, mettre dans facile
                $exercise['difficulty'] = $easyKey;
                $byDifficulty[$easyKey][] = array_merge($exercise, ['original_index' => $index + 1]);
            }
        }
        
        // Utiliser l'identifiant utilisateur pour créer une variation
        $seed = hexdec(substr($userIdentifier, 0, 8));
        mt_srand($seed);
        
        $selectedExercises = [];
        
        // Prendre TOUS les exercices de chaque niveau (5 Facile + 5 Moyen + 5 Difficile = 15)
        // Facile: tous les exercices (5)
        if (count($byDifficulty[$easyKey]) > 0) {
            foreach ($byDifficulty[$easyKey] as $exercise) {
                $selectedExercises[] = $exercise;
            }
        }
        
        // Moyen: tous les exercices (5)
        if (count($byDifficulty[$mediumKey]) > 0) {
            foreach ($byDifficulty[$mediumKey] as $exercise) {
                $selectedExercises[] = $exercise;
            }
        }
        
        // Difficile: tous les exercices (5)
        if (count($byDifficulty[$hardKey]) > 0) {
            foreach ($byDifficulty[$hardKey] as $exercise) {
                $selectedExercises[] = $exercise;
            }
        }
        
        // Mélanger l'ordre pour plus de variété
        shuffle($selectedExercises);
        
        // Réindexer pour les URLs
        foreach ($selectedExercises as $index => &$exercise) {
            $exercise['display_index'] = $index + 1;
        }
        
        return $selectedExercises;
    }
    
    /**
     * Sélectionner des exercices aléatoires de manière déterministe
     */
    private function selectRandomExercises($exercises, $count)
    {
        if (count($exercises) <= $count) {
            return $exercises;
        }
        
        $selected = [];
        $indices = range(0, count($exercises) - 1);
        shuffle($indices);
        
        for ($i = 0; $i < $count; $i++) {
            $selected[] = $exercises[$indices[$i]];
        }
        
        return $selected;
    }

    public function exerciceDetail($language, $id)
    {
        // Forcer la locale
        $this->ensureLocale();
        
        // Récupérer tous les exercices pour trouver celui correspondant à l'index d'affichage
        $allExercises = $this->getExercisesByLanguage($language);
        
        // Varier les exercices par utilisateur (même logique que dans exercicesLanguage)
        $userIdentifier = md5(request()->ip() . session()->getId() . $language);
        $variedExercises = $this->getVariedExercises($allExercises, $userIdentifier);
        
        // Trouver l'exercice par son index d'affichage
        $exercise = null;
        foreach ($variedExercises as $ex) {
            if (isset($ex['display_index']) && $ex['display_index'] == $id) {
                // Utiliser original_index s'il existe (correspond à l'ID dans getExerciseDetail)
                if (isset($ex['original_index'])) {
                    $originalIndex = $ex['original_index'];
                    $exercise = $this->getExerciseDetail($language, $originalIndex);
                    if ($exercise) {
                        $exercise['display_index'] = $id;
                    }
                } else {
                    // Fallback: chercher par titre
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
        
        // Compter le nombre total d'exercices pour ce langage
        $totalExercises = count($variedExercises);
        
        return view('exercice-detail', compact('language', 'id', 'exercise', 'totalExercises'));
    }
    
    /**
     * Trouver l'index d'un exercice par son titre
     */
    private function findExerciseIndexByTitle($language, $title)
    {
        // Récupérer tous les exercices pour ce langage
        $allExercises = $this->getExercisesByLanguage($language);
        
        // Parcourir tous les exercices pour trouver celui avec le bon titre
        foreach ($allExercises as $index => $exercise) {
            if (isset($exercise['title']) && $exercise['title'] === $title) {
                // L'index dans le tableau commence à 0, mais les IDs d'exercices commencent à 1
                // Pour trouver l'ID réel, on doit chercher dans getExerciseDetail
                // On utilise l'index + 1 comme ID potentiel
                $potentialId = $index + 1;
                
                // Vérifier que cet ID existe dans getExerciseDetail
                $exerciseDetail = $this->getExerciseDetail($language, $potentialId);
                if ($exerciseDetail && isset($exerciseDetail['title']) && $exerciseDetail['title'] === $title) {
                    return $potentialId;
                }
            }
        }
        
        // Si pas trouvé, essayer de parcourir getExerciseDetail directement
        // Limite à 20 exercices max par langage
        for ($i = 1; $i <= 20; $i++) {
            $exercise = $this->getExerciseDetail($language, $i);
            if ($exercise && isset($exercise['title']) && $exercise['title'] === $title) {
                return $i;
            }
        }
        
        return null;
    }

    public function exerciceSubmit(Request $request, $language, $id)
    {
        // Utiliser la même logique que exerciceDetail pour trouver l'exercice
        $allExercises = $this->getExercisesByLanguage($language);
        $userIdentifier = md5(request()->ip() . session()->getId() . $language);
        $variedExercises = $this->getVariedExercises($allExercises, $userIdentifier);
        
        $exercise = null;
        foreach ($variedExercises as $ex) {
            if (isset($ex['display_index']) && $ex['display_index'] == $id) {
                // Utiliser le titre pour trouver l'ID réel
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
        
        // Vérification simple (à améliorer)
        $isCorrect = $this->checkAnswer($exercise, $userCode);
        
        // Enregistrer la progression et l'activité si l'utilisateur est connecté
        if (Auth::check() && $isCorrect) {
            $user = Auth::user();
            
            // Utiliser l'ID réel (originalIndex) au lieu du display_index
            $realExerciseId = $originalIndex ?? $id;
            
            // Sauvegarder ou mettre à jour la progression de l'exercice
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
            
            // Marquer comme complété si c'est correct
            if ($isCorrect && !$exerciseProgress->completed) {
                $exerciseProgress->markAsCompleted(100, 0, $userCode);
                
                // Enregistrer l'activité
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
                
                // Invalider le cache du dashboard
                \App\Http\Controllers\ProfileController::clearCache($user->id);
            }
        }
        
        return response()->json([
            'correct' => $isCorrect,
            'message' => $isCorrect ? 'Bravo ! Votre réponse est correcte !' : 'Pas tout à fait. Réessayez !',
            'solution' => $isCorrect ? null : $exercise['solution']
        ]);
    }

    public function runCode(Request $request, $language)
    {
        $code = $request->input('code');
        $postData = $request->input('post_data', []);
        $getData = $request->input('get_data', []);
        
        // Exécution Python
        if ($language === 'python') {
            // Sécurité : Vérifier que le code ne contient pas de fonctions dangereuses
            // Note: input() est autorisé pour les exercices, mais sera limité à une valeur par défaut
            $dangerousFunctions = [
                'exec', 'eval', 'compile', '__import__', 'open', 'file',
                'execfile', 'reload', '__builtin__',
                'subprocess', 'os.system', 'os.popen', 'popen2', 'commands',
                'socket', 'urllib', 'urllib2', 'httplib', 'ftplib', 'telnetlib'
            ];
            
            foreach ($dangerousFunctions as $func) {
                if (preg_match('/\b' . preg_quote($func, '/') . '\s*\(/i', $code)) {
                    return response()->json([
                        'output' => '',
                        'error' => 'Fonction non autorisée détectée : ' . $func . '(). Cette fonction est désactivée pour des raisons de sécurité.'
                    ]);
                }
            }
            
            // Vérifier les imports dangereux
            if (preg_match('/\bimport\s+(os|sys|subprocess|socket|urllib|commands|popen2|eval|exec|compile)/i', $code)) {
                return response()->json([
                    'output' => '',
                    'error' => 'Import non autorisé détecté. Certains modules sont désactivés pour des raisons de sécurité.'
                ]);
            }
            
            $output = '';
            $error = null;
            
            // Détecter si input() est utilisé dans le code
            $usesInput = preg_match('/\binput\s*\(/i', $code);
            $stdinData = "test\n"; // Valeur par défaut pour input() si utilisé (avec \n pour terminer l'entrée)
            
            // Si input() est utilisé, privilégier l'API externe qui gère mieux stdin
            $preferApiForInput = $usesInput;
            
            try {
                // Créer un fichier temporaire pour exécuter le code Python
                $tempFile = tempnam(sys_get_temp_dir(), 'python_exercise_') . '.py';
                $errorFile = $tempFile . '.err';
                
                // Préparer le code avec flush automatique
                $codeToWrite = $code;
                
                // Ajouter import sys et flush si nécessaire
                if (!preg_match('/\bimport\s+sys\b/i', $codeToWrite)) {
                    $codeToWrite = "import sys\n" . $codeToWrite;
                }
                
                // Ajouter flush à la fin pour forcer l'affichage
                if (!preg_match('/sys\.stdout\.flush\(\)/i', $codeToWrite)) {
                    $codeToWrite .= "\nsys.stdout.flush()";
                }
                
                // Écrire le code dans le fichier temporaire avec encodage UTF-8
                // S'assurer que le code est en UTF-8 avant écriture
                if (!mb_check_encoding($codeToWrite, 'UTF-8')) {
                    $codeToWrite = mb_convert_encoding($codeToWrite, 'UTF-8', mb_detect_encoding($codeToWrite, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true));
                }
                
                // Écrire en UTF-8 sans BOM (Python préfère sans BOM)
                if (file_put_contents($tempFile, $codeToWrite, LOCK_EX) === false) {
                    throw new \Exception('Impossible d\'écrire dans le fichier temporaire');
                }
                
                // Essayer différentes commandes Python
                $pythonCmd = null;
                
                // Sur Linux/Unix (serveurs hébergés), essayer d'abord les chemins standards
                if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
                    // Chemins standards sur Linux/Unix
                    $linuxPaths = [
                        '/usr/bin/python3',
                        '/usr/local/bin/python3',
                        '/bin/python3',
                        '/opt/python3/bin/python3',
                        '/usr/bin/python',
                        '/usr/local/bin/python',
                        '/bin/python',
                    ];
                    
                    // Chercher dans les chemins Linux standards
                    foreach ($linuxPaths as $path) {
                        if (file_exists($path) && is_executable($path)) {
                            // Tester si Python fonctionne
                            $testCmd = escapeshellarg($path) . ' --version 2>&1';
                            $testOutput = @shell_exec($testCmd);
                            if ($testOutput !== null && (strpos($testOutput, 'Python') !== false || strpos($testOutput, 'python') !== false)) {
                                $pythonCmd = $path;
                                break;
                            }
                        }
                    }
                    
                    // Si pas trouvé, utiliser 'which' pour trouver Python
                    if ($pythonCmd === null) {
                        $whichCmd = 'which python3 2>/dev/null';
                        $whichOutput = @shell_exec($whichCmd);
                        if (!empty($whichOutput)) {
                            $pythonPath = trim($whichOutput);
                            if (file_exists($pythonPath) && is_executable($pythonPath)) {
                                $pythonCmd = $pythonPath;
                            }
                        }
                    }
                    
                    // Si toujours pas trouvé, essayer 'which python'
                    if ($pythonCmd === null) {
                        $whichCmd = 'which python 2>/dev/null';
                        $whichOutput = @shell_exec($whichCmd);
                        if (!empty($whichOutput)) {
                            $pythonPath = trim($whichOutput);
                            if (file_exists($pythonPath) && is_executable($pythonPath)) {
                                $pythonCmd = $pythonPath;
                            }
                        }
                    }
                }
                
                // Sur Windows, chercher dans les emplacements courants
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    // Chemins courants pour Python sur Windows
                    $windowsPaths = [
                        'C:\\Python39\\python.exe',
                        'C:\\Python310\\python.exe',
                        'C:\\Python311\\python.exe',
                        'C:\\Python312\\python.exe',
                        'C:\\Python313\\python.exe',
                        'C:\\Program Files\\Python39\\python.exe',
                        'C:\\Program Files\\Python310\\python.exe',
                        'C:\\Program Files\\Python311\\python.exe',
                        'C:\\Program Files\\Python312\\python.exe',
                        'C:\\Program Files\\Python313\\python.exe',
                        'C:\\Program Files (x86)\\Python39\\python.exe',
                        'C:\\Program Files (x86)\\Python310\\python.exe',
                        'C:\\Program Files (x86)\\Python311\\python.exe',
                        'C:\\Program Files (x86)\\Python312\\python.exe',
                        'C:\\Program Files (x86)\\Python313\\python.exe',
                        'C:\\Users\\' . getenv('USERNAME') . '\\AppData\\Local\\Programs\\Python\\Python39\\python.exe',
                        'C:\\Users\\' . getenv('USERNAME') . '\\AppData\\Local\\Programs\\Python\\Python310\\python.exe',
                        'C:\\Users\\' . getenv('USERNAME') . '\\AppData\\Local\\Programs\\Python\\Python311\\python.exe',
                        'C:\\Users\\' . getenv('USERNAME') . '\\AppData\\Local\\Programs\\Python\\Python312\\python.exe',
                        'C:\\Users\\' . getenv('USERNAME') . '\\AppData\\Local\\Programs\\Python\\Python313\\python.exe',
                    ];
                    
                    // Chercher dans les chemins Windows
                    foreach ($windowsPaths as $path) {
                        if (file_exists($path)) {
                            $testCmd = escapeshellarg($path) . ' --version 2>&1';
                            $testOutput = @shell_exec($testCmd);
                            if ($testOutput !== null && strpos($testOutput, 'Python') !== false) {
                                $pythonCmd = $path;
                                break;
                            }
                        }
                    }
                }
                
                // Si pas trouvé, essayer les commandes standard (python3, python)
                if ($pythonCmd === null) {
                    $pythonCommands = ['python3', 'python'];
                    foreach ($pythonCommands as $cmd) {
                        // Tester si la commande existe
                        $testCmd = $cmd . ' --version 2>&1';
                        $testOutput = @shell_exec($testCmd);
                        if ($testOutput !== null && (strpos($testOutput, 'Python') !== false || strpos($testOutput, 'python') !== false)) {
                            $pythonCmd = $cmd;
                            break;
                        }
                    }
                }
                
                // Si Python n'est pas trouvé localement OU si input() est utilisé, utiliser une API externe
                // (L'API externe gère mieux stdin pour input() que l'exécution locale)
                if ($pythonCmd === null || $usesInput) {
                    // Utiliser Piston API (gratuite et open source) pour exécuter Python
                    try {
                        // Nettoyer le code (enlever les ajouts sys si présents)
                        $cleanCode = $code;
                        
                        // Préparer la requête pour Piston API
                        $pistonApiUrl = 'https://emkc.org/api/v2/piston/execute';
                        
                        // Utiliser stdin si input() est détecté
                        $stdinForApi = $usesInput ? $stdinData : '';
                        
                        $response = Http::timeout(10)->post($pistonApiUrl, [
                            'language' => 'python',
                            'version' => '3.10.0',
                            'files' => [
                                [
                                    'name' => 'main.py',
                                    'content' => $cleanCode
                                ]
                            ],
                            'stdin' => $stdinForApi,
                            'args' => []
                        ]);
                        
                        if ($response->successful()) {
                            $result = $response->json();
                            
                            if (isset($result['run'])) {
                                $run = $result['run'];
                                $output = $run['stdout'] ?? '';
                                $stderr = $run['stderr'] ?? '';
                                
                                // Nettoyer la sortie
                                $output = trim($output);
                                $stderr = trim($stderr);
                                
                                // Si code de retour non zéro ou stderr présent, considérer comme erreur
                                if (!empty($stderr) || ($run['code'] ?? 0) !== 0) {
                                    $error = !empty($stderr) ? $stderr : 'Erreur lors de l\'exécution du code Python';
                                }
                    } else {
                                $error = 'Erreur lors de l\'exécution via API externe';
                            }
                            
                            // Nettoyer les fichiers temporaires
                            if (isset($tempFile) && file_exists($tempFile)) {
                                @unlink($tempFile);
                            }
                            if (isset($errorFile) && file_exists($errorFile)) {
                                @unlink($errorFile);
                            }
                            
                            // Sortir de la boucle try, le code a été exécuté via API
                            goto python_executed;
                        } else {
                            throw new \Exception('Impossible de se connecter à l\'API d\'exécution Python');
                        }
                    } catch (\Exception $apiException) {
                        // Si l'API Piston échoue, essayer une instance alternative
                        try {
                            // Alternative: utiliser une autre instance de Piston API
                            $pistonApiUrlAlt = 'https://emkc.org/api/v2/piston/execute';
                            
                            $response = Http::timeout(10)->post($pistonApiUrlAlt, [
                                'language' => 'python',
                                'version' => '*', // Utiliser la dernière version disponible
                                'files' => [
                                    [
                                        'name' => 'main.py',
                                        'content' => $cleanCode
                                    ]
                                ],
                                'stdin' => $usesInput ? $stdinData : '',
                                'args' => []
                            ]);
                            
                            if ($response->successful()) {
                                $result = $response->json();
                                
                                if (isset($result['run'])) {
                                    $run = $result['run'];
                                    $output = $run['stdout'] ?? '';
                                    $stderr = $run['stderr'] ?? '';
                                    
                                    // Nettoyer la sortie
                                    $output = trim($output);
                                    $stderr = trim($stderr);
                                    
                                    // Si code de retour non zéro ou stderr présent, considérer comme erreur
                                    if (!empty($stderr) || ($run['code'] ?? 0) !== 0) {
                                        $error = !empty($stderr) ? $stderr : 'Erreur lors de l\'exécution du code Python';
                                    }
                                    
                                    // Nettoyer les fichiers temporaires
                                    if (isset($tempFile) && file_exists($tempFile)) {
                                        @unlink($tempFile);
                                    }
                                    if (isset($errorFile) && file_exists($errorFile)) {
                                        @unlink($errorFile);
                                    }
                                    
                                    goto python_executed;
                                }
                            }
                        } catch (\Exception $altApiException) {
                            // Si toutes les APIs échouent, retourner l'erreur
                            $errorMsg = 'Python n\'est pas installé localement et l\'API externe n\'est pas disponible. ';
                            $errorMsg .= 'Contactez votre hébergeur pour installer Python3. Erreur API: ' . $apiException->getMessage();
                    throw new \Exception($errorMsg);
                        }
                    }
                }
                
                // Exécuter Python localement si trouvé
                $env = $_ENV;
                $env['PYTHONUNBUFFERED'] = '1';
                $env['PYTHONIOENCODING'] = 'utf-8';
                $envString = '';
                foreach ($env as $key => $value) {
                    $envString .= escapeshellarg($key) . '=' . escapeshellarg($value) . ' ';
                }
                
                // Utiliser exec() pour obtenir le code de retour
                // Forcer l'encodage UTF-8 avec PYTHONIOENCODING
                // Si c'est un chemin complet (Windows), utiliser directement
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && strpos($pythonCmd, '\\') !== false) {
                    // Chemin complet Windows, utiliser directement avec escapeshellarg
                    $command = escapeshellarg($pythonCmd) . ' -u -X utf8 ' . escapeshellarg($tempFile);
                } else {
                    // Commande standard (python ou python3)
                    $command = $pythonCmd . ' -u -X utf8 ' . escapeshellarg($tempFile);
                }
                
                $outputLines = [];
                $returnValue = 0;
                
                // Exécuter avec exec() pour capturer stdout et stderr
                // Utiliser chcp 65001 sur Windows pour forcer UTF-8 dans le terminal
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    // Sur Windows, forcer UTF-8 dans le terminal
                    $command = 'chcp 65001 >nul 2>&1 && ' . $command;
                }
                
                // Si input() est utilisé, utiliser proc_open pour passer stdin correctement
                if ($usesInput) {
                    // Créer un fichier stdin temporaire
                    $stdinFile = $tempFile . '.stdin';
                    file_put_contents($stdinFile, $stdinData, LOCK_EX);
                    
                    // Construire la commande Python de base (sans redirection stdin)
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && strpos($pythonCmd, '\\') !== false) {
                        // Chemin complet Windows
                        $baseCommand = escapeshellarg($pythonCmd) . ' -u -X utf8 ' . escapeshellarg($tempFile);
                    } else {
                        // Commande standard (python ou python3)
                        $baseCommand = $pythonCmd . ' -u -X utf8 ' . escapeshellarg($tempFile);
                    }
                    
                    // Utiliser proc_open avec stdin depuis fichier
                    $descriptorspec = [
                        0 => ['file', $stdinFile, 'r'], // stdin depuis le fichier
                        1 => ['pipe', 'w'], // stdout
                        2 => ['pipe', 'w']  // stderr
                    ];
                    
                    $process = @proc_open($baseCommand, $descriptorspec, $pipes);
                    
                    if (is_resource($process) && isset($pipes) && is_array($pipes)) {
                        // Fermer stdin (déjà ouvert depuis le fichier)
                        if (isset($pipes[0]) && is_resource($pipes[0])) {
                            fclose($pipes[0]);
                        }
                        
                        // Lire stdout
                        $stdoutContent = '';
                        if (isset($pipes[1]) && is_resource($pipes[1])) {
                            $stdoutContent = stream_get_contents($pipes[1]);
                            fclose($pipes[1]);
                        }
                        
                        // Lire stderr
                        $stderrContent = '';
                        if (isset($pipes[2]) && is_resource($pipes[2])) {
                            $stderrContent = stream_get_contents($pipes[2]);
                            fclose($pipes[2]);
                        }
                        
                        // Obtenir le code de retour
                        $returnValue = proc_close($process);
                        
                        // Convertir en tableau de lignes
                        $outputLines = !empty($stdoutContent) ? explode("\n", $stdoutContent) : [];
                        if (!empty($stderrContent)) {
                            $stderr = $stderrContent;
                        }
                    } else {
                        // Fallback: utiliser exec avec redirection stdin
                        // Reconstruire la commande de base
                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' && strpos($pythonCmd, '\\') !== false) {
                            $fallbackCommand = escapeshellarg($pythonCmd) . ' -u -X utf8 ' . escapeshellarg($tempFile);
                        } else {
                            $fallbackCommand = $pythonCmd . ' -u -X utf8 ' . escapeshellarg($tempFile);
                        }
                        
                        // Rediriger stdin depuis le fichier
                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                            // Windows: utiliser type et pipe
                            $fallbackCommand = 'type ' . escapeshellarg($stdinFile) . ' | ' . $fallbackCommand;
                        } else {
                            // Linux/Unix: utiliser redirection <
                            $fallbackCommand = $fallbackCommand . ' < ' . escapeshellarg($stdinFile);
                        }
                        
                        exec($fallbackCommand . ' 2>&1', $outputLines, $returnValue);
                    }
                } else {
                    // Pas d'input: utiliser exec normal
                exec($command . ' 2>&1', $outputLines, $returnValue);
                }
                
                // Joindre toutes les lignes de sortie
                $output = implode("\n", $outputLines);
                
                // Séparer stdout et stderr si nécessaire
                if (!isset($stderr)) {
                $stderr = '';
                }
                if ($returnValue !== 0) {
                    // Si erreur, essayer de lire le fichier d'erreur
                    if (file_exists($errorFile)) {
                        $stderr = file_get_contents($errorFile);
                        @unlink($errorFile);
                    }
                    // Si pas de fichier d'erreur, utiliser la sortie comme erreur
                    if (empty($stderr) && !empty($output) && preg_match('/Error|Exception|Traceback/i', $output)) {
                        $stderr = $output;
                        $output = '';
                    }
                }
                
                // Nettoyer le fichier d'erreur s'il existe
                if (file_exists($errorFile)) {
                    @unlink($errorFile);
                }
                
                // Supprimer le fichier stdin temporaire s'il existe
                if ($usesInput && isset($stdinFile) && file_exists($stdinFile)) {
                    @unlink($stdinFile);
                }
                
                // Supprimer le fichier temporaire
                @unlink($tempFile);
                
                python_executed:
                
                // Nettoyer et encoder la sortie en UTF-8
                // Supprimer tous les espaces, tabulations et retours à la ligne en début/fin
                $output = $output !== null ? trim($output) : '';
                // Supprimer les espaces en début de chaque ligne (indentation indésirable)
                if (!empty($output)) {
                    // Supprimer tous les caractères d'espacement Unicode en début/fin
                    $output = preg_replace('/^[\s\x{00A0}\x{2000}-\x{200B}\x{2028}\x{2029}\x{202F}\x{205F}\x{3000}]+/u', '', $output);
                    $output = preg_replace('/[\s\x{00A0}\x{2000}-\x{200B}\x{2028}\x{2029}\x{202F}\x{205F}\x{3000}]+$/u', '', $output);
                    
                    $lines = explode("\n", $output);
                    $cleanedLines = [];
                    foreach ($lines as $line) {
                        // Supprimer tous les espaces, tabulations et caractères invisibles en début de ligne
                        $cleanedLine = preg_replace('/^[\s\t\r\x{00A0}\x{2000}-\x{200B}\x{2028}\x{2029}\x{202F}\x{205F}\x{3000}]+/u', '', $line);
                        $cleanedLines[] = $cleanedLine;
                    }
                    $output = implode("\n", $cleanedLines);
                    // Retrim pour supprimer les lignes vides en début/fin et tous les espaces
                    $output = trim($output);
                    // Supprimer une dernière fois tous les espaces invisibles
                    $output = preg_replace('/^[\s\x{00A0}\x{2000}-\x{200B}\x{2028}\x{2029}\x{202F}\x{205F}\x{3000}]+/u', '', $output);
                }
                $stderr = trim($stderr);
                
                // Encoder en UTF-8 pour éviter les erreurs JSON
                if (!empty($output)) {
                    // Détecter et convertir l'encodage si nécessaire
                    if (!mb_check_encoding($output, 'UTF-8')) {
                        $output = mb_convert_encoding($output, 'UTF-8', mb_detect_encoding($output, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true));
                    }
                    // Nettoyer les caractères invalides UTF-8
                    $output = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
                }
                
                if (!empty($stderr)) {
                    // Encoder aussi les erreurs en UTF-8
                    if (!mb_check_encoding($stderr, 'UTF-8')) {
                        $stderr = mb_convert_encoding($stderr, 'UTF-8', mb_detect_encoding($stderr, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true));
                    }
                    $stderr = mb_convert_encoding($stderr, 'UTF-8', 'UTF-8');
                }
                
                // Si le code de retour n'est pas 0 ou s'il y a des erreurs
                if ((isset($returnValue) && $returnValue !== 0) || !empty($stderr)) {
                    $error = $stderr ?: (isset($returnValue) ? 'Erreur lors de l\'exécution du code Python (code de retour: ' . $returnValue . ')' : 'Erreur lors de l\'exécution du code Python');
                }
                
            } catch (\Exception $e) {
                $error = 'Erreur : ' . $e->getMessage();
                if (isset($tempFile) && file_exists($tempFile)) {
                    @unlink($tempFile);
                }
            }
            
            // S'assurer que les valeurs sont des chaînes UTF-8 valides avant JSON
            $output = $output ?? '';
            $error = $error ?? null;
            
            // Nettoyer une dernière fois pour garantir UTF-8 valide
            if (!empty($output) && !mb_check_encoding($output, 'UTF-8')) {
                $output = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
            }
            if (!empty($error) && !mb_check_encoding($error, 'UTF-8')) {
                $error = mb_convert_encoding($error, 'UTF-8', 'UTF-8');
            }
            
            return response()->json([
                'output' => $output,
                'error' => $error
            ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_IGNORE);
        }
        
        // Exécution Java
        if ($language === 'java') {
            // Utiliser Piston API pour exécuter Java
            try {
                // Sécurité : Vérifier que le code ne contient pas de fonctions dangereuses
                $dangerousPatterns = [
                    '/Runtime\.getRuntime\(\)/',
                    '/ProcessBuilder/',
                    '/Process/',
                    '/FileWriter/',
                    '/FileOutputStream/',
                    '/Socket/',
                    '/ServerSocket/',
                    '/URLClassLoader/',
                    '/Class\.forName/',
                ];
                
                foreach ($dangerousPatterns as $pattern) {
                    if (preg_match($pattern, $code)) {
                        return response()->json([
                            'output' => '',
                            'error' => 'Code non autorisé détecté. Certaines fonctionnalités sont désactivées pour des raisons de sécurité.'
                        ]);
                    }
                }
                
                // Préparer la requête pour Piston API
                $pistonApiUrl = 'https://emkc.org/api/v2/piston/execute';
                
                // Optimiser la requête avec timeout approprié pour Java (compilation + exécution)
                $response = Http::timeout(20)
                    ->retry(2, 1000)
                    ->withOptions([
                        'http_errors' => false,
                        'verify' => true,
                        'timeout' => 20,
                        'connect_timeout' => 10,
                    ])
                    ->post($pistonApiUrl, [
                        'language' => 'java',
                        'version' => '15.0.2',
                        'files' => [
                            [
                                'name' => 'Main.java',
                                'content' => $code
                            ]
                        ],
                        'stdin' => '',
                        'args' => []
                    ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    $output = '';
                    $error = null;
                    
                    if (isset($data['run']['stdout'])) {
                        $output = $data['run']['stdout'];
                    }
                    
                    if (isset($data['run']['stderr']) && !empty($data['run']['stderr'])) {
                        $error = $data['run']['stderr'];
                    } elseif (isset($data['run']['code']) && $data['run']['code'] !== 0) {
                        $error = 'Erreur lors de l\'exécution (code de retour: ' . $data['run']['code'] . ')';
                        if (!empty($output)) {
                            $error .= "\n" . $output;
                        }
                    }
                    
                    // Nettoyer l'output
                    if (!empty($output)) {
                        $output = trim($output);
                        if (!mb_check_encoding($output, 'UTF-8')) {
                            $output = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
                        }
                    }
                    
                    if (!empty($error)) {
                        $error = trim($error);
                        if (!mb_check_encoding($error, 'UTF-8')) {
                            $error = mb_convert_encoding($error, 'UTF-8', 'UTF-8');
                        }
                    }
                    
                    return response()->json([
                        'output' => $output,
                        'error' => $error
                    ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_IGNORE);
                } else {
                    $statusCode = $response->status();
                    $errorBody = $response->body();
                    return response()->json([
                        'output' => '',
                        'error' => 'Erreur lors de l\'exécution du code Java (HTTP ' . $statusCode . '). L\'API d\'exécution peut être temporairement indisponible. Veuillez réessayer dans quelques instants.'
                    ]);
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                // Erreur de connexion/timeout
                return response()->json([
                    'output' => '',
                    'error' => 'Erreur de connexion : Impossible de se connecter à l\'API d\'exécution Java. Vérifiez votre connexion internet et réessayez.'
                ]);
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                // Masquer les détails techniques de cURL pour l'utilisateur
                if (strpos($errorMessage, 'cURL error') !== false) {
                    return response()->json([
                        'output' => '',
                        'error' => 'Erreur de connexion : L\'API d\'exécution Java est temporairement indisponible. Veuillez réessayer dans quelques instants.'
                    ]);
                }
                return response()->json([
                    'output' => '',
                    'error' => 'Erreur : ' . $errorMessage
                ]);
            }
        }
        
        // Exécution C
        if ($language === 'c') {
            try {
                // Vérifications de sécurité pour C
                $dangerousPatterns = [
                    '/system\s*\(/', '/exec\s*\(/', '/popen\s*\(/', '/fopen\s*\(/',
                    '/socket\s*\(/', '/connect\s*\(/', '/fork\s*\(/', '/vfork\s*\(/',
                    '/chmod\s*\(/', '/chown\s*\(/', '/unlink\s*\(/', '/remove\s*\(/',
                ];
                
                foreach ($dangerousPatterns as $pattern) {
                    if (preg_match($pattern, $code)) {
                        return response()->json([
                            'output' => '',
                            'error' => 'Code non autorisé détecté. Certaines fonctionnalités sont désactivées pour des raisons de sécurité.'
                        ]);
                    }
                }
                
                // Préparer la requête pour Piston API
                $pistonApiUrl = 'https://emkc.org/api/v2/piston/execute';
                
                // Optimiser la requête avec timeout approprié pour C (compilation + exécution)
                $response = Http::timeout(20)
                    ->retry(2, 1000)
                    ->withOptions([
                        'http_errors' => false,
                        'verify' => true,
                        'timeout' => 20,
                        'connect_timeout' => 10,
                    ])
                    ->post($pistonApiUrl, [
                        'language' => 'c',
                        'version' => '10.2.0',
                        'files' => [
                            [
                                'name' => 'main.c',
                                'content' => $code
                            ]
                        ],
                        'stdin' => '',
                        'args' => []
                    ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    $output = '';
                    $error = null;
                    
                    if (isset($data['run']['stdout'])) {
                        $output = $data['run']['stdout'];
                    }
                    
                    if (isset($data['run']['stderr']) && !empty($data['run']['stderr'])) {
                        $error = $data['run']['stderr'];
                    } elseif (isset($data['run']['code']) && $data['run']['code'] !== 0) {
                        $error = 'Erreur lors de l\'exécution (code de retour: ' . $data['run']['code'] . ')';
                        if (!empty($output)) {
                            $error .= "\n" . $output;
                        }
                    }
                    
                    // Nettoyer l'output
                    if (!empty($output)) {
                        $output = trim($output);
                        if (!mb_check_encoding($output, 'UTF-8')) {
                            $output = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
                        }
                    }
                    
                    if (!empty($error)) {
                        $error = trim($error);
                        if (!mb_check_encoding($error, 'UTF-8')) {
                            $error = mb_convert_encoding($error, 'UTF-8', 'UTF-8');
                        }
                    }
                    
                    return response()->json([
                        'output' => $output,
                        'error' => $error
                    ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_IGNORE);
                } else {
                    $statusCode = $response->status();
                    $errorBody = $response->body();
                    return response()->json([
                        'output' => '',
                        'error' => 'Erreur lors de l\'exécution du code C (HTTP ' . $statusCode . '). L\'API d\'exécution peut être temporairement indisponible. Veuillez réessayer dans quelques instants.'
                    ]);
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                // Erreur de connexion/timeout
                return response()->json([
                    'output' => '',
                    'error' => 'Erreur de connexion : Impossible de se connecter à l\'API d\'exécution C. Vérifiez votre connexion internet et réessayez.'
                ]);
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                // Masquer les détails techniques de cURL pour l'utilisateur
                if (strpos($errorMessage, 'cURL error') !== false) {
                    return response()->json([
                        'output' => '',
                        'error' => 'Erreur de connexion : L\'API d\'exécution C est temporairement indisponible. Veuillez réessayer dans quelques instants.'
                    ]);
                }
                return response()->json([
                    'output' => '',
                    'error' => 'Erreur : ' . $errorMessage
                ]);
            }
        }
        
        // Exécution C++
        if ($language === 'cpp' || $language === 'c++') {
            try {
                // Vérifications de sécurité pour C++
                $dangerousPatterns = [
                    '/system\s*\(/', '/exec\s*\(/', '/popen\s*\(/', '/fopen\s*\(/',
                    '/socket\s*\(/', '/connect\s*\(/', '/fork\s*\(/', '/vfork\s*\(/',
                    '/chmod\s*\(/', '/chown\s*\(/', '/unlink\s*\(/', '/remove\s*\(/',
                ];
                
                foreach ($dangerousPatterns as $pattern) {
                    if (preg_match($pattern, $code)) {
                        return response()->json([
                            'output' => '',
                            'error' => 'Code non autorisé détecté. Certaines fonctionnalités sont désactivées pour des raisons de sécurité.'
                        ]);
                    }
                }
                
                // Préparer la requête pour Piston API
                $pistonApiUrl = 'https://emkc.org/api/v2/piston/execute';
                
                // Optimiser la requête avec timeout approprié pour C++ (compilation + exécution)
                $response = Http::timeout(20)
                    ->retry(2, 1000)
                    ->withOptions([
                        'http_errors' => false,
                        'verify' => true,
                        'timeout' => 20,
                        'connect_timeout' => 10,
                    ])
                    ->post($pistonApiUrl, [
                        'language' => 'cpp',
                        'version' => '10.2.0',
                        'files' => [
                            [
                                'name' => 'main.cpp',
                                'content' => $code
                            ]
                        ],
                        'stdin' => '',
                        'args' => []
                    ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    $output = '';
                    $error = null;
                    
                    if (isset($data['run']['stdout'])) {
                        $output = $data['run']['stdout'];
                    }
                    
                    if (isset($data['run']['stderr']) && !empty($data['run']['stderr'])) {
                        $error = $data['run']['stderr'];
                    } elseif (isset($data['run']['code']) && $data['run']['code'] !== 0) {
                        $error = 'Erreur lors de l\'exécution (code de retour: ' . $data['run']['code'] . ')';
                        if (!empty($output)) {
                            $error .= "\n" . $output;
                        }
                    }
                    
                    // Nettoyer l'output
                    if (!empty($output)) {
                        $output = trim($output);
                        if (!mb_check_encoding($output, 'UTF-8')) {
                            $output = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
                        }
                    }
                    
                    if (!empty($error)) {
                        $error = trim($error);
                        if (!mb_check_encoding($error, 'UTF-8')) {
                            $error = mb_convert_encoding($error, 'UTF-8', 'UTF-8');
                        }
                    }
                    
                    return response()->json([
                        'output' => $output,
                        'error' => $error
                    ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_IGNORE);
                } else {
                    $statusCode = $response->status();
                    $errorBody = $response->body();
                    return response()->json([
                        'output' => '',
                        'error' => 'Erreur lors de l\'exécution du code C++ (HTTP ' . $statusCode . '). L\'API d\'exécution peut être temporairement indisponible. Veuillez réessayer dans quelques instants.'
                    ]);
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                // Erreur de connexion/timeout
                return response()->json([
                    'output' => '',
                    'error' => 'Erreur de connexion : Impossible de se connecter à l\'API d\'exécution C++. Vérifiez votre connexion internet et réessayez.'
                ]);
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                // Masquer les détails techniques de cURL pour l'utilisateur
                if (strpos($errorMessage, 'cURL error') !== false) {
                    return response()->json([
                        'output' => '',
                        'error' => 'Erreur de connexion : L\'API d\'exécution C++ est temporairement indisponible. Veuillez réessayer dans quelques instants.'
                    ]);
                }
                return response()->json([
                    'output' => '',
                    'error' => 'Erreur : ' . $errorMessage
                ]);
            }
        }
        
        // Exécution C#
        if ($language === 'csharp' || $language === 'c#') {
            try {
                // Vérifications de sécurité pour C#
                $dangerousPatterns = [
                    '/Process\.Start/',
                    '/ProcessStartInfo/',
                    '/System\.Diagnostics\.Process/',
                    '/File\.WriteAllText/',
                    '/File\.WriteAllBytes/',
                    '/File\.AppendAllText/',
                    '/Socket/',
                    '/TcpClient/',
                    '/TcpListener/',
                    '/UdpClient/',
                    '/WebClient/',
                    '/HttpWebRequest/',
                    '/Assembly\.Load/',
                    '/Type\.GetType/',
                    '/Activator\.CreateInstance/',
                ];
                
                foreach ($dangerousPatterns as $pattern) {
                    if (preg_match($pattern, $code)) {
                        return response()->json([
                            'output' => '',
                            'error' => 'Code non autorisé détecté. Certaines fonctionnalités sont désactivées pour des raisons de sécurité.'
                        ]);
                    }
                }
                
                // Préparer la requête pour Piston API
                $pistonApiUrl = 'https://emkc.org/api/v2/piston/execute';
                
                // Optimiser la requête avec timeout approprié pour C# (compilation + exécution)
                $response = Http::timeout(20)
                    ->retry(2, 1000)
                    ->withOptions([
                        'http_errors' => false,
                        'verify' => true,
                        'timeout' => 20,
                        'connect_timeout' => 10,
                    ])
                    ->post($pistonApiUrl, [
                        'language' => 'csharp',
                        'version' => '6.12.0',
                        'files' => [
                            [
                                'name' => 'Program.cs',
                                'content' => $code
                            ]
                        ],
                        'stdin' => '',
                        'args' => []
                    ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    $output = '';
                    $error = null;
                    
                    if (isset($data['run']['stdout'])) {
                        $output = $data['run']['stdout'];
                    }
                    
                    if (isset($data['run']['stderr']) && !empty($data['run']['stderr'])) {
                        $error = $data['run']['stderr'];
                    } elseif (isset($data['run']['code']) && $data['run']['code'] !== 0) {
                        $error = 'Erreur lors de l\'exécution (code de retour: ' . $data['run']['code'] . ')';
                        if (!empty($output)) {
                            $error .= "\n" . $output;
                        }
                    }
                    
                    // Nettoyer l'output
                    if (!empty($output)) {
                        $output = trim($output);
                        if (!mb_check_encoding($output, 'UTF-8')) {
                            $output = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
                        }
                    }
                    
                    if (!empty($error)) {
                        $error = trim($error);
                        if (!mb_check_encoding($error, 'UTF-8')) {
                            $error = mb_convert_encoding($error, 'UTF-8', 'UTF-8');
                        }
                    }
                    
                    return response()->json([
                        'output' => $output,
                        'error' => $error
                    ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_IGNORE);
                } else {
                    $statusCode = $response->status();
                    $errorBody = $response->body();
                    return response()->json([
                        'output' => '',
                        'error' => 'Erreur lors de l\'exécution du code C# (HTTP ' . $statusCode . '). L\'API d\'exécution peut être temporairement indisponible. Veuillez réessayer dans quelques instants.'
                    ]);
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                // Erreur de connexion/timeout
                return response()->json([
                    'output' => '',
                    'error' => 'Erreur de connexion : Impossible de se connecter à l\'API d\'exécution C#. Vérifiez votre connexion internet et réessayez.'
                ]);
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                // Masquer les détails techniques de cURL pour l'utilisateur
                if (strpos($errorMessage, 'cURL error') !== false) {
                    return response()->json([
                        'output' => '',
                        'error' => 'Erreur de connexion : L\'API d\'exécution C# est temporairement indisponible. Veuillez réessayer dans quelques instants.'
                    ]);
                }
                return response()->json([
                    'output' => '',
                    'error' => 'Erreur : ' . $errorMessage
                ]);
            }
        }
        
        // Exécution Dart
        if ($language === 'dart') {
            try {
                // Vérifications de sécurité pour Dart
                $dangerousPatterns = [
                    '/Process\.start/',
                    '/Process\.run/',
                    '/Process\.runSync/',
                    '/File\.writeAsString/',
                    '/File\.writeAsBytes/',
                    '/File\.writeAsStringSync/',
                    '/File\.writeAsBytesSync/',
                    '/Socket/',
                    '/ServerSocket/',
                    '/HttpClient/',
                    '/HttpServer/',
                    '/dart:io/',
                ];
                
                foreach ($dangerousPatterns as $pattern) {
                    if (preg_match($pattern, $code)) {
                        return response()->json([
                            'output' => '',
                            'error' => 'Code non autorisé détecté. Certaines fonctionnalités sont désactivées pour des raisons de sécurité.'
                        ]);
                    }
                }
                
                // Préparer la requête pour Piston API
                $pistonApiUrl = 'https://emkc.org/api/v2/piston/execute';
                
                // Optimiser la requête avec timeout approprié pour Dart (compilation + exécution)
                $response = Http::timeout(20)
                    ->retry(2, 1000)
                    ->withOptions([
                        'http_errors' => false,
                        'verify' => true,
                        'timeout' => 20,
                        'connect_timeout' => 10,
                    ])
                    ->post($pistonApiUrl, [
                        'language' => 'dart',
                        'version' => '*',
                        'files' => [
                            [
                                'name' => 'main.dart',
                                'content' => $code
                            ]
                        ],
                        'stdin' => '',
                        'args' => []
                    ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    $output = '';
                    $error = null;
                    
                    if (isset($data['run']['stdout'])) {
                        $output = $data['run']['stdout'];
                    }
                    
                    if (isset($data['run']['stderr']) && !empty($data['run']['stderr'])) {
                        $error = $data['run']['stderr'];
                    } elseif (isset($data['run']['code']) && $data['run']['code'] !== 0) {
                        $error = 'Erreur lors de l\'exécution (code de retour: ' . $data['run']['code'] . ')';
                        if (!empty($output)) {
                            $error .= "\n" . $output;
                        }
                    }
                    
                    // Nettoyer l'output
                    if (!empty($output)) {
                        $output = trim($output);
                        if (!mb_check_encoding($output, 'UTF-8')) {
                            $output = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
                        }
                    }
                    
                    if (!empty($error)) {
                        $error = trim($error);
                        if (!mb_check_encoding($error, 'UTF-8')) {
                            $error = mb_convert_encoding($error, 'UTF-8', 'UTF-8');
                        }
                    }
                    
                    return response()->json([
                        'output' => $output,
                        'error' => $error
                    ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_IGNORE);
                } else {
                    $statusCode = $response->status();
                    $errorBody = $response->body();
                    return response()->json([
                        'output' => '',
                        'error' => 'Erreur lors de l\'exécution du code Dart (HTTP ' . $statusCode . '). L\'API d\'exécution peut être temporairement indisponible. Veuillez réessayer dans quelques instants.'
                    ]);
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                // Erreur de connexion/timeout
                return response()->json([
                    'output' => '',
                    'error' => 'Erreur de connexion : Impossible de se connecter à l\'API d\'exécution Dart. Vérifiez votre connexion internet et réessayez.'
                ]);
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
                // Masquer les détails techniques de cURL pour l'utilisateur
                if (strpos($errorMessage, 'cURL error') !== false) {
                    return response()->json([
                        'output' => '',
                        'error' => 'Erreur de connexion : L\'API d\'exécution Dart est temporairement indisponible. Veuillez réessayer dans quelques instants.'
                    ]);
                }
                return response()->json([
                    'output' => '',
                    'error' => 'Erreur : ' . $errorMessage
                ]);
            }
        }
        
        // Sécurité : Ne permettre l'exécution que pour PHP
        if ($language !== 'php') {
            // Pour les autres langages (HTML, CSS, JS, SQL), retourner le code tel quel
            return response()->json([
                'output' => $code,
                'error' => null
            ]);
        }
        
        // Sécurité : Vérifier que le code ne contient pas de fonctions dangereuses
        $dangerousFunctions = [
            'exec', 'system', 'shell_exec', 'passthru', 'proc_open',
            'popen', 'fopen', 'fwrite', 'rmdir', 'mkdir', 'chmod', 'chown',
            'curl_exec', 'curl_init', 'fsockopen', 'pfsockopen',
            'mail', 'ini_set', 'ini_alter', 'putenv', 'dl'
        ];
        
        foreach ($dangerousFunctions as $func) {
            // Vérifier avec des limites pour éviter les faux positifs
            if (preg_match('/\b' . preg_quote($func, '/') . '\s*\(/i', $code)) {
                return response()->json([
                    'output' => '',
                    'error' => 'Fonction non autorisée détectée : ' . $func . '(). Cette fonction est désactivée pour des raisons de sécurité.'
                ]);
            }
        }
        
        // Vérifier les includes/requires
        if (preg_match('/\b(include|require|include_once|require_once)\s*\(/i', $code)) {
            return response()->json([
                'output' => '',
                'error' => 'Les fonctions include/require ne sont pas autorisées pour des raisons de sécurité.'
            ]);
        }
        
        // Capturer la sortie
        ob_start();
        $error = null;
        $output = '';
        
        // Sauvegarder les valeurs originales
        $originalPost = $_POST;
        $originalGet = $_GET;
        $originalServer = $_SERVER;
        
        try {
            // Créer un fichier temporaire pour exécuter le code PHP
            $tempFile = tempnam(sys_get_temp_dir(), 'php_exercise_');
            
            // Simuler $_POST et $_GET si des données sont fournies
            if (!empty($postData)) {
                $_POST = $postData;
                $_SERVER['REQUEST_METHOD'] = 'POST';
            }
            
            if (!empty($getData)) {
                $_GET = $getData;
                if (empty($postData)) {
                    $_SERVER['REQUEST_METHOD'] = 'GET';
                }
            }
            
            // Écrire le code dans le fichier temporaire
            if (file_put_contents($tempFile, $code) === false) {
                throw new \Exception('Impossible d\'écrire dans le fichier temporaire');
            }
            
            // Exécuter le code PHP
            include $tempFile;
            
            // Récupérer la sortie
            $output = ob_get_clean();
            
            // Vérifier que la sortie n'est pas vide avant nettoyage
            if ($output === null || $output === false) {
                $output = '';
            }
            
            // Supprimer le fichier temporaire
            @unlink($tempFile);
            
        } catch (\ParseError $e) {
            $error = 'Erreur de syntaxe : ' . $e->getMessage();
            $output = ob_get_clean();
            if ($output !== null && $output !== false) {
                $output = trim($output);
            }
        } catch (\Error $e) {
            $errorMessage = $e->getMessage();
            // Améliorer les messages d'erreur pour qu'ils soient plus pédagogiques
            if (strpos($errorMessage, 'Call to undefined function') !== false) {
                // Extraire le nom de la fonction
                if (preg_match('/Call to undefined function\s+([a-zA-Z_][a-zA-Z0-9_]*)/', $errorMessage, $matches)) {
                    $functionName = $matches[1];
                    $error = "La fonction '{$functionName}' n'a pas été définie. Vous devez créer cette fonction avec 'function {$functionName}(...)' avant de l'appeler.";
                } else {
                    $error = 'Erreur : ' . $errorMessage;
                }
            } else {
                $error = 'Erreur : ' . $errorMessage;
            }
            $output = ob_get_clean();
            if ($output !== null && $output !== false) {
                $output = trim($output);
            }
        } catch (\Exception $e) {
            $error = 'Exception : ' . $e->getMessage();
            $output = ob_get_clean();
            if ($output !== null && $output !== false) {
                $output = trim($output);
            }
        } catch (\Throwable $e) {
            $errorMessage = $e->getMessage();
            // Améliorer les messages d'erreur pour qu'ils soient plus pédagogiques
            if (strpos($errorMessage, 'Call to undefined function') !== false) {
                // Extraire le nom de la fonction
                if (preg_match('/Call to undefined function\s+([a-zA-Z_][a-zA-Z0-9_]*)/', $errorMessage, $matches)) {
                    $functionName = $matches[1];
                    $error = "La fonction '{$functionName}' n'a pas été définie. Vous devez créer cette fonction avec 'function {$functionName}(...)' avant de l'appeler.";
                } else {
                    $error = 'Erreur : ' . $errorMessage;
                }
            } else {
                $error = 'Erreur : ' . $errorMessage;
            }
            $output = ob_get_clean();
            if ($output !== null && $output !== false) {
                $output = trim($output);
            }
        } finally {
            // Restaurer TOUJOURS les valeurs originales, même en cas d'erreur
            $_POST = $originalPost;
            $_GET = $originalGet;
            $_SERVER = $originalServer;
            
            // Nettoyer le fichier temporaire en cas d'erreur
            if (isset($tempFile) && file_exists($tempFile)) {
                @unlink($tempFile);
            }
        }
        
        // Nettoyer et encoder la sortie en UTF-8 (comme pour Python)
        // Vérifier que la sortie existe et n'est pas vide
        if ($output === null || $output === false) {
            $output = '';
        }
        
        // Nettoyer et encoder la sortie en UTF-8 (MÊME LOGIQUE QUE PYTHON)
        // Supprimer tous les espaces, tabulations et retours à la ligne en début/fin
        $output = $output !== null ? trim($output) : '';
        
        // Supprimer les espaces en début de chaque ligne (indentation indésirable)
        if (!empty($output)) {
            // Supprimer tous les caractères d'espacement Unicode en début/fin (comme Python)
            $output = preg_replace('/^[\s\x{00A0}\x{2000}-\x{200B}\x{2028}\x{2029}\x{202F}\x{205F}\x{3000}]+/u', '', $output);
            $output = preg_replace('/[\s\x{00A0}\x{2000}-\x{200B}\x{2028}\x{2029}\x{202F}\x{205F}\x{3000}]+$/u', '', $output);
            
            // Nettoyer ligne par ligne (comme Python)
            $lines = explode("\n", $output);
            $cleanedLines = [];
            foreach ($lines as $line) {
                // Supprimer tous les espaces, tabulations et caractères invisibles en début de ligne
                $cleanedLine = preg_replace('/^[\s\t\r\x{00A0}\x{2000}-\x{200B}\x{2028}\x{2029}\x{202F}\x{205F}\x{3000}]+/u', '', $line);
                $cleanedLines[] = $cleanedLine;
            }
            $output = implode("\n", $cleanedLines);
            
            // Retrim pour supprimer les lignes vides en début/fin et tous les espaces (comme Python)
            $output = trim($output);
            
            // Supprimer une dernière fois tous les espaces invisibles (comme Python)
            $output = preg_replace('/^[\s\x{00A0}\x{2000}-\x{200B}\x{2028}\x{2029}\x{202F}\x{205F}\x{3000}]+/u', '', $output);
            
            // ÉTAPE FINALE : Trouver le premier caractère non-blanc et supprimer TOUT avant (comme Python mais plus agressif)
            if (!empty($output)) {
                // Trouver le premier caractère non-blanc
                $firstNonWhitespace = preg_match('/\S/u', $output, $matches, PREG_OFFSET_CAPTURE);
                if ($firstNonWhitespace && isset($matches[0][1])) {
                    // Supprimer tout avant ce caractère
                    $output = substr($output, $matches[0][1]);
                }
            }
        }
        
        // Encoder en UTF-8 pour éviter les erreurs JSON
        if (!empty($output)) {
            // Détecter et convertir l'encodage si nécessaire
            if (!mb_check_encoding($output, 'UTF-8')) {
                $output = mb_convert_encoding($output, 'UTF-8', mb_detect_encoding($output, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true));
            }
            // Nettoyer les caractères invalides UTF-8
            $output = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
        }
        
        if (!empty($error)) {
            // Encoder aussi les erreurs en UTF-8
            if (!mb_check_encoding($error, 'UTF-8')) {
                $error = mb_convert_encoding($error, 'UTF-8', mb_detect_encoding($error, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true));
            }
            $error = mb_convert_encoding($error, 'UTF-8', 'UTF-8');
        }
        
        return response()->json([
            'output' => $output,
            'error' => $error
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_IGNORE);
    }

    private function checkAnswer($exercise, $userCode)
    {
        // Normaliser le code (enlever espaces, sauts de ligne, etc.)
        $userCodeNormalized = preg_replace('/\s+/', '', strtolower($userCode));
        $solutionNormalized = preg_replace('/\s+/', '', strtolower($exercise['solution']));
        
        return $userCodeNormalized === $solutionNormalized;
    }

    private function getExerciseDetail($language, $id)
    {
        // Helper function to get translated exercise data
        $getTranslated = function($key, $default) use ($language, $id) {
            $translationKey = "exercises.{$language}.{$id}.{$key}";
            $translated = trans($translationKey);
            // If translation doesn't exist (returns the key itself), use default
            return ($translated !== $translationKey && !empty($translated)) ? $translated : $default;
        };
        
        $allExercises = [
            'html5' => [
                1 => [
                    'title' => $getTranslated('title', 'Les balises de base'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Ajoutez un titre "Bienvenue" à la page HTML ci-dessous en utilisant la balise de titre de niveau 1.'),
                    'description' => $getTranslated('description', 'Les balises de titre HTML permettent de structurer le contenu. La balise <h1> représente le titre principal de la page. Elle est importante pour le SEO et l\'accessibilité. Complétez le code pour afficher "Bienvenue" comme titre principal.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma première page</title>
</head>
<body>

"Bienvenue"

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma première page</title>
</head>
<body>

<h1>Bienvenue</h1>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez la balise <h1> pour créer un titre de niveau 1. La structure est : <h1>Bienvenue</h1>')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Les paragraphes'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Ajoutez un paragraphe avec le texte "Ceci est un paragraphe." en utilisant la balise appropriée.'),
                    'description' => $getTranslated('description', 'Les paragraphes en HTML permettent de structurer le texte. La balise <p> crée un bloc de texte séparé avec un espacement automatique. C\'est l\'élément de base pour afficher du contenu textuel sur une page web.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paragraphes</title>
</head>
<body>

<h1>Mon premier titre</h1>

"Ceci est un paragraphe."

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paragraphes</title>
</head>
<body>

<h1>Mon premier titre</h1>

<p>Ceci est un paragraphe.</p>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez la balise <p> pour créer un paragraphe. La structure est : <p>Ceci est un paragraphe.</p>')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Les liens'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 15,
                    'instruction' => $getTranslated('instruction', 'Créez un lien hypertexte vers "https://www.google.com" avec le texte "Aller sur Google". Le lien doit s\'ouvrir dans un nouvel onglet.'),
                    'description' => $getTranslated('description', 'Les liens HTML permettent de naviguer entre les pages. La balise <a> avec l\'attribut href crée un lien. L\'attribut target="_blank" ouvre le lien dans un nouvel onglet. Les liens sont essentiels pour la navigation web.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liens HTML</title>
</head>
<body>

<h1>Liens HTML</h1>

"Aller sur Google"

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liens HTML</title>
</head>
<body>

<h1>Liens HTML</h1>

<a href="https://www.google.com" target="_blank">Aller sur Google</a>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez <a href="URL" target="_blank">Texte</a> pour créer un lien qui s\'ouvre dans un nouvel onglet. La structure est : <a href="https://www.google.com" target="_blank">Aller sur Google</a>')
                ],
                6 => [
                    'title' => $getTranslated('title', 'Les images'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 15,
                    'instruction' => $getTranslated('instruction', 'Ajoutez une image avec la source "logo.png", le texte alternatif "Logo du site" et une largeur de 200 pixels.'),
                    'description' => $getTranslated('description', 'Les images enrichissent le contenu web. La balise <img> est auto-fermante et nécessite les attributs src (source) et alt (texte alternatif pour l\'accessibilité). L\'attribut width permet de contrôler la taille de l\'image.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Images HTML</title>
</head>
<body>

<h1>Images HTML</h1>



</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Images HTML</title>
</head>
<body>

<h1>Images HTML</h1>

<img src="logo.png" alt="Logo du site" width="200">

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez <img src="..." alt="..." width="..."> pour ajouter une image. La structure est : <img src="logo.png" alt="Logo du site" width="200">')
                ],
                7 => [
                    'title' => $getTranslated('title', 'Les listes'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Créez une liste non ordonnée avec trois éléments : "HTML", "CSS", "JavaScript". Ajoutez un titre "Langages web" avant la liste.'),
                    'description' => $getTranslated('description', 'Les listes permettent d\'organiser l\'information. <ul> crée une liste non ordonnée (à puces), <ol> crée une liste ordonnée (numérotée). Chaque élément utilise la balise <li>. Les listes améliorent la lisibilité et la structure du contenu.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Listes HTML</title>
</head>
<body>

<h1>Mes langages préférés</h1>



</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Listes HTML</title>
</head>
<body>

<h1>Mes langages préférés</h1>

<ul>
<li>HTML</li>
<li>CSS</li>
<li>JavaScript</li>
</ul>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez <ul> pour la liste non ordonnée et <li> pour chaque élément. La structure est : <ul><li>HTML</li><li>CSS</li><li>JavaScript</li></ul>')
                ],
                8 => [
                    'title' => $getTranslated('title', 'Les tableaux HTML5'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Créez un tableau avec 2 colonnes (Nom, Age) et 3 lignes de données. Utilisez les balises <table>, <thead>, <tbody>, <tr>, <th> et <td>.'),
                    'description' => $getTranslated('description', 'Les tableaux HTML permettent d\'organiser des données en lignes et colonnes. <table> crée le tableau, <thead> pour l\'en-tête, <tbody> pour le corps, <tr> pour les lignes, <th> pour les cellules d\'en-tête, et <td> pour les cellules de données.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableaux HTML</title>
</head>
<body>

<h1>Tableau des utilisateurs</h1>



</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableaux HTML</title>
</head>
<body>

<h1>Tableau des utilisateurs</h1>

<table>
<thead>
<tr>
<th>Nom</th>
<th>Age</th>
</tr>
</thead>
<tbody>
<tr>
<td>Jean</td>
<td>25</td>
</tr>
<tr>
<td>Marie</td>
<td>30</td>
</tr>
<tr>
<td>Pierre</td>
<td>22</td>
</tr>
</tbody>
</table>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Structurez le tableau avec <table><thead><tr><th>Nom</th><th>Age</th></tr></thead><tbody><tr><td>...</td></tr></tbody></table>')
                ],
                11 => [
                    'title' => $getTranslated('title', 'Formulaires HTML5 avancés'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 25,
                    'instruction' => $getTranslated('instruction', 'Créez un formulaire complet avec : un champ texte (nom), un email (email), un champ date (date de naissance), un select (pays), une checkbox (conditions), et un bouton submit. Utilisez les attributs HTML5 required, placeholder et type appropriés.'),
                    'description' => $getTranslated('description', 'Les formulaires HTML5 offrent de nouveaux types d\'input (email, date, etc.) et attributs (required, placeholder, pattern) pour améliorer l\'expérience utilisateur et la validation côté client. La balise <form> contient tous les champs, et <label> améliore l\'accessibilité.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire HTML5</title>
</head>
<body>

<h1>Inscription</h1>



</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire HTML5</title>
</head>
<body>

<h1>Inscription</h1>

<form>
<label for="nom">Nom :</label>
<input type="text" id="nom" name="nom" placeholder="Votre nom" required>

<label for="email">Email :</label>
<input type="email" id="email" name="email" placeholder="votre@email.com" required>

<label for="date">Date de naissance :</label>
<input type="date" id="date" name="date" required>

<label for="pays">Pays :</label>
<select id="pays" name="pays" required>
<option value="">Sélectionnez un pays</option>
<option value="fr">France</option>
<option value="sn">Sénégal</option>
</select>

<label>
<input type="checkbox" name="conditions" required>
J\'accepte les conditions
</label>

<button type="submit">S\'inscrire</button>
</form>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez <form> avec <input type="text|email|date">, <select>, <input type="checkbox">, et <button type="submit">. Ajoutez required et placeholder aux inputs.')
                ],
                12 => [
                    'title' => $getTranslated('title', 'Éléments sémantiques HTML5'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 25,
                    'instruction' => $getTranslated('instruction', 'Créez une structure de page complète en utilisant les éléments sémantiques HTML5 : <header>, <nav>, <main>, <article>, <section>, <aside>, et <footer>. Ajoutez du contenu dans chaque section.'),
                    'description' => $getTranslated('description', 'Les éléments sémantiques HTML5 (<header>, <nav>, <main>, <article>, <section>, <aside>, <footer>) donnent du sens au contenu et améliorent le SEO et l\'accessibilité. Ils remplacent les <div> génériques par des balises significatives.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Structure sémantique</title>
</head>
<body>



</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Structure sémantique</title>
</head>
<body>

<header>
<h1>Mon Site Web</h1>
</header>

<nav>
<ul>
<li><a href="#accueil">Accueil</a></li>
<li><a href="#articles">Articles</a></li>
</ul>
</nav>

<main>
<article>
<h2>Article principal</h2>
<p>Contenu de l\'article...</p>
</article>

<section>
<h2>Section</h2>
<p>Contenu de la section...</p>
</section>
</main>

<aside>
<h3>Sidebar</h3>
<p>Contenu complémentaire...</p>
</aside>

<footer>
<p>&copy; 2025 Mon Site</p>
</footer>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Structurez avec <header>, <nav>, <main>, <article>, <section>, <aside>, et <footer>. Chaque élément a un rôle sémantique spécifique.')
                ],
                13 => [
                    'title' => $getTranslated('title', 'Accessibilité HTML5'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Créez une page accessible avec : attributs ARIA appropriés (aria-label, role), balises <label> liées aux inputs, attribut alt sur les images, et structure de titres hiérarchique (h1, h2, h3).'),
                    'description' => $getTranslated('description', 'L\'accessibilité web garantit que tous les utilisateurs peuvent accéder au contenu. Utilisez les attributs ARIA, les labels, les textes alternatifs, et une structure sémantique claire. C\'est essentiel pour les lecteurs d\'écran et le SEO.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accessibilité</title>
</head>
<body>



</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accessibilité</title>
</head>
<body>

<header role="banner">
<h1>Page accessible</h1>
</header>

<nav role="navigation" aria-label="Menu principal">
<ul>
<li><a href="#accueil">Accueil</a></li>
</ul>
</nav>

<main role="main">
<section>
<h2>Section principale</h2>
<img src="image.jpg" alt="Description détaillée de l\'image" role="img">
</section>

<form>
<label for="nom">Nom complet :</label>
<input type="text" id="nom" name="nom" aria-required="true" required>

<button type="submit" aria-label="Soumettre le formulaire">Envoyer</button>
</form>
</main>

<footer role="contentinfo">
<p>&copy; 2025</p>
</footer>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Ajoutez role="banner|navigation|main|contentinfo", aria-label, aria-required, et liez les <label> avec for/id. Utilisez alt descriptif sur les images.')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Les titres HTML'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Créez une structure de titres hiérarchique avec h1, h2 et h3. Le h1 doit contenir "Titre principal", le h2 "Sous-titre" et le h3 "Sous-sous-titre".'),
                    'description' => $getTranslated('description', 'Les titres HTML (h1 à h6) structurent le contenu de manière hiérarchique. h1 est le titre principal (un seul par page), h2 pour les sections, h3 pour les sous-sections. La hiérarchie est importante pour le SEO et l\'accessibilité.'),
                    'hint' => $getTranslated('hint', 'Utilisez <h1>Titre principal</h1>, <h2>Sous-titre</h2>, et <h3>Sous-sous-titre</h3> pour créer la hiérarchie des titres.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Titres HTML</title>
</head>
<body>



</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Titres HTML</title>
</head>
<body>

<h1>Titre principal</h1>
<h2>Sous-titre</h2>
<h3>Sous-sous-titre</h3>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez <h1>Titre principal</h1>, <h2>Sous-titre</h2>, et <h3>Sous-sous-titre</h3> pour créer la hiérarchie des titres.')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Les sauts de ligne'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Créez deux lignes de texte séparées en utilisant la balise de saut de ligne. La première ligne doit dire "Première ligne" et la deuxième "Deuxième ligne".'),
                    'description' => $getTranslated('description', 'La balise <br> crée un saut de ligne dans le texte. C\'est une balise auto-fermante (<br> ou <br />). Contrairement à <p>, <br> ne crée pas d\'espacement vertical, juste un retour à la ligne. Utilisez-la pour forcer un saut de ligne dans un paragraphe.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sauts de ligne</title>
</head>
<body>

<p>Première ligne Deuxième ligne</p>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sauts de ligne</title>
</head>
<body>

<p>Première ligne<br>Deuxième ligne</p>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez <br> entre les deux lignes pour créer un saut de ligne. La structure est : <p>Première ligne<br>Deuxième ligne</p>')
                ],
                9 => [
                    'title' => $getTranslated('title', 'Les formulaires de base'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Créez un formulaire avec un champ texte (nom), un champ email, et un bouton submit. Utilisez les balises <form>, <input>, <label> et <button>.'),
                    'description' => $getTranslated('description', 'Les formulaires HTML permettent de collecter des données utilisateur. <form> contient les champs, <label> améliore l\'accessibilité, <input> crée les champs (type="text", type="email"), et <button type="submit"> envoie le formulaire. L\'attribut for dans <label> doit correspondre à l\'id de l\'input.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire de base</title>
</head>
<body>

<h1>Contact</h1>



</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire de base</title>
</head>
<body>

<h1>Contact</h1>

<form>
<label for="nom">Nom :</label>
<input type="text" id="nom" name="nom">

<label for="email">Email :</label>
<input type="email" id="email" name="email">

<button type="submit">Envoyer</button>
</form>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez <form> pour contenir le formulaire, <label for="id"> pour les étiquettes, <input type="text|email" id="..." name="..."> pour les champs, et <button type="submit"> pour le bouton.')
                ],
                10 => [
                    'title' => $getTranslated('title', 'Les citations et abréviations'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 15,
                    'instruction' => $getTranslated('instruction', 'Créez une citation avec <blockquote> contenant "Le code est poésie" et une abréviation <abbr> pour "HTML" avec le titre "HyperText Markup Language".'),
                    'description' => $getTranslated('description', 'Les citations et abréviations enrichissent le contenu. <blockquote> crée une citation, <q> crée une citation courte, <abbr> crée une abréviation avec title pour le texte complet. C\'est utile pour les références et les explications.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Citations et abréviations</title>
</head>
<body>

<h1>Citations</h1>



</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Citations et abréviations</title>
</head>
<body>

<h1>Citations</h1>

<blockquote>Le code est poésie</blockquote>

<p>J\'apprends <abbr title="HyperText Markup Language">HTML</abbr>.</p>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez <blockquote>Le code est poésie</blockquote> pour la citation et <abbr title="HyperText Markup Language">HTML</abbr> pour l\'abréviation.')
                ],
                14 => [
                    'title' => $getTranslated('title', 'Métadonnées et SEO HTML5'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Créez une page HTML5 optimisée pour le SEO avec : meta description, meta keywords, Open Graph (og:title, og:description), et un lien canonique. Ajoutez aussi un schema.org de type "Article".'),
                    'description' => $getTranslated('description', 'Le SEO (Search Engine Optimization) améliore la visibilité dans les moteurs de recherche. Les meta tags (description, keywords) fournissent des informations aux moteurs. Open Graph améliore le partage sur les réseaux sociaux. Schema.org structure les données pour les moteurs de recherche. C\'est essentiel pour le référencement moderne.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Article SEO</title>
</head>
<body>

<h1>Mon article</h1>
<p>Contenu de l\'article...</p>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Article SEO</title>
    <meta name="description" content="Description de l\'article pour le SEO">
    <meta name="keywords" content="HTML5, SEO, Web">
    <link rel="canonical" href="https://example.com/article">
    <meta property="og:title" content="Article SEO">
    <meta property="og:description" content="Description de l\'article">
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Article",
      "headline": "Mon article",
      "author": {
        "@type": "Person",
        "name": "Auteur"
      }
    }
    </script>
</head>
<body>

<h1>Mon article</h1>
<p>Contenu de l\'article...</p>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Ajoutez <meta name="description">, <meta name="keywords">, <link rel="canonical">, <meta property="og:title|og:description">, et un <script type="application/ld+json"> avec le schema.org.')
                ],
                15 => [
                    'title' => $getTranslated('title', 'Multimédia HTML5 (audio/vidéo)'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Intégrez une vidéo HTML5 avec les attributs controls, autoplay, loop, et poster. Ajoutez aussi une piste audio avec <audio> et controls. Utilisez <source> pour plusieurs formats (mp4, webm pour vidéo).'),
                    'description' => $getTranslated('description', 'HTML5 introduit <video> et <audio> pour intégrer du multimédia natif. controls affiche les contrôles, autoplay démarre automatiquement, loop répète, poster définit une image de prévisualisation. <source> permet de spécifier plusieurs formats pour la compatibilité. C\'est la méthode moderne pour le multimédia web.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Multimédia HTML5</title>
</head>
<body>

<h1>Vidéo et Audio</h1>



</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Multimédia HTML5</title>
</head>
<body>

<h1>Vidéo et Audio</h1>

<video controls autoplay loop poster="preview.jpg" width="640" height="360">
<source src="video.mp4" type="video/mp4">
<source src="video.webm" type="video/webm">
Votre navigateur ne supporte pas la vidéo.
</video>

<audio controls>
<source src="audio.mp3" type="audio/mpeg">
<source src="audio.ogg" type="audio/ogg">
Votre navigateur ne supporte pas l\'audio.
</audio>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez <video controls autoplay loop poster="..."> avec <source> pour les formats, et <audio controls> avec <source> pour l\'audio. Ajoutez un texte de fallback entre les balises.')
                ],
            ],
            'css3' => [
                1 => [
                    'title' => $getTranslated('title', 'Les sélecteurs CSS'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Changez la couleur du texte du paragraphe en bleu en utilisant la propriété CSS color.'),
                    'description' => $getTranslated('description', 'Les sélecteurs CSS permettent de cibler des éléments HTML pour leur appliquer des styles. La propriété color définit la couleur du texte. Les valeurs peuvent être des noms de couleurs (blue, red), des codes hexadécimaux (#0000FF), ou des valeurs RGB. Complétez le code pour que le paragraphe soit bleu.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sélecteurs CSS</title>
    <style>
    p {
      
    }
    </style>
</head>
<body>

<p>Ce texte doit être bleu.</p>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sélecteurs CSS</title>
    <style>
    p {
      color: blue;
    }
    </style>
</head>
<body>

<p>Ce texte doit être bleu.</p>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez color: blue; dans le sélecteur p. La syntaxe est : color: valeur;')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Couleurs et arrière-plans'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Changez la couleur de fond de la div en bleu et la couleur du texte en blanc.'),
                    'description' => $getTranslated('description', 'Les propriétés background-color et color contrôlent respectivement la couleur de fond et du texte. Les valeurs peuvent être des noms (blue, white), des codes hex (#0000FF, #FFFFFF), ou des valeurs RGB/rgba. C\'est la base du style CSS.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Couleurs CSS</title>
    <style>
    .box {
      padding: 20px;
      
    }
    </style>
</head>
<body>

<div class="box">Texte dans la boîte</div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Couleurs CSS</title>
    <style>
    .box {
      padding: 20px;
      background-color: blue;
      color: white;
    }
    </style>
</head>
<body>

<div class="box">Texte dans la boîte</div>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez background-color: blue; pour le fond et color: white; pour le texte.')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Marges et padding'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Ajoutez un padding de 20px et une marge de 10px à la div.'),
                    'description' => $getTranslated('description', 'Le padding crée un espacement interne (entre le contenu et la bordure), tandis que margin crée un espacement externe (entre l\'élément et les autres éléments). Les valeurs peuvent être en px, em, rem, ou %. C\'est fondamental pour le layout CSS.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Marges et Padding</title>
    <style>
    .box {
      background: lightblue;
      
    }
    </style>
</head>
<body>

<div class="box">Contenu</div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Marges et Padding</title>
    <style>
    .box {
      background: lightblue;
      padding: 20px;
      margin: 10px;
    }
    </style>
</head>
<body>

<div class="box">Contenu</div>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez padding: 20px; pour l\'espacement interne et margin: 10px; pour l\'espacement externe.')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Bordures CSS'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Ajoutez une bordure solide de 2px de couleur rouge autour de la div.'),
                    'description' => $getTranslated('description', 'La propriété border crée une bordure autour d\'un élément. La syntaxe est : border: width style color. Les styles courants sont solid, dashed, dotted, double. border peut être défini globalement ou par côté (border-top, border-left, etc.).'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bordures CSS</title>
    <style>
    .box {
      padding: 20px;
      background: lightgray;
      
    }
    </style>
</head>
<body>

<div class="box">Contenu avec bordure</div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bordures CSS</title>
    <style>
    .box {
      padding: 20px;
      background: lightgray;
      border: 2px solid red;
    }
    </style>
</head>
<body>

<div class="box">Contenu avec bordure</div>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez border: 2px solid red; pour créer une bordure de 2px, solide, rouge.')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Polices et texte'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Changez la police en Arial, la taille en 18px, et mettez le texte en gras.'),
                    'description' => $getTranslated('description', 'Les propriétés de texte CSS contrôlent l\'apparence du texte. font-family définit la police, font-size la taille, font-weight l\'épaisseur (normal, bold), font-style le style (normal, italic). C\'est essentiel pour la typographie web.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Polices CSS</title>
    <style>
    p {

    }
    </style>
</head>
<body>

<p>Texte à styliser</p>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Polices CSS</title>
    <style>
    p {
      font-family: Arial, sans-serif;
      font-size: 18px;
      font-weight: bold;
    }
    </style>
</head>
<body>

<p>Texte à styliser</p>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez font-family: Arial, sans-serif; font-size: 18px; font-weight: bold;')
                ],
                6 => [
                    'title' => $getTranslated('title', 'Flexbox - Centrage'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Centrez horizontalement et verticalement la div bleue dans son conteneur en utilisant Flexbox.'),
                    'description' => $getTranslated('description', 'Flexbox est un système de mise en page CSS moderne qui facilite l\'alignement et la distribution de l\'espace. display: flex active Flexbox, justify-content centre horizontalement, et align-items centre verticalement. C\'est la méthode moderne pour centrer des éléments.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Flexbox Centrage</title>
    <style>
    .container {
      height: 300px;
      border: 2px solid black;
      
    
      
    }
    .box {
      width: 100px;
      height: 100px;
      background: cyan;
    }
    </style>
</head>
<body>

<div class="container">
  <div class="box"></div>
</div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Flexbox Centrage</title>
    <style>
    .container {
      height: 300px;
      border: 2px solid black;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .box {
      width: 100px;
      height: 100px;
      background: cyan;
    }
    </style>
</head>
<body>

<div class="container">
  <div class="box"></div>
</div>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez display: flex pour activer Flexbox, justify-content: center pour centrer horizontalement, et align-items: center pour centrer verticalement.')
                ],
                7 => [
                    'title' => $getTranslated('title', 'Grid Layout'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Créez une grille CSS avec 3 colonnes de largeur égale pour organiser les éléments.'),
                    'description' => $getTranslated('description', 'CSS Grid est un système de mise en page bidimensionnel puissant. display: grid active Grid, et grid-template-columns définit les colonnes. L\'unité fr (fraction) distribue l\'espace disponible. Grid est idéal pour créer des layouts complexes.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CSS Grid</title>
    <style>
    .grid {
      
      
    }
    .item {
      background: lightblue;
      padding: 20px;
      border: 1px solid blue;
    }
    </style>
</head>
<body>

<div class="grid">
  <div class="item">1</div>
  <div class="item">2</div>
  <div class="item">3</div>
</div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CSS Grid</title>
    <style>
    .grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
    }
    .item {
      background: lightblue;
      padding: 20px;
      border: 1px solid blue;
    }
    </style>
</head>
<body>

<div class="grid">
  <div class="item">1</div>
  <div class="item">2</div>
  <div class="item">3</div>
</div>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez display: grid pour activer Grid, et grid-template-columns: 1fr 1fr 1fr pour créer 3 colonnes égales.')
                ],
                8 => [
                    'title' => $getTranslated('title', 'Responsive Design'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 15,
                    'instruction' => $getTranslated('instruction', 'Rendez le texte rouge sur les petits écrans (largeur maximale 600px) en utilisant une media query.'),
                    'description' => $getTranslated('description', 'Le responsive design adapte le design aux différentes tailles d\'écran. Les media queries (@media) permettent d\'appliquer des styles conditionnels selon la largeur de l\'écran. max-width définit la largeur maximale pour laquelle les styles s\'appliquent.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Responsive Design</title>
    <style>
    p {
      color: blue;
    }

    
    </style>
</head>
<body>

<p>Ce texte doit être rouge sur mobile.</p>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Responsive Design</title>
    <style>
    p {
      color: blue;
    }

    @media (max-width: 600px) {
      p { color: red; }
    }
    </style>
</head>
<body>

<p>Ce texte doit être rouge sur mobile.</p>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez @media (max-width: 600px) { p { color: red; } } pour appliquer le style rouge sur les écrans de moins de 600px.')
                ],
                9 => [
                    'title' => $getTranslated('title', 'Pseudo-classes et pseudo-éléments'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Changez la couleur du lien en rouge au survol (hover) et ajoutez un soulignement à la première ligne du paragraphe avec ::first-line.'),
                    'description' => $getTranslated('description', 'Les pseudo-classes (:hover, :active, :focus) ciblent des états d\'éléments. Les pseudo-éléments (::before, ::after, ::first-line, ::first-letter) ciblent des parties d\'éléments. :hover s\'active au survol, ::first-line cible la première ligne. C\'est puissant pour les interactions.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Pseudo-classes</title>
    <style>
    a {
      color: blue;
    }
    
    </style>
</head>
<body>

<a href="#">Lien</a>
<p>Première ligne du paragraphe. Deuxième ligne du paragraphe.</p>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Pseudo-classes</title>
    <style>
    a {
      color: blue;
    }
    a:hover {
      color: red;
    }
    p::first-line {
      text-decoration: underline;
    }
    </style>
</head>
<body>

<a href="#">Lien</a>
<p>Première ligne du paragraphe. Deuxième ligne du paragraphe.</p>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez a:hover { color: red; } pour le survol et p::first-line { text-decoration: underline; } pour la première ligne.')
                ],
                10 => [
                    'title' => $getTranslated('title', 'Transitions CSS'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Créez une transition fluide de 0.5s sur le changement de couleur de fond du bouton au survol.'),
                    'description' => $getTranslated('description', 'Les transitions CSS créent des animations fluides entre deux états. transition définit la propriété, la durée, et le timing-function. C\'est plus simple que les animations pour des changements d\'état. Les transitions améliorent l\'expérience utilisateur.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Transitions CSS</title>
    <style>
    button {
      background: blue;
      color: white;
      padding: 10px 20px;
      border: none;
      
    }
    button:hover {
      background: red;
    }
    </style>
</head>
<body>

<button>Survolez-moi</button>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Transitions CSS</title>
    <style>
    button {
      background: blue;
      color: white;
      padding: 10px 20px;
      border: none;
      transition: background 0.5s;
    }
    button:hover {
      background: red;
    }
    </style>
</head>
<body>

<button>Survolez-moi</button>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Ajoutez transition: background 0.5s; au bouton pour créer une transition fluide de 0.5 secondes sur la couleur de fond.')
                ],
                11 => [
                    'title' => $getTranslated('title', 'Animations CSS'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 25,
                    'instruction' => $getTranslated('instruction', 'Créez une animation CSS qui fait tourner la div violette en continu (rotation 360 degrés).'),
                    'description' => $getTranslated('description', 'Les animations CSS permettent de créer des effets visuels fluides sans JavaScript. @keyframes définit les étapes de l\'animation, et la propriété animation applique l\'animation avec durée, timing-function et répétition. linear assure une vitesse constante, infinite répète indéfiniment.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Animations CSS</title>
    <style>
    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { }
    }

    .box {
      width: 100px;
      height: 100px;
      background: purple;
      
    }
    </style>
</head>
<body>

<div class="box"></div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Animations CSS</title>
    <style>
    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    .box {
      width: 100px;
      height: 100px;
      background: purple;
      animation: rotate 2s linear infinite;
    }
    </style>
</head>
<body>

<div class="box"></div>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Complétez le @keyframes avec to { transform: rotate(360deg); } et ajoutez animation: rotate 2s linear infinite; à .box.')
                ],
                12 => [
                    'title' => $getTranslated('title', 'CSS Variables'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 25,
                    'instruction' => $getTranslated('instruction', 'Créez des variables CSS (--primary-color: blue, --spacing: 20px) et utilisez-les pour styliser les éléments. Changez la couleur de fond et le padding en utilisant var().'),
                    'description' => $getTranslated('description', 'Les variables CSS (custom properties) permettent de stocker des valeurs réutilisables. Définies avec --nom-variable, elles sont accessibles via var(). Elles facilitent la maintenance et permettent de créer des thèmes dynamiques. Les variables sont héritées et peuvent être redéfinies.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CSS Variables</title>
    <style>
    :root {
      
    }
    
    .box {
      background: ;
      padding: ;
    }
    </style>
</head>
<body>

<div class="box">Contenu avec variables CSS</div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CSS Variables</title>
    <style>
    :root {
      --primary-color: blue;
      --spacing: 20px;
    }
    
    .box {
      background: var(--primary-color);
      padding: var(--spacing);
    }
    </style>
</head>
<body>

<div class="box">Contenu avec variables CSS</div>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Définissez les variables dans :root avec --primary-color: blue; et --spacing: 20px; puis utilisez-les avec var(--primary-color) et var(--spacing).')
                ],
                13 => [
                    'title' => $getTranslated('title', 'Advanced Grid Layout'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Créez une grille CSS complexe avec 3 colonnes (200px, 1fr, 200px), une zone header qui spanne 3 colonnes, et une zone sidebar qui spanne 2 lignes. Utilisez grid-template-areas pour nommer les zones.'),
                    'description' => $getTranslated('description', 'CSS Grid avancé permet de créer des layouts complexes avec grid-template-areas pour nommer les zones, grid-column et grid-row pour le spanning, et des tailles mixtes (px, fr, auto). C\'est la méthode moderne pour créer des layouts de type magazine.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Advanced Grid</title>
    <style>
    .grid {
      display: grid;
      
      
      
    }
    .header {
      background: lightblue;
    }
    .sidebar {
      background: lightgreen;
    }
    .content {
      background: lightyellow;
    }
    </style>
</head>
<body>

<div class="grid">
  <div class="header">Header</div>
  <div class="sidebar">Sidebar</div>
  <div class="content">Content</div>
</div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Advanced Grid</title>
    <style>
    .grid {
      display: grid;
      grid-template-columns: 200px 1fr 200px;
      grid-template-rows: auto 1fr;
      grid-template-areas: 
        "header header header"
        "sidebar content .";
    }
    .header {
      background: lightblue;
      grid-area: header;
    }
    .sidebar {
      background: lightgreen;
      grid-area: sidebar;
    }
    .content {
      background: lightyellow;
      grid-area: content;
    }
    </style>
</head>
<body>

<div class="grid">
  <div class="header">Header</div>
  <div class="sidebar">Sidebar</div>
  <div class="content">Content</div>
</div>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez grid-template-columns: 200px 1fr 200px; grid-template-areas pour nommer les zones, et grid-area pour assigner les éléments aux zones.')
                ],
                14 => [
                    'title' => $getTranslated('title', 'Transformations CSS 3D'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Créez une transformation 3D qui fait tourner la div de 45 degrés sur l\'axe Y (rotateY) avec une perspective de 1000px sur le conteneur parent.'),
                    'description' => $getTranslated('description', 'Les transformations 3D CSS créent des effets de profondeur. transform: rotateY() fait tourner sur l\'axe Y, perspective sur le parent crée la profondeur 3D. transform-style: preserve-3d maintient la 3D. C\'est avancé mais puissant pour les effets visuels.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Transformations 3D</title>
    <style>
    .container {
      width: 200px;
      height: 200px;
      
    }
    .box {
      width: 100%;
      height: 100%;
      background: cyan;
      
    }
    </style>
</head>
<body>

<div class="container">
  <div class="box"></div>
</div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Transformations 3D</title>
    <style>
    .container {
      width: 200px;
      height: 200px;
      perspective: 1000px;
    }
    .box {
      width: 100%;
      height: 100%;
      background: cyan;
      transform: rotateY(45deg);
    }
    </style>
</head>
<body>

<div class="container">
  <div class="box"></div>
</div>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Ajoutez perspective: 1000px; au conteneur et transform: rotateY(45deg); à la box pour créer la rotation 3D.')
                ],
                15 => [
                    'title' => $getTranslated('title', 'CSS Architecture (BEM, SMACSS)'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Créez une structure CSS suivant la méthodologie BEM (Block Element Modifier). Créez un bloc "card", un élément "card__title", et un modificateur "card--highlighted".'),
                    'description' => $getTranslated('description', 'BEM (Block Element Modifier) est une méthodologie de nommage CSS. Block = composant indépendant, Element = partie du bloc (__element), Modifier = variation (--modifier). SMACSS organise le CSS en catégories (Base, Layout, Module, State, Theme). C\'est essentiel pour maintenir du CSS à grande échelle.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BEM CSS</title>
    <style>
    /* Créez les classes BEM */
    
    </style>
</head>
<body>

<div class="card">
  <h2 class="">Titre de la carte</h2>
  <p>Contenu</p>
</div>

<div class="">
  <h2 class="">Titre mis en évidence</h2>
  <p>Contenu</p>
</div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BEM CSS</title>
    <style>
    .card {
      border: 1px solid #ccc;
      padding: 20px;
    }
    .card__title {
      color: #333;
      font-size: 24px;
    }
    .card--highlighted {
      background: yellow;
      border-color: orange;
    }
    </style>
</head>
<body>

<div class="card">
  <h2 class="card__title">Titre de la carte</h2>
  <p>Contenu</p>
</div>

<div class="card card--highlighted">
  <h2 class="card__title">Titre mis en évidence</h2>
  <p>Contenu</p>
</div>

</body>
</html>',
                    'hint' => 'Utilisez .card pour le bloc, .card__title pour l\'élément, et .card--highlighted pour le modificateur. Appliquez card et card--highlighted ensemble sur la deuxième div.'
                ],
            ],
            'javascript' => [
                1 => [
                    'title' => $getTranslated('title', 'Variables et types'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Créez une variable "nom" avec votre prénom et affichez-la dans l\'élément avec l\'id "demo".'),
                    'description' => $getTranslated('description', 'Les variables JavaScript stockent des valeurs. let permet de déclarer une variable modifiable, const une constante. Les chaînes de caractères sont entre guillemets. document.getElementById() sélectionne un élément par son ID, et innerHTML modifie son contenu HTML.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Variables JavaScript</title>
</head>
<body>

<p id="demo"></p>

<script>


document.getElementById("demo").innerHTML = nom;
</script>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Variables JavaScript</title>
</head>
<body>

<p id="demo"></p>

<script>
let nom = "Jean";

document.getElementById("demo").innerHTML = nom;
</script>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez let nom = "VotreNom"; pour déclarer la variable, puis document.getElementById("demo").innerHTML = nom; pour l\'afficher.')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Fonctions'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 15,
                    'instruction' => $getTranslated('instruction', 'Créez une fonction qui additionne deux nombres.'),
                    'description' => $getTranslated('description', 'Les fonctions JavaScript organisent le code réutilisable. function nomFonction(param1, param2) { return valeur; } définit une fonction. return retourne une valeur. Les fonctions peuvent avoir des paramètres par défaut. C\'est essentiel pour éviter la duplication de code.'),
                    'startCode' => '<html>
<body>

<p id="demo"></p>

<script>


let resultat = additionner(5, 3);
document.getElementById("demo").innerHTML = resultat;
</script>

</body>
</html>',
                    'solution' => '<html>
<body>

<p id="demo"></p>

<script>
function additionner(a, b) {
  return a + b;
}

let resultat = additionner(5, 3);
document.getElementById("demo").innerHTML = resultat;
</script>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Créez function additionner(a, b) { return a + b; }')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Conditions if/else'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Créez une condition if/else qui affiche "Majeur" si l\'âge est >= 18, sinon "Mineur".'),
                    'description' => $getTranslated('description', 'Les conditions if/else permettent d\'exécuter du code de manière conditionnelle. if (condition) exécute le code si la condition est vraie, else exécute le code alternatif. Les opérateurs de comparaison (==, ===, <, >, <=, >=) comparent des valeurs. C\'est fondamental pour la logique de programmation.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Conditions JavaScript</title>
</head>
<body>

<p id="demo"></p>

<script>
let age = 20;


document.getElementById("demo").innerHTML = statut;
</script>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Conditions JavaScript</title>
</head>
<body>

<p id="demo"></p>

<script>
let age = 20;
let statut;

if (age >= 18) {
  statut = "Majeur";
} else {
  statut = "Mineur";
}

document.getElementById("demo").innerHTML = statut;
</script>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez if (age >= 18) { statut = "Majeur"; } else { statut = "Mineur"; } pour créer la condition.')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Boucles for et while'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Affichez les nombres de 1 à 5 en utilisant une boucle for.'),
                    'description' => $getTranslated('description', 'Les boucles répètent du code. for (initialisation; condition; incrément) répète tant que la condition est vraie. while (condition) répète aussi, mais l\'incrément doit être géré manuellement. Les boucles sont essentielles pour traiter des collections de données.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Boucles JavaScript</title>
</head>
<body>

<p id="demo"></p>

<script>
let texte = "";


document.getElementById("demo").innerHTML = texte;
</script>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Boucles JavaScript</title>
</head>
<body>

<p id="demo"></p>

<script>
let texte = "";

for (let i = 1; i <= 5; i++) {
  texte += i + " ";
}

document.getElementById("demo").innerHTML = texte;
</script>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez for (let i = 1; i <= 5; i++) { texte += i + " "; } pour créer la boucle.')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Opérateurs JavaScript'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Utilisez les opérateurs arithmétiques pour calculer et afficher le résultat de (10 + 5) * 2.'),
                    'description' => $getTranslated('description', 'Les opérateurs JavaScript effectuent des opérations. + (addition), - (soustraction), * (multiplication), / (division), % (modulo). Les opérateurs de comparaison (==, ===, !=, !==, <, >, <=, >=) comparent des valeurs. Les opérateurs logiques (&&, ||, !) combinent des conditions.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Opérateurs JavaScript</title>
</head>
<body>

<p id="demo"></p>

<script>
// Complétez cette ligne pour calculer (10 + 5) * 2
let resultat = 


document.getElementById("demo").innerHTML = resultat;
</script>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Opérateurs JavaScript</title>
</head>
<body>

<p id="demo"></p>

<script>
let resultat = (10 + 5) * 2;

document.getElementById("demo").innerHTML = resultat;
</script>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez let resultat = (10 + 5) * 2; pour calculer le résultat.')
                ],
                6 => [
                    'title' => $getTranslated('title', 'DOM Manipulation'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Sélectionnez l\'élément avec l\'id "titre" et changez son contenu en "Nouveau Titre".'),
                    'description' => $getTranslated('description', 'Le DOM (Document Object Model) représente la structure HTML. document.getElementById() sélectionne par ID, querySelector() par sélecteur CSS. innerHTML modifie le contenu HTML, textContent modifie le texte. C\'est essentiel pour créer des interfaces interactives.'),
                    'startCode' => '<html>
<body>

<button id="monBouton">Cliquez-moi</button>

<script>
let bouton = document.getElementById("monBouton");


</script>

</body>
</html>',
                    'solution' => '<html>
<body>

<button id="monBouton">Cliquez-moi</button>

<script>
let bouton = document.getElementById("monBouton");
bouton.addEventListener("click", function() {
  bouton.innerHTML = "Cliqué !";
});
</script>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez document.getElementById("titre").innerHTML = "Nouveau Titre"; pour modifier le contenu.')
                ],
                7 => [
                    'title' => $getTranslated('title', 'Tableaux et boucles'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Créez un tableau avec 3 fruits et affichez chaque fruit avec une boucle forEach.'),
                    'description' => $getTranslated('description', 'Les tableaux JavaScript stockent des collections ordonnées. [] crée un tableau, push() ajoute un élément, forEach() parcourt tous les éléments. Les tableaux sont fondamentaux en JavaScript.'),
                    'startCode' => '<html>
<body>

<p id="demo"></p>

<script>
let fruits = ["Pomme", "Banane", "Orange"];
let texte = "";



document.getElementById("demo").innerHTML = texte;
</script>

</body>
</html>',
                    'solution' => '<html>
<body>

<p id="demo"></p>

<script>
let fruits = ["Pomme", "Banane", "Orange"];
let texte = "";

for (let i = 0; i < fruits.length; i++) {
  texte += fruits[i] + " ";
}

document.getElementById("demo").innerHTML = texte;
</script>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Créez let fruits = ["Pomme", "Banane", "Orange"]; puis fruits.forEach(fruit => console.log(fruit));')
                ],
                8 => [
                    'title' => $getTranslated('title', 'Événements JavaScript'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Ajoutez un événement click sur un bouton qui affiche "Clic effectué !" dans la console.'),
                    'description' => $getTranslated('description', 'Les événements JavaScript réagissent aux interactions utilisateur. addEventListener() attache un gestionnaire d\'événements. Les événements courants sont click, mouseover, keydown, submit. C\'est essentiel pour l\'interactivité web.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Événements JavaScript</title>
    <style>
    .box {
      width: 200px;
      height: 200px;
      background: gray;
    }
    </style>
</head>
<body>

<div class="box" id="maBox"></div>

<script>
let box = document.getElementById("maBox");


</script>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Événements JavaScript</title>
    <style>
    .box {
      width: 200px;
      height: 200px;
      background: gray;
    }
    </style>
</head>
<body>

<div class="box" id="maBox"></div>

<script>
let box = document.getElementById("maBox");

box.addEventListener("mouseover", function() {
  box.style.backgroundColor = "red";
});

box.addEventListener("mouseout", function() {
  box.style.backgroundColor = "blue";
});
</script>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez button.addEventListener("click", () => { console.log("Clic effectué !"); });')
                ],
                9 => [
                    'title' => $getTranslated('title', 'Manipulation de chaînes'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 15,
                    'instruction' => $getTranslated('instruction', 'Créez une chaîne "Bonjour" et utilisez toUpperCase() pour l\'afficher en majuscules.'),
                    'description' => $getTranslated('description', 'Les chaînes JavaScript ont de nombreuses méthodes. toUpperCase() convertit en majuscules, toLowerCase() en minuscules, length retourne la longueur, substring() extrait une partie. C\'est essentiel pour traiter du texte.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Manipulation de chaînes</title>
</head>
<body>

<p id="demo"></p>

<script>
let chaine1 = "Bonjour";
let chaine2 = "Monde";
let resultat = 


document.getElementById("demo").innerHTML = resultat;
</script>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Manipulation de chaînes</title>
</head>
<body>

<p id="demo"></p>

<script>
let chaine1 = "Bonjour";
let chaine2 = "Monde";
let resultat = (chaine1 + " " + chaine2).toUpperCase();

document.getElementById("demo").innerHTML = resultat;
</script>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez let texte = "Bonjour"; puis texte.toUpperCase();')
                ],
                10 => [
                    'title' => $getTranslated('title', 'Fonctions fléchées'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Convertissez la fonction function multiplier(a, b) { return a * b; } en fonction fléchée.'),
                    'description' => $getTranslated('description', 'Les fonctions fléchées (arrow functions) sont une syntaxe ES6 concise. (param) => expression remplace function(param) { return expression; }. Elles préservent le contexte this. C\'est la syntaxe moderne recommandée.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fonctions fléchées</title>
</head>
<body>

<p id="demo"></p>

<script>
function multiplier(a, b) {
  return a * b;
}

let resultat = multiplier(5, 3);
document.getElementById("demo").innerHTML = resultat;
</script>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fonctions fléchées</title>
</head>
<body>

<p id="demo"></p>

<script>
const multiplier = (a, b) => a * b;

let resultat = multiplier(5, 3);
document.getElementById("demo").innerHTML = resultat;
</script>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez const multiplier = (a, b) => a * b;')
                ],
                11 => [
                    'title' => $getTranslated('title', 'Objets JavaScript'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Créez un objet personne avec les propriétés nom et age, puis affichez-les.'),
                    'description' => $getTranslated('description', 'Les objets JavaScript stockent des paires clé-valeur. {} crée un objet, .propriété accède à une propriété, [\'propriété\'] aussi. Les objets sont fondamentaux pour représenter des données structurées.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Objets JavaScript</title>
</head>
<body>

<p id="demo"></p>

<script>


document.getElementById("demo").innerHTML = personne.nom;
</script>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Objets JavaScript</title>
</head>
<body>

<p id="demo"></p>

<script>
let personne = {
  nom: "Marie",
  age: 25
};

document.getElementById("demo").innerHTML = personne.nom;
</script>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Créez let personne = { nom: "Jean", age: 30 }; puis console.log(personne.nom, personne.age);')
                ],
                12 => [
                    'title' => $getTranslated('title', 'Promises et Async/Await'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Créez une Promise qui se résout après 1 seconde avec la valeur "Succès" et utilisez async/await pour l\'attendre.'),
                    'description' => $getTranslated('description', 'Les Promises gèrent les opérations asynchrones. new Promise() crée une promise, then() gère le succès, catch() gère les erreurs. async/await est une syntaxe moderne pour les promises. C\'est essentiel pour les appels API.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Async/Await</title>
</head>
<body>

<p id="demo">Chargement...</p>

<script>



</script>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Async/Await</title>
</head>
<body>

<p id="demo">Chargement...</p>

<script>
async function chargerDonnees() {
  try {
    const response = await fetch("https://jsonplaceholder.typicode.com/posts/1");
    const data = await response.json();
    document.getElementById("demo").innerHTML = data.title;
  } catch (error) {
    document.getElementById("demo").innerHTML = "Erreur: " + error.message;
  }
}

chargerDonnees();
</script>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Créez const promise = new Promise(resolve => setTimeout(() => resolve("Succès"), 1000)); puis const resultat = await promise;')
                ],
                13 => [
                    'title' => $getTranslated('title', 'Closures et Scope'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Créez une fonction qui retourne une fonction interne qui incrémente un compteur privé.'),
                    'description' => $getTranslated('description', 'Les closures permettent à une fonction interne d\'accéder aux variables de la fonction externe même après que la fonction externe soit terminée. C\'est puissant pour créer des variables privées et des fonctions factory.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Closures</title>
</head>
<body>

<p id="demo"></p>

<script>



</script>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Closures</title>
</head>
<body>

<p id="demo"></p>

<script>
function creerCompteur() {
  let compteur = 0;
  return function() {
    compteur++;
    return compteur;
  };
}

const incrementer = creerCompteur();
document.getElementById("demo").innerHTML = incrementer();
</script>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Créez function creerCompteur() { let compteur = 0; return function() { compteur++; return compteur; }; }')
                ],
                14 => [
                    'title' => $getTranslated('title', 'Destructuring et Spread'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 25,
                    'instruction' => $getTranslated('instruction', 'Utilisez le destructuring pour extraire "nom" et "age" de l\'objet personne, puis utilisez le spread operator pour copier le tableau fruits dans un nouveau tableau.'),
                    'description' => $getTranslated('description', 'Le destructuring extrait des valeurs d\'objets ou tableaux. const {nom, age} = personne extrait les propriétés. Le spread operator (...) copie ou fusionne des tableaux/objets. const nouveau = [...ancien] copie un tableau. C\'est une syntaxe moderne ES6 très utilisée.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Destructuring et Spread</title>
</head>
<body>

<p id="demo"></p>

<script>
let personne = { nom: "Jean", age: 30, ville: "Paris" };
let fruits = ["Pomme", "Banane"];

// Utilisez le destructuring pour extraire nom et age


// Utilisez le spread pour copier fruits dans nouveauFruits


document.getElementById("demo").innerHTML = nom + " " + age + " - " + nouveauFruits.join(", ");
</script>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Destructuring et Spread</title>
</head>
<body>

<p id="demo"></p>

<script>
let personne = { nom: "Jean", age: 30, ville: "Paris" };
let fruits = ["Pomme", "Banane"];

// Utilisez le destructuring pour extraire nom et age
const { nom, age } = personne;

// Utilisez le spread pour copier fruits dans nouveauFruits
const nouveauFruits = [...fruits];

document.getElementById("demo").innerHTML = nom + " " + age + " - " + nouveauFruits.join(", ");
</script>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez const { nom, age } = personne; pour le destructuring et const nouveauFruits = [...fruits]; pour le spread.')
                ],
                15 => [
                    'title' => $getTranslated('title', 'Modules ES6 (import/export)'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Créez un module qui exporte une fonction "multiplier" et importez-la dans le script principal. Utilisez export et import.'),
                    'description' => $getTranslated('description', 'Les modules ES6 permettent d\'organiser le code en fichiers séparés. export expose des fonctions/variables, import les importe. C\'est la méthode moderne pour structurer du JavaScript. Les modules améliorent la maintenabilité et permettent le tree-shaking.'),
                    'startCode' => '/* math.js */
// Exportez la fonction multiplier


/* main.js */
// Importez la fonction multiplier


let resultat = multiplier(5, 3);
console.log(resultat);',
                    'solution' => '/* math.js */
// Exportez la fonction multiplier
export function multiplier(a, b) {
  return a * b;
}

/* main.js */
// Importez la fonction multiplier
import { multiplier } from "./math.js";

let resultat = multiplier(5, 3);
console.log(resultat);',
                    'hint' => 'Utilisez export function multiplier(a, b) { return a * b; } dans math.js et import { multiplier } from "./math.js"; dans main.js.'
                ],
            ],
            'php' => [
                1 => [
                    'title' => $getTranslated('title', 'Syntaxe de base PHP'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Affichez "Bonjour PHP" avec echo.'),
                    'description' => $getTranslated('description', 'La syntaxe PHP utilise <?php ?> pour délimiter le code. echo affiche du texte. C\'est la base de PHP.'),
                    'startCode' => '<html>
<body>

<?php

?>

</body>
</html>',
                    'solution' => '<html>
<body>

<?php
echo "Bonjour PHP";
?>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez echo "Bonjour PHP"; entre les balises PHP.')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Variables PHP'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Créez une variable $nom avec votre prénom et affichez-la.'),
                    'description' => $getTranslated('description', 'Les variables PHP commencent par $. Elles sont typées dynamiquement. C\'est la base de PHP.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Variables PHP</title>
</head>
<body>

<?php


?>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Variables PHP</title>
</head>
<body>

<?php
$nom = "Pierre";
echo $nom;
?>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Créez $nom = "VotreNom"; puis echo $nom;')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Opérateurs PHP'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Calculez la somme de 10 et 5, puis affichez le résultat.'),
                    'description' => $getTranslated('description', 'PHP supporte les opérateurs arithmétiques : +, -, *, /, %, **. Les opérateurs de comparaison (==, ===, <, >) comparent des valeurs.'),
                    'startCode' => '<html>
<body>

<?php
$resultat = 


echo $resultat;
?>

</body>
</html>',
                    'solution' => '<html>
<body>

<?php
$resultat = (10 + 5) * 2;

echo $resultat;
?>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez $resultat = (10 + 5) * 2; pour calculer.')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Chaînes de caractères PHP'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Concaténez deux chaînes "Bonjour" et "PHP" avec un point (.), puis affichez le résultat en majuscules avec strtoupper().'),
                    'description' => $getTranslated('description', 'Les chaînes PHP peuvent être concaténées avec le point (.). strtoupper() met en majuscules, strtolower() en minuscules, strlen() retourne la longueur, substr() extrait une partie, strpos() trouve une position. C\'est essentiel pour manipuler du texte.'),
                    'startCode' => '<html>
<body>

<?php
$chaine1 = "Bonjour";
$chaine2 = "PHP";
$resultat = 


echo $resultat;
?>

</body>
</html>',
                    'solution' => '<html>
<body>

<?php
$chaine1 = "Bonjour";
$chaine2 = "PHP";
$resultat = strtoupper($chaine1 . " " . $chaine2);

echo $resultat;
?>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez $resultat = strtoupper($chaine1 . " " . $chaine2); pour concaténer avec un espace et convertir en majuscules.')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Commentaires PHP'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 8,
                    'instruction' => $getTranslated('instruction', 'Ajoutez un commentaire sur une ligne et un commentaire sur plusieurs lignes dans le code PHP.'),
                    'description' => $getTranslated('description', 'Les commentaires PHP documentent le code. // crée un commentaire sur une ligne, # aussi, /* */ crée un commentaire multi-lignes. Les commentaires ne sont pas exécutés. C\'est essentiel pour la maintenabilité du code.'),
                    'startCode' => '<html>
<body>

<?php
echo "Hello World";
?>

</body>
</html>',
                    'solution' => '<html>
<body>

<?php
// Commentaire sur une ligne
/* Commentaire
   sur plusieurs
   lignes */
echo "Hello World";
?>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez // pour un commentaire sur une ligne et /* */ pour un commentaire multi-lignes.')
                ],
                6 => [
                    'title' => $getTranslated('title', 'Conditions PHP'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 15,
                    'instruction' => $getTranslated('instruction', 'Affichez "Majeur" si $age >= 18, sinon "Mineur".'),
                    'description' => $getTranslated('description', 'Les conditions PHP utilisent if/elseif/else. Les opérateurs de comparaison (==, ===, <, >, <=, >=) comparent des valeurs.'),
                    'startCode' => '<html>
<body>

<?php
$age = 20;


?>

</body>
</html>',
                    'solution' => '<html>
<body>

<?php
$age = 20;
if ($age >= 18) {
  echo "Majeur";
} else {
  echo "Mineur";
}
?>

</body>
</html>',
                    'hint' => 'Utilisez if ($age >= 18) { echo "Majeur"; } else { echo "Mineur"; }'
                ],
                7 => [
                    'title' => 'Boucles PHP',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Affichez les nombres de 1 à 5 avec une boucle for.',
                    'description' => 'Utilisez une boucle for en PHP.',
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Boucles PHP</title>
</head>
<body>

<?php


?>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Boucles PHP</title>
</head>
<body>

<?php
for ($i = 1; $i <= 5; $i++) {
  echo $i . " ";
}
?>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez for ($i = 1; $i <= 5; $i++) { echo $i; }')
                ],
                8 => [
                    'title' => $getTranslated('title', 'Fonctions PHP'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Créez une fonction "calculerCarre" qui prend un nombre en paramètre et retourne son carré. Appelez la fonction avec 5 et affichez le résultat.'),
                    'description' => $getTranslated('description', 'Les fonctions PHP organisent le code réutilisable. function nomFonction($param) { return valeur; } définit une fonction. return retourne une valeur. Les fonctions peuvent avoir des paramètres par défaut. C\'est essentiel pour éviter la duplication de code.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fonctions PHP</title>
</head>
<body>

<?php


$resultat = calculerCarre(5);
echo $resultat;
?>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fonctions PHP</title>
</head>
<body>

<?php
function calculerCarre($nombre) {
  return $nombre * $nombre;
}

$resultat = calculerCarre(5);
echo $resultat;
?>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Créez function calculerCarre($nombre) { return $nombre * $nombre; } puis appelez calculerCarre(5).')
                ],
                9 => [
                    'title' => $getTranslated('title', 'Tableaux simples PHP'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Créez un tableau simple avec 3 fruits, puis affichez chaque fruit avec une boucle foreach.'),
                    'description' => $getTranslated('description', 'Les tableaux PHP simples sont indexés numériquement. array() ou [] crée un tableau. foreach parcourt tous les éléments. count() retourne le nombre d\'éléments. Les tableaux sont fondamentaux en PHP.'),
                    'startCode' => '<html>
<body>

<?php


?>

</body>
</html>',
                    'solution' => '<html>
<body>

<?php
$fruits = array("Pomme", "Banane", "Orange");

foreach ($fruits as $fruit) {
  echo $fruit . "<br>";
}
?>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Créez $fruits = array("Pomme", "Banane", "Orange"); puis foreach ($fruits as $fruit) { echo $fruit . "<br>"; }')
                ],
                10 => [
                    'title' => $getTranslated('title', 'Formulaires PHP (GET/POST)'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Créez un formulaire HTML avec un champ texte "nom" et un bouton submit. Traitez le formulaire en PHP : si le formulaire est soumis (POST), affichez "Bonjour [nom]".'),
                    'description' => $getTranslated('description', 'Les formulaires PHP collectent des données utilisateur. $_POST contient les données POST, $_GET contient les données GET. isset() vérifie si une variable existe. Les formulaires sont essentiels pour l\'interaction utilisateur.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire PHP</title>
</head>
<body>

<form method="POST">
  <input type="text" name="nom" placeholder="Votre nom">
  <button type="submit">Envoyer</button>
</form>

<?php


?>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire PHP</title>
</head>
<body>

<form method="POST">
  <input type="text" name="nom" placeholder="Votre nom">
  <button type="submit">Envoyer</button>
</form>

<?php
if (isset($_POST["nom"])) {
  echo "Bonjour " . $_POST["nom"];
}
?>

</body>
</html>',
                    'hint' => 'Utilisez if (isset($_POST["nom"])) { echo "Bonjour " . $_POST["nom"]; } pour traiter le formulaire.'
                ],
                11 => [
                    'title' => 'Tableaux PHP',
                    'difficulty' => 'Difficile',
                    'points' => 25,
                    'instruction' => 'Créez un tableau associatif de fruits avec leurs prix, puis affichez chaque fruit avec son prix en utilisant foreach.',
                    'description' => 'Les tableaux PHP peuvent être indexés numériquement ou associatifs (clé-valeur). array() ou [] crée un tableau. foreach parcourt les éléments. Les tableaux associatifs utilisent des clés personnalisées. C\'est fondamental pour manipuler des données structurées.',
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableaux PHP</title>
</head>
<body>

<?php




?>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableaux PHP</title>
</head>
<body>

<?php
$fruits = array(
  "Pomme" => 2.50,
  "Banane" => 1.80,
  "Orange" => 3.00
);

foreach ($fruits as $fruit => $prix) {
  echo $fruit . " : " . $prix . "€<br>";
}
?>

</body>
</html>',
                    'hint' => 'Créez $fruits = array("Pomme" => 2.50, "Banane" => 1.80, "Orange" => 3.00); puis foreach ($fruits as $fruit => $prix) { echo $fruit . " : " . $prix . "€<br>"; }'
                ],
                12 => [
                    'title' => 'POO en PHP',
                    'difficulty' => 'Difficile',
                    'points' => 30,
                    'instruction' => 'Créez une classe "Personne" avec les propriétés privées $nom et $age, un constructeur, des méthodes getter (getNom, getAge) et setter (setNom, setAge), puis instanciez un objet et affichez les informations.',
                    'description' => 'La Programmation Orientée Objet (POO) organise le code en classes et objets. class définit une classe, private protège les propriétés, __construct() est le constructeur, $this référence l\'instance. Les getters/setters contrôlent l\'accès aux propriétés. C\'est essentiel pour le code maintenable.',
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>POO PHP</title>
</head>
<body>

<?php




?>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>POO PHP</title>
</head>
<body>

<?php
class Personne {
  private $nom;
  private $age;
  
  public function __construct($nom, $age) {
    $this->nom = $nom;
    $this->age = $age;
  }
  
  public function getNom() {
    return $this->nom;
  }
  
  public function getAge() {
    return $this->age;
  }
  
  public function setNom($nom) {
    $this->nom = $nom;
  }
  
  public function setAge($age) {
    $this->age = $age;
  }
}

$personne = new Personne("Jean", 25);
echo "Nom : " . $personne->getNom() . "<br>";
echo "Age : " . $personne->getAge();
?>

</body>
</html>',
                    'hint' => 'Créez class Personne { private $nom, $age; public function __construct($nom, $age) { $this->nom = $nom; $this->age = $age; } public function getNom() { return $this->nom; } } puis $personne = new Personne("Jean", 25);'
                ],
                13 => [
                    'title' => 'Les sessions PHP',
                    'difficulty' => 'Difficile',
                    'points' => 28,
                    'instruction' => 'Démarrez une session PHP, stockez une variable de session "utilisateur" avec la valeur "admin", puis affichez-la. N\'oubliez pas session_start() au début.',
                    'description' => 'Les sessions PHP permettent de stocker des données côté serveur entre les requêtes. session_start() démarre une session, $_SESSION stocke les données. Les sessions sont essentielles pour l\'authentification et le suivi des utilisateurs. Elles utilisent des cookies de session.',
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sessions PHP</title>
</head>
<body>

<?php



?>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sessions PHP</title>
</head>
<body>

<?php
session_start();

$_SESSION["utilisateur"] = "admin";

echo "Utilisateur : " . $_SESSION["utilisateur"];
?>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez session_start(); au début, puis $_SESSION["utilisateur"] = "admin"; pour stocker, et $_SESSION["utilisateur"] pour récupérer.')
                ],
                14 => [
                    'title' => $getTranslated('title', 'Traitement des fichiers PHP'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Créez un fichier "test.txt" avec le contenu "Hello PHP", puis lisez et affichez son contenu. Utilisez file_put_contents() pour écrire et file_get_contents() pour lire.'),
                    'description' => $getTranslated('description', 'PHP peut lire et écrire des fichiers. file_put_contents() écrit dans un fichier, file_get_contents() lit un fichier. fopen(), fwrite(), fread(), fclose() offrent plus de contrôle. file_exists() vérifie l\'existence. C\'est essentiel pour la gestion de fichiers.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Traitement fichiers PHP</title>
</head>
<body>

<?php


?>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Traitement fichiers PHP</title>
</head>
<body>

<?php
file_put_contents("test.txt", "Hello PHP");

$contenu = file_get_contents("test.txt");
echo $contenu;
?>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez file_put_contents("test.txt", "Hello PHP"); pour écrire et $contenu = file_get_contents("test.txt"); pour lire.')
                ],
                15 => [
                    'title' => $getTranslated('title', 'Exceptions et gestion d\'erreurs'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Créez une fonction qui divise deux nombres. Utilisez try/catch pour gérer l\'exception si le diviseur est 0. Affichez un message d\'erreur approprié.'),
                    'description' => $getTranslated('description', 'Les exceptions PHP gèrent les erreurs de manière élégante. try exécute du code, catch capture les exceptions. throw lance une exception. Exception est la classe de base. C\'est la méthode moderne pour gérer les erreurs.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Exceptions PHP</title>
</head>
<body>

<?php


?>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Exceptions PHP</title>
</head>
<body>

<?php
function diviser($a, $b) {
  if ($b == 0) {
    throw new Exception("Division par zéro impossible");
  }
  return $a / $b;
}

try {
  echo diviser(10, 2) . "<br>";
  echo diviser(10, 0);
} catch (Exception $e) {
  echo "Erreur : " . $e->getMessage();
}
?>

</body>
</html>',
                    'hint' => 'Utilisez if ($b == 0) { throw new Exception("..."); } dans la fonction, et try { ... } catch (Exception $e) { ... } pour gérer l\'exception.'
                ],
            ],
            'bootstrap' => [
                1 => [
                    'title' => 'Grille Bootstrap',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Créez une grille Bootstrap responsive avec 3 colonnes égales en utilisant le système de grille Bootstrap.',
                    'description' => 'Bootstrap utilise un système de grille à 12 colonnes. container contient la grille, row crée une ligne, et col crée des colonnes égales. Le système est responsive par défaut. C\'est la base de tous les layouts Bootstrap.',
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grille Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <div class="row">
    <div class="">Colonne 1</div>
    <div class="">Colonne 2</div>
    <div class="">Colonne 3</div>
  </div>
</div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grille Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <div class="row">
    <div class="col">Colonne 1</div>
    <div class="col">Colonne 2</div>
    <div class="col">Colonne 3</div>
  </div>
</div>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Ajoutez la classe "col" à chaque div pour créer des colonnes égales.')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Bouton Bootstrap'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Créez un bouton bleu primaire avec Bootstrap.'),
                    'description' => $getTranslated('description', 'Bootstrap fournit des classes de boutons prédéfinies. btn crée un bouton, btn-primary le rend bleu primaire.'),
                    'startCode' => '<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <button class="">Cliquez-moi</button>
</div>

</body>
</html>',
                    'solution' => '<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <button class="btn btn-primary">Cliquez-moi</button>
</div>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Ajoutez les classes "btn btn-primary" au bouton.')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Typographie Bootstrap'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Utilisez les classes Bootstrap pour créer un titre h1 avec la classe "display-1", un paragraphe avec "lead", et un texte en gras avec "fw-bold".'),
                    'description' => $getTranslated('description', 'Bootstrap fournit des classes typographiques prédéfinies. display-1 à display-6 créent de grands titres, lead met en évidence un paragraphe, fw-bold met en gras, text-muted assombrit le texte. C\'est la base de la typographie Bootstrap.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Typographie Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <h1 class="">Titre principal</h1>
  <p class="">Paragraphe important</p>
  <p class="">Texte en gras</p>
</div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Typographie Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <h1 class="display-1">Titre principal</h1>
  <p class="lead">Paragraphe important</p>
  <p class="fw-bold">Texte en gras</p>
</div>

</body>
</html>',
                    'hint' => 'Utilisez display-1 pour le h1, lead pour le paragraphe important, et fw-bold pour le texte en gras.'
                ],
                4 => [
                    'title' => 'Couleurs Bootstrap',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Créez trois divs avec les classes de couleur de texte Bootstrap : text-primary (bleu), text-success (vert), et text-danger (rouge).',
                    'description' => 'Bootstrap fournit des classes de couleurs contextuelles. text-primary (bleu), text-success (vert), text-danger (rouge), text-warning (jaune), text-info (cyan), text-dark (noir), text-light (blanc). bg-* crée des couleurs de fond. C\'est la palette de couleurs Bootstrap.',
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Couleurs Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <p class="">Texte bleu</p>
  <p class="">Texte vert</p>
  <p class="">Texte rouge</p>
</div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Couleurs Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <p class="text-primary">Texte bleu</p>
  <p class="text-success">Texte vert</p>
  <p class="text-danger">Texte rouge</p>
</div>

</body>
</html>',
                    'hint' => 'Utilisez text-primary pour le bleu, text-success pour le vert, et text-danger pour le rouge.'
                ],
                5 => [
                    'title' => 'Alertes Bootstrap',
                    'difficulty' => 'Facile',
                    'points' => 12,
                    'instruction' => 'Créez trois alertes Bootstrap : une alerte de succès (success), une d\'information (info), et une d\'avertissement (warning). Utilisez la classe "alert".',
                    'description' => 'Les alertes Bootstrap affichent des messages contextuels. alert est la classe de base, alert-success (vert), alert-info (bleu), alert-warning (jaune), alert-danger (rouge). alert-dismissible permet de fermer l\'alerte. C\'est essentiel pour les notifications utilisateur.',
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alertes Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  
</div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alertes Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <div class="alert alert-success">Opération réussie !</div>
  <div class="alert alert-info">Information importante</div>
  <div class="alert alert-warning">Attention requise</div>
</div>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez class="alert alert-success" et ajoutez le texte "Opération réussie !".')
                ],
                6 => [
                    'title' => $getTranslated('title', 'Card Bootstrap'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 15,
                    'instruction' => $getTranslated('instruction', 'Créez une carte Bootstrap (card) avec un titre (card-title) et un texte (card-text).'),
                    'description' => $getTranslated('description', 'Les cartes Bootstrap sont des conteneurs flexibles et extensibles. card est le conteneur principal, card-body pour le contenu, card-title pour le titre, card-text pour le texte. C\'est un composant polyvalent pour afficher du contenu.'),
                    'startCode' => '<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <div class="card" style="width: 18rem;">
    <div class="">
      <h5 class="">Titre de la carte</h5>
      <p class="card-text">Contenu de la carte.</p>
    </div>
  </div>
</div>

</body>
</html>',
                    'solution' => '<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <div class="card" style="width: 18rem;">
    <div class="card-body">
      <h5 class="card-title">Titre de la carte</h5>
      <p class="card-text">Contenu de la carte.</p>
    </div>
  </div>
</div>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez class="card" pour le conteneur, class="card-body" pour le corps, class="card-title" pour le titre, et class="card-text" pour le texte.')
                ],
                7 => [
                    'title' => $getTranslated('title', 'Navbar Bootstrap'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Créez une navbar Bootstrap simple avec un titre de marque et un lien "Accueil".'),
                    'description' => $getTranslated('description', 'La navbar Bootstrap est un composant de navigation réactif. navbar est la classe de base, navbar-brand pour le titre, nav-item et nav-link pour les liens. C\'est essentiel pour la navigation des sites web.'),
                    'startCode' => '<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="" href="#">MonSite</a>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="" href="#">Accueil</a>
      </li>
    </ul>
  </div>
</nav>

</body>
</html>',
                    'solution' => '<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="#">MonSite</a>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="#">Accueil</a>
      </li>
    </ul>
  </div>
</nav>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez navbar navbar-expand-lg navbar-light bg-light pour la nav, navbar-brand pour le titre, navbar-nav pour la liste, nav-item pour l\'élément, et nav-link pour le lien.')
                ],
                8 => [
                    'title' => $getTranslated('title', 'Formulaires Bootstrap'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Créez un formulaire Bootstrap avec un champ texte (nom) et un bouton submit. Utilisez les classes form-label et form-control.'),
                    'description' => $getTranslated('description', 'Bootstrap stylise les formulaires pour une meilleure apparence. form-label pour les labels, form-control pour les inputs. mb-3 ajoute une marge en bas. C\'est essentiel pour des formulaires modernes.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaires Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <form>
    
  </form>
</div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaires Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <form>
    <div class="mb-3">
      <label for="nom" class="form-label">Nom</label>
      <input type="text" class="form-control" id="nom" name="nom">
    </div>
    <button type="submit" class="btn btn-primary">Envoyer</button>
  </form>
</div>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez <div class="mb-3">, <label class="form-label">, <input class="form-control">, et <button class="btn btn-primary">.')
                ],
                9 => [
                    'title' => $getTranslated('title', 'Badges et boutons groupés'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 15,
                    'instruction' => $getTranslated('instruction', 'Créez un badge "Nouveau" avec la classe badge bg-primary, et un groupe de boutons avec btn-group contenant 3 boutons.'),
                    'description' => $getTranslated('description', 'Les badges Bootstrap affichent de petites étiquettes. badge bg-primary|success|danger crée des badges colorés. btn-group groupe des boutons horizontalement, btn-group-vertical verticalement. C\'est utile pour les tags et les groupes d\'actions.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badges et boutons groupés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <h1>Article <span class=""></span></h1>
  
  <div class="">
    <button class="btn btn-outline-primary">Gauche</button>
    <button class="btn btn-outline-primary">Centre</button>
    <button class="btn btn-outline-primary">Droite</button>
  </div>
</div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badges et boutons groupés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <h1>Article <span class="badge bg-primary">Nouveau</span></h1>
  
  <div class="btn-group">
    <button class="btn btn-outline-primary">Gauche</button>
    <button class="btn btn-outline-primary">Centre</button>
    <button class="btn btn-outline-primary">Droite</button>
  </div>
</div>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez <span class="badge bg-primary"> pour le badge et <div class="btn-group"> pour grouper les boutons.')
                ],
                10 => [
                    'title' => $getTranslated('title', 'Listes et groupes Bootstrap'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 15,
                    'instruction' => $getTranslated('instruction', 'Créez une liste groupée Bootstrap (list-group) avec 3 éléments (list-group-item). Ajoutez un élément actif (active) et un élément désactivé (disabled).'),
                    'description' => $getTranslated('description', 'Les listes groupées Bootstrap affichent des listes stylisées. list-group contient la liste, list-group-item chaque élément. active met en évidence, disabled désactive. list-group-flush enlève les bordures. C\'est utile pour les menus et les listes de contenu.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listes Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <ul class="">
    <li class="">Élément 1</li>
    <li class="">Élément 2</li>
    <li class="">Élément 3</li>
  </ul>
</div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listes Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <ul class="list-group">
    <li class="list-group-item active">Élément 1</li>
    <li class="list-group-item">Élément 2</li>
    <li class="list-group-item disabled">Élément 3</li>
  </ul>
</div>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez class="list-group" pour la liste, class="list-group-item" pour chaque élément. Ajoutez active et disabled aux éléments appropriés.')
                ],
                11 => [
                    'title' => $getTranslated('title', 'Responsive Bootstrap'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Créez une div qui occupe 12 colonnes sur mobile (col-12), 6 colonnes sur tablette (col-md-6) et 4 colonnes sur desktop (col-lg-4).'),
                    'description' => $getTranslated('description', 'Le système de grille Bootstrap est responsive. col-12 prend toute la largeur sur mobile, col-md-6 prend la moitié sur tablette, col-lg-4 prend un tiers sur desktop. C\'est essentiel pour un design adaptatif.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <div class="row">
    <div class="">Colonne 1</div>
    <div class="">Colonne 2</div>
    <div class="">Colonne 3</div>
  </div>
</div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <div class="row">
    <div class="col-12 col-md-6 col-lg-4">Colonne 1</div>
    <div class="col-12 col-md-6 col-lg-4">Colonne 2</div>
    <div class="col-12 col-md-6 col-lg-4">Colonne 3</div>
  </div>
</div>

</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez col-12 pour mobile, col-md-6 pour tablette, et col-lg-4 pour desktop.')
                ],
                12 => [
                    'title' => $getTranslated('title', 'Customisation Bootstrap'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 25,
                    'instruction' => $getTranslated('instruction', 'Changez la couleur primaire de Bootstrap en vert en utilisant les variables CSS de Bootstrap (via :root ou un sélecteur).'),
                    'description' => $getTranslated('description', 'Bootstrap 5 utilise des variables CSS pour la customisation. Vous pouvez redéfinir ces variables (ex: --bs-primary) dans :root ou un sélecteur pour changer les couleurs, les espacements, etc. C\'est la méthode moderne pour personnaliser Bootstrap.'),
                    'startCode' => '/* custom.scss */
@import "bootstrap/scss/bootstrap";

/* Variables personnalisées */



/* Styles personnalisés */
.custom-btn {
  
}',
                    'solution' => '/* custom.scss */
@import "bootstrap/scss/bootstrap";

/* Variables personnalisées */
$primary: #06b6d4;
$font-family-base: "Arial", sans-serif;

/* Styles personnalisés */
.custom-btn {
  background-color: $primary;
  border-radius: 20px;
}',
                    'hint' => $getTranslated('hint', 'Dans :root, définissez --bs-primary: green; pour changer la couleur primaire.')
                ],
                13 => [
                    'title' => $getTranslated('title', 'Bootstrap avec JavaScript'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Créez un bouton qui ouvre un modal Bootstrap. Le modal doit avoir un titre "Titre du Modal" et un corps "Contenu du modal".'),
                    'description' => $getTranslated('description', 'Bootstrap utilise JavaScript pour ses composants interactifs comme les modals, carousels, dropdowns. data-bs-toggle="modal" et data-bs-target="#id" activent le modal. C\'est essentiel pour les interfaces dynamiques.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modale Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <button class="btn btn-primary">Ouvrir la modale</button>
  
  <!-- Modale -->
  <div class="modal" id="maModale">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Titre de la modale</h5>
        </div>
        <div class="modal-body">
          <p>Contenu de la modale</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary">Fermer</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modale Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#maModale">Ouvrir la modale</button>
  
  <!-- Modale -->
  <div class="modal" id="maModale" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Titre de la modale</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Contenu de la modale</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>',
                    'hint' => $getTranslated('hint', 'Ajoutez data-bs-toggle="modal" data-bs-target="#myModal" au bouton, et ajoutez le titre et le contenu au modal.')
                ],
                14 => [
                    'title' => $getTranslated('title', 'Carousel et composants avancés'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Créez un carousel Bootstrap avec 3 slides. Utilisez carousel, carousel-inner, carousel-item, et les contrôles (carousel-control-prev, carousel-control-next). Ajoutez aussi les indicateurs (carousel-indicators).'),
                    'description' => $getTranslated('description', 'Le carousel Bootstrap crée un diaporama d\'images. carousel contient le carousel, carousel-inner les slides, carousel-item chaque slide. carousel-control-prev/next ajoutent les flèches, carousel-indicators les points. data-bs-ride="carousel" active l\'auto-play. C\'est un composant avancé très utilisé.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carousel Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <div id="monCarousel" class="carousel slide" data-bs-ride="carousel">
    
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carousel Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <div id="monCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#monCarousel" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#monCarousel" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#monCarousel" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="d-block w-100 bg-primary" style="height: 300px;">Slide 1</div>
      </div>
      <div class="carousel-item">
        <div class="d-block w-100 bg-success" style="height: 300px;">Slide 2</div>
      </div>
      <div class="carousel-item">
        <div class="d-block w-100 bg-danger" style="height: 300px;">Slide 3</div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#monCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#monCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>',
                    'hint' => $getTranslated('hint', 'Utilisez carousel-indicators pour les points, carousel-inner pour les slides, carousel-item pour chaque slide, et carousel-control-prev/next pour les flèches.')
                ],
                15 => [
                    'title' => $getTranslated('title', 'CSS Architecture (BEM, SMACSS)'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Créez une structure CSS suivant la méthodologie BEM (Block Element Modifier). Créez un bloc "card", un élément "card__title", et un modificateur "card--highlighted".'),
                    'description' => $getTranslated('description', 'BEM (Block Element Modifier) est une méthodologie de nommage CSS. Block = composant indépendant, Element = partie du bloc (__element), Modifier = variation (--modifier). SMACSS organise le CSS en catégories (Base, Layout, Module, State, Theme). C\'est essentiel pour maintenir du CSS à grande échelle.'),
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grille avancée Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <div class="row">
    <div class="">Colonne 1</div>
    <div class="">Colonne 2</div>
    <div class="">Colonne 3</div>
  </div>
</div>

</body>
</html>',
                    'solution' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grille avancée Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <div class="row">
    <div class="col-md-2 offset-md-2 bg-primary p-3">Colonne 1</div>
    <div class="col-md-2 bg-success p-3">Colonne 2</div>
    <div class="col-md-2 bg-danger p-3">Colonne 3</div>
  </div>
</div>

</body>
</html>',
                    'hint' => 'Utilisez col-md-2 offset-md-2 pour la première colonne (décalée de 2), et col-md-2 pour les autres colonnes.'
                ],
            ],
            'git' => [
                1 => [
                    'title' => $getTranslated('title', 'Initialiser un dépôt Git'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Quelle commande initialise un nouveau dépôt Git ?'),
                    'description' => $getTranslated('description', 'Tapez la commande Git pour créer un nouveau dépôt.'),
                    'startCode' => 'git ',
                    'solution' => 'git init',
                    'hint' => $getTranslated('hint', 'La commande commence par "git" et utilise "init".')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Ajouter des fichiers'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Quelle commande ajoute tous les fichiers au staging ?'),
                    'description' => $getTranslated('description', 'Utilisez la commande pour ajouter tous les fichiers modifiés.'),
                    'startCode' => 'git ',
                    'solution' => 'git add .',
                    'hint' => $getTranslated('hint', 'Utilisez "git add" suivi d\'un point pour tout ajouter.')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Créer un commit'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 15,
                    'instruction' => $getTranslated('instruction', 'Créez un commit avec le message "Premier commit".'),
                    'description' => $getTranslated('description', 'Utilisez la commande commit avec l\'option -m.'),
                    'startCode' => 'git ',
                    'solution' => 'git commit -m "Premier commit"',
                    'hint' => $getTranslated('hint', 'Utilisez git commit -m suivi du message entre guillemets.')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Voir l\'historique Git'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Quelle commande affiche l\'historique des commits Git avec les messages et les auteurs ?'),
                    'description' => $getTranslated('description', 'git log affiche l\'historique des commits. git log --oneline affiche une version compacte, git log --graph affiche un graphique, git log --all affiche toutes les branches. C\'est essentiel pour comprendre l\'historique du projet.'),
                    'startCode' => 'git ',
                    'solution' => 'git log',
                    'hint' => $getTranslated('hint', 'La commande est git log. Utilisez git log --oneline pour une version compacte.')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Vérifier le statut Git'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Quelle commande affiche le statut actuel du dépôt Git (fichiers modifiés, ajoutés, etc.) ?'),
                    'description' => $getTranslated('description', 'git status affiche l\'état actuel du dépôt. Il montre les fichiers modifiés, ajoutés au staging, et non suivis. C\'est la commande la plus utilisée pour voir ce qui a changé. git status -s affiche une version courte.'),
                    'startCode' => 'git ',
                    'solution' => 'git status',
                    'hint' => $getTranslated('hint', 'La commande est git status. Utilisez git status -s pour une version courte.')
                ],
                6 => [
                    'title' => $getTranslated('title', 'Créer une branche'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 15,
                    'instruction' => $getTranslated('instruction', 'Créez une nouvelle branche appelée "develop" et basculez dessus en une seule commande.'),
                    'description' => $getTranslated('description', 'Les branches permettent de travailler sur des fonctionnalités isolées. git checkout -b crée et bascule sur une nouvelle branche. Les branches permettent de développer en parallèle sans affecter la branche principale. C\'est essentiel pour le workflow Git.'),
                    'startCode' => 'git ',
                    'solution' => 'git checkout -b develop',
                    'hint' => $getTranslated('hint', 'Utilisez git checkout -b develop. -b crée la branche et checkout y bascule.')
                ],
                7 => [
                    'title' => $getTranslated('title', 'Changer de branche'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 15,
                    'instruction' => $getTranslated('instruction', 'Basculez sur la branche "develop" en utilisant la commande appropriée.'),
                    'description' => $getTranslated('description', 'git checkout bascule sur une branche existante. git switch (nouvelle syntaxe) fait la même chose. git checkout -b crée et bascule. C\'est essentiel pour travailler sur différentes fonctionnalités en parallèle.'),
                    'startCode' => 'git ',
                    'solution' => 'git checkout develop',
                    'hint' => $getTranslated('hint', 'Utilisez git checkout develop ou git switch develop pour basculer sur la branche develop.')
                ],
                8 => [
                    'title' => $getTranslated('title', 'Cloner un dépôt distant'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Clonez le dépôt distant "https://github.com/user/repo.git" dans un dossier local.'),
                    'description' => $getTranslated('description', 'git clone copie un dépôt distant localement. git clone URL crée un dossier avec le nom du dépôt, git clone URL nom crée un dossier avec un nom personnalisé. C\'est la première étape pour travailler sur un projet existant.'),
                    'startCode' => 'git ',
                    'solution' => 'git clone https://github.com/user/repo.git',
                    'hint' => $getTranslated('hint', 'Utilisez git clone suivi de l\'URL du dépôt. git clone https://github.com/user/repo.git')
                ],
                9 => [
                    'title' => $getTranslated('title', 'Pousser vers un dépôt distant'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Poussez la branche "main" vers le dépôt distant "origin".'),
                    'description' => $getTranslated('description', 'git push envoie les commits locaux vers le dépôt distant. git push origin main pousse la branche main vers origin. git push -u origin main configure le tracking. C\'est essentiel pour partager le code.'),
                    'startCode' => 'git ',
                    'solution' => 'git push origin main',
                    'hint' => $getTranslated('hint', 'Utilisez git push origin main pour pousser la branche main vers origin. git push -u origin main configure le tracking.')
                ],
                10 => [
                    'title' => $getTranslated('title', 'Récupérer les changements (pull)'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Récupérez et fusionnez les changements du dépôt distant "origin" dans la branche actuelle.'),
                    'description' => $getTranslated('description', 'git pull récupère et fusionne les changements du dépôt distant. C\'est équivalent à git fetch suivi de git merge. git pull origin main récupère depuis origin/main. C\'est essentiel pour synchroniser avec le dépôt distant.'),
                    'startCode' => 'git ',
                    'solution' => 'git pull origin main',
                    'hint' => $getTranslated('hint', 'Utilisez git pull origin main pour récupérer et fusionner les changements. git pull est équivalent à git fetch + git merge.')
                ],
                11 => [
                    'title' => $getTranslated('title', 'Fusionner des branches'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Fusionnez la branche "feature" dans la branche actuelle (main) en créant un merge commit.'),
                    'description' => $getTranslated('description', 'git merge combine les changements d\'une branche dans une autre. Il faut être sur la branche de destination. Git crée un merge commit si nécessaire. Les conflits doivent être résolus manuellement. C\'est la méthode standard pour intégrer des fonctionnalités.'),
                    'startCode' => 'git ',
                    'solution' => 'git merge feature',
                    'hint' => $getTranslated('hint', 'Assurez-vous d\'être sur main, puis utilisez git merge feature pour fusionner.')
                ],
                12 => [
                    'title' => $getTranslated('title', 'Résoudre les conflits'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 25,
                    'instruction' => $getTranslated('instruction', 'Après un merge conflictuel, résolvez le conflit dans le fichier "fichier.txt". Les marqueurs de conflit sont <<<<<<, ======, >>>>>>. Supprimez les marqueurs et gardez la version correcte, puis ajoutez le fichier et complétez le merge.'),
                    'description' => $getTranslated('description', 'Les conflits surviennent quand Git ne peut pas fusionner automatiquement. Les marqueurs <<<<<<, ======, >>>>>> indiquent les zones conflictuelles. Il faut éditer manuellement, supprimer les marqueurs, garder le code correct, puis git add et git commit pour finaliser.'),
                    'startCode' => '/* Après git merge feature, fichier.txt contient : */
<<<<<<< HEAD
Ligne dans main
=======
Ligne dans feature
>>>>>>> feature

/* Résolvez le conflit et complétez le merge */
git ',
                    'solution' => '/* Résolution : garder la version de feature */
Ligne dans feature

/* Commandes Git */
git add fichier.txt
git commit -m "Résolution du conflit"',
                    'hint' => $getTranslated('hint', 'Supprimez les marqueurs <<<<<<, ======, >>>>>>, gardez le code correct, puis git add fichier.txt et git commit.')
                ],
                13 => [
                    'title' => $getTranslated('title', 'Git rebase'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Effectuez un rebase de la branche "feature" sur "main" pour réécrire l\'historique et créer un historique linéaire.'),
                    'description' => $getTranslated('description', 'git rebase réapplique les commits d\'une branche sur une autre, créant un historique linéaire. C\'est une alternative à merge. git rebase main réapplique les commits de la branche actuelle sur main. Attention : ne jamais rebaser une branche partagée.'),
                    'startCode' => '/* Vous êtes sur la branche feature */
git ',
                    'solution' => 'git rebase main',
                    'hint' => $getTranslated('hint', 'Sur la branche feature, utilisez git rebase main pour réappliquer les commits sur main.')
                ],
                14 => [
                    'title' => $getTranslated('title', 'Git stash (mise de côté)'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 25,
                    'instruction' => $getTranslated('instruction', 'Mettez de côté les modifications en cours avec git stash, puis récupérez-les avec git stash pop.'),
                    'description' => $getTranslated('description', 'git stash sauvegarde temporairement les modifications non commitées. git stash sauvegarde, git stash list liste les stashes, git stash pop récupère et supprime le dernier stash, git stash apply récupère sans supprimer. C\'est utile pour changer de branche sans committer.'),
                    'startCode' => '/* Vous avez des modifications non commitées */
git 

/* Changez de branche, travaillez, puis revenez */

git ',
                    'solution' => '/* Mettre de côté */
git stash

/* Après avoir changé de branche et travaillé, récupérer */
git stash pop',
                    'hint' => $getTranslated('hint', 'Utilisez git stash pour sauvegarder, puis git stash pop pour récupérer. git stash list affiche tous les stashes.')
                ],
                15 => [
                    'title' => $getTranslated('title', 'Annuler des changements Git'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Annulez les modifications non commitées dans le fichier "fichier.txt" avec git checkout ou git restore.'),
                    'description' => $getTranslated('description', 'git restore (nouveau) ou git checkout -- (ancien) annule les modifications non commitées. git reset --hard annule tout, git reset --soft annule le commit mais garde les modifications. Attention : ces commandes sont destructives. C\'est utile pour corriger des erreurs.'),
                    'startCode' => '/* Vous avez modifié fichier.txt mais voulez annuler */
git ',
                    'solution' => 'git restore fichier.txt',
                    'hint' => $getTranslated('hint', 'Utilisez git restore fichier.txt (nouveau) ou git checkout -- fichier.txt (ancien) pour annuler les modifications. git restore . annule tout.')
                ],
            ],
            'wordpress' => [
                1 => [
                    'title' => $getTranslated('title', 'The Loop WordPress'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Complétez la boucle WordPress (The Loop) pour afficher les articles avec leur titre et contenu.'),
                    'description' => $getTranslated('description', 'The Loop est le cœur de WordPress. have_posts() vérifie s\'il y a des articles, the_post() charge les données de l\'article courant, the_title() et the_content() affichent le titre et le contenu. C\'est la base de tous les templates WordPress.'),
                    'startCode' => '<?php
if ( ) {
  while ( ) {
    
    the_title();
    the_content();
  }
}
?>',
                    'solution' => '<?php
if (have_posts()) {
  while (have_posts()) {
    the_post();
    the_title();
    the_content();
  }
}
?>',
                    'hint' => $getTranslated('hint', 'Utilisez have_posts() dans le if et le while, puis the_post() dans la boucle pour charger les données de l\'article.')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Afficher le titre'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Affichez le titre de l\'article dans un élément h1 en utilisant la fonction WordPress appropriée.'),
                    'description' => $getTranslated('description', 'WordPress fournit des fonctions template pour afficher les données. the_title() affiche le titre de l\'article courant. Il existe aussi get_the_title() qui retourne le titre sans l\'afficher. Ces fonctions doivent être utilisées dans The Loop.'),
                    'startCode' => '<h1><?php  ?></h1>',
                    'solution' => '<h1><?php the_title(); ?></h1>',
                    'hint' => $getTranslated('hint', 'Utilisez the_title() pour afficher le titre. Cette fonction doit être appelée dans The Loop.')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Afficher le contenu'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Affichez le contenu de l\'article dans un élément <div> en utilisant la fonction WordPress appropriée.'),
                    'description' => $getTranslated('description', 'WordPress fournit the_content() pour afficher le contenu de l\'article. Il existe aussi get_the_content() qui retourne le contenu sans l\'afficher. Ces fonctions doivent être utilisées dans The Loop. C\'est la base de l\'affichage de contenu.'),
                    'startCode' => '<div><?php  ?></div>',
                    'solution' => '<div><?php the_content(); ?></div>',
                    'hint' => $getTranslated('hint', 'Utilisez the_content() pour afficher le contenu. Cette fonction doit être appelée dans The Loop.')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Afficher la date'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Affichez la date de publication de l\'article en utilisant the_date() ou get_the_date().'),
                    'description' => $getTranslated('description', 'WordPress fournit the_date() pour afficher la date de publication. get_the_date() retourne la date sans l\'afficher. On peut formater la date avec des paramètres. C\'est essentiel pour afficher les métadonnées des articles.'),
                    'startCode' => '<p>Publié le : <?php  ?></p>',
                    'solution' => '<p>Publié le : <?php the_date(); ?></p>',
                    'hint' => $getTranslated('hint', 'Utilisez the_date() pour afficher la date. Utilisez the_date(\'d/m/Y\') pour formater la date.')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Afficher l\'auteur'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Affichez le nom de l\'auteur de l\'article en utilisant the_author() ou get_the_author().'),
                    'description' => $getTranslated('description', 'WordPress fournit the_author() pour afficher le nom de l\'auteur. get_the_author() retourne le nom sans l\'afficher. the_author_meta() permet d\'accéder à d\'autres informations de l\'auteur. C\'est essentiel pour afficher les crédits.'),
                    'startCode' => '<p>Auteur : <?php  ?></p>',
                    'solution' => '<p>Auteur : <?php the_author(); ?></p>',
                    'hint' => $getTranslated('hint', 'Utilisez the_author() pour afficher le nom de l\'auteur. Cette fonction doit être appelée dans The Loop.')
                ],
                6 => [
                    'title' => $getTranslated('title', 'Image à la une'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 15,
                    'instruction' => $getTranslated('instruction', 'Affichez l\'image à la une (featured image) de l\'article avec une taille personnalisée "medium".'),
                    'description' => $getTranslated('description', 'Les images à la une enrichissent les articles. has_post_thumbnail() vérifie si une image existe, the_post_thumbnail() l\'affiche. On peut spécifier une taille (\'thumbnail\', \'medium\', \'large\', \'full\'). C\'est essentiel pour un design professionnel.'),
                    'startCode' => '<?php
if (has_post_thumbnail()) {
  
}
?>',
                    'solution' => '<?php
if (has_post_thumbnail()) {
  the_post_thumbnail(\'medium\');
}
?>',
                    'hint' => $getTranslated('hint', 'Utilisez the_post_thumbnail(\'medium\') pour afficher l\'image avec la taille medium.')
                ],
                7 => [
                    'title' => $getTranslated('title', 'Menu WordPress'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Affichez le menu WordPress enregistré avec l\'emplacement "primary" en utilisant wp_nav_menu().'),
                    'description' => $getTranslated('description', 'Les menus WordPress sont gérés via l\'interface admin. wp_nav_menu() affiche un menu. theme_location correspond à l\'emplacement enregistré avec register_nav_menus(). Les menus sont essentiels pour la navigation du site.'),
                    'startCode' => '<?php
wp_nav_menu(array(
  \'theme_location\' => \'\'
));
?>',
                    'solution' => '<?php
wp_nav_menu(array(
  \'theme_location\' => \'primary\'
));
?>',
                    'hint' => $getTranslated('hint', 'Utilisez \'primary\' comme theme_location. Cet emplacement doit être enregistré dans functions.php avec register_nav_menus().')
                ],
                8 => [
                    'title' => $getTranslated('title', 'Widgets WordPress'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Enregistrez une zone de widgets (sidebar) avec register_sidebar() dans functions.php, puis affichez-la dans le template avec dynamic_sidebar().'),
                    'description' => $getTranslated('description', 'Les widgets WordPress permettent d\'ajouter du contenu dynamique dans les sidebars. register_sidebar() enregistre une zone, dynamic_sidebar() l\'affiche. is_active_sidebar() vérifie si des widgets sont actifs. C\'est essentiel pour les thèmes personnalisables.'),
                    'startCode' => '/* functions.php */
<?php
function mon_theme_widgets() {
  
}
add_action(\'widgets_init\', \'mon_theme_widgets\');
?>

/* sidebar.php */
<?php


?>',
                    'solution' => '/* functions.php */
<?php
function mon_theme_widgets() {
  register_sidebar(array(
    \'name\' => \'Sidebar principale\',
    \'id\' => \'sidebar-1\'
  ));
}
add_action(\'widgets_init\', \'mon_theme_widgets\');
?>

/* sidebar.php */
<?php
if (is_active_sidebar(\'sidebar-1\')) {
  dynamic_sidebar(\'sidebar-1\');
}
?>',
                    'hint' => $getTranslated('hint', 'Utilisez register_sidebar(array(\'name\' => \'...\', \'id\' => \'...\')) dans functions.php, et dynamic_sidebar(\'id\') dans le template.')
                ],
                9 => [
                    'title' => $getTranslated('title', 'Catégories et tags'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 15,
                    'instruction' => $getTranslated('instruction', 'Affichez les catégories et les tags de l\'article en utilisant the_category() et the_tags().'),
                    'description' => $getTranslated('description', 'WordPress utilise les catégories et tags pour organiser le contenu. the_category() affiche les catégories, the_tags() affiche les tags. get_the_category() et get_the_tags() retournent les données. C\'est essentiel pour la navigation et l\'organisation.'),
                    'startCode' => '<?php
if (have_posts()) {
  while (have_posts()) {
    the_post();
    the_title();
    
  }
}
?>',
                    'solution' => '<?php
if (have_posts()) {
  while (have_posts()) {
    the_post();
    the_title();
    the_category(\', \');
    the_tags(\'Tags: \', \', \');
  }
}
?>',
                    'hint' => $getTranslated('hint', 'Utilisez the_category(\', \') pour afficher les catégories séparées par des virgules, et the_tags(\'Tags: \', \', \') pour les tags.')
                ],
                10 => [
                    'title' => $getTranslated('title', 'Pagination WordPress'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Ajoutez la pagination WordPress en utilisant the_posts_pagination() ou paginate_links().'),
                    'description' => $getTranslated('description', 'La pagination WordPress divise les articles en pages. the_posts_pagination() affiche la pagination moderne, paginate_links() offre plus de contrôle. previous_posts_link() et next_posts_link() créent des liens simples. C\'est essentiel pour naviguer entre les pages d\'articles.'),
                    'startCode' => '<?php
if (have_posts()) {
  while (have_posts()) {
    the_post();
    the_title();
  }
  
}
?>',
                    'solution' => '<?php
if (have_posts()) {
  while (have_posts()) {
    the_post();
    the_title();
  }
  the_posts_pagination();
}
?>',
                    'hint' => $getTranslated('hint', 'Utilisez the_posts_pagination() après The Loop pour afficher la pagination. previous_posts_link() et next_posts_link() créent des liens simples.')
                ],
                11 => [
                    'title' => $getTranslated('title', 'Custom Post Type'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Enregistrez un custom post type "portfolio" avec les paramètres : public => true, has_archive => true, et supports => [\'title\', \'editor\', \'thumbnail\']. Utilisez add_action(\'init\', ...).'),
                    'description' => $getTranslated('description', 'Les Custom Post Types étendent WordPress au-delà des articles et pages. register_post_type() crée un nouveau type de contenu. public rend accessible, has_archive active l\'archive, supports définit les fonctionnalités. C\'est essentiel pour des sites complexes.'),
                    'startCode' => '<?php
function create_portfolio_post_type() {
  register_post_type(\'\', array(
    \'labels\' => array(\'name\' => \'Portfolio\'),
    \'public\' => true
  ));
}
add_action(\'init\', \'create_portfolio_post_type\');
?>',
                    'solution' => '<?php
function create_portfolio_post_type() {
  register_post_type(\'portfolio\', array(
    \'labels\' => array(\'name\' => \'Portfolio\'),
    \'public\' => true,
    \'has_archive\' => true,
    \'supports\' => array(\'title\', \'editor\', \'thumbnail\')
  ));
}
add_action(\'init\', \'create_portfolio_post_type\');
?>',
                    'hint' => $getTranslated('hint', 'Utilisez "portfolio" comme premier paramètre, et ajoutez has_archive => true et supports => array(\'title\', \'editor\', \'thumbnail\').')
                ],
                12 => [
                    'title' => $getTranslated('title', 'Les actions et filtres'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 25,
                    'instruction' => $getTranslated('instruction', 'Créez un filtre qui modifie le contenu des articles en ajoutant "Publié par NiangProgrammeur" à la fin. Utilisez add_filter() avec le hook "the_content".'),
                    'description' => $getTranslated('description', 'Les hooks (actions et filtres) sont le système d\'extensibilité de WordPress. add_filter() modifie des données, add_action() exécute du code. the_content est un filtre qui permet de modifier le contenu avant affichage. C\'est la base du développement WordPress avancé.'),
                    'startCode' => '<?php

function ajouter_signature($content) {
  
  return $content;
}

?>',
                    'solution' => '<?php
function ajouter_signature($content) {
  if (is_single()) {
    $content .= "<p><em>Publié par NiangProgrammeur</em></p>";
  }
  return $content;
}
add_filter(\'the_content\', \'ajouter_signature\');
?>',
                    'hint' => $getTranslated('hint', 'Créez function ajouter_signature($content) { $content .= "..."; return $content; } puis add_filter(\'the_content\', \'ajouter_signature\');')
                ],
                13 => [
                    'title' => $getTranslated('title', 'Créer un thème complet'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Créez la structure minimale d\'un thème WordPress avec : style.css (avec en-tête), index.php, functions.php, et header.php. Le style.css doit contenir les informations du thème (Theme Name, Author, Version).'),
                    'description' => $getTranslated('description', 'Un thème WordPress nécessite au minimum style.css avec l\'en-tête du thème, index.php comme template de fallback, functions.php pour les fonctionnalités, et header.php/footer.php pour la structure. C\'est la base pour créer un thème personnalisé.'),
                    'startCode' => '/* style.css */
/*
Theme Name: 
Author: 
Version: 
*/

/* index.php */
<?php get_header(); ?>

<?php get_footer(); ?>

/* functions.php */
<?php

?>',
                    'solution' => '/* style.css */
/*
Theme Name: Mon Thème
Author: NiangProgrammeur
Version: 1.0
*/

/* index.php */
<?php get_header(); ?>
<?php
if (have_posts()) {
  while (have_posts()) {
    the_post();
    the_title();
    the_content();
  }
}
?>
<?php get_footer(); ?>

/* functions.php */
<?php
function mon_theme_setup() {
  add_theme_support(\'post-thumbnails\');
}
add_action(\'after_setup_theme\', \'mon_theme_setup\');
?>',
                    'hint' => $getTranslated('hint', 'Ajoutez Theme Name, Author, Version dans style.css. Créez index.php avec The Loop. Ajoutez add_theme_support() dans functions.php.')
                ],
                14 => [
                    'title' => $getTranslated('title', 'Taxonomies personnalisées'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Enregistrez une taxonomie personnalisée "genre" pour le custom post type "livre" avec register_taxonomy(). La taxonomie doit être hiérarchique (comme les catégories).'),
                    'description' => $getTranslated('description', 'Les taxonomies personnalisées organisent les custom post types. register_taxonomy() crée une taxonomie, hierarchical => true crée une taxonomie hiérarchique (comme catégories), false crée une taxonomie non-hiérarchique (comme tags). C\'est essentiel pour organiser du contenu personnalisé.'),
                    'startCode' => '<?php
function creer_taxonomie_genre() {
  
}
add_action(\'init\', \'creer_taxonomie_genre\');
?>',
                    'solution' => '<?php
function creer_taxonomie_genre() {
  register_taxonomy(\'genre\', \'livre\', array(
    \'labels\' => array(\'name\' => \'Genres\'),
    \'hierarchical\' => true,
    \'public\' => true
  ));
}
add_action(\'init\', \'creer_taxonomie_genre\');
?>',
                    'hint' => $getTranslated('hint', 'Utilisez register_taxonomy(\'genre\', \'livre\', array(\'hierarchical\' => true, ...)) pour créer une taxonomie hiérarchique.')
                ],
                15 => [
                    'title' => $getTranslated('title', 'Meta boxes personnalisées'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Créez une meta box personnalisée "Prix" pour les articles en utilisant add_meta_box(), et sauvegardez les données avec save_post. Affichez le champ dans l\'éditeur.'),
                    'description' => $getTranslated('description', 'Les meta boxes ajoutent des champs personnalisés aux articles. add_meta_box() crée la meta box, save_post sauvegarde les données, get_post_meta() récupère les valeurs. C\'est essentiel pour ajouter des métadonnées personnalisées aux articles.'),
                    'startCode' => '<?php
function ajouter_meta_box_prix() {
  
}
add_action(\'add_meta_boxes\', \'ajouter_meta_box_prix\');

function sauvegarder_prix($post_id) {
  
}
add_action(\'save_post\', \'sauvegarder_prix\');
?>',
                    'solution' => '<?php
function ajouter_meta_box_prix() {
  add_meta_box(
    \'prix_meta_box\',
    \'Prix\',
    \'afficher_meta_box_prix\',
    \'post\'
  );
}
add_action(\'add_meta_boxes\', \'ajouter_meta_box_prix\');

function afficher_meta_box_prix($post) {
  $prix = get_post_meta($post->ID, \'_prix\', true);
  echo \'<input type="text" name="prix" value="\' . esc_attr($prix) . \'">\';
}

function sauvegarder_prix($post_id) {
  if (isset($_POST[\'prix\'])) {
    update_post_meta($post_id, \'_prix\', sanitize_text_field($_POST[\'prix\']));
  }
}
add_action(\'save_post\', \'sauvegarder_prix\');
?>',
                    'hint' => $getTranslated('hint', 'Utilisez add_meta_box() pour créer, une fonction callback pour afficher, et save_post pour sauvegarder avec update_post_meta().')
                ],
            ],
            'ia' => [
                1 => [
                    'title' => $getTranslated('title', 'Concepts de base IA'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Complétez la définition : L\'IA est la simulation de l\'intelligence ___ par des machines.'),
                    'description' => $getTranslated('description', 'L\'Intelligence Artificielle (IA) est un domaine de l\'informatique qui vise à créer des systèmes capables d\'effectuer des tâches nécessitant normalement l\'intelligence humaine. Elle simule les processus cognitifs comme l\'apprentissage, le raisonnement et la résolution de problèmes.'),
                    'startCode' => 'L\'IA est la simulation de l\'intelligence ___ par des machines.',
                    'solution' => 'L\'IA est la simulation de l\'intelligence humaine par des machines.',
                    'hint' => $getTranslated('hint', 'L\'IA simule l\'intelligence humaine. Réponse : humaine.')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Machine Learning'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Citez les 3 types principaux de Machine Learning (Apprentissage automatique).'),
                    'description' => $getTranslated('description', 'Le Machine Learning est un sous-domaine de l\'IA. L\'apprentissage supervisé utilise des données étiquetées, l\'apprentissage non supervisé trouve des patterns sans étiquettes, et l\'apprentissage par renforcement apprend par essais et erreurs avec des récompenses.'),
                    'startCode' => '1. Apprentissage supervisé
2. Apprentissage non supervisé
3. Apprentissage ___',
                    'solution' => '1. Apprentissage supervisé
2. Apprentissage non supervisé
3. Apprentissage par renforcement',
                    'hint' => $getTranslated('hint', 'Le troisième type est l\'apprentissage par renforcement (reinforcement learning).')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Types d\'apprentissage'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Complétez : L\'apprentissage ___ utilise des données étiquetées pour entraîner un modèle.'),
                    'description' => $getTranslated('description', 'L\'apprentissage supervisé utilise des données étiquetées (input et output connus). L\'apprentissage non supervisé trouve des patterns sans étiquettes. L\'apprentissage par renforcement apprend par essais et erreurs avec des récompenses. C\'est la base de la classification des algorithmes ML.'),
                    'startCode' => 'L\'apprentissage ___ utilise des données étiquetées pour entraîner un modèle.',
                    'solution' => 'L\'apprentissage supervisé utilise des données étiquetées pour entraîner un modèle.',
                    'hint' => $getTranslated('hint', 'L\'apprentissage supervisé utilise des données avec des étiquettes (labels). Réponse : supervisé.')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Données et datasets'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Complétez : Un ___ est un ensemble de données utilisé pour entraîner un modèle d\'IA.'),
                    'description' => $getTranslated('description', 'Un dataset est une collection de données structurées. Le dataset d\'entraînement entraîne le modèle, le dataset de test évalue les performances, le dataset de validation ajuste les hyperparamètres. La qualité du dataset détermine la qualité du modèle. C\'est fondamental en ML.'),
                    'startCode' => 'Un ___ est un ensemble de données utilisé pour entraîner un modèle d\'IA.',
                    'solution' => 'Un dataset est un ensemble de données utilisé pour entraîner un modèle d\'IA.',
                    'hint' => $getTranslated('hint', 'Un dataset est un ensemble de données. Réponse : dataset.')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Algorithmes de base'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Citez 2 algorithmes de Machine Learning couramment utilisés pour la classification.'),
                    'description' => $getTranslated('description', 'Les algorithmes ML de base incluent la régression linéaire (prédiction), la classification (KNN, SVM, Decision Tree), le clustering (K-means), et les réseaux de neurones. Chaque algorithme a ses forces et faiblesses selon le type de problème.'),
                    'startCode' => 'Algorithmes de classification :
1. ___
2. ___',
                    'solution' => 'Algorithmes de classification :
1. Decision Tree (Arbre de décision)
2. K-Nearest Neighbors (KNN)',
                    'hint' => $getTranslated('hint', 'Exemples d\'algorithmes de classification : Decision Tree, KNN, SVM, Naive Bayes, Random Forest.')
                ],
                6 => [
                    'title' => $getTranslated('title', 'Réseaux de neurones'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 15,
                    'instruction' => $getTranslated('instruction', 'Complétez la structure d\'un réseau de neurones : Couche d\'entrée → Couches ___ → Couche de sortie.'),
                    'description' => $getTranslated('description', 'Les réseaux de neurones artificiels sont inspirés du cerveau. Ils ont une couche d\'entrée (input), des couches cachées (hidden layers) qui traitent les données, et une couche de sortie (output). Plus il y a de couches cachées, plus le réseau est "profond" (deep learning).'),
                    'startCode' => 'Couche d\'entrée → Couches ___ → Couche de sortie',
                    'solution' => 'Couche d\'entrée → Couches cachées → Couche de sortie',
                    'hint' => $getTranslated('hint', 'Les couches intermédiaires sont appelées "couches cachées" (hidden layers).')
                ],
                7 => [
                    'title' => $getTranslated('title', 'Deep Learning'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 15,
                    'instruction' => $getTranslated('instruction', 'Quelle bibliothèque Python open-source, développée par Google, est la plus populaire pour le Deep Learning ? Complétez : import ___.'),
                    'description' => $getTranslated('description', 'TensorFlow est une bibliothèque open-source développée par Google pour le machine learning et le deep learning. Elle permet de créer et entraîner des réseaux de neurones complexes. Keras est une API haut niveau qui s\'appuie sur TensorFlow.'),
                    'startCode' => 'import ___',
                    'solution' => 'import tensorflow',
                    'hint' => $getTranslated('hint', 'La bibliothèque s\'appelle TensorFlow. Réponse : tensorflow.')
                ],
                8 => [
                    'title' => $getTranslated('title', 'Entraînement d\'un modèle'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Complétez : L\'___ est le processus d\'ajustement des paramètres d\'un modèle pour minimiser l\'erreur.'),
                    'description' => $getTranslated('description', 'L\'entraînement (training) ajuste les paramètres du modèle pour minimiser l\'erreur. L\'epoch est une passe complète sur le dataset. Le batch size est le nombre d\'échantillons traités avant la mise à jour. Le learning rate contrôle la vitesse d\'apprentissage. C\'est le cœur du ML.'),
                    'startCode' => 'L\'___ est le processus d\'ajustement des paramètres d\'un modèle pour minimiser l\'erreur.',
                    'solution' => 'L\'entraînement (training) est le processus d\'ajustement des paramètres d\'un modèle pour minimiser l\'erreur.',
                    'hint' => $getTranslated('hint', 'L\'entraînement (training) ajuste les paramètres. Réponse : entraînement ou training.')
                ],
                9 => [
                    'title' => $getTranslated('title', 'Évaluation de performance'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Citez 2 métriques utilisées pour évaluer les performances d\'un modèle de classification.'),
                    'description' => $getTranslated('description', 'Les métriques évaluent les performances. Accuracy (précision globale), Precision (précision des prédictions positives), Recall (taux de détection), F1-score (moyenne harmonique). Pour la régression : MSE, MAE, R². Le choix dépend du problème. C\'est essentiel pour valider un modèle.'),
                    'startCode' => 'Métriques de classification :
1. ___
2. ___',
                    'solution' => 'Métriques de classification :
1. Accuracy (Précision)
2. F1-score',
                    'hint' => $getTranslated('hint', 'Métriques courantes : Accuracy, Precision, Recall, F1-score, Confusion Matrix.')
                ],
                10 => [
                    'title' => $getTranslated('title', 'Bibliothèques Python (TensorFlow, PyTorch)'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Complétez : ___ est une bibliothèque Python open-source développée par Facebook pour le Deep Learning.'),
                    'description' => $getTranslated('description', 'TensorFlow (Google) et PyTorch (Facebook) sont les deux principales bibliothèques de Deep Learning. TensorFlow est plus mature, PyTorch est plus flexible. Keras est une API haut niveau sur TensorFlow. Scikit-learn est pour le ML classique. C\'est essentiel pour développer en IA.'),
                    'startCode' => '___ est une bibliothèque Python open-source développée par Facebook pour le Deep Learning.',
                    'solution' => 'PyTorch est une bibliothèque Python open-source développée par Facebook pour le Deep Learning.',
                    'hint' => $getTranslated('hint', 'PyTorch est développé par Facebook. TensorFlow est développé par Google. Réponse : PyTorch.')
                ],
                11 => [
                    'title' => $getTranslated('title', 'Applications IA'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Citez 3 applications concrètes de l\'IA dans la vie quotidienne.'),
                    'description' => $getTranslated('description', 'L\'IA est partout : reconnaissance vocale (Siri, Alexa), voitures autonomes (Tesla), assistants virtuels, recommandations (Netflix, Amazon), reconnaissance d\'images, traduction automatique, chatbots, diagnostic médical, etc. L\'IA transforme de nombreux secteurs.'),
                    'startCode' => '1. Reconnaissance ___
2. Voitures ___
3. Assistants ___',
                    'solution' => '1. Reconnaissance vocale
2. Voitures autonomes
3. Assistants virtuels',
                    'hint' => $getTranslated('hint', 'Exemples : reconnaissance vocale (Siri), voitures autonomes (Tesla), assistants virtuels (ChatGPT).')
                ],
                12 => [
                    'title' => $getTranslated('title', 'Natural Language Processing'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 25,
                    'instruction' => $getTranslated('instruction', 'Expliquez ce qu\'est le NLP (Natural Language Processing) et donnez 2 exemples d\'applications.'),
                    'description' => $getTranslated('description', 'Le NLP (Traitement du Langage Naturel) permet aux machines de comprendre et générer le langage humain. Applications : traduction automatique (Google Translate), chatbots, analyse de sentiment, résumé automatique, assistants vocaux. Les modèles modernes comme GPT utilisent le NLP.'),
                    'startCode' => 'NLP signifie : ___

Applications :
1. ___
2. ___',
                    'solution' => 'NLP signifie : Natural Language Processing (Traitement du Langage Naturel)

Applications :
1. Traduction automatique
2. Chatbots et assistants vocaux',
                    'hint' => $getTranslated('hint', 'NLP = Natural Language Processing. Applications : traduction, chatbots, analyse de sentiment, etc.')
                ],
                13 => [
                    'title' => $getTranslated('title', 'Éthique de l\'IA'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Citez 3 principes éthiques importants dans le développement de l\'IA (ex: transparence, équité, confidentialité).'),
                    'description' => $getTranslated('description', 'L\'éthique de l\'IA est cruciale. Principes clés : transparence (compréhensibilité), équité (pas de biais), confidentialité (protection des données), responsabilité (qui est responsable), robustesse (sécurité). Ces principes guident le développement responsable de l\'IA.'),
                    'startCode' => 'Principes éthiques de l\'IA :
1. ___
2. ___
3. ___',
                    'solution' => 'Principes éthiques de l\'IA :
1. Transparence
2. Équité (absence de biais)
3. Confidentialité et protection des données',
                    'hint' => $getTranslated('hint', 'Principes : transparence, équité, confidentialité, responsabilité, robustesse.')
                ],
                14 => [
                    'title' => $getTranslated('title', 'Computer Vision'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Expliquez ce qu\'est la Computer Vision et donnez 2 applications concrètes.'),
                    'description' => $getTranslated('description', 'La Computer Vision permet aux machines de comprendre les images. Applications : reconnaissance d\'objets, classification d\'images, détection de visages, OCR (reconnaissance de texte), voitures autonomes, imagerie médicale. Les CNN (Convolutional Neural Networks) sont la base. C\'est un domaine en pleine expansion.'),
                    'startCode' => 'Computer Vision signifie : ___

Applications :
1. ___
2. ___',
                    'solution' => 'Computer Vision signifie : Vision par ordinateur (analyse et compréhension d\'images par les machines)

Applications :
1. Reconnaissance d\'objets et classification d\'images
2. Détection de visages et OCR (reconnaissance de texte)',
                    'hint' => $getTranslated('hint', 'Computer Vision = Vision par ordinateur. Applications : reconnaissance d\'objets, classification, OCR, voitures autonomes, imagerie médicale.')
                ],
                15 => [
                    'title' => $getTranslated('title', 'Optimisation et hyperparamètres'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Citez 3 hyperparamètres importants à ajuster lors de l\'entraînement d\'un réseau de neurones.'),
                    'description' => $getTranslated('description', 'Les hyperparamètres contrôlent l\'entraînement. Learning rate (vitesse d\'apprentissage), batch size (taille des lots), nombre d\'epochs (itérations), nombre de couches, nombre de neurones par couche. L\'optimisation des hyperparamètres améliore les performances. C\'est un processus itératif.'),
                    'startCode' => 'Hyperparamètres importants :
1. ___
2. ___
3. ___',
                    'solution' => 'Hyperparamètres importants :
1. Learning rate (taux d\'apprentissage)
2. Batch size (taille des lots)
3. Nombre d\'epochs (itérations)',
                    'hint' => $getTranslated('hint', 'Hyperparamètres clés : learning rate, batch size, nombre d\'epochs, nombre de couches, nombre de neurones, fonction d\'activation.')
                ],
            ],
            'python' => [
                1 => [
                    'title' => $getTranslated('title', 'Syntaxe de base Python'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Affichez le message "Bonjour Python !" en utilisant la fonction print().'),
                    'description' => $getTranslated('description', 'La fonction print() est la fonction de base pour afficher du texte en Python. Elle permet d\'afficher des chaînes de caractères, des variables, et des expressions. C\'est l\'une des premières fonctions que vous apprendrez en Python.'),
                    'startCode' => '# Affichez "Bonjour Python !"
',
                    'solution' => '# Affichez "Bonjour Python !"
print("Bonjour Python !")',
                    'hint' => $getTranslated('hint', 'Utilisez print("Bonjour Python !") pour afficher le message.')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Variables Python'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Créez une variable nom avec la valeur "Python" et affichez-la.'),
                    'description' => $getTranslated('description', 'En Python, les variables sont créées simplement en leur assignant une valeur. Pas besoin de déclarer le type. Python est un langage à typage dynamique.'),
                    'startCode' => '# Créez une variable nom avec la valeur "Python"
# Affichez la variable
',
                    'solution' => '# Créez une variable nom avec la valeur "Python"
nom = "Python"
# Affichez la variable
print(nom)',
                    'hint' => $getTranslated('hint', 'Créez la variable avec nom = "Python" puis utilisez print(nom) pour l\'afficher.')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Types de données Python'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Créez trois variables : un entier (age = 25), un décimal (prix = 19.99), et une chaîne (nom = "Python").'),
                    'description' => $getTranslated('description', 'Python a plusieurs types de données : int (entiers), float (décimaux), str (chaînes de caractères), bool (booléens), list (listes), dict (dictionnaires), etc.'),
                    'startCode' => '# Créez les trois variables
',
                    'solution' => '# Créez les trois variables
age = 25
prix = 19.99
nom = "Python"',
                    'hint' => $getTranslated('hint', 'age = 25 (int), prix = 19.99 (float), nom = "Python" (str).')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Opérateurs Python'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Calculez la somme de 10 et 5, puis affichez le résultat.'),
                    'description' => $getTranslated('description', 'Python supporte les opérateurs arithmétiques : + (addition), - (soustraction), * (multiplication), / (division), // (division entière), % (modulo), ** (puissance).'),
                    'startCode' => '# Calculez 10 + 5 et affichez le résultat
',
                    'solution' => '# Calculez 10 + 5 et affichez le résultat
resultat = 10 + 5
print(resultat)',
                    'hint' => $getTranslated('hint', 'Utilisez resultat = 10 + 5 puis print(resultat).')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Commentaires Python'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 8,
                    'instruction' => $getTranslated('instruction', 'Ajoutez un commentaire sur une seule ligne "Ceci est un commentaire" et un commentaire sur plusieurs lignes "Ceci est un commentaire sur plusieurs lignes." dans le code Python.'),
                    'description' => $getTranslated('description', 'Les commentaires Python sont ignorés par l\'interpréteur. // ou # pour une ligne, /* ... */ pour plusieurs lignes. Ils sont essentiels pour documenter le code et le rendre compréhensible.'),
                    'startCode' => 'nom = "Python"
print(nom)',
                    'solution' => '# Définit une variable nom avec la valeur "Python"
nom = "Python"
# Affiche la valeur de la variable nom
print(nom)',
                    'hint' => $getTranslated('hint', 'Utilisez # pour un commentaire sur une ligne et """ ... """ pour un commentaire sur plusieurs lignes.')
                ],
                6 => [
                    'title' => $getTranslated('title', 'Conditions if/elif/else'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 15,
                    'instruction' => $getTranslated('instruction', 'Créez une condition if/else qui affiche "Majeur" si l\'âge est >= 18, sinon "Mineur".'),
                    'description' => $getTranslated('description', 'Les conditions if/else permettent d\'exécuter du code de manière conditionnelle. if (condition) exécute le code si la condition est vraie, else exécute le code alternatif. Les opérateurs de comparaison (==, ===, <, >, <=, >=) comparent des valeurs. C\'est fondamental pour la logique de programmation.'),
                    'startCode' => 'age = 20
# Ajoutez la condition
',
                    'solution' => 'age = 20
# Ajoutez la condition
if age >= 18:
    print("Majeur")
else:
    print("Mineur")',
                    'hint' => $getTranslated('hint', 'Utilisez if age >= 18: print("Majeur") else: print("Mineur"). N\'oubliez pas les deux-points et l\'indentation !')
                ],
                7 => [
                    'title' => $getTranslated('title', 'Boucles for et while'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Affichez les nombres de 1 à 5 en utilisant une boucle for.'),
                    'description' => $getTranslated('description', 'Les boucles répètent du code. for (initialisation; condition; incrément) répète tant que la condition est vraie. while (condition) répète aussi, mais l\'incrément doit être géré manuellement. Les boucles sont essentielles pour traiter des collections de données.'),
                    'startCode' => '# Affichez les nombres de 1 à 5 avec une boucle for
',
                    'solution' => '# Affichez les nombres de 1 à 5 avec une boucle for
for i in range(1, 6):
    print(i)',
                    'hint' => $getTranslated('hint', 'Utilisez for i in range(1, 6): print(i). range(1, 6) génère les nombres de 1 à 5.')
                ],
                8 => [
                    'title' => $getTranslated('title', 'Fonctions Python'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Créez une fonction qui additionne deux nombres.'),
                    'description' => $getTranslated('description', 'Les fonctions Python organisent le code réutilisable. function nomFonction(param) { return valeur; } définit une fonction. return retourne une valeur. Les fonctions peuvent avoir des paramètres par défaut. C\'est essentiel pour éviter la duplication de code.'),
                    'startCode' => '# Créez la fonction saluer
# Appelez-la avec "Python"
',
                    'solution' => '# Créez la fonction saluer
def saluer(nom):
    return f"Bonjour, {nom} !"

# Appelez-la avec "Python"
print(saluer("Python"))',
                    'hint' => $getTranslated('hint', 'Créez def additionner(a, b): return a + b')
                ],
                9 => [
                    'title' => $getTranslated('title', 'Listes Python'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Affichez tous les fruits du tableau avec une boucle.'),
                    'description' => $getTranslated('description', 'Les tableaux Python stockent des collections d\'éléments. Les boucles for et forEach parcourent les tableaux. fruits.length retourne la taille du tableau. Les tableaux sont fondamentaux pour gérer des listes de données.'),
                    'startCode' => '# Créez la liste fruits
# Affichez le premier élément
',
                    'solution' => '# Créez la liste fruits
fruits = ["pomme", "banane", "orange"]
# Affichez le premier élément
print(fruits[0])',
                    'hint' => $getTranslated('hint', 'Utilisez for fruit in fruits: print(fruit) pour parcourir le tableau.')
                ],
                10 => [
                    'title' => $getTranslated('title', 'Dictionnaires Python'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Créez un objet personne avec les propriétés nom et age, puis affichez la valeur de nom.'),
                    'description' => $getTranslated('description', 'Les objets Python stockent des paires clé-valeur. On accède aux propriétés avec la notation point (personne.nom) ou avec des crochets (personne["nom"]). Les objets sont très utiles pour représenter des données structurées.'),
                    'startCode' => '# Créez le dictionnaire personne
# Affichez la valeur de "nom"
',
                    'solution' => '# Créez le dictionnaire personne
personne = {"nom": "Python", "age": 30}
# Affichez la valeur de "nom"
print(personne["nom"])',
                    'hint' => $getTranslated('hint', 'Créez personne = {"nom": "Python", "age": 30} puis print(personne["nom"]).')
                ],
                11 => [
                    'title' => $getTranslated('title', 'Programmation Orientée Objet'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Créez une classe Personne avec un constructeur qui prend nom et age, puis créez un objet personne1.'),
                    'description' => $getTranslated('description', 'La POO permet de créer des classes et des objets. Une classe est un modèle, un objet est une instance. Le constructeur __init__ est appelé lors de la création d\'un objet. self représente l\'instance. C\'est essentiel pour organiser le code en entités réutilisables.'),
                    'startCode' => '# Créez la classe Personne
# Créez un objet personne1
',
                    'solution' => '# Créez la classe Personne
class Personne:
    def __init__(self, nom, age):
        self.nom = nom
        self.age = age

# Créez un objet personne1
personne1 = Personne("Python", 30)
print(personne1.nom)',
                    'hint' => $getTranslated('hint', 'Créez class Personne: def __init__(self, nom, age): self.nom = nom; self.age = age puis personne1 = Personne("Python", 30).')
                ],
                12 => [
                    'title' => $getTranslated('title', 'Modules et packages'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 25,
                    'instruction' => $getTranslated('instruction', 'Importez le module math et utilisez math.sqrt(16) pour calculer la racine carrée de 16.'),
                    'description' => $getTranslated('description', 'Les modules permettent d\'organiser le code et de réutiliser des fonctions. Python a une vaste bibliothèque standard. On importe avec import. On peut aussi importer des fonctions spécifiques avec from module import fonction. C\'est essentiel pour structurer des projets complexes.'),
                    'startCode' => '# Importez math et calculez sqrt(16)
',
                    'solution' => '# Importez math et calculez sqrt(16)
import math
resultat = math.sqrt(16)
print(resultat)',
                    'hint' => $getTranslated('hint', 'Utilisez import math puis resultat = math.sqrt(16) et print(resultat).')
                ],
                13 => [
                    'title' => $getTranslated('title', 'Gestion des exceptions'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Utilisez try/except pour gérer une erreur de division par zéro.'),
                    'description' => $getTranslated('description', 'Les exceptions permettent de gérer les erreurs. try exécute le code, except capture les exceptions. C\'est essentiel pour créer des programmes robustes qui ne plantent pas en cas d\'erreur.'),
                    'startCode' => '# Gérer la division par zéro avec try/except
a = 10
b = 0
',
                    'solution' => '# Gérer la division par zéro avec try/except
a = 10
b = 0
try:
    resultat = a / b
    print(resultat)
except ZeroDivisionError:
    print("Erreur : Division par zéro !")',
                    'hint' => $getTranslated('hint', 'Utilisez try: resultat = a / b; print(resultat) except ZeroDivisionError: print("Erreur : Division par zéro !").')
                ],
                14 => [
                    'title' => $getTranslated('title', 'Manipulation de fichiers'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Écrivez "Bonjour Python !" dans un fichier texte nommé "fichier.txt".'),
                    'description' => $getTranslated('description', 'Python permet de lire et écrire dans des fichiers. On utilise open() avec les modes "r" (lecture), "w" (écriture), "a" (ajout). Il est recommandé d\'utiliser with pour garantir la fermeture du fichier. C\'est essentiel pour la persistance des données.'),
                    'startCode' => '# Écrivez dans le fichier
',
                    'solution' => '# Écrivez dans le fichier
with open("fichier.txt", "w") as f:
    f.write("Bonjour Python !")',
                    'hint' => $getTranslated('hint', 'Utilisez with open("fichier.txt", "w") as f: f.write("Bonjour Python !").')
                ],
                15 => [
                    'title' => $getTranslated('title', 'Compréhensions de listes et générateurs'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Créez une liste des carrés des nombres de 1 à 5 en utilisant une compréhension de liste.'),
                    'description' => $getTranslated('description', 'Les compréhensions de listes permettent de créer des listes de manière concise. Syntaxe : [expression for item in iterable]. Les générateurs sont similaires mais utilisent () au lieu de [] et sont plus efficaces en mémoire. C\'est une technique avancée pour manipuler des collections.'),
                    'startCode' => '# Créez la liste des carrés avec une compréhension
',
                    'solution' => '# Créez la liste des carrés avec une compréhension
carres = [x**2 for x in range(1, 6)]
print(carres)',
                    'hint' => $getTranslated('hint', 'Utilisez carres = [x**2 for x in range(1, 6)] pour créer [1, 4, 9, 16, 25].')
                ],
            ],
            'java' => [
                1 => [
                    'title' => $getTranslated('title', 'Premier programme Java'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Créez un programme Java qui affiche "Bonjour Java !" dans la console.'),
                    'description' => $getTranslated('description', 'En Java, chaque programme doit avoir une classe publique avec une méthode main(). La méthode main() est le point d\'entrée du programme. Utilisez System.out.println() pour afficher du texte.'),
                    'startCode' => 'public class Bonjour {
    public static void main(String[] args) {
        // Affichez "Bonjour Java !"
    }
}',
                    'solution' => 'public class Bonjour {
    public static void main(String[] args) {
        System.out.println("Bonjour Java !");
    }
}',
                    'hint' => $getTranslated('hint', 'Utilisez System.out.println("Bonjour Java !"); dans la méthode main().')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Variables et types'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Déclarez une variable nom de type String avec la valeur "Java" et affichez-la.'),
                    'description' => $getTranslated('description', 'En Java, les variables doivent être déclarées avec un type. String est utilisé pour les chaînes de caractères. Java est un langage fortement typé.'),
                    'startCode' => 'public class Variables {
    public static void main(String[] args) {
        // Déclarez une variable nom de type String avec la valeur "Java"
        // Affichez-la
    }
}',
                    'solution' => 'public class Variables {
    public static void main(String[] args) {
        String nom = "Java";
        System.out.println(nom);
    }
}',
                    'hint' => $getTranslated('hint', 'Utilisez String nom = "Java"; puis System.out.println(nom);')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Opérateurs arithmétiques'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Calculez la somme de 10 et 5, puis affichez le résultat.'),
                    'description' => $getTranslated('description', 'Java supporte les opérateurs arithmétiques : + (addition), - (soustraction), * (multiplication), / (division), % (modulo).'),
                    'startCode' => 'public class Calcul {
    public static void main(String[] args) {
        // Calculez la somme de 10 et 5
        // Affichez le résultat
    }
}',
                    'solution' => 'public class Calcul {
    public static void main(String[] args) {
        int resultat = 10 + 5;
        System.out.println(resultat);
    }
}',
                    'hint' => $getTranslated('hint', 'Utilisez int resultat = 10 + 5; puis System.out.println(resultat);')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Conditions if/else'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Créez une condition if/else qui affiche "Majeur" si l\'âge est >= 18, sinon "Mineur".'),
                    'description' => $getTranslated('description', 'Les conditions Java utilisent if/else. Les opérateurs de comparaison (==, <, >, <=, >=) comparent des valeurs.'),
                    'startCode' => 'public class Age {
    public static void main(String[] args) {
        int age = 20;
        // Créez une condition if/else
        // Affichez "Majeur" si age >= 18, sinon "Mineur"
    }
}',
                    'solution' => 'public class Age {
    public static void main(String[] args) {
        int age = 20;
        if (age >= 18) {
            System.out.println("Majeur");
        } else {
            System.out.println("Mineur");
        }
    }
}',
                    'hint' => $getTranslated('hint', 'Utilisez if (age >= 18) { System.out.println("Majeur"); } else { System.out.println("Mineur"); }')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Boucles for et while'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Affichez les nombres de 1 à 5 en utilisant une boucle for.'),
                    'description' => $getTranslated('description', 'Les boucles Java répètent du code. for (initialisation; condition; incrément) répète tant que la condition est vraie.'),
                    'startCode' => 'public class Boucle {
    public static void main(String[] args) {
        // Affichez les nombres de 1 à 5 avec une boucle for
    }
}',
                    'solution' => 'public class Boucle {
    public static void main(String[] args) {
        for (int i = 1; i <= 5; i++) {
            System.out.println(i);
        }
    }
}',
                    'hint' => $getTranslated('hint', 'Utilisez for (int i = 1; i <= 5; i++) { System.out.println(i); }')
                ],
                6 => [
                    'title' => $getTranslated('title', 'Méthodes Java'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Créez une méthode qui additionne deux nombres et retourne le résultat.'),
                    'description' => $getTranslated('description', 'Les méthodes Java organisent le code réutilisable. public static int nomMethode(int a, int b) { return valeur; } définit une méthode.'),
                    'startCode' => 'public class Methodes {
    // Créez une méthode qui additionne deux nombres
    public static void main(String[] args) {
        // Appelez la méthode et affichez le résultat
    }
}',
                    'solution' => 'public class Methodes {
    public static int additionner(int a, int b) {
        return a + b;
    }
    public static void main(String[] args) {
        int resultat = additionner(10, 5);
        System.out.println(resultat);
    }
}',
                    'hint' => $getTranslated('hint', 'Créez public static int additionner(int a, int b) { return a + b; }')
                ],
                7 => [
                    'title' => $getTranslated('title', 'Tableaux Java'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Créez un tableau de 5 entiers et affichez chaque élément avec une boucle for.'),
                    'description' => $getTranslated('description', 'Les tableaux Java stockent des collections ordonnées. int[] arr = new int[5] crée un tableau. Les tableaux ont une taille fixe.'),
                    'startCode' => 'public class Tableaux {
    public static void main(String[] args) {
        // Créez un tableau de 5 entiers
        // Affichez chaque élément avec une boucle for
    }
}',
                    'solution' => 'public class Tableaux {
    public static void main(String[] args) {
        int[] arr = new int[]{1, 2, 3, 4, 5};
        for (int i = 0; i < arr.length; i++) {
            System.out.println(arr[i]);
        }
    }
}',
                    'hint' => $getTranslated('hint', 'Créez int[] arr = new int[5]; puis utilisez for (int i = 0; i < arr.length; i++) pour parcourir.')
                ],
                8 => [
                    'title' => $getTranslated('title', 'ArrayList et Collections'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Créez une ArrayList de fruits et ajoutez 3 fruits, puis affichez-les.'),
                    'description' => $getTranslated('description', 'ArrayList est une collection dynamique. ArrayList<String> liste = new ArrayList<>(); crée une liste. add() ajoute un élément.'),
                    'startCode' => 'import java.util.ArrayList;

public class Collections {
    public static void main(String[] args) {
        // Créez une ArrayList de fruits
        // Ajoutez 3 fruits et affichez-les
    }
}',
                    'solution' => 'import java.util.ArrayList;

public class Collections {
    public static void main(String[] args) {
        ArrayList<String> fruits = new ArrayList<>();
        fruits.add("Pomme");
        fruits.add("Banane");
        fruits.add("Orange");
        for (String fruit : fruits) {
            System.out.println(fruit);
        }
    }
}',
                    'hint' => $getTranslated('hint', 'Créez ArrayList<String> fruits = new ArrayList<>(); puis fruits.add("Pomme"); et affichez avec une boucle for.')
                ],
                9 => [
                    'title' => $getTranslated('title', 'Classes et objets'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Créez une classe Personne avec un constructeur qui prend nom et age, puis créez un objet.'),
                    'description' => $getTranslated('description', 'La POO Java permet de créer des classes et des objets. class Personne { } définit une classe. new crée un objet.'),
                    'startCode' => '// Créez une classe Personne avec un constructeur
// Créez un objet dans main
public class Main {
    public static void main(String[] args) {
    }
}',
                    'solution' => 'class Personne {
    String nom;
    int age;
    Personne(String n, int a) {
        nom = n;
        age = a;
    }
}

public class Main {
    public static void main(String[] args) {
        Personne p = new Personne("Java", 30);
        System.out.println(p.nom + " - " + p.age);
    }
}',
                    'hint' => $getTranslated('hint', 'Créez class Personne { String nom; int age; Personne(String n, int a) { nom = n; age = a; } } puis Personne p = new Personne("Java", 30);')
                ],
                10 => [
                    'title' => $getTranslated('title', 'Héritage'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 22,
                    'instruction' => $getTranslated('instruction', 'Créez une classe Animal et une classe Chien qui hérite de Animal.'),
                    'description' => $getTranslated('description', 'L\'héritage Java permet à une classe d\'hériter des propriétés et méthodes d\'une autre. extends crée l\'héritage.'),
                    'startCode' => '// Créez une classe Animal
// Créez une classe Chien qui hérite de Animal
public class Main {
    public static void main(String[] args) {
    }
}',
                    'solution' => 'class Animal {
    String nom;
    Animal(String n) {
        nom = n;
    }
}

class Chien extends Animal {
    Chien(String n) {
        super(n);
    }
}

public class Main {
    public static void main(String[] args) {
        Chien chien = new Chien("Rex");
        System.out.println(chien.nom);
    }
}',
                    'hint' => $getTranslated('hint', 'Créez class Animal { } puis class Chien extends Animal { }')
                ],
                11 => [
                    'title' => $getTranslated('title', 'Polymorphisme'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Créez une classe Voiture avec les propriétés marque et modele, et une méthode afficher().'),
                    'description' => $getTranslated('description', 'La POO permet de créer des classes et des objets. Une classe est un modèle, un objet est une instance.'),
                    'startCode' => '// Créez une classe Voiture avec marque, modele et afficher()
public class Main {
    public static void main(String[] args) {
        // Créez un objet Voiture et appelez afficher()
    }
}',
                    'solution' => 'class Voiture {
    String marque;
    String modele;
    Voiture(String m, String mod) {
        marque = m;
        modele = mod;
    }
    void afficher() {
        System.out.println(marque + " " + modele);
    }
}

public class Main {
    public static void main(String[] args) {
        Voiture v = new Voiture("Toyota", "Corolla");
        v.afficher();
    }
}',
                    'hint' => $getTranslated('hint', 'Créez class Voiture { String marque; String modele; void afficher() { System.out.println(marque + " " + modele); } }')
                ],
                12 => [
                    'title' => $getTranslated('title', 'Interfaces et abstractions'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Créez un package "com.exemple" et placez-y une classe Test.'),
                    'description' => $getTranslated('description', 'Les packages Java organisent les classes. package com.exemple; définit le package. Les packages améliorent l\'organisation du code.'),
                    'startCode' => '// Créez un package com.exemple
// Placez-y une classe Test
',
                    'solution' => 'package com.exemple;

public class Test {
    public static void main(String[] args) {
        System.out.println("Test");
    }
}',
                    'hint' => $getTranslated('hint', 'Utilisez package com.exemple; au début du fichier, puis créez la classe Test dans ce package.')
                ],
                13 => [
                    'title' => $getTranslated('title', 'Gestion des exceptions'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 25,
                    'instruction' => $getTranslated('instruction', 'Utilisez try/catch pour gérer une exception de division par zéro.'),
                    'description' => $getTranslated('description', 'Les exceptions Java gèrent les erreurs. try exécute du code, catch capture les exceptions. C\'est essentiel pour créer des programmes robustes.'),
                    'startCode' => 'public class Exceptions {
    public static void main(String[] args) {
        // Utilisez try/catch pour gérer la division par zéro
        int a = 10;
        int b = 0;
    }
}',
                    'solution' => 'public class Exceptions {
    public static void main(String[] args) {
        int a = 10;
        int b = 0;
        try {
            int resultat = a / b;
            System.out.println(resultat);
        } catch (ArithmeticException e) {
            System.out.println("Division par zéro");
        }
    }
}',
                    'hint' => $getTranslated('hint', 'Utilisez try { int resultat = 10 / 0; } catch (ArithmeticException e) { System.out.println("Division par zéro"); }')
                ],
                14 => [
                    'title' => $getTranslated('title', 'Fichiers et I/O'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Créez un fichier "test.txt" avec le contenu "Hello Java", puis lisez et affichez son contenu.'),
                    'description' => $getTranslated('description', 'Java peut lire et écrire des fichiers. FileWriter écrit dans un fichier, FileReader lit un fichier. BufferedReader améliore les performances.'),
                    'startCode' => 'import java.io.*;

public class Fichiers {
    public static void main(String[] args) {
        // Créez un fichier "test.txt" avec "Hello Java"
        // Lisez et affichez son contenu
    }
}',
                    'solution' => 'import java.io.*;

public class Fichiers {
    public static void main(String[] args) {
        try {
            FileWriter fw = new FileWriter("test.txt");
            fw.write("Hello Java");
            fw.close();
            
            FileReader fr = new FileReader("test.txt");
            BufferedReader br = new BufferedReader(fr);
            String ligne = br.readLine();
            System.out.println(ligne);
            br.close();
        } catch (IOException e) {
            System.out.println("Erreur: " + e.getMessage());
        }
    }
}',
                    'hint' => $getTranslated('hint', 'Utilisez FileWriter pour écrire et FileReader ou BufferedReader pour lire.')
                ],
                15 => [
                    'title' => $getTranslated('title', 'Threads et concurrence'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Créez un thread qui affiche "Thread en cours" toutes les secondes pendant 5 secondes.'),
                    'description' => $getTranslated('description', 'Les threads Java permettent l\'exécution simultanée. extends Thread ou implements Runnable crée un thread. start() démarre le thread.'),
                    'startCode' => '// Créez un thread qui affiche "Thread en cours" toutes les secondes pendant 5 secondes
public class Main {
    public static void main(String[] args) {
    }
}',
                    'solution' => 'class MonThread extends Thread {
    public void run() {
        for (int i = 0; i < 5; i++) {
            System.out.println("Thread en cours");
            try {
                Thread.sleep(1000);
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
        }
    }
}

public class Main {
    public static void main(String[] args) {
        new MonThread().start();
    }
}',
                    'hint' => $getTranslated('hint', 'Créez class MonThread extends Thread { public void run() { ... } } puis new MonThread().start();')
                ],
            ],
            'sql' => [
                1 => [
                    'title' => $getTranslated('title', 'Requête SELECT de base'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Écrivez une requête SQL pour sélectionner toutes les colonnes de la table "utilisateurs".'),
                    'description' => $getTranslated('description', 'La commande SELECT permet de récupérer des données d\'une table. SELECT * sélectionne toutes les colonnes. FROM spécifie la table source.'),
                    'startCode' => '-- Sélectionnez toutes les colonnes de la table utilisateurs
',
                    'solution' => 'SELECT * FROM utilisateurs;',
                    'hint' => $getTranslated('hint', 'Utilisez SELECT * FROM utilisateurs;')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Filtrage avec WHERE'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Sélectionnez tous les utilisateurs dont l\'âge est supérieur à 18.'),
                    'description' => $getTranslated('description', 'La clause WHERE permet de filtrer les résultats selon une condition. Utilisez les opérateurs de comparaison comme >, <, =, etc.'),
                    'startCode' => '-- Sélectionnez les utilisateurs avec age > 18
',
                    'solution' => 'SELECT * FROM utilisateurs WHERE age > 18;',
                    'hint' => $getTranslated('hint', 'Utilisez SELECT * FROM utilisateurs WHERE age > 18;')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Tri avec ORDER BY'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Écrivez une requête SQL pour sélectionner tous les utilisateurs triés par nom en ordre alphabétique.'),
                    'description' => $getTranslated('description', 'La clause ORDER BY trie les résultats. ORDER BY nom ASC trie par ordre croissant, ORDER BY nom DESC trie par ordre décroissant.'),
                    'startCode' => '-- Sélectionnez tous les utilisateurs triés par nom
',
                    'solution' => 'SELECT * FROM utilisateurs ORDER BY nom ASC;',
                    'hint' => $getTranslated('hint', 'Utilisez SELECT * FROM utilisateurs ORDER BY nom ASC;')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Opérateurs de comparaison'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Écrivez une requête SQL pour sélectionner les utilisateurs dont l\'âge est égal à 25.'),
                    'description' => $getTranslated('description', 'Les opérateurs de comparaison SQL comparent des valeurs. = (égal), != ou <> (différent), < (inférieur), > (supérieur), <= (inférieur ou égal), >= (supérieur ou égal).'),
                    'startCode' => '-- Sélectionnez les utilisateurs avec age = 25
',
                    'solution' => 'SELECT * FROM utilisateurs WHERE age = 25;',
                    'hint' => $getTranslated('hint', 'Utilisez SELECT * FROM utilisateurs WHERE age = 25;')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Opérateurs logiques AND/OR'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Écrivez une requête SQL pour sélectionner les utilisateurs dont l\'âge est supérieur à 18 ET le nom commence par "J".'),
                    'description' => $getTranslated('description', 'Les opérateurs logiques combinent des conditions. AND retourne vrai si toutes les conditions sont vraies, OR retourne vrai si au moins une condition est vraie.'),
                    'startCode' => '-- Sélectionnez les utilisateurs avec age > 18 ET nom commence par "J"
',
                    'solution' => 'SELECT * FROM utilisateurs WHERE age > 18 AND nom LIKE "J%";',
                    'hint' => $getTranslated('hint', 'Utilisez SELECT * FROM utilisateurs WHERE age > 18 AND nom LIKE "J%";')
                ],
                6 => [
                    'title' => $getTranslated('title', 'Fonctions d\'agrégation'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Écrivez une requête SQL pour calculer le nombre total d\'utilisateurs, la moyenne d\'âge, l\'âge maximum et l\'âge minimum.'),
                    'description' => $getTranslated('description', 'Les fonctions d\'agrégation SQL calculent des valeurs sur plusieurs lignes. COUNT() compte, AVG() calcule la moyenne, MAX() trouve le maximum, MIN() trouve le minimum, SUM() additionne.'),
                    'startCode' => '-- Calculez COUNT, AVG, MAX, MIN pour l\'âge
',
                    'solution' => 'SELECT COUNT(*), AVG(age), MAX(age), MIN(age) FROM utilisateurs;',
                    'hint' => $getTranslated('hint', 'Utilisez SELECT COUNT(*), AVG(age), MAX(age), MIN(age) FROM utilisateurs;')
                ],
                7 => [
                    'title' => $getTranslated('title', 'GROUP BY et HAVING'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Écrivez une requête SQL pour grouper les utilisateurs par ville et afficher le nombre d\'utilisateurs par ville.'),
                    'description' => $getTranslated('description', 'GROUP BY groupe les lignes par valeurs identiques. HAVING filtre les groupes (similaire à WHERE mais pour les groupes).'),
                    'startCode' => '-- Groupez par ville et comptez les utilisateurs
',
                    'solution' => 'SELECT ville, COUNT(*) FROM utilisateurs GROUP BY ville;',
                    'hint' => $getTranslated('hint', 'Utilisez SELECT ville, COUNT(*) FROM utilisateurs GROUP BY ville;')
                ],
                8 => [
                    'title' => $getTranslated('title', 'JOIN INNER'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Écrivez une requête SQL pour joindre les tables "utilisateurs" et "commandes" et afficher le nom de l\'utilisateur et le montant de chaque commande.'),
                    'description' => $getTranslated('description', 'INNER JOIN retourne uniquement les lignes qui ont une correspondance dans les deux tables. ON spécifie la condition de jointure.'),
                    'startCode' => '-- Joignez utilisateurs et commandes avec INNER JOIN
',
                    'solution' => 'SELECT u.nom, c.montant FROM utilisateurs u INNER JOIN commandes c ON u.id = c.utilisateur_id;',
                    'hint' => $getTranslated('hint', 'Utilisez SELECT u.nom, c.montant FROM utilisateurs u INNER JOIN commandes c ON u.id = c.utilisateur_id;')
                ],
                9 => [
                    'title' => $getTranslated('title', 'JOIN LEFT/RIGHT'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 22,
                    'instruction' => $getTranslated('instruction', 'Écrivez une requête SQL pour afficher tous les utilisateurs et leurs commandes, même si un utilisateur n\'a pas de commande.'),
                    'description' => $getTranslated('description', 'LEFT JOIN retourne toutes les lignes de la table de gauche, même s\'il n\'y a pas de correspondance. RIGHT JOIN fait l\'inverse.'),
                    'startCode' => '-- Utilisez LEFT JOIN pour afficher tous les utilisateurs
',
                    'solution' => 'SELECT u.nom, c.montant FROM utilisateurs u LEFT JOIN commandes c ON u.id = c.utilisateur_id;',
                    'hint' => $getTranslated('hint', 'Utilisez SELECT u.nom, c.montant FROM utilisateurs u LEFT JOIN commandes c ON u.id = c.utilisateur_id;')
                ],
                10 => [
                    'title' => $getTranslated('title', 'Sous-requêtes'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Écrivez une requête SQL pour sélectionner les utilisateurs dont l\'âge est supérieur à la moyenne d\'âge de tous les utilisateurs.'),
                    'description' => $getTranslated('description', 'Les sous-requêtes sont des requêtes imbriquées. Elles peuvent être utilisées dans SELECT, FROM, WHERE, etc. Les sous-requêtes doivent être entre parenthèses.'),
                    'startCode' => '-- Utilisez une sous-requête pour trouver la moyenne d\'âge
',
                    'solution' => 'SELECT * FROM utilisateurs WHERE age > (SELECT AVG(age) FROM utilisateurs);',
                    'hint' => $getTranslated('hint', 'Utilisez SELECT * FROM utilisateurs WHERE age > (SELECT AVG(age) FROM utilisateurs);')
                ],
                11 => [
                    'title' => $getTranslated('title', 'INSERT, UPDATE, DELETE'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 25,
                    'instruction' => $getTranslated('instruction', 'Écrivez une requête SQL pour insérer un nouvel utilisateur, mettre à jour son âge, puis le supprimer.'),
                    'description' => $getTranslated('description', 'INSERT ajoute des lignes, UPDATE modifie des lignes existantes, DELETE supprime des lignes. Ces commandes modifient les données.'),
                    'startCode' => '-- Insérez un utilisateur, mettez à jour son âge, puis supprimez-le
',
                    'solution' => 'INSERT INTO utilisateurs (nom, age) VALUES ("Jean", 30); UPDATE utilisateurs SET age = 31 WHERE nom = "Jean"; DELETE FROM utilisateurs WHERE nom = "Jean";',
                    'hint' => $getTranslated('hint', 'Utilisez INSERT INTO utilisateurs (nom, age) VALUES ("Jean", 30); UPDATE utilisateurs SET age = 31 WHERE nom = "Jean"; DELETE FROM utilisateurs WHERE nom = "Jean";')
                ],
                12 => [
                    'title' => $getTranslated('title', 'Création de tables'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Écrivez une requête SQL pour créer une table "produits" avec les colonnes id (INT, PRIMARY KEY), nom (VARCHAR(100)), prix (DECIMAL(10,2)).'),
                    'description' => $getTranslated('description', 'CREATE TABLE crée une nouvelle table. Les colonnes sont définies avec leur nom, type et contraintes. PRIMARY KEY identifie de manière unique chaque ligne.'),
                    'startCode' => '-- Créez la table produits
',
                    'solution' => 'CREATE TABLE produits (id INT PRIMARY KEY, nom VARCHAR(100), prix DECIMAL(10,2));',
                    'hint' => $getTranslated('hint', 'Utilisez CREATE TABLE produits (id INT PRIMARY KEY, nom VARCHAR(100), prix DECIMAL(10,2));')
                ],
                13 => [
                    'title' => $getTranslated('title', 'Contraintes et clés'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Écrivez une requête SQL pour créer une table avec une clé primaire, une clé étrangère, et une contrainte UNIQUE.'),
                    'description' => $getTranslated('description', 'Les contraintes SQL garantissent l\'intégrité des données. PRIMARY KEY identifie de manière unique, FOREIGN KEY lie à une autre table, UNIQUE garantit l\'unicité.'),
                    'startCode' => '-- Créez une table avec PRIMARY KEY, FOREIGN KEY et UNIQUE
',
                    'solution' => 'CREATE TABLE commandes (id INT PRIMARY KEY, utilisateur_id INT, email VARCHAR(100) UNIQUE, FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id));',
                    'hint' => $getTranslated('hint', 'Utilisez PRIMARY KEY (id), FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id), UNIQUE (email) dans CREATE TABLE.')
                ],
                14 => [
                    'title' => $getTranslated('title', 'Vues et index'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 25,
                    'instruction' => $getTranslated('instruction', 'Écrivez une requête SQL pour créer une vue "utilisateurs_actifs" et un index sur la colonne "email".'),
                    'description' => $getTranslated('description', 'Les vues SQL sont des requêtes sauvegardées qui se comportent comme des tables. Les index améliorent les performances de recherche. CREATE VIEW crée une vue, CREATE INDEX crée un index.'),
                    'startCode' => '-- Créez une vue et un index
',
                    'solution' => 'CREATE VIEW utilisateurs_actifs AS SELECT * FROM utilisateurs WHERE actif = 1; CREATE INDEX idx_email ON utilisateurs(email);',
                    'hint' => $getTranslated('hint', 'Utilisez CREATE VIEW utilisateurs_actifs AS SELECT * FROM utilisateurs WHERE actif = 1; et CREATE INDEX idx_email ON utilisateurs(email);')
                ],
                15 => [
                    'title' => $getTranslated('title', 'Requêtes complexes'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Écrivez une requête SQL complexe qui joint plusieurs tables, utilise GROUP BY, HAVING, et des fonctions d\'agrégation.'),
                    'description' => $getTranslated('description', 'Les requêtes complexes combinent plusieurs concepts SQL : JOIN, GROUP BY, HAVING, fonctions d\'agrégation, sous-requêtes. C\'est la maîtrise avancée de SQL.'),
                    'startCode' => '-- Créez une requête complexe avec JOIN, GROUP BY, HAVING
',
                    'solution' => 'SELECT u.ville, COUNT(c.id) as nb_commandes, SUM(c.montant) as total FROM utilisateurs u LEFT JOIN commandes c ON u.id = c.utilisateur_id GROUP BY u.ville HAVING COUNT(c.id) > 0;',
                    'hint' => $getTranslated('hint', 'Combinez SELECT, FROM, JOIN, WHERE, GROUP BY, HAVING, et des fonctions d\'agrégation dans une seule requête.')
                ],
            ],
            'c' => [
                1 => [
                    'title' => $getTranslated('title', 'Premier programme C'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Créez un programme C qui affiche "Bonjour C !" dans la console.'),
                    'description' => $getTranslated('description', 'En C, chaque programme doit avoir une fonction main(). On utilise printf() pour afficher du texte. stdio.h contient les fonctions d\'entrée/sortie.'),
                    'startCode' => '#include <stdio.h>

int main() {
    // Affichez "Bonjour C !"
    return 0;
}',
                    'solution' => '#include <stdio.h>

int main() {
    printf("Bonjour C !\\n");
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Utilisez printf("Bonjour C !\\n"); dans la fonction main().')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Variables et types'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Déclarez une variable age de type int avec la valeur 25 et affichez-la.'),
                    'description' => $getTranslated('description', 'En C, les variables doivent être déclarées avec un type. int est utilisé pour les nombres entiers. %d est le format pour afficher un entier avec printf().'),
                    'startCode' => '#include <stdio.h>

int main() {
    // Déclarez une variable age de type int avec la valeur 25
    // Affichez-la
    return 0;
}',
                    'solution' => '#include <stdio.h>

int main() {
    int age = 25;
    printf("Age : %d\\n", age);
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Utilisez int age = 25; puis printf("Age : %d\\n", age);')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Opérateurs arithmétiques'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Calculez la somme de 10 et 5, puis affichez le résultat.'),
                    'description' => $getTranslated('description', 'C supporte les opérateurs arithmétiques : + (addition), - (soustraction), * (multiplication), / (division), % (modulo).'),
                    'startCode' => '#include <stdio.h>

int main() {
    // Calculez la somme de 10 et 5
    // Affichez le résultat
    return 0;
}',
                    'solution' => '#include <stdio.h>

int main() {
    int resultat = 10 + 5;
    printf("%d\\n", resultat);
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Utilisez int resultat = 10 + 5; puis printf("%d\\n", resultat);')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Conditions if/else'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Créez une condition if/else qui affiche "Majeur" si l\'âge est >= 18, sinon "Mineur".'),
                    'description' => $getTranslated('description', 'Les conditions C utilisent if/else. Les opérateurs de comparaison (==, <, >, <=, >=, !=) comparent des valeurs.'),
                    'startCode' => '#include <stdio.h>

int main() {
    int age = 20;
    // Créez une condition if/else
    // Affichez "Majeur" si age >= 18, sinon "Mineur"
    return 0;
}',
                    'solution' => '#include <stdio.h>

int main() {
    int age = 20;
    if (age >= 18) {
        printf("Majeur\\n");
    } else {
        printf("Mineur\\n");
    }
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Utilisez if (age >= 18) { printf("Majeur\\n"); } else { printf("Mineur\\n"); }')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Boucles for et while'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Affichez les nombres de 1 à 5 en utilisant une boucle for.'),
                    'description' => $getTranslated('description', 'Les boucles C répètent du code. for (initialisation; condition; incrément) répète tant que la condition est vraie. while répète aussi mais l\'incrément doit être géré manuellement.'),
                    'startCode' => '#include <stdio.h>

int main() {
    // Affichez les nombres de 1 à 5 avec une boucle for
    return 0;
}',
                    'solution' => '#include <stdio.h>

int main() {
    for (int i = 1; i <= 5; i++) {
        printf("%d\\n", i);
    }
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Utilisez for (int i = 1; i <= 5; i++) { printf("%d\\n", i); }')
                ],
                6 => [
                    'title' => $getTranslated('title', 'Fonctions C'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Créez une fonction qui additionne deux nombres et retourne le résultat.'),
                    'description' => $getTranslated('description', 'Les fonctions C organisent le code réutilisable. int nomFonction(int a, int b) { return valeur; } définit une fonction. return retourne une valeur.'),
                    'startCode' => '#include <stdio.h>

// Créez une fonction qui additionne deux nombres
int main() {
    // Appelez la fonction et affichez le résultat
    return 0;
}',
                    'solution' => '#include <stdio.h>

int additionner(int a, int b) {
    return a + b;
}

int main() {
    int resultat = additionner(10, 5);
    printf("%d\\n", resultat);
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Créez int additionner(int a, int b) { return a + b; }')
                ],
                7 => [
                    'title' => $getTranslated('title', 'Tableaux'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Créez un tableau de 5 entiers et affichez chaque élément avec une boucle for.'),
                    'description' => $getTranslated('description', 'Les tableaux C stockent des collections ordonnées. int arr[5] crée un tableau de 5 entiers. Les tableaux ont une taille fixe.'),
                    'startCode' => '#include <stdio.h>

int main() {
    // Créez un tableau de 5 entiers
    // Affichez chaque élément avec une boucle for
    return 0;
}',
                    'solution' => '#include <stdio.h>

int main() {
    int arr[5] = {1, 2, 3, 4, 5};
    for (int i = 0; i < 5; i++) {
        printf("%d\\n", arr[i]);
    }
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Créez int arr[5] = {1, 2, 3, 4, 5}; puis utilisez for (int i = 0; i < 5; i++) pour parcourir.')
                ],
                8 => [
                    'title' => $getTranslated('title', 'Pointeurs de base'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Créez un pointeur vers une variable entière et affichez la valeur via le pointeur.'),
                    'description' => $getTranslated('description', 'Les pointeurs C stockent l\'adresse mémoire d\'une variable. int *ptr déclare un pointeur, &variable obtient l\'adresse, *ptr accède à la valeur.'),
                    'startCode' => '#include <stdio.h>

int main() {
    int x = 10;
    // Créez un pointeur vers x
    // Affichez la valeur via le pointeur
    return 0;
}',
                    'solution' => '#include <stdio.h>

int main() {
    int x = 10;
    int *ptr = &x;
    printf("%d\\n", *ptr);
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Utilisez int x = 10; int *ptr = &x; puis printf("%d\\n", *ptr);')
                ],
                9 => [
                    'title' => $getTranslated('title', 'Pointeurs et tableaux'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 22,
                    'instruction' => $getTranslated('instruction', 'Utilisez un pointeur pour parcourir un tableau et afficher tous les éléments.'),
                    'description' => $getTranslated('description', 'Les pointeurs et tableaux sont étroitement liés en C. Le nom d\'un tableau est un pointeur vers son premier élément. ptr++ avance au prochain élément.'),
                    'startCode' => '#include <stdio.h>

int main() {
    int arr[5] = {1, 2, 3, 4, 5};
    // Utilisez un pointeur pour parcourir le tableau
    return 0;
}',
                    'solution' => '#include <stdio.h>

int main() {
    int arr[5] = {1, 2, 3, 4, 5};
    int *ptr = arr;
    for (int i = 0; i < 5; i++) {
        printf("%d\\n", *ptr);
        ptr++;
    }
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Utilisez int arr[5] = {1, 2, 3, 4, 5}; int *ptr = arr; puis parcourez avec ptr++ et *ptr.')
                ],
                10 => [
                    'title' => $getTranslated('title', 'Structures (struct)'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Créez une structure Personne avec les champs nom et age, puis créez une variable de ce type.'),
                    'description' => $getTranslated('description', 'Les structures C permettent de regrouper des variables de types différents. struct Personne { } définit une structure. . accède aux champs.'),
                    'startCode' => '#include <stdio.h>

// Créez une structure Personne
int main() {
    // Créez une variable de type Personne et affichez ses champs
    return 0;
}',
                    'solution' => '#include <stdio.h>

struct Personne {
    char nom[50];
    int age;
};

int main() {
    struct Personne p = {"Jean", 30};
    printf("%s - %d\\n", p.nom, p.age);
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Créez struct Personne { char nom[50]; int age; }; puis struct Personne p = {"Jean", 30};')
                ],
                11 => [
                    'title' => $getTranslated('title', 'Allocation mémoire'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Allouez dynamiquement de la mémoire pour un tableau de 10 entiers, puis libérez-la.'),
                    'description' => $getTranslated('description', 'L\'allocation mémoire dynamique permet de créer des tableaux de taille variable. malloc() alloue, free() libère. stdlib.h contient ces fonctions.'),
                    'startCode' => '#include <stdio.h>
#include <stdlib.h>

int main() {
    // Allouez de la mémoire pour un tableau de 10 entiers
    // Libérez la mémoire
    return 0;
}',
                    'solution' => '#include <stdio.h>
#include <stdlib.h>

int main() {
    int *arr = (int*)malloc(10 * sizeof(int));
    if (arr != NULL) {
        for (int i = 0; i < 10; i++) {
            arr[i] = i + 1;
        }
        free(arr);
    }
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Utilisez int *arr = (int*)malloc(10 * sizeof(int)); puis free(arr);')
                ],
                12 => [
                    'title' => $getTranslated('title', 'Pointeurs avancés'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Créez un pointeur de pointeur (double pointeur) et utilisez-le pour modifier une variable.'),
                    'description' => $getTranslated('description', 'Les pointeurs de pointeurs stockent l\'adresse d\'un pointeur. int **ptr déclare un double pointeur. C\'est utile pour les tableaux multidimensionnels.'),
                    'startCode' => '#include <stdio.h>

int main() {
    int x = 10;
    // Créez un double pointeur et modifiez x
    return 0;
}',
                    'solution' => '#include <stdio.h>

int main() {
    int x = 10;
    int *ptr = &x;
    int **pptr = &ptr;
    **pptr = 20;
    printf("%d\\n", x);
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Utilisez int x = 10; int *ptr = &x; int **pptr = &ptr; puis **pptr = 20;')
                ],
                13 => [
                    'title' => $getTranslated('title', 'Fichiers et I/O'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Créez un fichier "test.txt" avec le contenu "Hello C", puis lisez et affichez son contenu.'),
                    'description' => $getTranslated('description', 'C peut lire et écrire des fichiers. fopen() ouvre un fichier, fwrite() écrit, fread() lit, fclose() ferme. FILE* représente un fichier.'),
                    'startCode' => '#include <stdio.h>

int main() {
    // Créez un fichier "test.txt" avec "Hello C"
    // Lisez et affichez son contenu
    return 0;
}',
                    'solution' => '#include <stdio.h>

int main() {
    FILE *f = fopen("test.txt", "w");
    if (f != NULL) {
        fprintf(f, "Hello C");
        fclose(f);
    }
    
    f = fopen("test.txt", "r");
    if (f != NULL) {
        char ligne[100];
        if (fgets(ligne, sizeof(ligne), f) != NULL) {
            printf("%s", ligne);
        }
        fclose(f);
    }
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Utilisez FILE *f = fopen("test.txt", "w"); fwrite("Hello C", 1, 7, f); fclose(f); puis lisez avec fopen("test.txt", "r");')
                ],
                14 => [
                    'title' => $getTranslated('title', 'Chaînes de caractères'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 25,
                    'instruction' => $getTranslated('instruction', 'Créez une chaîne de caractères, copiez-la dans une autre chaîne, puis concaténez deux chaînes.'),
                    'description' => $getTranslated('description', 'Les chaînes C sont des tableaux de caractères terminés par \\0. string.h contient strcpy() pour copier, strcat() pour concaténer, strlen() pour la longueur.'),
                    'startCode' => '#include <stdio.h>
#include <string.h>

int main() {
    // Créez une chaîne, copiez-la, puis concaténez
    return 0;
}',
                    'solution' => '#include <stdio.h>
#include <string.h>

int main() {
    char str1[50] = "Hello";
    char str2[50];
    strcpy(str2, str1);
    strcat(str1, " World");
    printf("%s\\n", str1);
    printf("%s\\n", str2);
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Utilisez char str1[50] = "Hello"; char str2[50]; strcpy(str2, str1); strcat(str1, " World");')
                ],
                15 => [
                    'title' => $getTranslated('title', 'Programmation système'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Créez un programme qui utilise des appels système pour créer un processus enfant avec fork().'),
                    'description' => $getTranslated('description', 'La programmation système C permet d\'interagir avec le système d\'exploitation. fork() crée un processus enfant, exec() exécute un programme, wait() attend un processus.'),
                    'startCode' => '#include <stdio.h>
#include <unistd.h>
#include <sys/wait.h>

int main() {
    // Créez un processus enfant avec fork()
    return 0;
}',
                    'solution' => '#include <stdio.h>
#include <unistd.h>
#include <sys/wait.h>

int main() {
    pid_t pid = fork();
    if (pid == 0) {
        printf("Processus enfant\\n");
    } else if (pid > 0) {
        wait(NULL);
        printf("Processus parent\\n");
    }
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Utilisez pid_t pid = fork(); pour créer un processus enfant. Vérifiez pid pour distinguer le parent et l\'enfant.')
                ],
            ],
            'cpp' => [
                1 => [
                    'title' => $getTranslated('title', 'Premier programme C++'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Créez un programme C++ qui affiche "Bonjour C++ !" dans la console.'),
                    'description' => $getTranslated('description', 'En C++, on utilise iostream et cout pour afficher du texte. using namespace std; permet d\'utiliser cout sans std::.'),
                    'startCode' => '#include <iostream>
using namespace std;

int main() {
    // Affichez "Bonjour C++ !"
    return 0;
}',
                    'solution' => '#include <iostream>
using namespace std;

int main() {
    cout << "Bonjour C++ !" << endl;
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Utilisez cout << "Bonjour C++ !" << endl; dans la fonction main().')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Variables et types'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Déclarez une variable age de type int avec la valeur 25 et affichez-la.'),
                    'description' => $getTranslated('description', 'En C++, les variables sont déclarées comme en C. On utilise cout pour afficher.'),
                    'startCode' => '#include <iostream>
using namespace std;

int main() {
    // Déclarez une variable age de type int avec la valeur 25
    // Affichez-la
    return 0;
}',
                    'solution' => '#include <iostream>
using namespace std;

int main() {
    int age = 25;
    cout << "Age : " << age << endl;
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Utilisez int age = 25; puis cout << "Age : " << age << endl;')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Opérateurs arithmétiques'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Calculez la somme de deux nombres (10 et 5) et affichez le résultat.'),
                    'description' => $getTranslated('description', 'En C++, utilisez les opérateurs arithmétiques +, -, *, / pour effectuer des calculs.'),
                    'startCode' => '#include <iostream>
using namespace std;

int main() {
    int a = 10;
    int b = 5;
    // Calculez et affichez la somme de a et b
    return 0;
}',
                    'solution' => '#include <iostream>
using namespace std;

int main() {
    int a = 10;
    int b = 5;
    int somme = a + b;
    cout << "Somme : " << somme << endl;
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Utilisez int somme = a + b; puis affichez le résultat avec cout.')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Conditions if/else'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Vérifiez si un nombre est pair ou impair et affichez le résultat.'),
                    'description' => $getTranslated('description', 'Utilisez l\'opérateur modulo % pour vérifier si un nombre est divisible par 2.'),
                    'startCode' => '#include <iostream>
using namespace std;

int main() {
    int nombre = 7;
    // Vérifiez si nombre est pair ou impair
    return 0;
}',
                    'solution' => '#include <iostream>
using namespace std;

int main() {
    int nombre = 7;
    if (nombre % 2 == 0) {
        cout << nombre << " est pair" << endl;
    } else {
        cout << nombre << " est impair" << endl;
    }
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Utilisez if (nombre % 2 == 0) pour vérifier si le nombre est pair.')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Boucles for et while'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Affichez les nombres de 1 à 10 en utilisant une boucle for.'),
                    'description' => $getTranslated('description', 'Les boucles for permettent de répéter du code un nombre défini de fois.'),
                    'startCode' => '#include <iostream>
using namespace std;

int main() {
    // Utilisez une boucle for pour afficher les nombres de 1 à 10
    return 0;
}',
                    'solution' => '#include <iostream>
using namespace std;

int main() {
    for (int i = 1; i <= 10; i++) {
        cout << i << " ";
    }
    cout << endl;
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Utilisez for (int i = 1; i <= 10; i++) pour itérer de 1 à 10.')
                ],
                6 => [
                    'title' => $getTranslated('title', 'Fonctions C++'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Créez une fonction qui calcule la somme de deux nombres et retourne le résultat.'),
                    'description' => $getTranslated('description', 'Les fonctions permettent de réutiliser du code. Déclarez le type de retour, le nom de la fonction et ses paramètres.'),
                    'startCode' => '#include <iostream>
using namespace std;

// Créez une fonction somme qui prend deux int et retourne leur somme
int main() {
    int resultat = somme(10, 5);
    cout << "Résultat : " << resultat << endl;
    return 0;
}',
                    'solution' => '#include <iostream>
using namespace std;

int somme(int a, int b) {
    return a + b;
}

int main() {
    int resultat = somme(10, 5);
    cout << "Résultat : " << resultat << endl;
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Créez int somme(int a, int b) { return a + b; } avant main().')
                ],
                7 => [
                    'title' => $getTranslated('title', 'Classes et objets'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Créez une classe Personne avec un attribut nom et une méthode afficher().'),
                    'description' => $getTranslated('description', 'Les classes permettent de créer des types personnalisés avec des attributs et des méthodes.'),
                    'startCode' => '#include <iostream>
#include <string>
using namespace std;

// Créez une classe Personne avec nom et méthode afficher()
int main() {
    Personne p("Bassirou");
    p.afficher();
    return 0;
}',
                    'solution' => '#include <iostream>
#include <string>
using namespace std;

class Personne {
private:
    string nom;
public:
    Personne(string n) : nom(n) {}
    void afficher() {
        cout << "Nom : " << nom << endl;
    }
};

int main() {
    Personne p("Bassirou");
    p.afficher();
    return 0;
}',
                    'hint' => $getTranslated('hint', 'Créez class Personne { private: string nom; public: Personne(string n) : nom(n) {} void afficher() { ... } };')
                ],
            ],
            'csharp' => [
                1 => [
                    'title' => $getTranslated('title', 'Premier programme C#'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Créez un programme C# qui affiche "Bonjour C# !" dans la console.'),
                    'description' => $getTranslated('description', 'En C#, on utilise System.Console.WriteLine() pour afficher du texte. Chaque programme C# a une classe avec une méthode Main().'),
                    'startCode' => 'using System;

class Program {
    static void Main() {
        // Affichez "Bonjour C# !"
    }
}',
                    'solution' => 'using System;

class Program {
    static void Main() {
        Console.WriteLine("Bonjour C# !");
    }
}',
                    'hint' => $getTranslated('hint', 'Utilisez Console.WriteLine("Bonjour C# !"); dans la méthode Main().')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Variables et types'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Déclarez une variable age de type int avec la valeur 25 et affichez-la.'),
                    'description' => $getTranslated('description', 'En C#, les variables sont typées. int est utilisé pour les nombres entiers. Console.WriteLine() peut afficher directement les variables.'),
                    'startCode' => 'using System;

class Program {
    static void Main() {
        // Déclarez une variable age de type int avec la valeur 25
        // Affichez-la
    }
}',
                    'solution' => 'using System;

class Program {
    static void Main() {
        int age = 25;
        Console.WriteLine("Age : " + age);
    }
}',
                    'hint' => $getTranslated('hint', 'Utilisez int age = 25; puis Console.WriteLine("Age : " + age);')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Opérateurs arithmétiques'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Calculez la somme de deux nombres (10 et 5) et affichez le résultat.'),
                    'description' => $getTranslated('description', 'En C#, utilisez les opérateurs arithmétiques +, -, *, / pour effectuer des calculs.'),
                    'startCode' => 'using System;

class Program {
    static void Main() {
        int a = 10;
        int b = 5;
        // Calculez et affichez la somme de a et b
    }
}',
                    'solution' => 'using System;

class Program {
    static void Main() {
        int a = 10;
        int b = 5;
        int somme = a + b;
        Console.WriteLine("Somme : " + somme);
    }
}',
                    'hint' => $getTranslated('hint', 'Utilisez int somme = a + b; puis affichez le résultat avec Console.WriteLine.')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Conditions if/else'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Vérifiez si un nombre est pair ou impair et affichez le résultat.'),
                    'description' => $getTranslated('description', 'Utilisez l\'opérateur modulo % pour vérifier si un nombre est divisible par 2.'),
                    'startCode' => 'using System;

class Program {
    static void Main() {
        int nombre = 7;
        // Vérifiez si nombre est pair ou impair
    }
}',
                    'solution' => 'using System;

class Program {
    static void Main() {
        int nombre = 7;
        if (nombre % 2 == 0) {
            Console.WriteLine(nombre + " est pair");
        } else {
            Console.WriteLine(nombre + " est impair");
        }
    }
}',
                    'hint' => $getTranslated('hint', 'Utilisez if (nombre % 2 == 0) pour vérifier si le nombre est pair.')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Boucles for et while'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Affichez les nombres de 1 à 10 en utilisant une boucle for.'),
                    'description' => $getTranslated('description', 'Les boucles for permettent de répéter du code un nombre défini de fois.'),
                    'startCode' => 'using System;

class Program {
    static void Main() {
        // Utilisez une boucle for pour afficher les nombres de 1 à 10
    }
}',
                    'solution' => 'using System;

class Program {
    static void Main() {
        for (int i = 1; i <= 10; i++) {
            Console.Write(i + " ");
        }
        Console.WriteLine();
    }
}',
                    'hint' => $getTranslated('hint', 'Utilisez for (int i = 1; i <= 10; i++) pour itérer de 1 à 10.')
                ],
                6 => [
                    'title' => $getTranslated('title', 'Méthodes C#'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Créez une méthode qui calcule la somme de deux nombres et retourne le résultat.'),
                    'description' => $getTranslated('description', 'Les méthodes permettent de réutiliser du code. Déclarez le type de retour, le nom de la méthode et ses paramètres.'),
                    'startCode' => 'using System;

class Program {
    // Créez une méthode Somme qui prend deux int et retourne leur somme
    static void Main() {
        int resultat = Somme(10, 5);
        Console.WriteLine("Résultat : " + resultat);
    }
}',
                    'solution' => 'using System;

class Program {
    static int Somme(int a, int b) {
        return a + b;
    }
    
    static void Main() {
        int resultat = Somme(10, 5);
        Console.WriteLine("Résultat : " + resultat);
    }
}',
                    'hint' => $getTranslated('hint', 'Créez static int Somme(int a, int b) { return a + b; } dans la classe Program.')
                ],
                7 => [
                    'title' => $getTranslated('title', 'Classes et objets'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Créez une classe Personne avec une propriété Nom et une méthode Afficher().'),
                    'description' => $getTranslated('description', 'Les classes permettent de créer des types personnalisés avec des propriétés et des méthodes.'),
                    'startCode' => 'using System;

// Créez une classe Personne avec Nom et méthode Afficher()
class Program {
    static void Main() {
        Personne p = new Personne("Bassirou");
        p.Afficher();
    }
}',
                    'solution' => 'using System;

class Personne {
    public string Nom { get; set; }
    
    public Personne(string nom) {
        Nom = nom;
    }
    
    public void Afficher() {
        Console.WriteLine("Nom : " + Nom);
    }
}

class Program {
    static void Main() {
        Personne p = new Personne("Bassirou");
        p.Afficher();
    }
}',
                    'hint' => $getTranslated('hint', 'Créez class Personne { public string Nom { get; set; } public Personne(string nom) { ... } public void Afficher() { ... } }')
                ],
            ],
            'dart' => [
                1 => [
                    'title' => $getTranslated('title', 'Premier programme Dart'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Créez un programme Dart qui affiche "Bonjour Dart !" dans la console.'),
                    'description' => $getTranslated('description', 'En Dart, on utilise print() pour afficher du texte. Chaque programme Dart a une fonction main().'),
                    'startCode' => 'void main() {
    // Affichez "Bonjour Dart !"
}',
                    'solution' => 'void main() {
    print("Bonjour Dart !");
}',
                    'hint' => $getTranslated('hint', 'Utilisez print("Bonjour Dart !"); dans la fonction main().')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Variables et types'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Déclarez une variable age de type int avec la valeur 25 et affichez-la.'),
                    'description' => $getTranslated('description', 'En Dart, les variables peuvent être déclarées avec var (inférence de type) ou explicitement avec int. print() peut afficher directement les variables.'),
                    'startCode' => 'void main() {
    // Déclarez une variable age de type int avec la valeur 25
    // Affichez-la
}',
                    'solution' => 'void main() {
    int age = 25;
    print("Age : $age");
}',
                    'hint' => $getTranslated('hint', 'Utilisez int age = 25; puis print("Age : $age"); (interpolation de chaîne)')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Opérateurs arithmétiques'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Calculez la somme de deux nombres (10 et 5) et affichez le résultat.'),
                    'description' => $getTranslated('description', 'En Dart, utilisez les opérateurs arithmétiques +, -, *, / pour effectuer des calculs.'),
                    'startCode' => 'void main() {
  int a = 10;
  int b = 5;
  // Calculez et affichez la somme de a et b
}',
                    'solution' => 'void main() {
  int a = 10;
  int b = 5;
  int somme = a + b;
  print("Somme : $somme");
}',
                    'hint' => $getTranslated('hint', 'Utilisez int somme = a + b; puis affichez le résultat avec print("Somme : $somme").')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Conditions if/else'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Vérifiez si un nombre est pair ou impair et affichez le résultat.'),
                    'description' => $getTranslated('description', 'Utilisez l\'opérateur modulo % pour vérifier si un nombre est divisible par 2.'),
                    'startCode' => 'void main() {
  int nombre = 7;
  // Vérifiez si nombre est pair ou impair
}',
                    'solution' => 'void main() {
  int nombre = 7;
  if (nombre % 2 == 0) {
    print("$nombre est pair");
  } else {
    print("$nombre est impair");
  }
}',
                    'hint' => $getTranslated('hint', 'Utilisez if (nombre % 2 == 0) pour vérifier si le nombre est pair.')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Boucles for et while'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Affichez les nombres de 1 à 10 en utilisant une boucle for.'),
                    'description' => $getTranslated('description', 'Les boucles for permettent de répéter du code un nombre défini de fois.'),
                    'startCode' => 'void main() {
  // Utilisez une boucle for pour afficher les nombres de 1 à 10
}',
                    'solution' => 'void main() {
  for (int i = 1; i <= 10; i++) {
    print(i);
  }
}',
                    'hint' => $getTranslated('hint', 'Utilisez for (int i = 1; i <= 10; i++) pour itérer de 1 à 10.')
                ],
                6 => [
                    'title' => $getTranslated('title', 'Fonctions Dart'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Créez une fonction qui calcule la somme de deux nombres et retourne le résultat.'),
                    'description' => $getTranslated('description', 'Les fonctions permettent de réutiliser du code. Déclarez le type de retour, le nom de la fonction et ses paramètres.'),
                    'startCode' => '// Créez une fonction somme qui prend deux int et retourne leur somme
void main() {
  int resultat = somme(10, 5);
  print("Résultat : $resultat");
}',
                    'solution' => 'int somme(int a, int b) {
  return a + b;
}

void main() {
  int resultat = somme(10, 5);
  print("Résultat : $resultat");
}',
                    'hint' => $getTranslated('hint', 'Créez int somme(int a, int b) { return a + b; } avant main().')
                ],
                7 => [
                    'title' => $getTranslated('title', 'Classes et objets'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Créez une classe Personne avec un attribut nom et une méthode afficher().'),
                    'description' => $getTranslated('description', 'Les classes permettent de créer des types personnalisés avec des attributs et des méthodes.'),
                    'startCode' => '// Créez une classe Personne avec nom et méthode afficher()
void main() {
  Personne p = Personne("Bassirou");
  p.afficher();
}',
                    'solution' => 'class Personne {
  String nom;
  
  Personne(this.nom);
  
  void afficher() {
    print("Nom : $nom");
  }
}

void main() {
  Personne p = Personne("Bassirou");
  p.afficher();
}',
                    'hint' => $getTranslated('hint', 'Créez class Personne { String nom; Personne(this.nom); void afficher() { ... } }')
                ],
            ],
            'go' => [
                1 => [
                    'title' => $getTranslated('title', 'Premier programme Go'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Créez un programme Go qui affiche "Bonjour Go !" dans la console.'),
                    'description' => $getTranslated('description', 'En Go, on utilise fmt.Println() pour afficher du texte. Chaque programme Go a une fonction main() dans le package main.'),
                    'startCode' => 'package main

import "fmt"

func main() {
    // Affichez "Bonjour Go !"
}',
                    'solution' => 'package main

import "fmt"

func main() {
    fmt.Println("Bonjour Go !")
}',
                    'hint' => $getTranslated('hint', 'Utilisez fmt.Println("Bonjour Go !") dans la fonction main().')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Variables et types'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Déclarez une variable age de type int avec la valeur 25 et affichez-la.'),
                    'description' => $getTranslated('description', 'En Go, les variables peuvent être déclarées avec var ou := (déclaration courte).'),
                    'startCode' => 'package main

import "fmt"

func main() {
    // Déclarez une variable age de type int avec la valeur 25
    // Affichez-la
}',
                    'solution' => 'package main

import "fmt"

func main() {
    var age int = 25
    fmt.Println("Age :", age)
}',
                    'hint' => $getTranslated('hint', 'Utilisez var age int = 25; puis fmt.Println("Age :", age)')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Opérateurs arithmétiques'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Calculez la somme de deux nombres (10 et 5) et affichez le résultat.'),
                    'description' => $getTranslated('description', 'En Go, utilisez les opérateurs arithmétiques +, -, *, / pour effectuer des calculs.'),
                    'startCode' => 'package main

import "fmt"

func main() {
    a := 10
    b := 5
    // Calculez et affichez la somme de a et b
}',
                    'solution' => 'package main

import "fmt"

func main() {
    a := 10
    b := 5
    somme := a + b
    fmt.Println("Somme :", somme)
}',
                    'hint' => $getTranslated('hint', 'Utilisez somme := a + b; puis fmt.Println("Somme :", somme)')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Conditions if/else'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Vérifiez si un nombre est pair ou impair et affichez le résultat.'),
                    'description' => $getTranslated('description', 'Utilisez l\'opérateur modulo % pour vérifier si un nombre est divisible par 2.'),
                    'startCode' => 'package main

import "fmt"

func main() {
    nombre := 7
    // Vérifiez si nombre est pair ou impair
}',
                    'solution' => 'package main

import "fmt"

func main() {
    nombre := 7
    if nombre%2 == 0 {
        fmt.Println(nombre, "est pair")
    } else {
        fmt.Println(nombre, "est impair")
    }
}',
                    'hint' => $getTranslated('hint', 'Utilisez if nombre%2 == 0 pour vérifier si le nombre est pair.')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Boucles for'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Affichez les nombres de 1 à 10 en utilisant une boucle for.'),
                    'description' => $getTranslated('description', 'En Go, la boucle for est la seule boucle disponible, mais elle peut être utilisée de différentes façons.'),
                    'startCode' => 'package main

import "fmt"

func main() {
    // Utilisez une boucle for pour afficher les nombres de 1 à 10
}',
                    'solution' => 'package main

import "fmt"

func main() {
    for i := 1; i <= 10; i++ {
        fmt.Println(i)
    }
}',
                    'hint' => $getTranslated('hint', 'Utilisez for i := 1; i <= 10; i++ pour itérer de 1 à 10.')
                ],
            ],
            'rust' => [
                1 => [
                    'title' => $getTranslated('title', 'Premier programme Rust'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Créez un programme Rust qui affiche "Bonjour Rust !" dans la console.'),
                    'description' => $getTranslated('description', 'En Rust, on utilise println!() pour afficher du texte. Chaque programme Rust a une fonction main().'),
                    'startCode' => 'fn main() {
    // Affichez "Bonjour Rust !"
}',
                    'solution' => 'fn main() {
    println!("Bonjour Rust !");
}',
                    'hint' => $getTranslated('hint', 'Utilisez println!("Bonjour Rust !"); dans la fonction main().')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Variables et mutabilité'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Déclarez une variable age de type i32 avec la valeur 25 et affichez-la.'),
                    'description' => $getTranslated('description', 'En Rust, les variables sont immuables par défaut. Utilisez mut pour rendre une variable mutable.'),
                    'startCode' => 'fn main() {
    // Déclarez une variable age de type i32 avec la valeur 25
    // Affichez-la
}',
                    'solution' => 'fn main() {
    let age: i32 = 25;
    println!("Age : {}", age);
}',
                    'hint' => $getTranslated('hint', 'Utilisez let age: i32 = 25; puis println!("Age : {}", age)')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Types de données'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Déclarez une variable nom de type String avec la valeur "Rust" et affichez-la.'),
                    'description' => $getTranslated('description', 'En Rust, String est un type de chaîne de caractères possédée, tandis que &str est une référence.'),
                    'startCode' => 'fn main() {
    // Déclarez une variable nom de type String avec la valeur "Rust"
    // Affichez-la
}',
                    'solution' => 'fn main() {
    let nom = String::from("Rust");
    println!("Nom : {}", nom);
}',
                    'hint' => $getTranslated('hint', 'Utilisez let nom = String::from("Rust"); puis println!("Nom : {}", nom)')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Conditions if/else'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Vérifiez si un nombre est pair ou impair et affichez le résultat.'),
                    'description' => $getTranslated('description', 'Utilisez l\'opérateur modulo % pour vérifier si un nombre est divisible par 2.'),
                    'startCode' => 'fn main() {
    let nombre = 7;
    // Vérifiez si nombre est pair ou impair
}',
                    'solution' => 'fn main() {
    let nombre = 7;
    if nombre % 2 == 0 {
        println!("{} est pair", nombre);
    } else {
        println!("{} est impair", nombre);
    }
}',
                    'hint' => $getTranslated('hint', 'Utilisez if nombre % 2 == 0 pour vérifier si le nombre est pair.')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Boucles loop/while/for'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Affichez les nombres de 1 à 10 en utilisant une boucle for.'),
                    'description' => $getTranslated('description', 'En Rust, on peut utiliser loop, while, ou for pour créer des boucles.'),
                    'startCode' => 'fn main() {
    // Utilisez une boucle for pour afficher les nombres de 1 à 10
}',
                    'solution' => 'fn main() {
    for i in 1..=10 {
        println!("{}", i);
    }
}',
                    'hint' => $getTranslated('hint', 'Utilisez for i in 1..=10 pour itérer de 1 à 10 inclus.')
                ],
            ],
            'ruby' => [
                1 => [
                    'title' => $getTranslated('title', 'Premier programme Ruby'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Créez un programme Ruby qui affiche "Bonjour Ruby !" dans la console.'),
                    'description' => $getTranslated('description', 'En Ruby, on utilise puts ou print pour afficher du texte. Pas besoin de fonction main().'),
                    'startCode' => '# Affichez "Bonjour Ruby !"
',
                    'solution' => 'puts "Bonjour Ruby !"',
                    'hint' => $getTranslated('hint', 'Utilisez puts "Bonjour Ruby !" pour afficher le message.')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Variables et types'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Créez une variable age avec la valeur 25 et affichez-la.'),
                    'description' => $getTranslated('description', 'En Ruby, les variables sont créées simplement en leur assignant une valeur. Pas besoin de déclarer le type.'),
                    'startCode' => '# Créez une variable age avec la valeur 25
# Affichez-la
',
                    'solution' => 'age = 25
puts "Age : #{age}"',
                    'hint' => $getTranslated('hint', 'Utilisez age = 25; puis puts "Age : #{age}" (interpolation de chaîne)')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Opérateurs arithmétiques'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Calculez la somme de deux nombres (10 et 5) et affichez le résultat.'),
                    'description' => $getTranslated('description', 'En Ruby, utilisez les opérateurs arithmétiques +, -, *, / pour effectuer des calculs.'),
                    'startCode' => 'a = 10
b = 5
# Calculez et affichez la somme de a et b
',
                    'solution' => 'a = 10
b = 5
somme = a + b
puts "Somme : #{somme}"',
                    'hint' => $getTranslated('hint', 'Utilisez somme = a + b; puis puts "Somme : #{somme}"')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Conditions if/elsif/else'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Vérifiez si un nombre est pair ou impair et affichez le résultat.'),
                    'description' => $getTranslated('description', 'Utilisez l\'opérateur modulo % pour vérifier si un nombre est divisible par 2.'),
                    'startCode' => 'nombre = 7
# Vérifiez si nombre est pair ou impair
',
                    'solution' => 'nombre = 7
if nombre % 2 == 0
  puts "#{nombre} est pair"
else
  puts "#{nombre} est impair"
end',
                    'hint' => $getTranslated('hint', 'Utilisez if nombre % 2 == 0 pour vérifier si le nombre est pair.')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Boucles while/for/each'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Affichez les nombres de 1 à 10 en utilisant une boucle for.'),
                    'description' => $getTranslated('description', 'En Ruby, on peut utiliser while, for, ou each pour créer des boucles.'),
                    'startCode' => '# Utilisez une boucle for pour afficher les nombres de 1 à 10
',
                    'solution' => 'for i in 1..10
  puts i
end',
                    'hint' => $getTranslated('hint', 'Utilisez for i in 1..10 pour itérer de 1 à 10.')
                ],
            ],
            'cybersecurite' => [
                1 => [
                    'title' => $getTranslated('title', 'Introduction à la Cybersécurité'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Expliquez les concepts fondamentaux de la cybersécurité et identifiez les principales menaces informatiques.'),
                    'description' => $getTranslated('description', 'La cybersécurité est la pratique de protéger les systèmes, réseaux et programmes contre les attaques numériques. Elle vise à réduire les risques et à protéger contre l\'exploitation non autorisée des systèmes, réseaux et technologies.'),
                    'startCode' => '# Exercice : Introduction à la Cybersécurité
# 
# 1. Définissez ce qu\'est la cybersécurité
# 2. Listez 3 types de menaces courantes
# 3. Expliquez pourquoi la cybersécurité est importante
',
                    'solution' => '# Solution :
# 1. La cybersécurité est la protection des systèmes informatiques contre les accès non autorisés
# 2. Types de menaces : Malware, Phishing, DDoS
# 3. Elle protège les données sensibles et maintient la continuité des activités',
                    'hint' => $getTranslated('hint', 'Pensez aux différents types d\'attaques : virus, ransomware, hameçonnage, etc.')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Principes de Sécurité (CIA)'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Expliquez la triade CIA (Confidentialité, Intégrité, Disponibilité) et donnez un exemple pour chaque principe.'),
                    'description' => $getTranslated('description', 'La triade CIA est le modèle fondamental de la sécurité de l\'information. Elle représente les trois objectifs principaux de la sécurité : Confidentialité (protection des données), Intégrité (exactitude des données), et Disponibilité (accès aux données).'),
                    'startCode' => '# Exercice : Triade CIA
# 
# Expliquez chaque principe avec un exemple concret :
# - Confidentialité :
# - Intégrité :
# - Disponibilité :
',
                    'solution' => '# Solution :
# - Confidentialité : Chiffrement des mots de passe (ex: bcrypt)
# - Intégrité : Hachage SHA-256 pour vérifier l\'intégrité des fichiers
# - Disponibilité : Redondance des serveurs pour éviter les pannes',
                    'hint' => $getTranslated('hint', 'Confidentialité = secret, Intégrité = non modifié, Disponibilité = accessible')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Identification des Menaces'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Identifiez et classez différents types de menaces de cybersécurité (malware, phishing, DDoS, etc.).'),
                    'description' => $getTranslated('description', 'L\'identification des menaces est cruciale pour la sécurité. Les menaces peuvent être classées par type (malware, attaques réseau, ingénierie sociale) et par niveau de risque.'),
                    'startCode' => '# Exercice : Identification des Menaces
# 
# Classez ces menaces par catégorie :
# - Virus, Ransomware, Trojan
# - Phishing, Spear Phishing
# - DDoS, Man-in-the-Middle
# - SQL Injection, XSS
',
                    'solution' => '# Solution :
# Malware : Virus, Ransomware, Trojan
# Ingénierie sociale : Phishing, Spear Phishing
# Attaques réseau : DDoS, Man-in-the-Middle
# Vulnérabilités web : SQL Injection, XSS',
                    'hint' => $getTranslated('hint', 'Pensez aux différentes catégories : logiciels malveillants, attaques réseau, vulnérabilités applicatives')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Cryptographie de Base'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Expliquez les concepts de base de la cryptographie : chiffrement symétrique vs asymétrique, hachage.'),
                    'description' => $getTranslated('description', 'La cryptographie est la science du chiffrement des données. Le chiffrement symétrique utilise une seule clé, tandis que le chiffrement asymétrique utilise une paire de clés (publique/privée). Le hachage transforme les données en une empreinte unique.'),
                    'startCode' => '# Exercice : Cryptographie
# 
# Comparez :
# 1. Chiffrement symétrique (AES) :
# 2. Chiffrement asymétrique (RSA) :
# 3. Hachage (SHA-256) :
',
                    'solution' => '# Solution :
# 1. AES : Une seule clé pour chiffrer/déchiffrer (rapide, pour gros volumes)
# 2. RSA : Paire clé publique/privée (sécurisé, pour échange de clés)
# 3. SHA-256 : Fonction à sens unique (vérification d\'intégrité, mots de passe)',
                    'hint' => $getTranslated('hint', 'Symétrique = même clé, Asymétrique = deux clés, Hachage = irréversible')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Sécurité des Réseaux'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Expliquez les mécanismes de sécurité réseau : firewall, VPN, IDS/IPS.'),
                    'description' => $getTranslated('description', 'La sécurité réseau implique la protection de l\'infrastructure réseau contre les accès non autorisés, les attaques et les intrusions. Les firewalls filtrent le trafic, les VPN créent des tunnels sécurisés, et les IDS/IPS détectent et préviennent les intrusions.'),
                    'startCode' => '# Exercice : Sécurité Réseau
# 
# Expliquez le rôle de chaque outil :
# - Firewall :
# - VPN :
# - IDS/IPS :
',
                    'solution' => '# Solution :
# - Firewall : Filtre le trafic entrant/sortant selon des règles (port, IP, protocole)
# - VPN : Tunnel chiffré pour connexion sécurisée à distance
# - IDS/IPS : Détection (IDS) et prévention (IPS) d\'intrusions en temps réel',
                    'hint' => $getTranslated('hint', 'Firewall = filtre, VPN = tunnel sécurisé, IDS/IPS = détection/prévention')
                ],
                6 => [
                    'title' => $getTranslated('title', 'Sécurité des Applications'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Expliquez les bonnes pratiques de sécurité applicative : validation des entrées, gestion des erreurs, authentification.'),
                    'description' => $getTranslated('description', 'La sécurité applicative protège les applications contre les vulnérabilités. Les bonnes pratiques incluent la validation des entrées utilisateur, la gestion sécurisée des erreurs, l\'authentification robuste et la protection contre les injections.'),
                    'startCode' => '# Exercice : Sécurité Applicative
# 
# Listez 5 bonnes pratiques :
# 1.
# 2.
# 3.
# 4.
# 5.
',
                    'solution' => '# Solution :
# 1. Validation et sanitisation des entrées utilisateur
# 2. Authentification forte (2FA, mots de passe complexes)
# 3. Gestion sécurisée des sessions
# 4. Protection contre les injections (SQL, XSS)
# 5. Gestion d\'erreurs sans exposer d\'informations sensibles',
                    'hint' => $getTranslated('hint', 'Pensez aux vulnérabilités OWASP Top 10')
                ],
                7 => [
                    'title' => $getTranslated('title', 'Gestion des Incidents'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 22,
                    'instruction' => $getTranslated('instruction', 'Décrivez le processus de gestion d\'incident de sécurité : détection, réponse, récupération.'),
                    'description' => $getTranslated('description', 'La gestion des incidents suit un cycle : préparation, détection, analyse, confinement, éradication, récupération et leçons apprises. Une réponse rapide minimise les dommages.'),
                    'startCode' => '# Exercice : Gestion d\'Incidents
# 
# Étapes du processus :
# 1. Détection :
# 2. Analyse :
# 3. Confinement :
# 4. Récupération :
',
                    'solution' => '# Solution :
# 1. Détection : Identification de l\'incident (logs, alertes, monitoring)
# 2. Analyse : Évaluation de la portée et de l\'impact
# 3. Confinement : Isolation des systèmes affectés
# 4. Récupération : Restauration des systèmes et vérification',
                    'hint' => $getTranslated('hint', 'Pensez au cycle complet de réponse à incident')
                ],
                8 => [
                    'title' => $getTranslated('title', 'Législation et Conformité'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Expliquez les principales réglementations : RGPD, PCI-DSS, ISO 27001.'),
                    'description' => $getTranslated('description', 'La conformité réglementaire est essentielle. Le RGPD protège les données personnelles en UE, PCI-DSS sécurise les transactions de cartes, et ISO 27001 définit un système de management de la sécurité.'),
                    'startCode' => '# Exercice : Conformité
# 
# Associez chaque réglementation à son domaine :
# - RGPD :
# - PCI-DSS :
# - ISO 27001 :
',
                    'solution' => '# Solution :
# - RGPD : Protection des données personnelles (UE)
# - PCI-DSS : Sécurité des transactions par carte bancaire
# - ISO 27001 : Système de management de la sécurité de l\'information',
                    'hint' => $getTranslated('hint', 'RGPD = données personnelles, PCI-DSS = paiements, ISO 27001 = SMSI')
                ],
                9 => [
                    'title' => $getTranslated('title', 'Analyse de Vulnérabilités'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Expliquez les méthodes d\'analyse de vulnérabilités : scanning, pentesting, code review.'),
                    'description' => $getTranslated('description', 'L\'analyse de vulnérabilités identifie les faiblesses avant exploitation. Les méthodes incluent le scanning automatisé, les tests de pénétration manuels, et la revue de code pour détecter les failles.'),
                    'startCode' => '# Exercice : Analyse Vulnérabilités
# 
# Comparez les méthodes :
# - Scanning automatisé :
# - Pentesting :
# - Code review :
',
                    'solution' => '# Solution :
# - Scanning : Outils automatisés (Nessus, OpenVAS) pour détecter vulnérabilités connues
# - Pentesting : Tests manuels simulant des attaques réelles
# - Code review : Analyse statique du code source pour détecter failles',
                    'hint' => $getTranslated('hint', 'Scanning = automatique, Pentesting = manuel, Code review = analyse statique')
                ],
                10 => [
                    'title' => $getTranslated('title', 'Hacking Éthique'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Expliquez les principes du hacking éthique et les phases d\'un test de pénétration.'),
                    'description' => $getTranslated('description', 'Le hacking éthique utilise les techniques des attaquants à des fins défensives. Les phases incluent la reconnaissance, le scanning, l\'accès, le maintien et l\'effacement des traces, toujours avec autorisation.'),
                    'startCode' => '# Exercice : Hacking Éthique
# 
# Phases d\'un pentest :
# 1. Reconnaissance :
# 2. Scanning :
# 3. Accès :
# 4. Maintien :
',
                    'solution' => '# Solution :
# 1. Reconnaissance : Collecte d\'informations (OSINT, réseaux sociaux)
# 2. Scanning : Identification des ports ouverts, services, vulnérabilités
# 3. Accès : Exploitation des vulnérabilités pour obtenir accès
# 4. Maintien : Installation de backdoors, escalade de privilèges',
                    'hint' => $getTranslated('hint', 'Pensez au cycle d\'attaque : reconnaissance → exploitation → post-exploitation')
                ],
                11 => [
                    'title' => $getTranslated('title', 'Forensique Numérique'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Expliquez les étapes de l\'investigation forensique : collecte, préservation, analyse des preuves numériques.'),
                    'description' => $getTranslated('description', 'La forensique numérique collecte et analyse les preuves numériques de manière légale. Les étapes incluent la collecte sécurisée, la préservation de l\'intégrité, l\'analyse approfondie et la documentation pour les procédures judiciaires.'),
                    'startCode' => '# Exercice : Forensique
# 
# Étapes de l\'investigation :
# 1. Collecte :
# 2. Préservation :
# 3. Analyse :
# 4. Documentation :
',
                    'solution' => '# Solution :
# 1. Collecte : Acquisition sécurisée des preuves (images disque, logs)
# 2. Préservation : Maintien de l\'intégrité (hash MD5/SHA, chaîne de traçabilité)
# 3. Analyse : Examen approfondi avec outils forensiques
# 4. Documentation : Rapport détaillé pour procédures judiciaires',
                    'hint' => $getTranslated('hint', 'Pensez à la chaîne de traçabilité et à l\'intégrité des preuves')
                ],
                12 => [
                    'title' => $getTranslated('title', 'Sécurité Cloud'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Expliquez les défis de sécurité cloud : responsabilité partagée, configuration, accès.'),
                    'description' => $getTranslated('description', 'La sécurité cloud implique un modèle de responsabilité partagée. Le fournisseur sécurise l\'infrastructure, tandis que le client sécurise les données, configurations et accès. Les erreurs de configuration sont une cause majeure de violations.'),
                    'startCode' => '# Exercice : Sécurité Cloud
# 
# Modèle de responsabilité partagée :
# - Fournisseur (AWS/Azure/GCP) :
# - Client :
',
                    'solution' => '# Solution :
# - Fournisseur : Infrastructure, virtualisation, matériel, centres de données
# - Client : Données, configurations, identités, accès, applications, système d\'exploitation',
                    'hint' => $getTranslated('hint', 'Fournisseur = infrastructure, Client = données et configurations')
                ],
                13 => [
                    'title' => $getTranslated('title', 'DevSecOps'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Expliquez l\'intégration de la sécurité dans DevOps : sécurité shift-left, CI/CD sécurisé.'),
                    'description' => $getTranslated('description', 'DevSecOps intègre la sécurité tout au long du cycle de développement. Le "shift-left" signifie intégrer la sécurité dès le début. Les pipelines CI/CD incluent des scans de sécurité automatisés.'),
                    'startCode' => '# Exercice : DevSecOps
# 
# Pratiques DevSecOps :
# 1. Shift-left :
# 2. CI/CD sécurisé :
# 3. Infrastructure as Code sécurisée :
',
                    'solution' => '# Solution :
# 1. Shift-left : Intégration de la sécurité dès la conception et le développement
# 2. CI/CD sécurisé : Scans automatisés (SAST, DAST) dans le pipeline
# 3. Infrastructure as Code : Validation et scans de sécurité pour Terraform/CloudFormation',
                    'hint' => $getTranslated('hint', 'Shift-left = sécurité tôt, CI/CD = automatisation, IaC = infrastructure sécurisée')
                ],
                14 => [
                    'title' => $getTranslated('title', 'Gouvernance et GRC'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Expliquez la gouvernance de la sécurité : politiques, procédures, gestion des risques (GRC).'),
                    'description' => $getTranslated('description', 'La gouvernance définit les politiques et procédures de sécurité. La GRC (Gouvernance, Risque, Conformité) aligne la sécurité avec les objectifs métier, identifie et gère les risques, et assure la conformité réglementaire.'),
                    'startCode' => '# Exercice : Gouvernance et GRC
# 
# Composants de la GRC :
# - Gouvernance :
# - Risque :
# - Conformité :
',
                    'solution' => '# Solution :
# - Gouvernance : Définition des politiques, procédures, rôles et responsabilités
# - Risque : Identification, évaluation, traitement et monitoring des risques
# - Conformité : Vérification de l\'adéquation aux réglementations et standards',
                    'hint' => $getTranslated('hint', 'Gouvernance = politiques, Risque = gestion, Conformité = vérification')
                ],
            ],
            'data-science' => [
                1 => [
                    'title' => $getTranslated('title', 'Introduction à la Data Science'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Expliquez ce qu\'est la Data Science et son processus (collecte, nettoyage, analyse, visualisation).'),
                    'description' => $getTranslated('description', 'La Data Science combine statistiques, programmation et expertise métier pour extraire des insights des données. Le processus inclut la collecte, le nettoyage, l\'exploration, l\'analyse et la visualisation des données.'),
                    'startCode' => '# Exercice : Introduction Data Science
# 
# Décrivez les étapes du processus Data Science :
# 1. Collecte :
# 2. Nettoyage :
# 3. Analyse :
# 4. Visualisation :
',
                    'solution' => '# Solution :
# 1. Collecte : Récupération des données depuis diverses sources
# 2. Nettoyage : Suppression des doublons, gestion des valeurs manquantes
# 3. Analyse : Application de techniques statistiques et ML
# 4. Visualisation : Représentation graphique des résultats',
                    'hint' => $getTranslated('hint', 'Pensez au cycle complet : de la collecte à la présentation des résultats')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Statistiques Descriptives'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Calculez les statistiques descriptives de base : moyenne, médiane, écart-type pour un ensemble de données.'),
                    'description' => $getTranslated('description', 'Les statistiques descriptives résument et décrivent les caractéristiques principales d\'un dataset. La moyenne mesure la tendance centrale, la médiane est la valeur médiane, et l\'écart-type mesure la dispersion.'),
                    'startCode' => 'import pandas as pd
import numpy as np

# Données : [10, 15, 20, 25, 30, 35, 40]
data = [10, 15, 20, 25, 30, 35, 40]

# Calculez :
# - Moyenne :
# - Médiane :
# - Écart-type :
',
                    'solution' => 'import pandas as pd
import numpy as np

data = [10, 15, 20, 25, 30, 35, 40]

# Moyenne
moyenne = np.mean(data)  # 25.0

# Médiane
mediane = np.median(data)  # 25.0

# Écart-type
ecart_type = np.std(data)  # ~10.8',
                    'hint' => $getTranslated('hint', 'Utilisez np.mean(), np.median(), np.std() de NumPy')
                ],
                3 => [
                    'title' => $getTranslated('title', 'Manipulation avec Pandas'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Créez un DataFrame Pandas, filtrez les données et effectuez des opérations de base.'),
                    'description' => $getTranslated('description', 'Pandas est la bibliothèque Python principale pour la manipulation de données. Les DataFrames permettent de stocker et manipuler des données tabulaires avec des opérations comme le filtrage, le groupement et l\'agrégation.'),
                    'startCode' => 'import pandas as pd

# Créez un DataFrame avec colonnes : nom, age, ville
# Filtrez les personnes de plus de 25 ans
# Affichez la moyenne d\'âge
',
                    'solution' => 'import pandas as pd

# Création du DataFrame
df = pd.DataFrame({
    \'nom\': [\'Alice\', \'Bob\', \'Charlie\'],
    \'age\': [25, 30, 22],
    \'ville\': [\'Paris\', \'Lyon\', \'Marseille\']
})

# Filtrage
df_filtre = df[df[\'age\'] > 25]

# Moyenne
moyenne_age = df[\'age\'].mean()',
                    'hint' => $getTranslated('hint', 'Utilisez pd.DataFrame() pour créer, df[condition] pour filtrer, df[\'col\'].mean() pour la moyenne')
                ],
                4 => [
                    'title' => $getTranslated('title', 'Analyse Exploratoire (EDA)'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Effectuez une analyse exploratoire des données : identification des patterns, valeurs aberrantes, corrélations.'),
                    'description' => $getTranslated('description', 'L\'EDA (Exploratory Data Analysis) est l\'étape cruciale pour comprendre les données avant la modélisation. Elle inclut la visualisation, l\'identification des patterns, la détection des valeurs aberrantes et l\'analyse des corrélations.'),
                    'startCode' => 'import pandas as pd
import matplotlib.pyplot as plt

# Chargez un dataset et effectuez une EDA :
# 1. Affichez les infos de base (head, info, describe)
# 2. Identifiez les valeurs manquantes
# 3. Visualisez la distribution d\'une variable
',
                    'solution' => 'import pandas as pd
import matplotlib.pyplot as plt

df = pd.read_csv(\'data.csv\')

# 1. Infos de base
print(df.head())
print(df.info())
print(df.describe())

# 2. Valeurs manquantes
print(df.isnull().sum())

# 3. Visualisation
df[\'colonne\'].hist()
plt.show()',
                    'hint' => $getTranslated('hint', 'Utilisez df.head(), df.info(), df.describe(), df.isnull().sum(), et matplotlib pour visualiser')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Visualisation de Données'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Créez différents types de visualisations : histogrammes, scatter plots, box plots avec Matplotlib/Seaborn.'),
                    'description' => $getTranslated('description', 'La visualisation est essentielle pour communiquer les insights. Matplotlib et Seaborn offrent de nombreux types de graphiques : histogrammes pour les distributions, scatter plots pour les relations, box plots pour les comparaisons.'),
                    'startCode' => 'import matplotlib.pyplot as plt
import seaborn as sns
import pandas as pd

# Créez :
# 1. Un histogramme
# 2. Un scatter plot
# 3. Un box plot
',
                    'solution' => 'import matplotlib.pyplot as plt
import seaborn as sns
import pandas as pd

df = pd.read_csv(\'data.csv\')

# 1. Histogramme
plt.hist(df[\'colonne\'])
plt.show()

# 2. Scatter plot
plt.scatter(df[\'x\'], df[\'y\'])
plt.show()

# 3. Box plot
sns.boxplot(data=df, x=\'categorie\', y=\'valeur\')
plt.show()',
                    'hint' => $getTranslated('hint', 'Utilisez plt.hist(), plt.scatter(), sns.boxplot()')
                ],
                6 => [
                    'title' => $getTranslated('title', 'Machine Learning - Classification'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 22,
                    'instruction' => $getTranslated('instruction', 'Créez un modèle de classification avec scikit-learn (ex: Random Forest) sur un dataset.'),
                    'description' => $getTranslated('description', 'La classification prédit des catégories. Scikit-learn offre plusieurs algorithmes : Random Forest, SVM, KNN. Le processus inclut la préparation des données, l\'entraînement, l\'évaluation avec des métriques (accuracy, precision, recall).'),
                    'startCode' => 'from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score

# Chargez les données et créez un modèle de classification
',
                    'solution' => 'from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score

# Chargement et préparation
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2)

# Modèle
model = RandomForestClassifier()
model.fit(X_train, y_train)

# Prédiction et évaluation
y_pred = model.predict(X_test)
accuracy = accuracy_score(y_test, y_pred)',
                    'hint' => $getTranslated('hint', 'Utilisez train_test_split(), fit(), predict(), accuracy_score()')
                ],
                7 => [
                    'title' => $getTranslated('title', 'Machine Learning - Régression'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 22,
                    'instruction' => $getTranslated('instruction', 'Créez un modèle de régression linéaire pour prédire des valeurs continues.'),
                    'description' => $getTranslated('description', 'La régression prédit des valeurs continues. La régression linéaire trouve la meilleure ligne pour prédire la variable cible. Les métriques incluent R², MAE, RMSE.'),
                    'startCode' => 'from sklearn.linear_model import LinearRegression
from sklearn.metrics import mean_squared_error, r2_score

# Créez un modèle de régression linéaire
',
                    'solution' => 'from sklearn.linear_model import LinearRegression
from sklearn.metrics import mean_squared_error, r2_score

# Modèle
model = LinearRegression()
model.fit(X_train, y_train)

# Prédiction
y_pred = model.predict(X_test)

# Évaluation
mse = mean_squared_error(y_test, y_pred)
r2 = r2_score(y_test, y_pred)',
                    'hint' => $getTranslated('hint', 'Utilisez LinearRegression(), fit(), predict(), mean_squared_error(), r2_score()')
                ],
                8 => [
                    'title' => $getTranslated('title', 'Clustering (K-Means)'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Appliquez l\'algorithme K-Means pour regrouper des données similaires.'),
                    'description' => $getTranslated('description', 'Le clustering regroupe des données similaires sans étiquettes. K-Means partitionne les données en k clusters en minimisant la distance intra-cluster. Le choix de k est crucial (méthode du coude).'),
                    'startCode' => 'from sklearn.cluster import KMeans
import matplotlib.pyplot as plt

# Appliquez K-Means avec k=3
',
                    'solution' => 'from sklearn.cluster import KMeans
import matplotlib.pyplot as plt

# K-Means
kmeans = KMeans(n_clusters=3, random_state=42)
kmeans.fit(X)
labels = kmeans.labels_

# Visualisation
plt.scatter(X[:, 0], X[:, 1], c=labels)
plt.show()',
                    'hint' => $getTranslated('hint', 'Utilisez KMeans(n_clusters=k), fit(), labels_ pour les clusters')
                ],
                9 => [
                    'title' => $getTranslated('title', 'Évaluation des Modèles'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Évaluez un modèle avec validation croisée et différentes métriques (confusion matrix, ROC curve).'),
                    'description' => $getTranslated('description', 'L\'évaluation valide la performance. La validation croisée (k-fold) réduit le surapprentissage. La matrice de confusion montre les vrais/faux positifs/négatifs. La courbe ROC mesure la capacité de discrimination.'),
                    'startCode' => 'from sklearn.model_selection import cross_val_score
from sklearn.metrics import confusion_matrix, roc_auc_score

# Évaluez avec validation croisée
',
                    'solution' => 'from sklearn.model_selection import cross_val_score
from sklearn.metrics import confusion_matrix, roc_auc_score

# Validation croisée
scores = cross_val_score(model, X, y, cv=5)

# Matrice de confusion
cm = confusion_matrix(y_test, y_pred)

# ROC-AUC
roc_auc = roc_auc_score(y_test, y_pred_proba)',
                    'hint' => $getTranslated('hint', 'Utilisez cross_val_score(), confusion_matrix(), roc_auc_score()')
                ],
                10 => [
                    'title' => $getTranslated('title', 'Feature Engineering'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Créez de nouvelles features : encodage, normalisation, sélection de features.'),
                    'description' => $getTranslated('description', 'Le feature engineering améliore les performances. L\'encodage transforme les catégories (one-hot, label encoding). La normalisation standardise les valeurs. La sélection choisit les features les plus importantes.'),
                    'startCode' => 'from sklearn.preprocessing import StandardScaler, LabelEncoder
from sklearn.feature_selection import SelectKBest

# Préparez les features
',
                    'solution' => 'from sklearn.preprocessing import StandardScaler, LabelEncoder
from sklearn.feature_selection import SelectKBest

# Encodage
le = LabelEncoder()
X_encoded = le.fit_transform(X_categorical)

# Normalisation
scaler = StandardScaler()
X_scaled = scaler.fit_transform(X)

# Sélection
selector = SelectKBest(k=10)
X_selected = selector.fit_transform(X, y)',
                    'hint' => $getTranslated('hint', 'Utilisez LabelEncoder(), StandardScaler(), SelectKBest()')
                ],
                11 => [
                    'title' => $getTranslated('title', 'Deep Learning avec TensorFlow'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Créez un réseau de neurones simple avec TensorFlow/Keras.'),
                    'description' => $getTranslated('description', 'Le deep learning utilise des réseaux de neurones multicouches. TensorFlow/Keras simplifie la création. Un réseau inclut des couches denses, des fonctions d\'activation, et un optimiseur.'),
                    'startCode' => 'import tensorflow as tf
from tensorflow import keras

# Créez un modèle séquentiel
',
                    'solution' => 'import tensorflow as tf
from tensorflow import keras

# Modèle
model = keras.Sequential([
    keras.layers.Dense(128, activation=\'relu\', input_shape=(784,)),
    keras.layers.Dense(64, activation=\'relu\'),
    keras.layers.Dense(10, activation=\'softmax\')
])

# Compilation
model.compile(optimizer=\'adam\', loss=\'sparse_categorical_crossentropy\', metrics=[\'accuracy\'])

# Entraînement
model.fit(X_train, y_train, epochs=10)',
                    'hint' => $getTranslated('hint', 'Utilisez keras.Sequential(), Dense(), compile(), fit()')
                ],
                12 => [
                    'title' => $getTranslated('title', 'NLP - Analyse de Sentiments'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Analysez le sentiment de textes avec des techniques NLP (TF-IDF, word embeddings).'),
                    'description' => $getTranslated('description', 'Le NLP traite le langage naturel. L\'analyse de sentiments classe les textes (positif/négatif). TF-IDF pondère les mots. Les word embeddings (Word2Vec, GloVe) capturent la sémantique.'),
                    'startCode' => 'from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.naive_bayes import MultinomialNB

# Analysez le sentiment
',
                    'solution' => 'from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.naive_bayes import MultinomialNB

# Vectorisation
vectorizer = TfidfVectorizer()
X_vectorized = vectorizer.fit_transform(texts)

# Modèle
model = MultinomialNB()
model.fit(X_vectorized, labels)',
                    'hint' => $getTranslated('hint', 'Utilisez TfidfVectorizer(), MultinomialNB() pour classification de texte')
                ],
                13 => [
                    'title' => $getTranslated('title', 'Time Series Analysis'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Analysez une série temporelle : décomposition, prévision avec ARIMA ou LSTM.'),
                    'description' => $getTranslated('description', 'Les séries temporelles ont une dimension temporelle. La décomposition sépare tendance, saisonnalité et résidu. ARIMA modélise les séries stationnaires. LSTM capture les dépendances longues.'),
                    'startCode' => 'import pandas as pd
from statsmodels.tsa.arima.model import ARIMA

# Analysez une série temporelle
',
                    'solution' => 'import pandas as pd
from statsmodels.tsa.arima.model import ARIMA

# Modèle ARIMA
model = ARIMA(series, order=(1, 1, 1))
model_fit = model.fit()

# Prévision
forecast = model_fit.forecast(steps=10)',
                    'hint' => $getTranslated('hint', 'Utilisez ARIMA(order=(p,d,q)), fit(), forecast()')
                ],
                14 => [
                    'title' => $getTranslated('title', 'Déploiement de Modèles'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Déployez un modèle ML en production : sérialisation, API REST, monitoring.'),
                    'description' => $getTranslated('description', 'Le déploiement met le modèle en production. La sérialisation (pickle, joblib) sauvegarde le modèle. Une API REST expose les prédictions. Le monitoring suit la performance et la dérive des données.'),
                    'startCode' => 'import pickle
from flask import Flask, request, jsonify

# Créez une API pour servir le modèle
',
                    'solution' => 'import pickle
from flask import Flask, request, jsonify

# Chargement du modèle
with open(\'model.pkl\', \'rb\') as f:
    model = pickle.load(f)

# API
app = Flask(__name__)

@app.route(\'/predict\', methods=[\'POST\'])
def predict():
    data = request.json
    prediction = model.predict([data[\'features\']])
    return jsonify({\'prediction\': prediction.tolist()})',
                    'hint' => $getTranslated('hint', 'Utilisez pickle pour sauvegarder/charger, Flask pour l\'API')
                ],
                15 => [
                    'title' => $getTranslated('title', 'Projet Data Science Complet'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Réalisez un projet complet : de la collecte à la visualisation, en passant par l\'analyse et la modélisation.'),
                    'description' => $getTranslated('description', 'Un projet complet suit toutes les étapes : définition du problème, collecte, nettoyage, EDA, feature engineering, modélisation, évaluation, déploiement et communication des résultats.'),
                    'startCode' => '# Projet complet Data Science
# 
# Étapes :
# 1. Définition du problème
# 2. Collecte des données
# 3. Nettoyage et préparation
# 4. EDA
# 5. Modélisation
# 6. Évaluation
# 7. Déploiement
',
                    'solution' => '# Solution : Projet complet
# 1. Problème : Prédire X en fonction de Y
# 2. Collecte : APIs, bases de données, fichiers CSV
# 3. Nettoyage : pandas, gestion valeurs manquantes
# 4. EDA : visualisations, statistiques descriptives
# 5. Modélisation : scikit-learn, sélection d\'algorithme
# 6. Évaluation : métriques, validation croisée
# 7. Déploiement : API Flask/FastAPI, dashboard',
                    'hint' => $getTranslated('hint', 'Suivez le cycle complet de la Data Science')
                ],
            ],
            'big-data' => [
                1 => [
                    'title' => $getTranslated('title', 'Introduction au Big Data'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 10,
                    'instruction' => $getTranslated('instruction', 'Expliquez ce qu\'est le Big Data et les 5 V (Volume, Vélocité, Variété, Véracité, Valeur).'),
                    'description' => $getTranslated('description', 'Le Big Data fait référence à des ensembles de données si volumineux et complexes que les outils traditionnels ne peuvent pas les traiter efficacement. Les 5 V décrivent les caractéristiques : Volume (taille), Vélocité (vitesse), Variété (formats), Véracité (qualité), Valeur (utilité).'),
                    'startCode' => '# Exercice : Introduction Big Data
# 
# Expliquez chaque V :
# - Volume :
# - Vélocité :
# - Variété :
# - Véracité :
# - Valeur :
',
                    'solution' => '# Solution :
# - Volume : Quantité massive de données (pétaoctets, exaoctets)
# - Vélocité : Vitesse de génération et traitement des données
# - Variété : Diversité des formats (structuré, non structuré, semi-structuré)
# - Véracité : Fiabilité et qualité des données
# - Valeur : Utilité et insights extraits des données',
                    'hint' => $getTranslated('hint', 'Pensez aux caractéristiques qui rendent les données "big"')
                ],
                2 => [
                    'title' => $getTranslated('title', 'Les 5 V du Big Data'),
                    'difficulty' => trans('app.exercices.difficulty.easy'),
                    'points' => 12,
                    'instruction' => $getTranslated('instruction', 'Donnez des exemples concrets pour chaque V du Big Data dans différents domaines.'),
                    'description' => $getTranslated('description', 'Les 5 V sont des dimensions qui caractérisent le Big Data. Chaque V présente des défis et opportunités spécifiques. Comprendre ces dimensions est crucial pour concevoir des solutions Big Data adaptées.'),
                    'startCode' => '# Exercice : 5 V - Exemples
# 
# Donnez un exemple pour chaque V :
# Volume (exemple) :
# Vélocité (exemple) :
# Variété (exemple) :
',
                    'solution' => '# Solution :
# Volume : Données des réseaux sociaux (milliards de posts/jour)
# Vélocité : Transactions financières en temps réel
# Variété : Textes, images, vidéos, logs, capteurs IoT
# Véracité : Nettoyage des données de capteurs IoT
# Valeur : Prédiction de tendances pour le marketing',
                    'hint' => $getTranslated('hint', 'Pensez à des exemples réels : réseaux sociaux, IoT, e-commerce')
                ],
                3 => [
                    'title' => $getTranslated('title', 'HDFS - Stockage Distribué'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 18,
                    'instruction' => $getTranslated('instruction', 'Expliquez l\'architecture HDFS (NameNode, DataNode) et comment les données sont répliquées.'),
                    'description' => $getTranslated('description', 'HDFS (Hadoop Distributed File System) est le système de fichiers distribué de Hadoop. Il divise les fichiers en blocs répliqués sur plusieurs DataNodes. Le NameNode gère les métadonnées, tandis que les DataNodes stockent les données.'),
                    'startCode' => '# Exercice : HDFS
# 
# Expliquez :
# 1. Rôle du NameNode :
# 2. Rôle du DataNode :
# 3. Réplication (par défaut) :
',
                    'solution' => '# Solution :
# 1. NameNode : Gère les métadonnées (structure, permissions, emplacement des blocs)
# 2. DataNode : Stocke les blocs de données et répond aux demandes de lecture/écriture
# 3. Réplication : 3 copies par défaut (1 locale, 2 sur d\'autres racks)',
                    'hint' => $getTranslated('hint', 'NameNode = métadonnées, DataNode = stockage, Réplication = tolérance aux pannes')
                ],
                4 => [
                    'title' => $getTranslated('title', 'MapReduce - Concepts'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Expliquez le modèle MapReduce : phases Map, Shuffle, Reduce avec un exemple concret.'),
                    'description' => $getTranslated('description', 'MapReduce est un modèle de programmation pour le traitement distribué de grandes quantités de données. La phase Map traite les données en parallèle, Shuffle redistribue les résultats, et Reduce agrège les résultats finaux.'),
                    'startCode' => '# Exercice : MapReduce
# 
# Pour compter les mots dans un texte :
# 1. Phase Map :
# 2. Phase Shuffle :
# 3. Phase Reduce :
',
                    'solution' => '# Solution :
# 1. Map : Chaque nœud traite une partie du texte et émet (mot, 1)
# 2. Shuffle : Regroupe les paires (mot, 1) par clé (mot)
# 3. Reduce : Agrège les valeurs pour chaque mot (somme des 1)',
                    'hint' => $getTranslated('hint', 'Map = transformation parallèle, Shuffle = regroupement, Reduce = agrégation')
                ],
                5 => [
                    'title' => $getTranslated('title', 'Apache Spark - RDDs'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 22,
                    'instruction' => $getTranslated('instruction', 'Créez un RDD Spark et effectuez des transformations (map, filter) et actions (collect, count).'),
                    'description' => $getTranslated('description', 'Les RDDs (Resilient Distributed Datasets) sont la structure de données fondamentale de Spark. Ils sont immutables et distribués. Les transformations (map, filter) sont lazy, tandis que les actions (collect, count) déclenchent l\'exécution.'),
                    'startCode' => 'from pyspark import SparkContext

sc = SparkContext("local", "RDD Example")

# Créez un RDD à partir d\'une liste
# Filtrez les nombres pairs
# Multipliez par 2
# Comptez les éléments
',
                    'solution' => 'from pyspark import SparkContext

sc = SparkContext("local", "RDD Example")

# Création
rdd = sc.parallelize([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])

# Filtrage
rdd_pairs = rdd.filter(lambda x: x % 2 == 0)

# Transformation
rdd_double = rdd_pairs.map(lambda x: x * 2)

# Action
count = rdd_double.count()  # 5',
                    'hint' => $getTranslated('hint', 'Utilisez sc.parallelize(), rdd.filter(), rdd.map(), rdd.count()')
                ],
                6 => [
                    'title' => $getTranslated('title', 'Spark SQL et DataFrames'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 22,
                    'instruction' => $getTranslated('instruction', 'Utilisez Spark SQL et DataFrames pour manipuler des données structurées.'),
                    'description' => $getTranslated('description', 'Spark SQL permet d\'utiliser SQL sur les données distribuées. Les DataFrames offrent une API optimisée similaire à Pandas mais distribuée. Ils sont plus performants que les RDDs pour les données structurées.'),
                    'startCode' => 'from pyspark.sql import SparkSession

spark = SparkSession.builder.appName("DataFrames").getOrCreate()

# Créez un DataFrame et effectuez des requêtes SQL
',
                    'solution' => 'from pyspark.sql import SparkSession

spark = SparkSession.builder.appName("DataFrames").getOrCreate()

# Création
df = spark.read.csv("data.csv", header=True, inferSchema=True)

# Requête SQL
df.createOrReplaceTempView("table")
result = spark.sql("SELECT * FROM table WHERE age > 25")',
                    'hint' => $getTranslated('hint', 'Utilisez spark.read.csv(), createOrReplaceTempView(), spark.sql()')
                ],
                7 => [
                    'title' => $getTranslated('title', 'NoSQL - MongoDB'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Manipulez des données dans MongoDB : insertion, requêtes, agrégations.'),
                    'description' => $getTranslated('description', 'MongoDB est une base NoSQL document. Les documents sont en JSON/BSON. Les opérations incluent insert, find, update, delete. Les agrégations permettent des requêtes complexes.'),
                    'startCode' => 'from pymongo import MongoClient

client = MongoClient("mongodb://localhost:27017/")
db = client["mydb"]
collection = db["users"]

# Insérez et interrogez des documents
',
                    'solution' => 'from pymongo import MongoClient

client = MongoClient("mongodb://localhost:27017/")
db = client["mydb"]
collection = db["users"]

# Insertion
collection.insert_one({"name": "Alice", "age": 30})

# Requête
result = collection.find({"age": {"$gt": 25}})',
                    'hint' => $getTranslated('hint', 'Utilisez insert_one(), find(), update_one(), delete_one()')
                ],
                8 => [
                    'title' => $getTranslated('title', 'NoSQL - Cassandra'),
                    'difficulty' => trans('app.exercices.difficulty.medium'),
                    'points' => 20,
                    'instruction' => $getTranslated('instruction', 'Expliquez l\'architecture Cassandra : keyspace, tables, partition key, clustering key.'),
                    'description' => $getTranslated('description', 'Cassandra est une base NoSQL orientée colonnes, distribuée et haute disponibilité. Les keyspaces contiennent les tables. La partition key détermine la distribution, la clustering key l\'ordre.'),
                    'startCode' => '# Exercice : Cassandra
# 
# Expliquez :
# - Keyspace :
# - Partition key :
# - Clustering key :
',
                    'solution' => '# Solution :
# - Keyspace : Conteneur logique (équivalent d\'une base de données)
# - Partition key : Détermine sur quel nœud les données sont stockées
# - Clustering key : Détermine l\'ordre des données dans une partition',
                    'hint' => $getTranslated('hint', 'Partition key = distribution, Clustering key = ordre')
                ],
                9 => [
                    'title' => $getTranslated('title', 'Traitement de Flux - Kafka'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Expliquez l\'architecture Kafka : topics, partitions, producers, consumers.'),
                    'description' => $getTranslated('description', 'Kafka est une plateforme de streaming distribuée. Les topics organisent les messages. Les partitions permettent la parallélisation. Les producers publient, les consumers consomment. Le consumer group distribue le traitement.'),
                    'startCode' => '# Exercice : Kafka
# 
# Expliquez :
# - Topic :
# - Partition :
# - Consumer Group :
',
                    'solution' => '# Solution :
# - Topic : Catégorie de messages (ex: "user-events")
# - Partition : Division d\'un topic pour parallélisation et scalabilité
# - Consumer Group : Groupe de consumers partageant le traitement des messages',
                    'hint' => $getTranslated('hint', 'Topic = catégorie, Partition = division, Consumer Group = distribution')
                ],
                10 => [
                    'title' => $getTranslated('title', 'Spark Streaming'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Créez un pipeline Spark Streaming pour traiter des données en temps réel.'),
                    'description' => $getTranslated('description', 'Spark Streaming traite les données en flux. Il divise le flux en micro-batches (DStreams). Les opérations sont similaires aux RDDs mais sur des fenêtres temporelles. L\'intégration avec Kafka est courante.'),
                    'startCode' => 'from pyspark.streaming import StreamingContext

ssc = StreamingContext(sc, 1)  # batch interval 1 seconde

# Créez un DStream et traitez les données
',
                    'solution' => 'from pyspark.streaming import StreamingContext

ssc = StreamingContext(sc, 1)

# DStream depuis Kafka
dstream = ssc.socketTextStream("localhost", 9999)

# Traitement
words = dstream.flatMap(lambda line: line.split(" "))
word_counts = words.map(lambda word: (word, 1)).reduceByKey(lambda a, b: a + b)

# Démarrage
ssc.start()
ssc.awaitTermination()',
                    'hint' => $getTranslated('hint', 'Utilisez StreamingContext(), socketTextStream(), flatMap(), reduceByKey()')
                ],
                11 => [
                    'title' => $getTranslated('title', 'Data Warehousing'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Expliquez l\'architecture d\'un data warehouse : ETL, schéma en étoile, OLAP.'),
                    'description' => $getTranslated('description', 'Un data warehouse centralise les données pour l\'analyse. L\'ETL (Extract, Transform, Load) prépare les données. Le schéma en étoile organise les faits et dimensions. OLAP permet l\'analyse multidimensionnelle.'),
                    'startCode' => '# Exercice : Data Warehouse
# 
# Expliquez :
# - ETL :
# - Schéma en étoile :
# - OLAP :
',
                    'solution' => '# Solution :
# - ETL : Extract (extraction), Transform (transformation), Load (chargement)
# - Schéma en étoile : Table de faits centrale entourée de tables de dimensions
# - OLAP : Analyse multidimensionnelle (drill-down, roll-up, slice, dice)',
                    'hint' => $getTranslated('hint', 'ETL = processus, Schéma étoile = structure, OLAP = analyse')
                ],
                12 => [
                    'title' => $getTranslated('title', 'Data Lakes'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Comparez Data Warehouse et Data Lake : structure, cas d\'usage, technologies.'),
                    'description' => $getTranslated('description', 'Un Data Lake stocke les données brutes dans leur format natif. Contrairement au Data Warehouse structuré, il accepte tous types de données. Les technologies incluent S3, Azure Data Lake, HDFS.'),
                    'startCode' => '# Exercice : Data Lake vs Data Warehouse
# 
# Comparez :
# - Structure :
# - Données :
# - Cas d\'usage :
',
                    'solution' => '# Solution :
# - Structure : Data Lake = schéma-on-read, Data Warehouse = schéma-on-write
# - Données : Data Lake = brutes/tous formats, Data Warehouse = structurées
# - Cas d\'usage : Data Lake = exploration, ML, Data Warehouse = reporting, BI',
                    'hint' => $getTranslated('hint', 'Data Lake = flexible/brut, Data Warehouse = structuré/optimisé')
                ],
                13 => [
                    'title' => $getTranslated('title', 'Machine Learning sur Big Data'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Utilisez MLlib de Spark pour entraîner des modèles ML sur de grandes quantités de données.'),
                    'description' => $getTranslated('description', 'MLlib est la bibliothèque ML de Spark. Elle distribue l\'entraînement sur plusieurs nœuds. Les algorithmes incluent la classification, la régression, le clustering. Les pipelines MLlib automatisent le workflow.'),
                    'startCode' => 'from pyspark.ml.classification import RandomForestClassifier
from pyspark.ml import Pipeline

# Créez un pipeline ML avec Spark
',
                    'solution' => 'from pyspark.ml.classification import RandomForestClassifier
from pyspark.ml import Pipeline
from pyspark.ml.feature import VectorAssembler

# Assemblage des features
assembler = VectorAssembler(inputCols=["feature1", "feature2"], outputCol="features")

# Modèle
rf = RandomForestClassifier(labelCol="label", featuresCol="features")

# Pipeline
pipeline = Pipeline(stages=[assembler, rf])
model = pipeline.fit(train_df)',
                    'hint' => $getTranslated('hint', 'Utilisez VectorAssembler(), RandomForestClassifier(), Pipeline()')
                ],
                14 => [
                    'title' => $getTranslated('title', 'Sécurité et Gouvernance'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 28,
                    'instruction' => $getTranslated('instruction', 'Expliquez les défis de sécurité et gouvernance dans le Big Data : accès, audit, conformité.'),
                    'description' => $getTranslated('description', 'La sécurité Big Data implique le contrôle d\'accès (RBAC), le chiffrement (at-rest, in-transit), l\'audit des accès, et la conformité (RGPD). La gouvernance définit les politiques de données.'),
                    'startCode' => '# Exercice : Sécurité Big Data
# 
# Défis principaux :
# - Contrôle d\'accès :
# - Chiffrement :
# - Audit :
# - Conformité :
',
                    'solution' => '# Solution :
# - Contrôle d\'accès : RBAC, ACLs, authentification (Kerberos)
# - Chiffrement : Données au repos (AES) et en transit (TLS)
# - Audit : Logs d\'accès, traçabilité des opérations
# - Conformité : RGPD, anonymisation, droit à l\'oubli',
                    'hint' => $getTranslated('hint', 'Pensez aux 4 piliers : accès, chiffrement, audit, conformité')
                ],
                15 => [
                    'title' => $getTranslated('title', 'Architecture Big Data Complète'),
                    'difficulty' => trans('app.exercices.difficulty.hard'),
                    'points' => 30,
                    'instruction' => $getTranslated('instruction', 'Concevez une architecture Big Data complète : ingestion, stockage, traitement, analyse.'),
                    'description' => $getTranslated('description', 'Une architecture complète intègre l\'ingestion (Kafka, Flume), le stockage (HDFS, S3), le traitement (Spark, Hadoop), l\'analyse (Hive, Presto), et la visualisation (Tableau, Power BI).'),
                    'startCode' => '# Exercice : Architecture Big Data
# 
# Composants :
# - Ingestion :
# - Stockage :
# - Traitement :
# - Analyse :
# - Visualisation :
',
                    'solution' => '# Solution :
# - Ingestion : Kafka, Flume, NiFi (collecte de données en temps réel)
# - Stockage : HDFS, S3, Data Lake (stockage distribué)
# - Traitement : Spark, Hadoop MapReduce (transformation)
# - Analyse : Hive, Presto, Spark SQL (requêtes SQL)
# - Visualisation : Tableau, Power BI, Grafana (dashboards)',
                    'hint' => $getTranslated('hint', 'Pensez au pipeline complet : de l\'ingestion à la visualisation')
                ],
            ],
        ];

        return $allExercises[$language][$id] ?? null;
    }

    private function getExercisesByLanguage($language)
    {
        // Helper function to get translated exercise title
        $getTitle = function($exerciseId, $default) use ($language) {
            $translationKey = "exercises.{$language}.{$exerciseId}.title";
            $translated = trans($translationKey);
            return $translated !== $translationKey ? $translated : $default;
        };
        
        // Exemples d'exercices par langage
        $allExercises = [
            'html5' => [
                // Niveau Facile (5 exercices)
                ['title' => $getTitle(1, 'Les balises de base'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Les paragraphes'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(3, 'Les liens'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 15],
                ['title' => $getTitle(4, 'Les titres HTML'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(5, 'Les sauts de ligne'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                // Niveau Moyen (5 exercices)
                ['title' => $getTitle(6, 'Les images'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 15],
                ['title' => $getTitle(7, 'Les listes'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(8, 'Les tableaux HTML5'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(9, 'Les formulaires de base'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(10, 'Les citations et abréviations'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 15],
                // Niveau Difficile (5 exercices)
                ['title' => $getTitle(11, 'Formulaires HTML5 avancés'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
                ['title' => $getTitle(12, 'Éléments sémantiques HTML5'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
                ['title' => $getTitle(13, 'Accessibilité HTML5'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(14, 'Métadonnées et SEO HTML5'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(15, 'Multimédia HTML5 (audio/vidéo)'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
            ],
            'css3' => [
                // Niveau Facile (5 exercices)
                ['title' => $getTitle(1, 'Les sélecteurs CSS'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Couleurs et arrière-plans'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(3, 'Marges et padding'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(4, 'Bordures CSS'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(5, 'Polices et texte'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                // Niveau Moyen (5 exercices)
                ['title' => $getTitle(6, 'Flexbox - Centrage'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(7, 'Grid Layout'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(8, 'Responsive Design'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 15],
                ['title' => $getTitle(9, 'Pseudo-classes et pseudo-éléments'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(10, 'Transitions CSS'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                // Niveau Difficile (5 exercices)
                ['title' => $getTitle(11, 'Animations CSS'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
                ['title' => $getTitle(12, 'CSS Variables'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
                ['title' => $getTitle(13, 'Advanced Grid Layout'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(14, 'Transformations CSS 3D'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(15, 'CSS Architecture (BEM, SMACSS)'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
            ],
            'javascript' => [
                // Niveau Facile (5 exercices)
                ['title' => $getTitle(1, 'Variables et types'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Fonctions'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 15],
                ['title' => $getTitle(3, 'Conditions if/else'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(4, 'Boucles for et while'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(5, 'Opérateurs JavaScript'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                // Niveau Moyen (5 exercices)
                ['title' => $getTitle(6, 'DOM Manipulation'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(7, 'Tableaux et boucles'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(8, 'Événements JavaScript'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(9, 'Manipulation de chaînes'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 15],
                ['title' => $getTitle(10, 'Fonctions fléchées'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                // Niveau Difficile (5 exercices)
                ['title' => $getTitle(11, 'Objets JavaScript'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(12, 'Promises et Async/Await'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(13, 'Closures et Scope'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(14, 'Destructuring et Spread'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
                ['title' => $getTitle(15, 'Modules ES6 (import/export)'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
            ],
            'php' => [
                // Niveau Facile (5 exercices)
                ['title' => $getTitle(1, 'Syntaxe de base PHP'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Variables PHP'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(3, 'Opérateurs PHP'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(4, 'Chaînes de caractères PHP'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(5, 'Commentaires PHP'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 8],
                // Niveau Moyen (5 exercices)
                ['title' => $getTitle(6, 'Conditions PHP'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 15],
                ['title' => $getTitle(7, 'Boucles PHP'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(8, 'Fonctions PHP'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(9, 'Tableaux simples PHP'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(10, 'Formulaires PHP (GET/POST)'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                // Niveau Difficile (5 exercices)
                ['title' => $getTitle(11, 'Tableaux PHP'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
                ['title' => $getTitle(12, 'POO en PHP'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(13, 'Les sessions PHP'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(14, 'Traitement des fichiers PHP'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(15, 'Exceptions et gestion d\'erreurs'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
            ],
            'bootstrap' => [
                // Niveau Facile (5 exercices)
                ['title' => $getTitle(1, 'Grille Bootstrap'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Bouton Bootstrap'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(3, 'Typographie Bootstrap'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(4, 'Couleurs Bootstrap'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(5, 'Alertes Bootstrap'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                // Niveau Moyen (5 exercices)
                ['title' => $getTitle(6, 'Card Bootstrap'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 15],
                ['title' => $getTitle(7, 'Navbar Bootstrap'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(8, 'Formulaires Bootstrap'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(9, 'Badges et boutons groupés'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 15],
                ['title' => $getTitle(10, 'Listes et groupes Bootstrap'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 15],
                // Niveau Difficile (5 exercices)
                ['title' => $getTitle(11, 'Responsive Bootstrap'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 20],
                ['title' => $getTitle(12, 'Customisation Bootstrap'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
                ['title' => $getTitle(13, 'Bootstrap avec JavaScript'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(14, 'Carousel et composants avancés'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(15, 'Système de grille avancé Bootstrap'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
            ],
            'git' => [
                // Niveau Facile (5 exercices)
                ['title' => $getTitle(1, 'Initialiser un dépôt Git'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Ajouter des fichiers'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(3, 'Créer un commit'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 15],
                ['title' => $getTitle(4, 'Voir l\'historique Git'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(5, 'Vérifier le statut Git'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                // Niveau Moyen (5 exercices)
                ['title' => $getTitle(6, 'Créer une branche'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 15],
                ['title' => $getTitle(7, 'Changer de branche'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 15],
                ['title' => $getTitle(8, 'Cloner un dépôt distant'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(9, 'Pousser vers un dépôt distant'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(10, 'Récupérer les changements (pull)'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                // Niveau Difficile (5 exercices)
                ['title' => $getTitle(11, 'Fusionner des branches'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 20],
                ['title' => $getTitle(12, 'Résoudre les conflits'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
                ['title' => $getTitle(13, 'Git rebase'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(14, 'Git stash (mise de côté)'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
                ['title' => $getTitle(15, 'Annuler des changements Git'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
            ],
            'wordpress' => [
                // Niveau Facile (5 exercices)
                ['title' => $getTitle(1, 'The Loop WordPress'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Afficher le titre'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(3, 'Afficher le contenu'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(4, 'Afficher la date'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(5, 'Afficher l\'auteur'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                // Niveau Moyen (5 exercices)
                ['title' => $getTitle(6, 'Image à la une'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 15],
                ['title' => $getTitle(7, 'Menu WordPress'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(8, 'Widgets WordPress'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(9, 'Catégories et tags'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 15],
                ['title' => $getTitle(10, 'Pagination WordPress'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                // Niveau Difficile (5 exercices)
                ['title' => $getTitle(11, 'Custom Post Type'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 20],
                ['title' => $getTitle(12, 'Les actions et filtres'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
                ['title' => $getTitle(13, 'Créer un thème complet'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(14, 'Taxonomies personnalisées'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(15, 'Meta boxes personnalisées'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
            ],
            'ia' => [
                // Niveau Facile (5 exercices)
                ['title' => $getTitle(1, 'Concepts de base IA'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Machine Learning'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(3, 'Types d\'apprentissage'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(4, 'Données et datasets'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(5, 'Algorithmes de base'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                // Niveau Moyen (5 exercices)
                ['title' => $getTitle(6, 'Réseaux de neurones'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 15],
                ['title' => $getTitle(7, 'Deep Learning'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 15],
                ['title' => $getTitle(8, 'Entraînement d\'un modèle'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(9, 'Évaluation de performance'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(10, 'Bibliothèques Python (TensorFlow, PyTorch)'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                // Niveau Difficile (5 exercices)
                ['title' => $getTitle(11, 'Applications IA'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 18],
                ['title' => $getTitle(12, 'Natural Language Processing'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
                ['title' => $getTitle(13, 'Éthique de l\'IA'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(14, 'Computer Vision'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(15, 'Optimisation et hyperparamètres'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
            ],
            'python' => [
                // Niveau Facile (5 exercices)
                ['title' => $getTitle(1, 'Syntaxe de base Python'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Variables Python'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(3, 'Types de données Python'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(4, 'Opérateurs Python'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(5, 'Commentaires Python'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 8],
                // Niveau Moyen (5 exercices)
                ['title' => $getTitle(6, 'Conditions if/elif/else'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 15],
                ['title' => $getTitle(7, 'Boucles for et while'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(8, 'Fonctions Python'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(9, 'Listes Python'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(10, 'Dictionnaires Python'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                // Niveau Difficile (5 exercices)
                ['title' => $getTitle(11, 'Programmation Orientée Objet'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(12, 'Modules et packages'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
                ['title' => $getTitle(13, 'Gestion des exceptions'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(14, 'Manipulation de fichiers'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(15, 'Compréhensions de listes et générateurs'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
            ],
            'java' => [
                ['title' => $getTitle(1, 'Premier programme Java'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Variables et types'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(3, 'Opérateurs arithmétiques'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(4, 'Conditions if/else'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(5, 'Boucles for et while'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(6, 'Méthodes Java'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(7, 'Tableaux Java'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(8, 'ArrayList et Collections'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(9, 'Classes et objets'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(10, 'Héritage'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 22],
                ['title' => $getTitle(11, 'Polymorphisme'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(12, 'Interfaces et abstractions'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(13, 'Gestion des exceptions'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
                ['title' => $getTitle(14, 'Fichiers et I/O'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(15, 'Threads et concurrence'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
            ],
            'sql' => [
                ['title' => $getTitle(1, 'Requête SELECT de base'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Filtrage avec WHERE'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(3, 'Tri avec ORDER BY'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(4, 'Opérateurs de comparaison'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(5, 'Opérateurs logiques AND/OR'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(6, 'Fonctions d\'agrégation'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(7, 'GROUP BY et HAVING'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(8, 'JOIN INNER'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(9, 'JOIN LEFT/RIGHT'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 22],
                ['title' => $getTitle(10, 'Sous-requêtes'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(11, 'INSERT, UPDATE, DELETE'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
                ['title' => $getTitle(12, 'Création de tables'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(13, 'Contraintes et clés'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(14, 'Vues et index'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
                ['title' => $getTitle(15, 'Requêtes complexes'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
            ],
            'c' => [
                ['title' => $getTitle(1, 'Premier programme C'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Variables et types'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(3, 'Opérateurs arithmétiques'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(4, 'Conditions if/else'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(5, 'Boucles for et while'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(6, 'Fonctions C'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(7, 'Tableaux'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(8, 'Pointeurs de base'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(9, 'Pointeurs et tableaux'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 22],
                ['title' => $getTitle(10, 'Structures (struct)'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(11, 'Allocation mémoire'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(12, 'Pointeurs avancés'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(13, 'Fichiers et I/O'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(14, 'Chaînes de caractères'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
                ['title' => $getTitle(15, 'Programmation système'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
            ],
            'cpp' => [
                ['title' => $getTitle(1, 'Premier programme C++'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Variables et types'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(3, 'Opérateurs arithmétiques'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(4, 'Conditions if/else'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(5, 'Boucles for et while'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(6, 'Fonctions C++'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(7, 'Classes et objets'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(8, 'Héritage'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 22],
                ['title' => $getTitle(9, 'Polymorphisme'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 25],
                ['title' => $getTitle(10, 'Templates'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 25],
                ['title' => $getTitle(11, 'STL - Vector'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(12, 'STL - Map et Set'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(13, 'Gestion mémoire'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(14, 'Pointeurs intelligents'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(15, 'Algorithmes STL'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
            ],
            'csharp' => [
                ['title' => $getTitle(1, 'Premier programme C#'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Variables et types'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(3, 'Opérateurs arithmétiques'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(4, 'Conditions if/else'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(5, 'Boucles for et while'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(6, 'Méthodes C#'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(7, 'Classes et objets'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(8, 'Héritage'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 22],
                ['title' => $getTitle(9, 'Interfaces'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 25],
                ['title' => $getTitle(10, 'Collections'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 22],
                ['title' => $getTitle(11, 'LINQ'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(12, 'Async/Await'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(13, 'Génériques'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(14, 'Délégués et événements'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(15, 'Exceptions'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
            ],
            'dart' => [
                ['title' => $getTitle(1, 'Premier programme Dart'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Variables et types'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(3, 'Opérateurs arithmétiques'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(4, 'Conditions if/else'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(5, 'Boucles for et while'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(6, 'Fonctions Dart'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(7, 'Classes et objets'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(8, 'Mixins'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 22],
                ['title' => $getTitle(9, 'Futures et Async'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 25],
                ['title' => $getTitle(10, 'Streams'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 25],
                ['title' => $getTitle(11, 'Collections Dart'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(12, 'Flutter - Widgets'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(13, 'Flutter - State Management'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(14, 'Flutter - Navigation'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(15, 'Flutter - API et HTTP'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
            ],
            'go' => [
                ['title' => $getTitle(1, 'Premier programme Go'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Variables et types'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(3, 'Opérateurs arithmétiques'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(4, 'Conditions if/else'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(5, 'Boucles for'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(6, 'Fonctions Go'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(7, 'Structs'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(8, 'Interfaces'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 22],
                ['title' => $getTitle(9, 'Slices et Maps'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(10, 'Packages'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(11, 'Goroutines'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(12, 'Channels'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(13, 'Gestion d\'erreurs'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
                ['title' => $getTitle(14, 'Manipulation de fichiers'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(15, 'HTTP Server'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
            ],
            'rust' => [
                ['title' => $getTitle(1, 'Premier programme Rust'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Variables et mutabilité'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(3, 'Types de données'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(4, 'Conditions if/else'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(5, 'Boucles loop/while/for'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(6, 'Fonctions Rust'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(7, 'Ownership'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 25],
                ['title' => $getTitle(8, 'Borrowing'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 25],
                ['title' => $getTitle(9, 'Structs'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(10, 'Enums et Pattern Matching'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 22],
                ['title' => $getTitle(11, 'Traits'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(12, 'Gestion d\'erreurs (Result)'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(13, 'Génériques'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(14, 'Lifetimes'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(15, 'Concurrence'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
            ],
            'ruby' => [
                ['title' => $getTitle(1, 'Premier programme Ruby'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Variables et types'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(3, 'Opérateurs arithmétiques'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(4, 'Conditions if/elsif/else'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(5, 'Boucles while/for/each'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(6, 'Méthodes Ruby'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(7, 'Classes et objets'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(8, 'Modules et Mixins'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 22],
                ['title' => $getTitle(9, 'Blocs, Procs et Lambdas'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 25],
                ['title' => $getTitle(10, 'Arrays et Hashes'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(11, 'Gestion d\'erreurs'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
                ['title' => $getTitle(12, 'Ruby on Rails - Routes'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(13, 'Ruby on Rails - Controllers'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(14, 'Ruby on Rails - Models'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(15, 'RubyGems'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 25],
            ],
            'cybersecurite' => [
                ['title' => $getTitle(1, 'Introduction à la Cybersécurité'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Principes de Sécurité (CIA)'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(3, 'Identification des Menaces'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(4, 'Cryptographie de Base'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(5, 'Sécurité des Réseaux'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(6, 'Sécurité des Applications'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(7, 'Gestion des Incidents'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 22],
                ['title' => $getTitle(8, 'Législation et Conformité'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(9, 'Analyse de Vulnérabilités'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(10, 'Hacking Éthique'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(11, 'Forensique Numérique'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(12, 'Sécurité Cloud'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(13, 'DevSecOps'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(14, 'Gouvernance et GRC'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(15, 'Cas Pratiques de Sécurité'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
            ],
            'data-science' => [
                ['title' => $getTitle(1, 'Introduction à la Data Science'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Statistiques Descriptives'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(3, 'Manipulation avec Pandas'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(4, 'Analyse Exploratoire (EDA)'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(5, 'Visualisation de Données'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(6, 'Machine Learning - Classification'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 22],
                ['title' => $getTitle(7, 'Machine Learning - Régression'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 22],
                ['title' => $getTitle(8, 'Clustering (K-Means)'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(9, 'Évaluation des Modèles'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(10, 'Feature Engineering'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(11, 'Deep Learning avec TensorFlow'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(12, 'NLP - Analyse de Sentiments'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(13, 'Time Series Analysis'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(14, 'Déploiement de Modèles'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(15, 'Projet Data Science Complet'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
            ],
            'big-data' => [
                ['title' => $getTitle(1, 'Introduction au Big Data'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 10],
                ['title' => $getTitle(2, 'Les 5 V du Big Data'), 'difficulty' => trans('app.exercices.difficulty.easy'), 'points' => 12],
                ['title' => $getTitle(3, 'HDFS - Stockage Distribué'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 18],
                ['title' => $getTitle(4, 'MapReduce - Concepts'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(5, 'Apache Spark - RDDs'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 22],
                ['title' => $getTitle(6, 'Spark SQL et DataFrames'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 22],
                ['title' => $getTitle(7, 'NoSQL - MongoDB'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(8, 'NoSQL - Cassandra'), 'difficulty' => trans('app.exercices.difficulty.medium'), 'points' => 20],
                ['title' => $getTitle(9, 'Traitement de Flux - Kafka'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(10, 'Spark Streaming'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(11, 'Data Warehousing'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(12, 'Data Lakes'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(13, 'Machine Learning sur Big Data'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
                ['title' => $getTitle(14, 'Sécurité et Gouvernance'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 28],
                ['title' => $getTitle(15, 'Architecture Big Data Complète'), 'difficulty' => trans('app.exercices.difficulty.hard'), 'points' => 30],
            ],
        ];

        return $allExercises[$language] ?? [];
    }

    public function quiz()
    {
        // Forcer la locale
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
            ['name' => trans('app.formations.languages.rust'), 'slug' => 'rust', 'icon' => 'fab fa-rust', 'color' => 'black', 'questions' => 20],
            ['name' => trans('app.formations.languages.ruby'), 'slug' => 'ruby', 'icon' => 'fas fa-gem', 'color' => 'red', 'questions' => 20],
            ['name' => trans('app.formations.languages.cybersecurite'), 'slug' => 'cybersecurite', 'icon' => 'fas fa-shield-alt', 'color' => 'orange', 'questions' => 20],
            ['name' => trans('app.formations.languages.data-science'), 'slug' => 'data-science', 'icon' => 'fas fa-chart-line', 'color' => 'blue', 'questions' => 20],
            ['name' => trans('app.formations.languages.big-data'), 'slug' => 'big-data', 'icon' => 'fas fa-database', 'color' => 'purple', 'questions' => 20],
        ];
        
        return view('quiz', compact('languages'));
    }

    public function quizLanguage($language)
    {
        // Forcer la locale
        $this->ensureLocale();
        
        $questions = $this->getQuizQuestions($language);
        
        if (empty($questions)) {
            abort(404);
        }
        
        // Traduire les questions et options
        $translatedQuestions = $this->translateQuizQuestions($language, $questions);
        
        return view('quiz-language', compact('language'))->with('questions', $translatedQuestions);
    }
    
    private function translateQuizQuestions($language, $questions)
    {
        $translatedQuestions = [];
        
        foreach ($questions as $index => $question) {
            $questionId = $index + 1;
            
            // Récupérer la traduction complète de la question
            $translation = trans("quiz.{$language}.{$questionId}", [], app()->getLocale());
            
            // Si la traduction existe et est un tableau
            if (is_array($translation) && isset($translation['question']) && isset($translation['options'])) {
                $translatedQuestions[] = [
                    'question' => $translation['question'],
                    'options' => array_values($translation['options']), // Réindexer pour garder l'ordre
                    'correct' => $question['correct']
                ];
            } else {
                // Fallback sur les valeurs par défaut si la traduction n'existe pas
                $translatedQuestions[] = [
                    'question' => $question['question'],
                    'options' => $question['options'],
                    'correct' => $question['correct']
                ];
            }
        }
        
        return $translatedQuestions;
    }

    public function quizSubmit(Request $request, $language)
    {
        // Forcer la locale
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
            
            // Calculer les bonnes et mauvaises réponses
            $correctAnswers = $score;
            $wrongAnswers = $total - $score;
            
            // Sauvegarder le résultat du quiz
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
            
            // Enregistrer l'activité
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
            
            // Invalider le cache du dashboard
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
        
        // Rediriger vers la route GET pour afficher les résultats (pattern Post-Redirect-Get)
        return redirect()->route('quiz.result', $language);
    }
    
    public function quizResult($language)
    {
        // Forcer la locale
        $this->ensureLocale();
        
        // Récupérer les résultats depuis la session
        $sessionKey = 'quiz_results_' . $language;
        $quizData = session($sessionKey);
        
        if (!$quizData) {
            // Si pas de résultats en session, rediriger vers le quiz
            return redirect()->route('quiz.language', $language)
                ->with('error', trans('app.quiz.result.no_results'));
        }
        
        // Supprimer les résultats de la session après récupération (une seule fois)
        session()->forget($sessionKey);
        
        // Traduire les questions dans les résultats
        $translatedResults = [];
        foreach ($quizData['results'] as $result) {
            $translatedResults[] = $result; // Les questions sont déjà traduites via translateQuizQuestions
        }
        
        return view('quiz-result', [
            'language' => $language,
            'score' => $quizData['score'],
            'total' => $quizData['total'],
            'percentage' => $quizData['percentage'],
            'results' => $translatedResults
        ]);
    }

    private function getQuizQuestions($language)
    {
        $allQuestions = [
            'html5' => [
                ['question' => 'Que signifie HTML ?', 'options' => ['Hyper Text Markup Language', 'High Tech Modern Language', 'Home Tool Markup Language', 'Hyperlinks and Text Markup Language'], 'correct' => 0],
                ['question' => 'Quelle balise est utilisée pour créer un lien hypertexte ?', 'options' => ['<link>', '<a>', '<href>', '<url>'], 'correct' => 1],
                ['question' => 'Quelle balise définit le titre d\'une page HTML ?', 'options' => ['<head>', '<title>', '<meta>', '<header>'], 'correct' => 1],
                ['question' => 'Quelle balise est utilisée pour insérer une image ?', 'options' => ['<image>', '<img>', '<picture>', '<src>'], 'correct' => 1],
                ['question' => 'Quelle balise crée un paragraphe ?', 'options' => ['<para>', '<p>', '<paragraph>', '<text>'], 'correct' => 1],
                ['question' => 'Quelle balise crée une liste non ordonnée ?', 'options' => ['<ol>', '<ul>', '<list>', '<li>'], 'correct' => 1],
                ['question' => 'Quel attribut spécifie une URL alternative pour une image ?', 'options' => ['title', 'alt', 'src', 'href'], 'correct' => 1],
                ['question' => 'Quelle balise définit un tableau ?', 'options' => ['<table>', '<tab>', '<tr>', '<td>'], 'correct' => 0],
                ['question' => 'Quelle balise crée un titre de niveau 1 ?', 'options' => ['<heading>', '<h1>', '<title>', '<head>'], 'correct' => 1],
                ['question' => 'Quelle balise définit une ligne dans un tableau ?', 'options' => ['<td>', '<tr>', '<th>', '<table>'], 'correct' => 1],
                ['question' => 'Quel attribut ouvre un lien dans un nouvel onglet ?', 'options' => ['new', 'target="_blank"', 'open', 'window'], 'correct' => 1],
                ['question' => 'Quelle balise crée un formulaire ?', 'options' => ['<input>', '<form>', '<field>', '<submit>'], 'correct' => 1],
                ['question' => 'Quelle balise HTML5 définit une section ?', 'options' => ['<div>', '<section>', '<article>', '<part>'], 'correct' => 1],
                ['question' => 'Quelle balise crée un champ de saisie de texte ?', 'options' => ['<textbox>', '<input>', '<field>', '<text>'], 'correct' => 1],
                ['question' => 'Quelle balise définit un en-tête de page ?', 'options' => ['<head>', '<header>', '<top>', '<nav>'], 'correct' => 1],
                ['question' => 'Quelle balise crée une liste ordonnée ?', 'options' => ['<ul>', '<ol>', '<list>', '<order>'], 'correct' => 1],
                ['question' => 'Quel attribut définit l\'URL d\'un lien ?', 'options' => ['src', 'link', 'href', 'url'], 'correct' => 2],
                ['question' => 'Quelle balise définit un pied de page ?', 'options' => ['<bottom>', '<footer>', '<foot>', '<end>'], 'correct' => 1],
                ['question' => 'Quelle balise crée un bouton ?', 'options' => ['<btn>', '<button>', '<input type="button">', 'Les deux B et C'], 'correct' => 3],
                ['question' => 'Quelle balise HTML5 définit une navigation ?', 'options' => ['<navigate>', '<nav>', '<menu>', '<links>'], 'correct' => 1],
            ],
            'css3' => [
                ['question' => 'Que signifie CSS ?', 'options' => ['Cascading Style Sheets', 'Computer Style Sheets', 'Creative Style Sheets', 'Colorful Style Sheets'], 'correct' => 0],
                ['question' => 'Quelle propriété change la couleur du texte ?', 'options' => ['text-color', 'color', 'font-color', 'text-style'], 'correct' => 1],
                ['question' => 'Comment ajouter une couleur de fond ?', 'options' => ['color', 'background-color', 'bg-color', 'bgcolor'], 'correct' => 1],
                ['question' => 'Quelle propriété change la taille de la police ?', 'options' => ['text-size', 'font-size', 'text-style', 'font-weight'], 'correct' => 1],
                ['question' => 'Comment centrer un texte ?', 'options' => ['text-align: center', 'align: center', 'text: center', 'center: text'], 'correct' => 0],
                ['question' => 'Quelle propriété ajoute une bordure ?', 'options' => ['outline', 'border', 'edge', 'frame'], 'correct' => 1],
                ['question' => 'Comment rendre un élément invisible ?', 'options' => ['visibility: hidden', 'display: none', 'opacity: 0', 'Toutes les réponses'], 'correct' => 3],
                ['question' => 'Quelle propriété définit l\'espacement interne ?', 'options' => ['margin', 'padding', 'spacing', 'border'], 'correct' => 1],
                ['question' => 'Quelle propriété définit l\'espacement externe ?', 'options' => ['margin', 'padding', 'spacing', 'border'], 'correct' => 0],
                ['question' => 'Comment sélectionner une classe en CSS ?', 'options' => ['#classe', '.classe', '*classe', 'classe'], 'correct' => 1],
                ['question' => 'Comment sélectionner un ID en CSS ?', 'options' => ['#id', '.id', '*id', 'id'], 'correct' => 0],
                ['question' => 'Quelle propriété change la police ?', 'options' => ['font-family', 'font-type', 'font-style', 'text-font'], 'correct' => 0],
                ['question' => 'Comment mettre un texte en gras ?', 'options' => ['font-weight: bold', 'text-style: bold', 'font-bold', 'text-weight: bold'], 'correct' => 0],
                ['question' => 'Quelle propriété contrôle la largeur ?', 'options' => ['width', 'size', 'length', 'dimension'], 'correct' => 0],
                ['question' => 'Quelle propriété contrôle la hauteur ?', 'options' => ['height', 'size', 'length', 'dimension'], 'correct' => 0],
                ['question' => 'Comment créer une animation CSS ?', 'options' => ['@animation', '@keyframes', '@animate', '@motion'], 'correct' => 1],
                ['question' => 'Quelle propriété définit la position ?', 'options' => ['position', 'location', 'place', 'pos'], 'correct' => 0],
                ['question' => 'Quelle valeur de display crée un flexbox ?', 'options' => ['flex', 'flexbox', 'flexible', 'box'], 'correct' => 0],
                ['question' => 'Quelle valeur de display crée une grille ?', 'options' => ['grid', 'table', 'gridbox', 'layout'], 'correct' => 0],
                ['question' => 'Comment arrondir les coins ?', 'options' => ['corner-radius', 'border-radius', 'round-corner', 'border-round'], 'correct' => 1],
            ],
            'javascript' => [
                ['question' => 'Comment déclarer une variable en JavaScript ?', 'options' => ['var x', 'variable x', 'v x', 'dim x'], 'correct' => 0],
                ['question' => 'Quelle méthode affiche un message dans la console ?', 'options' => ['console.log()', 'print()', 'echo()', 'display()'], 'correct' => 0],
                ['question' => 'Comment créer une fonction ?', 'options' => ['function myFunc()', 'def myFunc()', 'func myFunc()', 'create myFunc()'], 'correct' => 0],
                ['question' => 'Quel opérateur teste l\'égalité stricte ?', 'options' => ['==', '===', '=', '!='], 'correct' => 1],
                ['question' => 'Comment ajouter un commentaire sur une ligne ?', 'options' => ['// commentaire', '/* commentaire */', '# commentaire', '-- commentaire'], 'correct' => 0],
                ['question' => 'Quelle méthode sélectionne un élément par ID ?', 'options' => ['getElementById()', 'getElement()', 'selectById()', 'findById()'], 'correct' => 0],
                ['question' => 'Comment créer un tableau ?', 'options' => ['var arr = []', 'var arr = ()', 'var arr = {}', 'var arr = <>'], 'correct' => 0],
                ['question' => 'Quelle méthode ajoute un élément à la fin d\'un tableau ?', 'options' => ['add()', 'push()', 'append()', 'insert()'], 'correct' => 1],
                ['question' => 'Comment créer une boucle for ?', 'options' => ['for (i = 0; i < 5; i++)', 'for i = 0 to 5', 'for (i in 5)', 'loop (i < 5)'], 'correct' => 0],
                ['question' => 'Quelle méthode convertit une chaîne en nombre ?', 'options' => ['parseInt()', 'toNumber()', 'convert()', 'number()'], 'correct' => 0],
                ['question' => 'Comment créer un objet ?', 'options' => ['var obj = []', 'var obj = ()', 'var obj = {}', 'var obj = <>'], 'correct' => 2],
                ['question' => 'Quelle méthode retourne la longueur d\'une chaîne ?', 'options' => ['length', 'size()', 'count()', 'len()'], 'correct' => 0],
                ['question' => 'Comment ajouter un événement click ?', 'options' => ['element.click()', 'element.addEventListener("click")', 'element.onClick()', 'element.addClick()'], 'correct' => 1],
                ['question' => 'Quel mot-clé définit une constante ?', 'options' => ['var', 'let', 'const', 'constant'], 'correct' => 2],
                ['question' => 'Comment arrondir un nombre ?', 'options' => ['Math.round()', 'round()', 'Math.ceil()', 'number.round()'], 'correct' => 0],
                ['question' => 'Quelle méthode génère un nombre aléatoire ?', 'options' => ['Math.random()', 'random()', 'Math.rand()', 'getRandom()'], 'correct' => 0],
                ['question' => 'Comment vérifier le type d\'une variable ?', 'options' => ['typeof', 'type()', 'getType()', 'varType()'], 'correct' => 0],
                ['question' => 'Quelle méthode supprime le dernier élément d\'un tableau ?', 'options' => ['remove()', 'pop()', 'delete()', 'removeLast()'], 'correct' => 1],
                ['question' => 'Comment créer une condition if ?', 'options' => ['if (x > 5)', 'if x > 5', 'if (x > 5) then', 'if x > 5 then'], 'correct' => 0],
                ['question' => 'Quelle méthode transforme un tableau en chaîne ?', 'options' => ['toString()', 'join()', 'concat()', 'Les deux A et B'], 'correct' => 3],
            ],
            'php' => [
                ['question' => 'Que signifie PHP ?', 'options' => ['Personal Home Page', 'PHP: Hypertext Preprocessor', 'Private Home Page', 'Public HTML Page'], 'correct' => 1],
                ['question' => 'Comment commence une variable en PHP ?', 'options' => ['@', '#', '$', '&'], 'correct' => 2],
                ['question' => 'Comment afficher du texte en PHP ?', 'options' => ['echo', 'print', 'display', 'Les deux A et B'], 'correct' => 3],
                ['question' => 'Comment créer un commentaire sur une ligne ?', 'options' => ['// commentaire', '# commentaire', '/* commentaire */', 'Les deux A et B'], 'correct' => 3],
                ['question' => 'Quelle fonction retourne la longueur d\'une chaîne ?', 'options' => ['strlen()', 'length()', 'size()', 'count()'], 'correct' => 0],
                ['question' => 'Comment créer un tableau en PHP ?', 'options' => ['array()', '[]', '$arr = []', 'Toutes les réponses'], 'correct' => 3],
                ['question' => 'Quelle superglobale contient les données POST ?', 'options' => ['$POST', '$_POST', '$FORM', '$_FORM'], 'correct' => 1],
                ['question' => 'Comment inclure un fichier PHP ?', 'options' => ['include', 'require', 'import', 'Les deux A et B'], 'correct' => 3],
                ['question' => 'Quelle fonction vérifie si une variable existe ?', 'options' => ['isset()', 'exists()', 'defined()', 'check()'], 'correct' => 0],
                ['question' => 'Comment créer une fonction ?', 'options' => ['function myFunc()', 'def myFunc()', 'func myFunc()', 'create myFunc()'], 'correct' => 0],
                ['question' => 'Quelle fonction connecte à MySQL ?', 'options' => ['mysql_connect()', 'mysqli_connect()', 'connect_mysql()', 'db_connect()'], 'correct' => 1],
                ['question' => 'Comment démarrer une session ?', 'options' => ['session_start()', 'start_session()', 'session_begin()', 'begin_session()'], 'correct' => 0],
                ['question' => 'Quelle superglobale contient les cookies ?', 'options' => ['$COOKIE', '$_COOKIE', '$COOKIES', '$_COOKIES'], 'correct' => 1],
                ['question' => 'Comment rediriger vers une autre page ?', 'options' => ['header("Location: page.php")', 'redirect("page.php")', 'goto("page.php")', 'location("page.php")'], 'correct' => 0],
                ['question' => 'Quelle fonction retourne le type d\'une variable ?', 'options' => ['gettype()', 'typeof()', 'type()', 'vartype()'], 'correct' => 0],
                ['question' => 'Comment créer une classe ?', 'options' => ['class MyClass {}', 'create class MyClass', 'new class MyClass', 'define class MyClass'], 'correct' => 0],
                ['question' => 'Quelle fonction compte les éléments d\'un tableau ?', 'options' => ['count()', 'length()', 'size()', 'sizeof()'], 'correct' => 0],
                ['question' => 'Comment vérifier si un fichier existe ?', 'options' => ['file_exists()', 'exists()', 'is_file()', 'check_file()'], 'correct' => 0],
                ['question' => 'Quelle fonction convertit en entier ?', 'options' => ['intval()', 'int()', 'toInt()', 'parseInt()'], 'correct' => 0],
                ['question' => 'Comment créer une constante ?', 'options' => ['define("NAME", "value")', 'const NAME = "value"', 'constant NAME = "value"', 'Les deux A et B'], 'correct' => 3],
            ],
            'bootstrap' => [
                ['question' => 'Que signifie Bootstrap ?', 'options' => ['Un framework CSS', 'Un langage de programmation', 'Une base de données', 'Un serveur web'], 'correct' => 0],
                ['question' => 'Quelle classe crée un conteneur responsive ?', 'options' => ['.container', '.responsive', '.wrapper', '.box'], 'correct' => 0],
                ['question' => 'Quelle classe crée une ligne dans la grille ?', 'options' => ['.line', '.row', '.grid', '.flex'], 'correct' => 1],
                ['question' => 'Quelle classe crée une colonne ?', 'options' => ['.column', '.col', '.grid-col', '.cell'], 'correct' => 1],
                ['question' => 'Quelle classe crée un bouton primaire ?', 'options' => ['.btn-primary', '.button-primary', '.primary-btn', '.btn-blue'], 'correct' => 0],
                ['question' => 'Quelle classe centre le texte ?', 'options' => ['.center', '.text-center', '.align-center', '.middle'], 'correct' => 1],
                ['question' => 'Quelle classe crée une alerte ?', 'options' => ['.alert', '.message', '.notification', '.warning'], 'correct' => 0],
                ['question' => 'Quelle classe crée une navbar ?', 'options' => ['.navigation', '.navbar', '.nav', '.menu'], 'correct' => 1],
                ['question' => 'Quelle classe crée une card ?', 'options' => ['.card', '.box', '.panel', '.container'], 'correct' => 0],
                ['question' => 'Quelle classe ajoute une marge en haut ?', 'options' => ['.margin-top', '.mt-3', '.top-margin', '.m-top'], 'correct' => 1],
                ['question' => 'Quelle classe crée un badge ?', 'options' => ['.badge', '.label', '.tag', '.chip'], 'correct' => 0],
                ['question' => 'Quelle classe crée une modal ?', 'options' => ['.modal', '.popup', '.dialog', '.window'], 'correct' => 0],
                ['question' => 'Quelle classe masque un élément sur mobile ?', 'options' => ['.hide-mobile', '.d-none d-md-block', '.mobile-hide', '.hidden-xs'], 'correct' => 1],
                ['question' => 'Quelle classe crée un tableau rayé ?', 'options' => ['.table-striped', '.striped-table', '.table-zebra', '.zebra'], 'correct' => 0],
                ['question' => 'Quelle classe crée un formulaire inline ?', 'options' => ['.form-inline', '.inline-form', '.form-horizontal', '.horizontal'], 'correct' => 0],
            ],
            'git' => [
                ['question' => 'Que signifie Git ?', 'options' => ['Global Information Tracker', 'Un système de contrôle de version', 'Un langage de programmation', 'Un éditeur de code'], 'correct' => 1],
                ['question' => 'Quelle commande initialise un dépôt Git ?', 'options' => ['git start', 'git init', 'git create', 'git new'], 'correct' => 1],
                ['question' => 'Quelle commande ajoute tous les fichiers ?', 'options' => ['git add *', 'git add .', 'git add all', 'git add --all'], 'correct' => 1],
                ['question' => 'Quelle commande crée un commit ?', 'options' => ['git commit', 'git save', 'git push', 'git create'], 'correct' => 0],
                ['question' => 'Quelle commande envoie vers le dépôt distant ?', 'options' => ['git send', 'git upload', 'git push', 'git commit'], 'correct' => 2],
                ['question' => 'Quelle commande récupère les changements ?', 'options' => ['git get', 'git fetch', 'git pull', 'git download'], 'correct' => 2],
                ['question' => 'Quelle commande crée une branche ?', 'options' => ['git branch nom', 'git create nom', 'git new nom', 'git add nom'], 'correct' => 0],
                ['question' => 'Quelle commande change de branche ?', 'options' => ['git change', 'git switch', 'git checkout', 'Les deux B et C'], 'correct' => 3],
                ['question' => 'Quelle commande fusionne des branches ?', 'options' => ['git merge', 'git combine', 'git join', 'git fusion'], 'correct' => 0],
                ['question' => 'Quelle commande affiche l\'historique ?', 'options' => ['git history', 'git log', 'git show', 'git list'], 'correct' => 1],
                ['question' => 'Quelle commande affiche le statut ?', 'options' => ['git status', 'git state', 'git info', 'git check'], 'correct' => 0],
                ['question' => 'Quelle commande clone un dépôt ?', 'options' => ['git copy', 'git clone', 'git download', 'git get'], 'correct' => 1],
                ['question' => 'Quelle commande annule les modifications ?', 'options' => ['git undo', 'git reset', 'git revert', 'git cancel'], 'correct' => 1],
                ['question' => 'Quelle commande affiche les différences ?', 'options' => ['git diff', 'git compare', 'git changes', 'git show'], 'correct' => 0],
                ['question' => 'Quelle commande configure le nom d\'utilisateur ?', 'options' => ['git config user.name', 'git set name', 'git user name', 'git name'], 'correct' => 0],
            ],
            'wordpress' => [
                ['question' => 'Que signifie WordPress ?', 'options' => ['Un CMS', 'Un langage de programmation', 'Une base de données', 'Un serveur'], 'correct' => 0],
                ['question' => 'Quelle fonction affiche le titre ?', 'options' => ['get_title()', 'the_title()', 'show_title()', 'title()'], 'correct' => 1],
                ['question' => 'Quelle fonction affiche le contenu ?', 'options' => ['get_content()', 'the_content()', 'show_content()', 'content()'], 'correct' => 1],
                ['question' => 'Quelle boucle affiche les articles ?', 'options' => ['The Loop', 'Post Loop', 'Article Loop', 'Content Loop'], 'correct' => 0],
                ['question' => 'Quelle fonction vérifie s\'il y a des articles ?', 'options' => ['has_posts()', 'have_posts()', 'check_posts()', 'posts_exist()'], 'correct' => 1],
                ['question' => 'Quelle fonction affiche l\'image à la une ?', 'options' => ['the_thumbnail()', 'the_post_thumbnail()', 'featured_image()', 'post_image()'], 'correct' => 1],
                ['question' => 'Quelle fonction affiche le menu ?', 'options' => ['wp_menu()', 'wp_nav_menu()', 'the_menu()', 'show_menu()'], 'correct' => 1],
                ['question' => 'Quel fichier contient les fonctions du thème ?', 'options' => ['functions.php', 'theme.php', 'config.php', 'setup.php'], 'correct' => 0],
                ['question' => 'Quel fichier est le template principal ?', 'options' => ['main.php', 'index.php', 'home.php', 'template.php'], 'correct' => 1],
                ['question' => 'Quelle fonction enregistre un menu ?', 'options' => ['register_menu()', 'register_nav_menu()', 'add_menu()', 'create_menu()'], 'correct' => 1],
                ['question' => 'Quelle fonction enregistre un widget ?', 'options' => ['register_widget()', 'register_sidebar()', 'add_widget()', 'create_widget()'], 'correct' => 1],
                ['question' => 'Quelle fonction affiche le footer ?', 'options' => ['wp_footer()', 'get_footer()', 'the_footer()', 'show_footer()'], 'correct' => 0],
                ['question' => 'Quelle fonction affiche le header ?', 'options' => ['wp_header()', 'get_header()', 'the_header()', 'show_header()'], 'correct' => 1],
                ['question' => 'Quelle fonction récupère une option ?', 'options' => ['get_option()', 'option()', 'wp_option()', 'the_option()'], 'correct' => 0],
                ['question' => 'Quelle fonction crée un custom post type ?', 'options' => ['create_post_type()', 'register_post_type()', 'add_post_type()', 'new_post_type()'], 'correct' => 1],
            ],
            'ia' => [
                ['question' => 'Que signifie IA ?', 'options' => ['Intelligence Artificielle', 'Information Automatique', 'Internet Avancé', 'Interface Automatisée'], 'correct' => 0],
                ['question' => 'Que signifie ML ?', 'options' => ['Machine Learning', 'Modern Language', 'Multiple Layers', 'Memory Learning'], 'correct' => 0],
                ['question' => 'Quel type d\'apprentissage utilise des données étiquetées ?', 'options' => ['Non supervisé', 'Supervisé', 'Par renforcement', 'Semi-supervisé'], 'correct' => 1],
                ['question' => 'Quelle bibliothèque Python est populaire pour l\'IA ?', 'options' => ['TensorFlow', 'Django', 'Flask', 'Pandas'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un neurone artificiel ?', 'options' => ['Une unité de calcul', 'Un algorithme', 'Un langage', 'Un framework'], 'correct' => 0],
                ['question' => 'Que signifie CNN ?', 'options' => ['Convolutional Neural Network', 'Computer Neural Network', 'Complex Neural Network', 'Connected Neural Network'], 'correct' => 0],
                ['question' => 'Que signifie RNN ?', 'options' => ['Recurrent Neural Network', 'Random Neural Network', 'Recursive Neural Network', 'Regular Neural Network'], 'correct' => 0],
                ['question' => 'Quel algorithme est utilisé pour la classification ?', 'options' => ['K-means', 'Decision Tree', 'Linear Regression', 'PCA'], 'correct' => 1],
                ['question' => 'Que signifie NLP ?', 'options' => ['Natural Language Processing', 'Neural Learning Process', 'Network Layer Protocol', 'New Learning Pattern'], 'correct' => 0],
                ['question' => 'Quel est l\'objectif du Deep Learning ?', 'options' => ['Apprendre des représentations', 'Stocker des données', 'Créer des sites web', 'Gérer des bases de données'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un dataset ?', 'options' => ['Un ensemble de données', 'Un algorithme', 'Un réseau', 'Un langage'], 'correct' => 0],
                ['question' => 'Que signifie GPU ?', 'options' => ['Graphics Processing Unit', 'General Processing Unit', 'Global Processing Unit', 'Graphical Program Unit'], 'correct' => 0],
                ['question' => 'Quel est le rôle d\'une fonction d\'activation ?', 'options' => ['Introduire de la non-linéarité', 'Stocker des données', 'Compiler du code', 'Créer des graphiques'], 'correct' => 0],
                ['question' => 'Que signifie overfitting ?', 'options' => ['Surapprentissage', 'Sous-apprentissage', 'Apprentissage optimal', 'Apprentissage rapide'], 'correct' => 0],
                ['question' => 'Quelle technique réduit l\'overfitting ?', 'options' => ['Dropout', 'Augmentation', 'Compilation', 'Optimisation'], 'correct' => 0],
            ],
            'python' => [
                ['question' => 'Qui a créé Python ?', 'options' => ['Guido van Rossum', 'James Gosling', 'Brendan Eich', 'Dennis Ritchie'], 'correct' => 0],
                ['question' => 'En quelle année Python a-t-il été créé ?', 'options' => ['1989', '1991', '1995', '2000'], 'correct' => 1],
                ['question' => 'Comment afficher du texte en Python ?', 'options' => ['print()', 'echo()', 'display()', 'show()'], 'correct' => 0],
                ['question' => 'Comment créer une variable en Python ?', 'options' => ['var x = 5', 'x = 5', 'variable x = 5', 'def x = 5'], 'correct' => 1],
                ['question' => 'Comment créer un commentaire sur une ligne ?', 'options' => ['// commentaire', '# commentaire', '/* commentaire */', '-- commentaire'], 'correct' => 1],
                ['question' => 'Comment créer une liste ?', 'options' => ['list = []', 'list = ()', 'list = {}', 'list = <>'], 'correct' => 0],
                ['question' => 'Comment créer un dictionnaire ?', 'options' => ['dict = []', 'dict = ()', 'dict = {}', 'dict = <>'], 'correct' => 2],
                ['question' => 'Comment créer une fonction ?', 'options' => ['function ma_fonction()', 'def ma_fonction()', 'func ma_fonction()', 'create ma_fonction()'], 'correct' => 1],
                ['question' => 'Quelle méthode ajoute un élément à une liste ?', 'options' => ['add()', 'append()', 'insert()', 'push()'], 'correct' => 1],
                ['question' => 'Comment créer une boucle for ?', 'options' => ['for i in range(5)', 'for (i = 0; i < 5; i++)', 'for i = 0 to 5', 'loop i in 5'], 'correct' => 0],
                ['question' => 'Comment créer une condition if ?', 'options' => ['if x > 5:', 'if (x > 5)', 'if x > 5 then', 'if (x > 5) {'], 'correct' => 0],
                ['question' => 'Quelle fonction retourne la longueur d\'une liste ?', 'options' => ['length()', 'len()', 'size()', 'count()'], 'correct' => 1],
                ['question' => 'Comment importer un module ?', 'options' => ['import module', 'include module', 'require module', 'load module'], 'correct' => 0],
                ['question' => 'Quelle méthode convertit une chaîne en entier ?', 'options' => ['int()', 'toInt()', 'parseInt()', 'integer()'], 'correct' => 0],
                ['question' => 'Comment créer une classe ?', 'options' => ['class MaClasse:', 'create class MaClasse', 'new class MaClasse', 'define class MaClasse'], 'correct' => 0],
                ['question' => 'Quelle méthode lit un fichier ?', 'options' => ['read()', 'open().read()', 'file_read()', 'get_file()'], 'correct' => 1],
                ['question' => 'Comment créer un tuple ?', 'options' => ['tuple = []', 'tuple = ()', 'tuple = {}', 'tuple = <>'], 'correct' => 1],
                ['question' => 'Quelle méthode divise une chaîne ?', 'options' => ['split()', 'divide()', 'separate()', 'cut()'], 'correct' => 0],
                ['question' => 'Comment créer un ensemble (set) ?', 'options' => ['set = []', 'set = ()', 'set = {}', 'set = set()'], 'correct' => 3],
                ['question' => 'Quelle fonction génère un nombre aléatoire ?', 'options' => ['random.random()', 'rand()', 'Math.random()', 'random()'], 'correct' => 0],
            ],
            'java' => [
                ['question' => 'Qui a créé Java ?', 'options' => ['James Gosling', 'Guido van Rossum', 'Brendan Eich', 'Dennis Ritchie'], 'correct' => 0],
                ['question' => 'En quelle année Java a-t-il été créé ?', 'options' => ['1991', '1995', '2000', '2005'], 'correct' => 1],
                ['question' => 'Comment afficher du texte en Java ?', 'options' => ['System.out.println()', 'print()', 'echo()', 'display()'], 'correct' => 0],
                ['question' => 'Comment déclarer une variable en Java ?', 'options' => ['int x = 5', 'var x = 5', 'x = 5', 'variable x = 5'], 'correct' => 0],
                ['question' => 'Comment créer un commentaire sur une ligne ?', 'options' => ['// commentaire', '# commentaire', '/* commentaire */', '-- commentaire'], 'correct' => 0],
                ['question' => 'Comment créer un tableau ?', 'options' => ['int[] arr = new int[5]', 'int arr = []', 'array arr = []', 'int arr = {}'], 'correct' => 0],
                ['question' => 'Comment créer une classe ?', 'options' => ['class MaClasse {}', 'create class MaClasse', 'new class MaClasse', 'define class MaClasse'], 'correct' => 0],
                ['question' => 'Quelle méthode retourne la longueur d\'un tableau ?', 'options' => ['length', 'length()', 'size()', 'count()'], 'correct' => 0],
                ['question' => 'Comment créer une boucle for ?', 'options' => ['for (int i = 0; i < 5; i++)', 'for i in range(5)', 'for i = 0 to 5', 'loop i < 5'], 'correct' => 0],
                ['question' => 'Comment créer une condition if ?', 'options' => ['if (x > 5)', 'if x > 5', 'if (x > 5) then', 'if x > 5 then'], 'correct' => 0],
                ['question' => 'Quelle méthode ajoute un élément à une ArrayList ?', 'options' => ['add()', 'append()', 'insert()', 'push()'], 'correct' => 0],
                ['question' => 'Comment créer une méthode ?', 'options' => ['public void maMethode() {}', 'def maMethode()', 'function maMethode()', 'create maMethode()'], 'correct' => 0],
                ['question' => 'Quel mot-clé définit une constante ?', 'options' => ['const', 'final', 'static', 'constant'], 'correct' => 1],
                ['question' => 'Comment créer un objet ?', 'options' => ['MaClasse obj = new MaClasse()', 'new MaClasse()', 'create MaClasse()', 'MaClasse obj = MaClasse()'], 'correct' => 0],
                ['question' => 'Quelle interface représente une collection ?', 'options' => ['Collection', 'List', 'Set', 'Toutes les réponses'], 'correct' => 3],
                ['question' => 'Comment gérer une exception ?', 'options' => ['try-catch', 'try-except', 'catch-error', 'handle-exception'], 'correct' => 0],
                ['question' => 'Quelle méthode convertit une chaîne en entier ?', 'options' => ['Integer.parseInt()', 'int()', 'toInt()', 'parseInt()'], 'correct' => 0],
                ['question' => 'Comment créer un thread ?', 'options' => ['extends Thread', 'implements Runnable', 'Les deux A et B', 'new Thread()'], 'correct' => 2],
                ['question' => 'Quelle méthode lit un fichier ?', 'options' => ['FileReader', 'BufferedReader', 'Scanner', 'Toutes les réponses'], 'correct' => 3],
                ['question' => 'Quelle annotation marque une méthode de test ?', 'options' => ['@Test', '@TestAnnotation', '@TestMethod', '@TestFunction'], 'correct' => 0],
            ],
            'sql' => [
                ['question' => 'Que signifie SQL ?', 'options' => ['Structured Query Language', 'Simple Query Language', 'Standard Query Language', 'System Query Language'], 'correct' => 0],
                ['question' => 'Quelle commande sélectionne des données ?', 'options' => ['SELECT', 'GET', 'FETCH', 'RETRIEVE'], 'correct' => 0],
                ['question' => 'Quelle clause filtre les résultats ?', 'options' => ['WHERE', 'FILTER', 'CONDITION', 'IF'], 'correct' => 0],
                ['question' => 'Quelle clause trie les résultats ?', 'options' => ['ORDER BY', 'SORT BY', 'ORDER', 'SORT'], 'correct' => 0],
                ['question' => 'Quelle fonction compte les lignes ?', 'options' => ['COUNT()', 'SUM()', 'TOTAL()', 'NUMBER()'], 'correct' => 0],
                ['question' => 'Quelle fonction calcule la moyenne ?', 'options' => ['AVG()', 'MEAN()', 'AVERAGE()', 'MEDIAN()'], 'correct' => 0],
                ['question' => 'Quelle fonction trouve la valeur maximale ?', 'options' => ['MAX()', 'HIGHEST()', 'TOP()', 'BIGGEST()'], 'correct' => 0],
                ['question' => 'Quelle fonction trouve la valeur minimale ?', 'options' => ['MIN()', 'LOWEST()', 'BOTTOM()', 'SMALLEST()'], 'correct' => 0],
                ['question' => 'Quelle clause groupe les résultats ?', 'options' => ['GROUP BY', 'GROUP', 'CLUSTER BY', 'AGGREGATE BY'], 'correct' => 0],
                ['question' => 'Quel type de JOIN retourne toutes les lignes des deux tables ?', 'options' => ['INNER JOIN', 'LEFT JOIN', 'FULL OUTER JOIN', 'RIGHT JOIN'], 'correct' => 2],
                ['question' => 'Quelle commande insère des données ?', 'options' => ['INSERT', 'ADD', 'CREATE', 'NEW'], 'correct' => 0],
                ['question' => 'Quelle commande modifie des données ?', 'options' => ['UPDATE', 'MODIFY', 'CHANGE', 'EDIT'], 'correct' => 0],
                ['question' => 'Quelle commande supprime des données ?', 'options' => ['DELETE', 'REMOVE', 'DROP', 'CLEAR'], 'correct' => 0],
                ['question' => 'Quelle commande crée une table ?', 'options' => ['CREATE TABLE', 'NEW TABLE', 'MAKE TABLE', 'BUILD TABLE'], 'correct' => 0],
                ['question' => 'Quelle contrainte garantit l\'unicité ?', 'options' => ['UNIQUE', 'DISTINCT', 'ONLY', 'SINGLE'], 'correct' => 0],
                ['question' => 'Quelle contrainte garantit qu\'une valeur n\'est pas NULL ?', 'options' => ['NOT NULL', 'REQUIRED', 'MANDATORY', 'OBLIGATORY'], 'correct' => 0],
                ['question' => 'Quelle clé identifie de manière unique une ligne ?', 'options' => ['PRIMARY KEY', 'UNIQUE KEY', 'FOREIGN KEY', 'INDEX KEY'], 'correct' => 0],
                ['question' => 'Quelle clause limite le nombre de résultats ?', 'options' => ['LIMIT', 'TOP', 'FIRST', 'RESTRICT'], 'correct' => 0],
                ['question' => 'Quel opérateur recherche un motif ?', 'options' => ['LIKE', 'MATCH', 'FIND', 'SEARCH'], 'correct' => 0],
                ['question' => 'Quelle commande crée un index ?', 'options' => ['CREATE INDEX', 'NEW INDEX', 'MAKE INDEX', 'BUILD INDEX'], 'correct' => 0],
            ],
            'c' => [
                ['question' => 'Qui a créé le langage C ?', 'options' => ['Dennis Ritchie', 'James Gosling', 'Guido van Rossum', 'Bjarne Stroustrup'], 'correct' => 0],
                ['question' => 'En quelle année le langage C a-t-il été créé ?', 'options' => ['1969', '1972', '1975', '1980'], 'correct' => 1],
                ['question' => 'Comment afficher du texte en C ?', 'options' => ['printf()', 'print()', 'echo()', 'display()'], 'correct' => 0],
                ['question' => 'Comment déclarer une variable en C ?', 'options' => ['int x = 5', 'var x = 5', 'x = 5', 'variable x = 5'], 'correct' => 0],
                ['question' => 'Comment créer un commentaire sur une ligne ?', 'options' => ['// commentaire', '# commentaire', '/* commentaire */', '-- commentaire'], 'correct' => 0],
                ['question' => 'Comment créer un tableau ?', 'options' => ['int arr[5]', 'int[] arr = new int[5]', 'array arr = []', 'int arr = {}'], 'correct' => 0],
                ['question' => 'Comment créer une fonction ?', 'options' => ['int maFonction() {}', 'def maFonction()', 'function maFonction()', 'create maFonction()'], 'correct' => 0],
                ['question' => 'Quelle fonction retourne la longueur d\'une chaîne ?', 'options' => ['strlen()', 'length()', 'size()', 'count()'], 'correct' => 0],
                ['question' => 'Comment créer une boucle for ?', 'options' => ['for (int i = 0; i < 5; i++)', 'for i in range(5)', 'for i = 0 to 5', 'loop i < 5'], 'correct' => 0],
                ['question' => 'Comment créer une condition if ?', 'options' => ['if (x > 5)', 'if x > 5', 'if (x > 5) then', 'if x > 5 then'], 'correct' => 0],
                ['question' => 'Comment déclarer un pointeur ?', 'options' => ['int *ptr', 'int ptr*', 'pointer int ptr', 'int &ptr'], 'correct' => 0],
                ['question' => 'Comment allouer de la mémoire dynamiquement ?', 'options' => ['malloc()', 'alloc()', 'new()', 'create()'], 'correct' => 0],
                ['question' => 'Comment libérer la mémoire allouée ?', 'options' => ['free()', 'delete()', 'release()', 'remove()'], 'correct' => 0],
                ['question' => 'Comment créer une structure ?', 'options' => ['struct MaStruct {}', 'create struct MaStruct', 'new struct MaStruct', 'define struct MaStruct'], 'correct' => 0],
                ['question' => 'Quelle fonction ouvre un fichier ?', 'options' => ['fopen()', 'open()', 'file_open()', 'open_file()'], 'correct' => 0],
                ['question' => 'Quelle fonction lit un fichier ?', 'options' => ['fread()', 'read()', 'file_read()', 'get_file()'], 'correct' => 0],
                ['question' => 'Quelle fonction écrit dans un fichier ?', 'options' => ['fwrite()', 'write()', 'file_write()', 'put_file()'], 'correct' => 0],
                ['question' => 'Quelle fonction ferme un fichier ?', 'options' => ['fclose()', 'close()', 'file_close()', 'end_file()'], 'correct' => 0],
                ['question' => 'Comment copier une chaîne ?', 'options' => ['strcpy()', 'copy()', 'string_copy()', 'str_dup()'], 'correct' => 0],
                ['question' => 'Comment concaténer deux chaînes ?', 'options' => ['strcat()', 'concat()', 'string_concat()', 'str_join()'], 'correct' => 0],
            ],
            'cpp' => [
                ['question' => 'Qui a créé C++ ?', 'options' => ['Dennis Ritchie', 'Bjarne Stroustrup', 'James Gosling', 'Guido van Rossum'], 'correct' => 1],
                ['question' => 'En quelle année C++ a-t-il été créé ?', 'options' => ['1972', '1983', '1990', '1995'], 'correct' => 1],
                ['question' => 'Comment afficher du texte en C++ ?', 'options' => ['cout <<', 'print()', 'printf()', 'echo'], 'correct' => 0],
                ['question' => 'Comment déclarer une variable en C++ ?', 'options' => ['int x = 5', 'var x = 5', 'x = 5', 'variable x = 5'], 'correct' => 0],
                ['question' => 'Comment créer une classe ?', 'options' => ['class MaClasse {}', 'create class MaClasse', 'new class MaClasse', 'define class MaClasse'], 'correct' => 0],
                ['question' => 'Comment créer un objet ?', 'options' => ['MaClasse obj;', 'obj = new MaClasse()', 'obj = MaClasse()', 'create obj MaClasse'], 'correct' => 0],
                ['question' => 'Comment utiliser l\'héritage ?', 'options' => ['class Enfant : public Parent', 'class Enfant extends Parent', 'class Enfant inherits Parent', 'class Enfant : Parent'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un template ?', 'options' => ['Une fonction générique', 'Une classe générique', 'Les deux A et B', 'Un fichier modèle'], 'correct' => 2],
                ['question' => 'Comment utiliser un vector ?', 'options' => ['vector<int> v;', 'vector v = []', 'int[] v', 'array v'], 'correct' => 0],
                ['question' => 'Comment allouer de la mémoire en C++ ?', 'options' => ['new', 'malloc()', 'alloc()', 'Les deux A et B'], 'correct' => 3],
                ['question' => 'Comment libérer la mémoire allouée avec new ?', 'options' => ['delete', 'free()', 'release()', 'remove()'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que la STL ?', 'options' => ['Standard Template Library', 'Simple Template Library', 'System Template Library', 'Standard Type Library'], 'correct' => 0],
                ['question' => 'Comment créer un pointeur intelligent ?', 'options' => ['unique_ptr', 'smart_ptr', 'auto_ptr', 'Les deux A et C'], 'correct' => 3],
                ['question' => 'Comment utiliser namespace ?', 'options' => ['using namespace std;', 'namespace std;', 'import std;', 'include std;'], 'correct' => 0],
                ['question' => 'Comment créer une fonction virtuelle ?', 'options' => ['virtual void f()', 'void virtual f()', 'void f() virtual', 'virtual f()'], 'correct' => 0],
                ['question' => 'Comment utiliser auto ?', 'options' => ['auto x = 5;', 'var x = 5;', 'x = auto 5;', 'auto(5)'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un lambda ?', 'options' => ['Une fonction anonyme', 'Une fonction nommée', 'Une classe', 'Un template'], 'correct' => 0],
                ['question' => 'Comment utiliser range-based for ?', 'options' => ['for (auto x : vec)', 'for x in vec', 'for (x : vec)', 'foreach x in vec'], 'correct' => 0],
                ['question' => 'Comment utiliser std::string ?', 'options' => ['string s = "hello";', 'char* s = "hello";', 'string s("hello");', 'Les deux A et C'], 'correct' => 3],
                ['question' => 'Qu\'est-ce qu\'un constexpr ?', 'options' => ['Une constante calculée à la compilation', 'Une constante calculée à l\'exécution', 'Une variable', 'Une fonction'], 'correct' => 0],
            ],
            'csharp' => [
                ['question' => 'Qui a créé C# ?', 'options' => ['Microsoft', 'Google', 'Oracle', 'Apple'], 'correct' => 0],
                ['question' => 'En quelle année C# a-t-il été créé ?', 'options' => ['1995', '2000', '2005', '2010'], 'correct' => 1],
                ['question' => 'Comment afficher du texte en C# ?', 'options' => ['Console.WriteLine()', 'print()', 'printf()', 'echo'], 'correct' => 0],
                ['question' => 'Comment déclarer une variable en C# ?', 'options' => ['int x = 5', 'var x = 5', 'Les deux A et B', 'x = 5'], 'correct' => 2],
                ['question' => 'Comment créer une classe ?', 'options' => ['class MaClasse {}', 'create class MaClasse', 'new class MaClasse', 'define class MaClasse'], 'correct' => 0],
                ['question' => 'Comment créer un objet ?', 'options' => ['MaClasse obj = new MaClasse();', 'obj = new MaClasse()', 'obj = MaClasse()', 'create obj MaClasse'], 'correct' => 0],
                ['question' => 'Comment utiliser l\'héritage ?', 'options' => ['class Enfant : Parent', 'class Enfant extends Parent', 'class Enfant inherits Parent', 'class Enfant : public Parent'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que LINQ ?', 'options' => ['Language Integrated Query', 'Language Internal Query', 'Library Integrated Query', 'Language Input Query'], 'correct' => 0],
                ['question' => 'Comment utiliser async/await ?', 'options' => ['async Task f() { await ... }', 'async void f() { await ... }', 'async Task f() { wait ... }', 'Les deux A et B'], 'correct' => 3],
                ['question' => 'Comment créer une liste ?', 'options' => ['List<int> list = new List<int>();', 'list = []', 'int[] list', 'array list'], 'correct' => 0],
                ['question' => 'Comment créer un dictionnaire ?', 'options' => ['Dictionary<string, int>', 'dict = {}', 'map<string, int>', 'hash<string, int>'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'une interface ?', 'options' => ['Un contrat que les classes doivent implémenter', 'Une classe abstraite', 'Une méthode', 'Un namespace'], 'correct' => 0],
                ['question' => 'Comment utiliser using ?', 'options' => ['using System;', 'import System;', 'include System;', 'namespace System;'], 'correct' => 0],
                ['question' => 'Comment créer une propriété ?', 'options' => ['public int Age { get; set; }', 'int age;', 'property int Age;', 'var Age;'], 'correct' => 0],
                ['question' => 'Comment utiliser var ?', 'options' => ['var x = 5;', 'var int x = 5;', 'x = var 5;', 'var(5)'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un délégué ?', 'options' => ['Un type qui représente une référence à une méthode', 'Une classe', 'Une interface', 'Un namespace'], 'correct' => 0],
                ['question' => 'Comment utiliser try/catch ?', 'options' => ['try { } catch { }', 'try { } catch (Exception e) { }', 'try { } finally { }', 'Toutes les réponses'], 'correct' => 3],
                ['question' => 'Comment utiliser foreach ?', 'options' => ['foreach (var item in list)', 'for item in list', 'for (item : list)', 'foreach item in list'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que .NET ?', 'options' => ['Une plateforme de développement', 'Un langage', 'Un framework', 'Les deux A et C'], 'correct' => 3],
                ['question' => 'Comment utiliser string interpolation ?', 'options' => ['$"Hello {name}"', '"Hello " + name', 'string.Format("Hello {0}", name)', 'Les deux A et C'], 'correct' => 3],
            ],
            'dart' => [
                ['question' => 'Qui a créé Dart ?', 'options' => ['Google', 'Microsoft', 'Oracle', 'Facebook'], 'correct' => 0],
                ['question' => 'En quelle année Dart a-t-il été créé ?', 'options' => ['2008', '2011', '2015', '2017'], 'correct' => 1],
                ['question' => 'Comment afficher du texte en Dart ?', 'options' => ['print()', 'console.log()', 'printf()', 'echo'], 'correct' => 0],
                ['question' => 'Comment déclarer une variable en Dart ?', 'options' => ['int x = 5', 'var x = 5', 'Les deux A et B', 'x = 5'], 'correct' => 2],
                ['question' => 'Comment créer une classe ?', 'options' => ['class MaClasse {}', 'create class MaClasse', 'new class MaClasse', 'define class MaClasse'], 'correct' => 0],
                ['question' => 'Comment créer un objet ?', 'options' => ['MaClasse obj = MaClasse();', 'obj = new MaClasse()', 'obj = MaClasse()', 'create obj MaClasse'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un mixin ?', 'options' => ['Un moyen de réutiliser du code dans plusieurs classes', 'Une classe', 'Une interface', 'Une fonction'], 'correct' => 0],
                ['question' => 'Comment utiliser un Future ?', 'options' => ['Future<String>', 'Promise<String>', 'async String', 'String async'], 'correct' => 0],
                ['question' => 'Comment utiliser async/await ?', 'options' => ['Future<void> f() async { await ... }', 'async void f() { await ... }', 'async f() { wait ... }', 'Future f() async { wait ... }'], 'correct' => 0],
                ['question' => 'Comment créer une liste ?', 'options' => ['List<int> list = [];', 'list = []', 'int[] list', 'array list'], 'correct' => 0],
                ['question' => 'Comment créer un Map ?', 'options' => ['Map<String, int>', 'map = {}', 'dict = {}', 'hash = {}'], 'correct' => 0],
                ['question' => 'Comment utiliser string interpolation ?', 'options' => ['"Hello $name"', '"Hello " + name', 'string.format("Hello {0}", name)', 'Les deux A et B'], 'correct' => 3],
                ['question' => 'Qu\'est-ce que Flutter ?', 'options' => ['Un framework UI pour créer des applications multiplateformes', 'Un langage', 'Une bibliothèque', 'Un IDE'], 'correct' => 0],
                ['question' => 'Comment créer un Widget en Flutter ?', 'options' => ['class MonWidget extends StatelessWidget', 'widget MonWidget', 'create widget MonWidget', 'define widget MonWidget'], 'correct' => 0],
                ['question' => 'Comment utiliser setState ?', 'options' => ['setState(() { })', 'state.set()', 'updateState()', 'changeState()'], 'correct' => 0],
                ['question' => 'Comment utiliser un Stream ?', 'options' => ['Stream<int>', 'stream<int>', 'async int', 'int stream'], 'correct' => 0],
                ['question' => 'Comment utiliser forEach sur une liste ?', 'options' => ['list.forEach((item) { })', 'for item in list', 'for (item : list)', 'foreach item in list'], 'correct' => 0],
                ['question' => 'Comment utiliser where sur une liste ?', 'options' => ['list.where((x) => x > 5)', 'list.filter((x) => x > 5)', 'list.select((x) => x > 5)', 'list.find((x) => x > 5)'], 'correct' => 0],
                ['question' => 'Comment utiliser map sur une liste ?', 'options' => ['list.map((x) => x * 2)', 'list.transform((x) => x * 2)', 'list.apply((x) => x * 2)', 'list.change((x) => x * 2)'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un const en Dart ?', 'options' => ['Une constante calculée à la compilation', 'Une constante calculée à l\'exécution', 'Une variable', 'Une fonction'], 'correct' => 0],
            ],
            'go' => [
                ['question' => 'Qui a créé Go ?', 'options' => ['Google', 'Microsoft', 'Oracle', 'Facebook'], 'correct' => 0],
                ['question' => 'En quelle année Go a-t-il été créé ?', 'options' => ['2007', '2009', '2012', '2015'], 'correct' => 1],
                ['question' => 'Comment afficher du texte en Go ?', 'options' => ['fmt.Println()', 'print()', 'printf()', 'echo'], 'correct' => 0],
                ['question' => 'Comment déclarer une variable en Go ?', 'options' => ['var x int = 5', 'x := 5', 'Les deux A et B', 'int x = 5'], 'correct' => 2],
                ['question' => 'Comment créer une fonction ?', 'options' => ['func maFonction() {}', 'function maFonction()', 'def maFonction()', 'create maFonction()'], 'correct' => 0],
                ['question' => 'Comment créer une struct ?', 'options' => ['type MaStruct struct {}', 'struct MaStruct {}', 'create struct MaStruct', 'define struct MaStruct'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'une goroutine ?', 'options' => ['Un thread léger', 'Une fonction', 'Une classe', 'Un package'], 'correct' => 0],
                ['question' => 'Comment créer une goroutine ?', 'options' => ['go maFonction()', 'goroutine maFonction()', 'async maFonction()', 'thread maFonction()'], 'correct' => 0],
                ['question' => 'Comment créer un channel ?', 'options' => ['ch := make(chan int)', 'ch = new channel(int)', 'ch = channel(int)', 'ch := channel(int)'], 'correct' => 0],
                ['question' => 'Comment créer une slice ?', 'options' => ['s := []int{}', 's = []', 'int[] s', 'array s'], 'correct' => 0],
                ['question' => 'Comment créer un map ?', 'options' => ['m := make(map[string]int)', 'm = {}', 'map m = {}', 'dict m = {}'], 'correct' => 0],
                ['question' => 'Comment gérer les erreurs en Go ?', 'options' => ['if err != nil', 'try/catch', 'throw/catch', 'error handling'], 'correct' => 0],
                ['question' => 'Comment utiliser defer ?', 'options' => ['defer f()', 'finally f()', 'defer { f() }', 'end f()'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un package en Go ?', 'options' => ['Un moyen d\'organiser le code', 'Une bibliothèque', 'Un module', 'Les deux A et B'], 'correct' => 3],
                ['question' => 'Comment importer un package ?', 'options' => ['import "fmt"', 'import fmt', 'include "fmt"', 'require "fmt"'], 'correct' => 0],
                ['question' => 'Comment utiliser range ?', 'options' => ['for i, v := range slice', 'for i in slice', 'for (i : slice)', 'foreach i in slice'], 'correct' => 0],
                ['question' => 'Comment utiliser interface ?', 'options' => ['type MaInterface interface {}', 'interface MaInterface {}', 'create interface MaInterface', 'define interface MaInterface'], 'correct' => 0],
                ['question' => 'Comment utiliser switch ?', 'options' => ['switch x { case 1: }', 'switch (x) { case 1: }', 'switch x case 1', 'switch x then 1'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un pointer en Go ?', 'options' => ['Une référence à une variable', 'Une variable', 'Une fonction', 'Un type'], 'correct' => 0],
                ['question' => 'Comment créer un pointer ?', 'options' => ['p := &x', 'p = *x', 'p = pointer(x)', 'p := new(x)'], 'correct' => 0],
            ],
            'rust' => [
                ['question' => 'Qui a créé Rust ?', 'options' => ['Mozilla', 'Google', 'Microsoft', 'Facebook'], 'correct' => 0],
                ['question' => 'En quelle année Rust a-t-il été créé ?', 'options' => ['2006', '2010', '2012', '2015'], 'correct' => 1],
                ['question' => 'Comment afficher du texte en Rust ?', 'options' => ['println!()', 'print()', 'printf()', 'echo'], 'correct' => 0],
                ['question' => 'Comment déclarer une variable en Rust ?', 'options' => ['let x = 5', 'let mut x = 5', 'Les deux A et B', 'int x = 5'], 'correct' => 2],
                ['question' => 'Qu\'est-ce que l\'ownership ?', 'options' => ['Un système de propriété de la mémoire', 'Une fonction', 'Une classe', 'Un type'], 'correct' => 0],
                ['question' => 'Comment utiliser borrow ?', 'options' => ['&x', '*x', 'borrow x', 'ref x'], 'correct' => 0],
                ['question' => 'Comment créer une struct ?', 'options' => ['struct MaStruct {}', 'type MaStruct struct {}', 'create struct MaStruct', 'define struct MaStruct'], 'correct' => 0],
                ['question' => 'Comment créer un enum ?', 'options' => ['enum MonEnum {}', 'type MonEnum enum {}', 'create enum MonEnum', 'define enum MonEnum'], 'correct' => 0],
                ['question' => 'Comment utiliser match ?', 'options' => ['match x { case => }', 'match x { pattern => }', 'switch x { case }', 'match x then'], 'correct' => 1],
                ['question' => 'Comment gérer les erreurs en Rust ?', 'options' => ['Result<T, E>', 'Option<T>', 'Les deux A et B', 'try/catch'], 'correct' => 2],
                ['question' => 'Comment utiliser Option ?', 'options' => ['Option<T>', 'Optional<T>', 'Maybe<T>', 'Nullable<T>'], 'correct' => 0],
                ['question' => 'Comment utiliser Result ?', 'options' => ['Result<T, E>', 'Result<T>', 'Error<T>', 'Try<T>'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un trait ?', 'options' => ['Un contrat que les types doivent implémenter', 'Une classe', 'Une interface', 'Les deux A et C'], 'correct' => 3],
                ['question' => 'Comment utiliser impl ?', 'options' => ['impl Trait for Type', 'impl Type for Trait', 'implement Trait Type', 'impl Type Trait'], 'correct' => 0],
                ['question' => 'Comment utiliser Vec ?', 'options' => ['Vec<T>', 'vec<T>', 'vector<T>', 'array<T>'], 'correct' => 0],
                ['question' => 'Comment utiliser HashMap ?', 'options' => ['HashMap<K, V>', 'hash_map<K, V>', 'map<K, V>', 'dict<K, V>'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un lifetime ?', 'options' => ['La durée de vie d\'une référence', 'Une fonction', 'Une variable', 'Un type'], 'correct' => 0],
                ['question' => 'Comment utiliser \'a ?', 'options' => ['fn f<\'a>(x: &\'a str)', 'fn f(x: \'a str)', 'fn f(x: str<\'a>)', 'fn<\'a> f(x: str)'], 'correct' => 0],
                ['question' => 'Comment utiliser String et &str ?', 'options' => ['String est possédé, &str est une référence', 'Les deux sont identiques', 'String est une référence', '&str est possédé'], 'correct' => 0],
                ['question' => 'Comment utiliser unwrap ?', 'options' => ['x.unwrap()', 'unwrap(x)', 'x.unwrap_or()', 'Les deux A et C'], 'correct' => 3],
            ],
            'ruby' => [
                ['question' => 'Qui a créé Ruby ?', 'options' => ['Yukihiro Matsumoto', 'Guido van Rossum', 'James Gosling', 'Bjarne Stroustrup'], 'correct' => 0],
                ['question' => 'En quelle année Ruby a-t-il été créé ?', 'options' => ['1993', '1995', '2000', '2005'], 'correct' => 1],
                ['question' => 'Comment afficher du texte en Ruby ?', 'options' => ['puts', 'print', 'Les deux A et B', 'echo'], 'correct' => 2],
                ['question' => 'Comment déclarer une variable en Ruby ?', 'options' => ['x = 5', 'var x = 5', 'int x = 5', 'x := 5'], 'correct' => 0],
                ['question' => 'Comment créer une méthode ?', 'options' => ['def maMethode', 'function maMethode', 'method maMethode', 'create maMethode'], 'correct' => 0],
                ['question' => 'Comment créer une classe ?', 'options' => ['class MaClasse', 'class MaClasse {}', 'create class MaClasse', 'define class MaClasse'], 'correct' => 0],
                ['question' => 'Comment créer un objet ?', 'options' => ['obj = MaClasse.new', 'obj = new MaClasse()', 'obj = MaClasse()', 'obj = create MaClasse'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un module ?', 'options' => ['Un moyen de regrouper des méthodes', 'Une classe', 'Une interface', 'Les deux A et B'], 'correct' => 3],
                ['question' => 'Comment utiliser include ?', 'options' => ['include MonModule', 'import MonModule', 'require MonModule', 'use MonModule'], 'correct' => 0],
                ['question' => 'Comment créer un array ?', 'options' => ['arr = []', 'arr = Array.new', 'Les deux A et B', 'array arr'], 'correct' => 2],
                ['question' => 'Comment créer un hash ?', 'options' => ['h = {}', 'h = Hash.new', 'Les deux A et B', 'dict h'], 'correct' => 2],
                ['question' => 'Comment utiliser each ?', 'options' => ['arr.each { |x| }', 'for x in arr', 'foreach x in arr', 'Les deux A et B'], 'correct' => 3],
                ['question' => 'Comment utiliser map ?', 'options' => ['arr.map { |x| x * 2 }', 'arr.collect { |x| x * 2 }', 'Les deux A et B', 'arr.transform { |x| }'], 'correct' => 2],
                ['question' => 'Comment utiliser select ?', 'options' => ['arr.select { |x| x > 5 }', 'arr.filter { |x| x > 5 }', 'arr.where { |x| }', 'Les deux A et B'], 'correct' => 3],
                ['question' => 'Qu\'est-ce que Ruby on Rails ?', 'options' => ['Un framework web', 'Un langage', 'Une bibliothèque', 'Les deux A et C'], 'correct' => 3],
                ['question' => 'Comment créer un contrôleur Rails ?', 'options' => ['rails generate controller Nom', 'rails create controller Nom', 'rails new controller Nom', 'rails make controller Nom'], 'correct' => 0],
                ['question' => 'Comment créer un modèle Rails ?', 'options' => ['rails generate model Nom', 'rails create model Nom', 'rails new model Nom', 'rails make model Nom'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'une gem ?', 'options' => ['Une bibliothèque Ruby', 'Un package', 'Un module', 'Les deux A et B'], 'correct' => 3],
                ['question' => 'Comment utiliser une gem ?', 'options' => ['gem install nom', 'require "nom"', 'Les deux A et B', 'import nom'], 'correct' => 2],
                ['question' => 'Comment utiliser symbol ?', 'options' => [':sym', 'sym:', ':symbol', 'symbol:'], 'correct' => 0],
            ],
            'cybersecurite' => [
                ['question' => 'Que signifie la triade CIA en cybersécurité ?', 'options' => ['Confidentialité, Intégrité, Disponibilité', 'Central Intelligence Agency', 'Computer Information Access', 'Cybersecurity Information Analysis'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un malware ?', 'options' => ['Un logiciel malveillant', 'Un antivirus', 'Un pare-feu', 'Un protocole de sécurité'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que le phishing ?', 'options' => ['Une attaque d\'ingénierie sociale', 'Un type de virus', 'Un protocole réseau', 'Un algorithme de chiffrement'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un firewall ?', 'options' => ['Un système de filtrage du trafic réseau', 'Un antivirus', 'Un système de détection d\'intrusion', 'Un protocole de chiffrement'], 'correct' => 0],
                ['question' => 'Que signifie VPN ?', 'options' => ['Virtual Private Network', 'Very Private Network', 'Virtual Public Network', 'Verified Private Network'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que le chiffrement ?', 'options' => ['La transformation de données en format illisible', 'La suppression de données', 'La copie de données', 'La compression de données'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'une vulnérabilité Zero-Day ?', 'options' => ['Une vulnérabilité inconnue et non corrigée', 'Une vulnérabilité corrigée', 'Un type de malware', 'Un protocole de sécurité'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que l\'authentification à deux facteurs (2FA) ?', 'options' => ['Une méthode de vérification en deux étapes', 'Un type de chiffrement', 'Un protocole réseau', 'Un système de détection'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'une attaque DDoS ?', 'options' => ['Déni de service distribué', 'Data Distribution Service', 'Digital Defense System', 'Dynamic Data Storage'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que le RGPD ?', 'options' => ['Règlement Général sur la Protection des Données', 'Réseau Général de Protection des Données', 'Règlement Général de Prévention des Données', 'Réseau Général de Prévention des Données'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un IDS ?', 'options' => ['Intrusion Detection System', 'Internet Defense System', 'Internal Data Security', 'Information Defense System'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que le hacking éthique ?', 'options' => ['Le piratage autorisé pour tester la sécurité', 'Le piratage illégal', 'Un type de malware', 'Un protocole de sécurité'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que la cryptographie asymétrique ?', 'options' => ['Un système utilisant une paire de clés (publique/privée)', 'Un système utilisant une seule clé', 'Un type de malware', 'Un protocole réseau'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un certificat SSL/TLS ?', 'options' => ['Un certificat numérique pour sécuriser les communications', 'Un type de malware', 'Un protocole réseau', 'Un système de détection'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que la gestion des incidents de sécurité ?', 'options' => ['Le processus de réponse aux incidents', 'La prévention des incidents', 'Le chiffrement des données', 'La sauvegarde des données'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que DevSecOps ?', 'options' => ['L\'intégration de la sécurité dans DevOps', 'Un type de malware', 'Un protocole réseau', 'Un système de détection'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que la forensique numérique ?', 'options' => ['L\'investigation d\'incidents de sécurité', 'La prévention des attaques', 'Le chiffrement des données', 'La sauvegarde des données'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un honeypot ?', 'options' => ['Un système piège pour attirer les attaquants', 'Un type de malware', 'Un protocole réseau', 'Un système de détection'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que la sécurité du cloud ?', 'options' => ['La sécurisation des données et services cloud', 'Un type de malware', 'Un protocole réseau', 'Un système de détection'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que la gouvernance de la sécurité ?', 'options' => ['La gestion stratégique de la sécurité', 'La prévention des attaques', 'Le chiffrement des données', 'La sauvegarde des données'], 'correct' => 0],
            ],
            'data-science' => [
                ['question' => 'Que signifie EDA en Data Science ?', 'options' => ['Exploratory Data Analysis', 'External Data Access', 'Extended Data Analysis', 'Exact Data Analysis'], 'correct' => 0],
                ['question' => 'Quelle bibliothèque Python est utilisée pour la manipulation de données ?', 'options' => ['Pandas', 'NumPy', 'Matplotlib', 'Scikit-learn'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que le Machine Learning supervisé ?', 'options' => ['L\'apprentissage avec des données étiquetées', 'L\'apprentissage sans données étiquetées', 'L\'apprentissage par renforcement', 'L\'apprentissage automatique'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que le clustering ?', 'options' => ['Le regroupement de données similaires', 'La classification de données', 'La régression', 'La visualisation'], 'correct' => 0],
                ['question' => 'Quelle bibliothèque Python est utilisée pour le Machine Learning ?', 'options' => ['Scikit-learn', 'Pandas', 'NumPy', 'Matplotlib'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un DataFrame ?', 'options' => ['Une structure de données tabulaire en Pandas', 'Un type de graphique', 'Un algorithme', 'Un modèle'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que la validation croisée ?', 'options' => ['Une technique d\'évaluation de modèle', 'Un type de clustering', 'Une méthode de visualisation', 'Un algorithme de régression'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que l\'overfitting ?', 'options' => ['Le surapprentissage d\'un modèle', 'Le sous-apprentissage', 'L\'apprentissage optimal', 'L\'apprentissage rapide'], 'correct' => 0],
                ['question' => 'Quelle bibliothèque Python est utilisée pour la visualisation ?', 'options' => ['Matplotlib et Seaborn', 'Pandas', 'NumPy', 'Scikit-learn'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que la régression linéaire ?', 'options' => ['Un modèle prédictif pour les valeurs continues', 'Un modèle de classification', 'Un algorithme de clustering', 'Une méthode de visualisation'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que le NLP ?', 'options' => ['Natural Language Processing', 'Neural Learning Process', 'Network Layer Protocol', 'New Learning Pattern'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un feature en Machine Learning ?', 'options' => ['Une variable d\'entrée', 'Un résultat', 'Un modèle', 'Un algorithme'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que le Deep Learning ?', 'options' => ['L\'apprentissage avec des réseaux neuronaux profonds', 'L\'apprentissage superficiel', 'L\'apprentissage par renforcement', 'L\'apprentissage automatique'], 'correct' => 0],
                ['question' => 'Quelle bibliothèque est utilisée pour le Deep Learning ?', 'options' => ['TensorFlow et PyTorch', 'Pandas', 'NumPy', 'Scikit-learn'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que la normalisation des données ?', 'options' => ['La mise à l\'échelle des données', 'La suppression des données', 'La copie des données', 'La compression des données'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un modèle de classification ?', 'options' => ['Un modèle qui prédit des catégories', 'Un modèle qui prédit des valeurs continues', 'Un algorithme de clustering', 'Une méthode de visualisation'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que la corrélation ?', 'options' => ['La relation entre deux variables', 'La différence entre deux variables', 'La somme de deux variables', 'Le produit de deux variables'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que le feature engineering ?', 'options' => ['La création de nouvelles variables pertinentes', 'La suppression de variables', 'La copie de variables', 'La compression de variables'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un pipeline de données ?', 'options' => ['Un flux de traitement automatisé des données', 'Un type de graphique', 'Un algorithme', 'Un modèle'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que l\'A/B testing ?', 'options' => ['L\'expérimentation pour comparer deux versions', 'Un type de clustering', 'Une méthode de visualisation', 'Un algorithme de régression'], 'correct' => 0],
            ],
            'big-data' => [
                ['question' => 'Que signifie HDFS ?', 'options' => ['Hadoop Distributed File System', 'High Data File System', 'Huge Data File System', 'Hybrid Data File System'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que MapReduce ?', 'options' => ['Un modèle de programmation pour le traitement distribué', 'Un système de fichiers', 'Un protocole réseau', 'Un algorithme de clustering'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'Apache Spark ?', 'options' => ['Un moteur d\'analyse unifié pour le Big Data', 'Un système de fichiers', 'Un protocole réseau', 'Un algorithme de clustering'], 'correct' => 0],
                ['question' => 'Quels sont les 5 V du Big Data ?', 'options' => ['Volume, Vitesse, Variété, Véracité, Valeur', 'Volume, Vitesse, Variété, Validation, Valeur', 'Volume, Vitesse, Variété, Véracité, Validation', 'Volume, Vitesse, Variété, Véracité, Vérification'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un Data Lake ?', 'options' => ['Un dépôt de données brutes non structurées', 'Un entrepôt de données structurées', 'Un système de fichiers', 'Un protocole réseau'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un Data Warehouse ?', 'options' => ['Un entrepôt de données structurées et organisées', 'Un dépôt de données brutes', 'Un système de fichiers', 'Un protocole réseau'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'Apache Kafka ?', 'options' => ['Une plateforme de streaming distribué', 'Un système de fichiers', 'Un protocole réseau', 'Un algorithme de clustering'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que NoSQL ?', 'options' => ['Des bases de données non relationnelles', 'Des bases de données relationnelles', 'Un système de fichiers', 'Un protocole réseau'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un RDD dans Spark ?', 'options' => ['Resilient Distributed Dataset', 'Real-time Data Distribution', 'Reliable Data Distribution', 'Rapid Data Distribution'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que le streaming de données ?', 'options' => ['Le traitement de données en temps réel', 'Le stockage de données', 'La copie de données', 'La compression de données'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un NameNode dans HDFS ?', 'options' => ['Le nœud maître qui gère les métadonnées', 'Un nœud de données', 'Un protocole réseau', 'Un algorithme'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un DataNode dans HDFS ?', 'options' => ['Un nœud qui stocke les données', 'Le nœud maître', 'Un protocole réseau', 'Un algorithme'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que la réplication dans HDFS ?', 'options' => ['La copie de données sur plusieurs nœuds', 'La suppression de données', 'La compression de données', 'Le chiffrement de données'], 'correct' => 0],
                ['question' => 'Qu\'est-ce qu\'un DataFrame dans Spark ?', 'options' => ['Une structure de données distribuée', 'Un système de fichiers', 'Un protocole réseau', 'Un algorithme'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que le batch processing ?', 'options' => ['Le traitement par lots', 'Le traitement en temps réel', 'Le stockage de données', 'La copie de données'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que MongoDB ?', 'options' => ['Une base de données NoSQL orientée documents', 'Une base de données relationnelle', 'Un système de fichiers', 'Un protocole réseau'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que Cassandra ?', 'options' => ['Une base de données NoSQL orientée colonnes', 'Une base de données relationnelle', 'Un système de fichiers', 'Un protocole réseau'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que la scalabilité horizontale ?', 'options' => ['L\'ajout de nœuds pour augmenter la capacité', 'L\'amélioration des nœuds existants', 'La suppression de nœuds', 'Le remplacement de nœuds'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que Spark Streaming ?', 'options' => ['Le traitement de flux de données en temps réel avec Spark', 'Le stockage de données', 'La copie de données', 'La compression de données'], 'correct' => 0],
                ['question' => 'Qu\'est-ce que la gouvernance des données Big Data ?', 'options' => ['La gestion et le contrôle des données', 'Le stockage de données', 'La copie de données', 'La compression de données'], 'correct' => 0],
            ],
        ];

        return $allQuestions[$language] ?? [];
    }

    public function legal()
    {
        return view('legal');
    }

    public function privacyPolicy()
    {
        return view('privacy-policy');
    }

    public function terms()
    {
        return view('terms');
    }

    public function newsletterSubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:newsletters,email'
        ], [
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà inscrite à notre newsletter.'
        ]);

        $token = \Str::random(64);

        \App\Models\Newsletter::create([
            'email' => $request->email,
            'token' => $token,
            'is_active' => true,
            'is_read' => false,
            'subscribed_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Merci pour votre inscription ! Vous recevrez bientôt nos actualités.'
        ]);
    }

    public function newsletterUnsubscribe($token)
    {
        $subscriber = \App\Models\Newsletter::where('token', $token)->first();

        if (!$subscriber) {
            return redirect()->route('home')->with('error', 'Lien de désinscription invalide.');
        }

        $subscriber->update(['is_active' => false]);

        return redirect()->route('home')->with('success', 'Vous avez été désinscrit de notre newsletter.');
    }

    // Formations
    public function allFormations()
    {
        // Forcer la locale AVANT tout traitement
        $locale = $this->ensureLocale();
        
        $formations = [
            [
                'name' => trans('app.formations.languages.html5'),
                'slug' => 'html5',
                'icon' => 'fab fa-html5',
                'color' => '#e34c26',
                'description' => trans('app.formations.html5.description'),
                'route' => route('formations.html5')
            ],
            [
                'name' => trans('app.formations.languages.css3'),
                'slug' => 'css3',
                'icon' => 'fab fa-css3-alt',
                'color' => '#264de4',
                'description' => trans('app.formations.css3.description'),
                'route' => route('formations.css3')
            ],
            [
                'name' => trans('app.formations.languages.javascript'),
                'slug' => 'javascript',
                'icon' => 'fab fa-js',
                'color' => '#f0db4f',
                'description' => trans('app.formations.javascript.description'),
                'route' => route('formations.javascript')
            ],
            [
                'name' => trans('app.formations.languages.php'),
                'slug' => 'php',
                'icon' => 'fab fa-php',
                'color' => '#8993be',
                'description' => trans('app.formations.php.description'),
                'route' => route('formations.php')
            ],
            [
                'name' => trans('app.formations.languages.bootstrap'),
                'slug' => 'bootstrap',
                'icon' => 'fab fa-bootstrap',
                'color' => '#7952b3',
                'description' => trans('app.formations.bootstrap.description'),
                'route' => route('formations.bootstrap')
            ],
            [
                'name' => trans('app.formations.languages.git'),
                'slug' => 'git',
                'icon' => 'fab fa-git-alt',
                'color' => '#f34f29',
                'description' => trans('app.formations.git.description'),
                'route' => route('formations.git')
            ],
            [
                'name' => trans('app.formations.languages.wordpress'),
                'slug' => 'wordpress',
                'icon' => 'fab fa-wordpress',
                'color' => '#21759b',
                'description' => trans('app.formations.wordpress.description'),
                'route' => route('formations.wordpress')
            ],
            [
                'name' => trans('app.formations.languages.ia'),
                'slug' => 'ia',
                'icon' => 'fas fa-robot',
                'color' => '#06b6d4',
                'description' => trans('app.formations.ia.description'),
                'route' => route('formations.ia')
            ],
            [
                'name' => trans('app.formations.languages.python'),
                'slug' => 'python',
                'icon' => 'fab fa-python',
                'color' => '#3776ab',
                'description' => trans('app.formations.python.description'),
                'route' => route('formations.python')
            ],
            [
                'name' => trans('app.formations.languages.java'),
                'slug' => 'java',
                'icon' => 'fab fa-java',
                'color' => '#ed8b00',
                'description' => trans('app.formations.java.description'),
                'route' => route('formations.java')
            ],
            [
                'name' => trans('app.formations.languages.sql'),
                'slug' => 'sql',
                'icon' => 'fas fa-database',
                'color' => '#336791',
                'description' => trans('app.formations.sql.description'),
                'route' => route('formations.sql')
            ],
            [
                'name' => trans('app.formations.languages.c'),
                'slug' => 'c',
                'icon' => 'fab fa-c',
                'color' => '#a8b9cc',
                'description' => trans('app.formations.c.description'),
                'route' => route('formations.c')
            ],
            [
                'name' => trans('app.formations.languages.cpp'),
                'slug' => 'cpp',
                'icon' => 'fab fa-cuttlefish',
                'color' => '#00599c',
                'description' => trans('app.formations.cpp.description'),
                'route' => route('formations.cpp')
            ],
            [
                'name' => trans('app.formations.languages.csharp'),
                'slug' => 'csharp',
                'icon' => 'fab fa-microsoft',
                'color' => '#239120',
                'description' => trans('app.formations.csharp.description'),
                'route' => route('formations.csharp')
            ],
            [
                'name' => trans('app.formations.languages.dart'),
                'slug' => 'dart',
                'icon' => 'fas fa-feather-alt',
                'color' => '#0175c2',
                'description' => trans('app.formations.dart.description'),
                'route' => route('formations.dart')
            ],
        ];
        
        return view('formations.all', compact('formations'));
    }
    
    /**
     * Change la langue de l'application
     */
    public function setLanguage($locale)
    {
        if (in_array($locale, ['fr', 'en'])) {
            Session::put('language', $locale);
            Session::save(); // Forcer la sauvegarde de la session
            App::setLocale($locale);
            \Illuminate\Support\Facades\Lang::setLocale($locale);
            config(['app.locale' => $locale]);
            config(['app.fallback_locale' => 'fr']);
        }
        
        // Récupérer l'URL de redirection depuis le paramètre de requête
        $redirectUrl = request()->get('redirect');
        
        // Si une URL de redirection est fournie et valide (URL relative qui commence par /)
        if ($redirectUrl && strpos($redirectUrl, '/') === 0) {
            // Nettoyer l'URL pour éviter les injections
            $redirectUrl = parse_url($redirectUrl, PHP_URL_PATH);
            if ($redirectUrl) {
                // Ajouter le paramètre lang à l'URL pour forcer la locale
                $separator = strpos($redirectUrl, '?') !== false ? '&' : '?';
                return redirect($redirectUrl . $separator . 'lang=' . $locale);
            }
        }
        
        // Sinon, rediriger vers la page précédente ou la page d'accueil
        return redirect()->back()->with('language_changed', true);
    }

    public function html5()
    {
        $this->ensureLocale();
        
        $progress = null;
        if (Auth::check()) {
            $progress = Auth::user()->getProgressForFormation('html5');
        }
        
        return view('formations.html5', compact('progress'));
    }

    public function css3()
    {
        $this->ensureLocale();
        
        $progress = null;
        if (Auth::check()) {
            $progress = Auth::user()->getProgressForFormation('css3');
        }
        
        return view('formations.css3', compact('progress'));
    }

    public function javascript()
    {
        $this->ensureLocale();
        
        $progress = null;
        if (Auth::check()) {
            $progress = Auth::user()->getProgressForFormation('javascript');
        }
        
        return view('formations.javascript', compact('progress'));
    }

    public function php()
    {
        $locale = $this->ensureLocale();
        view()->share('currentLocale', $locale);
        
        $progress = null;
        if (Auth::check()) {
            $progress = Auth::user()->getProgressForFormation('php');
        }
        
        return view('formations.php', compact('progress'));
    }

    public function python()
    {
        $this->ensureLocale();
        
        $progress = null;
        if (Auth::check()) {
            $progress = Auth::user()->getProgressForFormation('python');
        }
        
        return view('formations.python', compact('progress'));
    }

    public function java()
    {
        $this->ensureLocale();
        
        $progress = null;
        if (Auth::check()) {
            $progress = Auth::user()->getProgressForFormation('java');
        }
        
        return view('formations.java', compact('progress'));
    }

    public function sql()
    {
        $this->ensureLocale();
        
        $progress = null;
        if (Auth::check()) {
            $progress = Auth::user()->getProgressForFormation('sql');
        }
        
        return view('formations.sql', compact('progress'));
    }

    public function c()
    {
        $this->ensureLocale();
        
        $progress = null;
        if (Auth::check()) {
            $progress = Auth::user()->getProgressForFormation('c');
        }
        
        return view('formations.c', compact('progress'));
    }

    public function bootstrap()
    {
        $this->ensureLocale();
        
        $progress = null;
        if (Auth::check()) {
            $progress = Auth::user()->getProgressForFormation('bootstrap');
        }
        
        return view('formations.bootstrap', compact('progress'));
    }

    public function git()
    {
        $this->ensureLocale();
        
        $progress = null;
        if (Auth::check()) {
            $progress = Auth::user()->getProgressForFormation('git');
        }
        
        return view('formations.git', compact('progress'));
    }

    public function wordpress()
    {
        $this->ensureLocale();
        
        $progress = null;
        if (Auth::check()) {
            $progress = Auth::user()->getProgressForFormation('wordpress');
        }
        
        return view('formations.wordpress', compact('progress'));
    }

    public function ia()
    {
        $this->ensureLocale();
        
        $progress = null;
        if (Auth::check()) {
            $progress = Auth::user()->getProgressForFormation('ia');
        }
        
        return view('formations.ia', compact('progress'));
    }

    public function cpp()
    {
        $this->ensureLocale();
        
        $progress = null;
        if (Auth::check()) {
            $progress = Auth::user()->getProgressForFormation('cpp');
        }
        
        return view('formations.cpp', compact('progress'));
    }

    public function csharp()
    {
        $this->ensureLocale();
        
        $progress = null;
        if (Auth::check()) {
            $progress = Auth::user()->getProgressForFormation('csharp');
        }
        
        return view('formations.csharp', compact('progress'));
    }

    public function dart()
    {
        $this->ensureLocale();
        
        $progress = null;
        if (Auth::check()) {
            $progress = Auth::user()->getProgressForFormation('dart');
        }
        
        return view('formations.dart', compact('progress'));
    }

    // Pages Emplois
    public function emplois()
    {
        // Cache les catégories actives avec sélection optimisée (15 minutes - réduit pour avoir des données plus fraîches)
        $categories = \Illuminate\Support\Facades\Cache::remember('active_categories', 900, function () {
            $categories = \App\Models\Category::where('is_active', true)
                ->withCount([
                    'articles' => function($query) {
                        $query->where('status', 'published');
                    }
                ])
                ->select('id', 'name', 'slug', 'description', 'icon', 'image', 'image_type', 'order')
                ->orderBy('order')
                ->get();
            
            // S'assurer que le count est accessible et calculé correctement
            foreach ($categories as $category) {
                // Utiliser articles_count qui est créé par withCount
                // Si articles_count n'existe pas, calculer directement
                if (!isset($category->articles_count)) {
                    $category->articles_count = \App\Models\JobArticle::where('category_id', $category->id)
                        ->where('status', 'published')
                        ->count();
                }
                $category->published_articles_count = $category->articles_count ?? 0;
            }
            
            return $categories;
        });
        
        // Cache les 6 derniers articles avec sélection optimisée (15 minutes)
        $recentArticles = \Illuminate\Support\Facades\Cache::remember('recent_job_articles', 900, function () {
            return \App\Models\JobArticle::where('status', 'published')
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->take(6)
                ->get();
        });
        
        return view('emplois.index', compact('categories', 'recentArticles'));
    }

    public function offresEmploi(Request $request)
    {
        $categorySlug = $request->get('category');
        $page = $request->get('page', 1);
        
        // Cache la catégorie (1 heure)
        if ($categorySlug) {
            $category = \Illuminate\Support\Facades\Cache::remember("category_{$categorySlug}", 3600, function () use ($categorySlug) {
                return \App\Models\Category::where('slug', $categorySlug)->first();
            });
        } else {
            $category = \Illuminate\Support\Facades\Cache::remember('category_offres-emploi', 3600, function () {
                return \App\Models\Category::where('slug', 'offres-emploi')->first();
            });
        }
        
        // Cache optimisé avec eager loading (15 minutes)
        $cacheKey = $category ? "job_articles_category_{$category->id}_page_{$page}" : "job_articles_all_page_{$page}";
        
        $articles = \Illuminate\Support\Facades\Cache::remember($cacheKey, 900, function () use ($category) {
            $query = \App\Models\JobArticle::where('status', 'published')
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views', 'created_at', 'updated_at');
            
            if ($category) {
                $query->where('category_id', $category->id);
            }
            
            // Trier du plus récent au plus ancien avec plusieurs critères
            return $query->orderBy('published_at', 'desc')
                ->orderBy('updated_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        });
        
        return view('emplois.offres', compact('articles', 'category'));
    }

    public function bourses()
    {
        // Cache la catégorie (1 heure)
        $category = \Illuminate\Support\Facades\Cache::remember('category_bourses-etudes', 3600, function () {
            return \App\Models\Category::where('slug', 'bourses-etudes')->first();
        });
        
        // Cache optimisé avec eager loading (15 minutes)
        $cacheKey = $category ? "job_articles_bourses_page_" . request()->get('page', 1) : "job_articles_bourses_all_page_" . request()->get('page', 1);
        
        $articles = \Illuminate\Support\Facades\Cache::remember($cacheKey, 900, function () use ($category) {
            return \App\Models\JobArticle::where('status', 'published')
                ->where('category_id', $category->id ?? null)
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->paginate(12);
        });
        
        return view('emplois.bourses', compact('articles', 'category'));
    }

    public function candidatureSpontanee()
    {
        // Cache la catégorie (1 heure)
        $category = \Illuminate\Support\Facades\Cache::remember('category_candidature-spontanee', 3600, function () {
            return \App\Models\Category::where('slug', 'candidature-spontanee')->first();
        });
        
        // Cache optimisé avec eager loading (15 minutes)
        $cacheKey = "job_articles_candidature_page_" . request()->get('page', 1);
        
        $articles = \Illuminate\Support\Facades\Cache::remember($cacheKey, 900, function () use ($category) {
            return \App\Models\JobArticle::where('status', 'published')
                ->where('category_id', $category->id ?? null)
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->paginate(12);
        });
        
        return view('emplois.candidature', compact('articles', 'category'));
    }

    public function opportunites()
    {
        // Cache la catégorie (1 heure)
        $category = \Illuminate\Support\Facades\Cache::remember('category_opportunites-professionnelles', 3600, function () {
            return \App\Models\Category::where('slug', 'opportunites-professionnelles')->first();
        });
        
        // Cache optimisé avec eager loading (15 minutes)
        $cacheKey = "job_articles_opportunites_page_" . request()->get('page', 1);
        
        $articles = \Illuminate\Support\Facades\Cache::remember($cacheKey, 900, function () use ($category) {
            return \App\Models\JobArticle::where('status', 'published')
                ->where('category_id', $category->id ?? null)
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->paginate(12);
        });
        
        return view('emplois.opportunites', compact('articles', 'category'));
    }

    public function concours()
    {
        // Cache la catégorie (1 heure)
        $category = \Illuminate\Support\Facades\Cache::remember('category_concours', 3600, function () {
            return \App\Models\Category::where('slug', 'concours')->first();
        });
        
        // Cache optimisé avec eager loading (15 minutes)
        $cacheKey = "job_articles_concours_page_" . request()->get('page', 1);
        
        $articles = \Illuminate\Support\Facades\Cache::remember($cacheKey, 900, function () use ($category) {
            return \App\Models\JobArticle::where('status', 'published')
                ->where('category_id', $category->id ?? null)
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->paginate(12);
        });
        
        return view('emplois.concours', compact('articles', 'category'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $category = $request->get('category', '');
        $dateFilter = $request->get('date', '');
        $sortBy = $request->get('sort', 'relevance');
        
        $results = [
            'formations' => collect(),
            'articles' => collect(),
            'total' => 0
        ];
        
        // Récupérer toutes les catégories pour les filtres
        $categories = \Illuminate\Support\Facades\Cache::remember('all_categories_search', 3600, function () {
            return \App\Models\Category::where('is_active', true)
                ->orderBy('name')
                ->get();
        });
        
        if (strlen($query) >= 2) {
            // Recherche dans les formations (titres et descriptions)
            $formations = collect([
                ['name' => 'HTML5', 'url' => route('formations.html5'), 'description' => 'Formation complète HTML5'],
                ['name' => 'CSS3', 'url' => route('formations.css3'), 'description' => 'Formation complète CSS3'],
                ['name' => 'JavaScript', 'url' => route('formations.javascript'), 'description' => 'Formation complète JavaScript'],
                ['name' => 'PHP', 'url' => route('formations.php'), 'description' => 'Formation complète PHP'],
                // Laravel route n'existe pas encore
                // ['name' => 'Laravel', 'url' => route('formations.laravel'), 'description' => 'Formation complète Laravel'],
                ['name' => 'Bootstrap', 'url' => route('formations.bootstrap'), 'description' => 'Formation complète Bootstrap'],
                ['name' => 'Git', 'url' => route('formations.git'), 'description' => 'Formation complète Git'],
                ['name' => 'WordPress', 'url' => route('formations.wordpress'), 'description' => 'Formation complète WordPress'],
                ['name' => 'Intelligence Artificielle', 'url' => route('formations.ia'), 'description' => 'Formation Intelligence Artificielle'],
            ])->filter(function($formation) use ($query) {
                return stripos($formation['name'], $query) !== false || 
                       stripos($formation['description'], $query) !== false;
            });
            
            // Recherche dans les articles d'emploi publiés avec filtres
            $articlesQuery = \App\Models\JobArticle::where('status', 'published')
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%")
                      ->orWhere('excerpt', 'like', "%{$query}%");
                });
            
            // Filtre par catégorie
            if ($category) {
                $articlesQuery->where('category_id', $category);
            }
            
            // Filtre par date
            if ($dateFilter) {
                switch($dateFilter) {
                    case 'today':
                        $articlesQuery->whereDate('published_at', today());
                        break;
                    case 'week':
                        $articlesQuery->where('published_at', '>=', now()->subWeek());
                        break;
                    case 'month':
                        $articlesQuery->where('published_at', '>=', now()->subMonth());
                        break;
                    case 'year':
                        $articlesQuery->where('published_at', '>=', now()->subYear());
                        break;
                }
            }
            
            // Tri
            switch($sortBy) {
                case 'date':
                    $articlesQuery->orderBy('published_at', 'desc');
                    break;
                case 'views':
                    $articlesQuery->orderBy('views', 'desc');
                    break;
                case 'title':
                    $articlesQuery->orderBy('title', 'asc');
                    break;
                case 'relevance':
                default:
                    $articlesQuery->orderBy('published_at', 'desc');
                    break;
            }
            
            $cacheKey = "search_articles_{$query}_{$category}_{$dateFilter}_{$sortBy}";
            $articles = \Illuminate\Support\Facades\Cache::remember($cacheKey, 300, function () use ($articlesQuery) {
                return $articlesQuery->with('category')->limit(50)->get();
            });
            
            $results['formations'] = $formations;
            $results['articles'] = $articles;
            $results['total'] = $formations->count() + $articles->count();
        }
        
        return view('search', compact('query', 'results', 'categories', 'category', 'dateFilter', 'sortBy'));
    }

    public function recentArticles()
    {
        // Cache les 70 articles les plus récents avec optimisation SEO (15 minutes)
        $recentArticles = \Illuminate\Support\Facades\Cache::remember('recent_articles_70_seo', 900, function () {
            return \App\Models\JobArticle::where('status', 'published')
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views', 'meta_title', 'meta_description', 'created_at')
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(70)
                ->get();
        });
        
        return view('emplois.recent-articles', compact('recentArticles'));
    }

    public function categoryArticles($slug)
    {
        $this->ensureLocale();
        
        // Récupérer la catégorie
        $category = \Illuminate\Support\Facades\Cache::remember("category_{$slug}", 900, function () use ($slug) {
            return \App\Models\Category::where('slug', $slug)
                ->where('is_active', true)
                ->firstOrFail();
        });
        
        // Cache les articles de la catégorie (15 minutes)
        $articles = \Illuminate\Support\Facades\Cache::remember("category_articles_{$slug}", 900, function () use ($category) {
            return \App\Models\JobArticle::where('category_id', $category->id)
                ->where('status', 'published')
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        });
        
        // Cache les publicités pour la sidebar (30 minutes)
        $sidebarAds = \Illuminate\Support\Facades\Cache::remember('sidebar_ads_content', 1800, function () {
            return \App\Models\Ad::active()
                ->forPosition('content')
                ->whereNull('location')
                ->orderBy('order')
                ->get();
        });
        
        return view('emplois.category', compact('category', 'articles', 'sidebarAds'));
    }
    
    public function showArticle($slug)
    {
        // Cache l'article avec sélection optimisée (30 minutes)
        $article = \Illuminate\Support\Facades\Cache::remember("job_article_{$slug}", 1800, function () use ($slug) {
            return \App\Models\JobArticle::where('slug', $slug)
                ->where('status', 'published')
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'content', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views', 'meta_title', 'meta_description', 'meta_keywords', 'created_at', 'updated_at')
                ->firstOrFail();
        });
        
        // Incrémenter les vues de manière optimisée (sans recharger l'article)
        \App\Models\JobArticle::where('id', $article->id)->increment('views');
        
        // Cache les articles similaires avec sélection optimisée (15 minutes)
        $relatedArticles = \Illuminate\Support\Facades\Cache::remember("related_articles_{$article->id}", 900, function () use ($article) {
            return \App\Models\JobArticle::where('status', 'published')
                ->where('category_id', $article->category_id)
                ->where('id', '!=', $article->id)
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->take(3)
                ->get();
        });
        
        // Cache les publicités pour la sidebar des articles (30 minutes)
        $sidebarAds = \Illuminate\Support\Facades\Cache::remember('sidebar_ads_articles', 1800, function () {
            return \App\Models\Ad::active()
                ->forPosition('content')
                ->where(function($q) {
                    $q->whereNull('location')
                      ->orWhere('location', 'article_sidebar');
                })
                ->select('id', 'name', 'description', 'image', 'image_type', 'link_url')
                ->orderBy('order')
                ->get();
        });

        // Cache les 3 derniers commentaires approuvés avec sélection optimisée (15 minutes)
        $latestComments = \Illuminate\Support\Facades\Cache::remember("article_latest_comments_{$article->id}", 900, function () use ($article) {
            return \App\Models\Comment::where('commentable_type', 'App\\Models\\JobArticle')
                ->where('commentable_id', $article->id)
                ->where('status', 'approved')
                ->whereNull('parent_id')
                ->select('id', 'name', 'email', 'content', 'created_at', 'user_id')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
        });

        // Cache les commentaires avec sélection optimisée (15 minutes) - Exclure les 3 derniers pour éviter les doublons
        $latestCommentIds = $latestComments->pluck('id')->toArray();
        $comments = \Illuminate\Support\Facades\Cache::remember("article_comments_{$article->id}", 900, function () use ($article, $latestCommentIds) {
            $query = \App\Models\Comment::where('commentable_type', 'App\\Models\\JobArticle')
                ->where('commentable_id', $article->id)
                ->where('status', 'approved')
                ->select('id', 'name', 'email', 'content', 'parent_id', 'created_at', 'user_id');
            if (!empty($latestCommentIds)) {
                $query->whereNotIn('id', $latestCommentIds);
            }
            $comments = $query->orderBy('created_at', 'desc')->get();
            
            // Charger les réponses de manière optimisée
            $commentIds = $comments->pluck('id')->toArray();
            if (!empty($commentIds)) {
                $replies = \App\Models\Comment::whereIn('parent_id', $commentIds)
                    ->where('status', 'approved')
                    ->select('id', 'name', 'email', 'content', 'parent_id', 'created_at', 'user_id')
                    ->get()
                    ->groupBy('parent_id');
                
                foreach ($comments as $comment) {
                    $comment->setRelation('replies', $replies->get($comment->id, collect()));
                }
            }
            
            return $comments;
        });
        
        return view('emplois.article', compact('article', 'relatedArticles', 'sidebarAds', 'comments', 'latestComments'));
    }
}
