<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;

class PageController extends Controller
{
    public function index()
    {
        // Cache les 3 derniers articles publiés (15 minutes) - Optimisé avec select()
        $latestJobs = \Illuminate\Support\Facades\Cache::remember('latest_jobs', 900, function () {
            return \App\Models\JobArticle::where('status', 'published')
                ->with('category:id,name,slug')
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->orderBy('updated_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(3)
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
        
        return view('index', compact('latestJobs', 'sidebarAds', 'homepageAds'));
    }

    public function about()
    {
        return view('about');
    }
    
    public function contact()
    {
        return view('contact');
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

    public function exercices()
    {
        $languages = [
            ['name' => 'HTML5', 'slug' => 'html5', 'icon' => 'fab fa-html5', 'color' => 'orange', 'exercises' => 25],
            ['name' => 'CSS3', 'slug' => 'css3', 'icon' => 'fab fa-css3-alt', 'color' => 'blue', 'exercises' => 30],
            ['name' => 'JavaScript', 'slug' => 'javascript', 'icon' => 'fab fa-js', 'color' => 'yellow', 'exercises' => 35],
            ['name' => 'PHP', 'slug' => 'php', 'icon' => 'fab fa-php', 'color' => 'purple', 'exercises' => 28],
            ['name' => 'Bootstrap', 'slug' => 'bootstrap', 'icon' => 'fab fa-bootstrap', 'color' => 'purple', 'exercises' => 20],
            ['name' => 'Git', 'slug' => 'git', 'icon' => 'fab fa-git-alt', 'color' => 'red', 'exercises' => 15],
            ['name' => 'WordPress', 'slug' => 'wordpress', 'icon' => 'fab fa-wordpress', 'color' => 'blue', 'exercises' => 18],
            ['name' => 'IA', 'slug' => 'ia', 'icon' => 'fas fa-robot', 'color' => 'green', 'exercises' => 12],
            ['name' => 'Python', 'slug' => 'python', 'icon' => 'fab fa-python', 'color' => 'blue', 'exercises' => 22],
        ];
        
        return view('exercices', compact('languages'));
    }

    public function exercicesLanguage($language)
    {
        $allExercises = $this->getExercisesByLanguage($language);
        
        // Varier les exercices par utilisateur (basé sur IP + session)
        $userIdentifier = md5(request()->ip() . session()->getId() . $language);
        $exercises = $this->getVariedExercises($allExercises, $userIdentifier);
        
        return view('exercices-language', compact('language', 'exercises'));
    }
    
    /**
     * Obtenir des exercices variés par utilisateur pour tous les niveaux
     */
    private function getVariedExercises($allExercises, $userIdentifier)
    {
        // Organiser les exercices par niveau
        $byDifficulty = [
            'Facile' => [],
            'Moyen' => [],
            'Difficile' => []
        ];
        
        foreach ($allExercises as $index => $exercise) {
            $difficulty = $exercise['difficulty'] ?? 'Facile';
            if (isset($byDifficulty[$difficulty])) {
                $byDifficulty[$difficulty][] = array_merge($exercise, ['original_index' => $index + 1]);
            }
        }
        
        // Utiliser l'identifiant utilisateur pour créer une variation
        $seed = hexdec(substr($userIdentifier, 0, 8));
        mt_srand($seed);
        
        $selectedExercises = [];
        
        // Prendre TOUS les exercices de chaque niveau (5 Facile + 5 Moyen + 5 Difficile = 15)
        // Facile: tous les exercices (5)
        if (count($byDifficulty['Facile']) > 0) {
            foreach ($byDifficulty['Facile'] as $exercise) {
                $selectedExercises[] = $exercise;
            }
        }
        
        // Moyen: tous les exercices (5)
        if (count($byDifficulty['Moyen']) > 0) {
            foreach ($byDifficulty['Moyen'] as $exercise) {
                $selectedExercises[] = $exercise;
            }
        }
        
        // Difficile: tous les exercices (5)
        if (count($byDifficulty['Difficile']) > 0) {
            foreach ($byDifficulty['Difficile'] as $exercise) {
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
        // Récupérer tous les exercices pour trouver celui correspondant à l'index d'affichage
        $allExercises = $this->getExercisesByLanguage($language);
        
        // Varier les exercices par utilisateur (même logique que dans exercicesLanguage)
        $userIdentifier = md5(request()->ip() . session()->getId() . $language);
        $variedExercises = $this->getVariedExercises($allExercises, $userIdentifier);
        
        // Trouver l'exercice par son index d'affichage
        $exercise = null;
        foreach ($variedExercises as $ex) {
            if (isset($ex['display_index']) && $ex['display_index'] == $id) {
                // Récupérer les détails complets de l'exercice en utilisant le titre pour trouver l'ID
                $exerciseTitle = $ex['title'];
                $originalIndex = $this->findExerciseIndexByTitle($language, $exerciseTitle);
                
                if ($originalIndex) {
                    $exercise = $this->getExerciseDetail($language, $originalIndex);
                    if ($exercise) {
                        $exercise['display_index'] = $id;
                    }
                }
                break;
            }
        }
        
        if (!$exercise) {
            abort(404, 'Exercice non trouvé');
        }
        
        return view('exercice-detail', compact('language', 'id', 'exercise'));
    }
    
    /**
     * Trouver l'index d'un exercice par son titre
     */
    private function findExerciseIndexByTitle($language, $title)
    {
        $allExercises = $this->getExerciseDetail($language, 1); // Juste pour obtenir la structure
        
        // Parcourir tous les exercices pour trouver celui avec le bon titre
        for ($i = 1; $i <= 20; $i++) { // Limite à 20 exercices max par langage
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
        
        return response()->json([
            'correct' => $isCorrect,
            'message' => $isCorrect ? 'Bravo ! Votre réponse est correcte !' : 'Pas tout à fait. Réessayez !',
            'solution' => $isCorrect ? null : $exercise['solution']
        ]);
    }

    public function runCode(Request $request, $language)
    {
        $code = $request->input('code');
        
        // Exécution Python
        if ($language === 'python') {
            // Sécurité : Vérifier que le code ne contient pas de fonctions dangereuses
            $dangerousFunctions = [
                'exec', 'eval', 'compile', '__import__', 'open', 'file',
                'input', 'raw_input', 'execfile', 'reload', '__builtin__',
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
                
                // Écrire le code dans le fichier temporaire
                if (file_put_contents($tempFile, $codeToWrite) === false) {
                    throw new \Exception('Impossible d\'écrire dans le fichier temporaire');
                }
                
                // Essayer différentes commandes Python
                $pythonCommands = ['python3', 'python'];
                $command = null;
                $pythonCmd = null;
                
                foreach ($pythonCommands as $cmd) {
                    // Tester si la commande existe
                    $testCmd = $cmd . ' --version 2>&1';
                    $testOutput = @shell_exec($testCmd);
                    if ($testOutput !== null && strpos($testOutput, 'Python') !== false) {
                        $pythonCmd = $cmd;
                        break;
                    }
                }
                
                if ($pythonCmd === null) {
                    throw new \Exception('Python n\'est pas installé ou non accessible sur le serveur.');
                }
                
                // Exécuter Python avec unbuffered et redirection d'erreur
                $env = $_ENV;
                $env['PYTHONUNBUFFERED'] = '1';
                $envString = '';
                foreach ($env as $key => $value) {
                    $envString .= escapeshellarg($key) . '=' . escapeshellarg($value) . ' ';
                }
                
                // Utiliser exec() pour obtenir le code de retour
                $command = $pythonCmd . ' -u ' . escapeshellarg($tempFile);
                $outputLines = [];
                $returnValue = 0;
                
                // Exécuter avec exec() pour capturer stdout et stderr
                exec($command . ' 2>&1', $outputLines, $returnValue);
                
                // Joindre toutes les lignes de sortie
                $output = implode("\n", $outputLines);
                
                // Séparer stdout et stderr si nécessaire
                $stderr = '';
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
                
                // Supprimer le fichier temporaire
                @unlink($tempFile);
                
                // Nettoyer la sortie
                $output = $output !== null ? rtrim($output, "\r\n") : '';
                $stderr = trim($stderr);
                
                // Si le code de retour n'est pas 0 ou s'il y a des erreurs
                if ($returnValue !== 0 || !empty($stderr)) {
                    $error = $stderr ?: 'Erreur lors de l\'exécution du code Python (code de retour: ' . $returnValue . ')';
                }
                
            } catch (\Exception $e) {
                $error = 'Erreur : ' . $e->getMessage();
                if (isset($tempFile) && file_exists($tempFile)) {
                    @unlink($tempFile);
                }
            }
            
            return response()->json([
                'output' => $output,
                'error' => $error
            ]);
        }
        
        // Sécurité : Ne permettre l'exécution que pour PHP
        if ($language !== 'php') {
            // Pour les autres langages (HTML, CSS, JS), retourner le code tel quel
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
        
        try {
            // Créer un fichier temporaire pour exécuter le code PHP
            $tempFile = tempnam(sys_get_temp_dir(), 'php_exercise_');
            
            // Écrire le code dans le fichier temporaire
            if (file_put_contents($tempFile, $code) === false) {
                throw new \Exception('Impossible d\'écrire dans le fichier temporaire');
            }
            
            // Exécuter le code PHP
            include $tempFile;
            
            // Récupérer la sortie
            $output = ob_get_clean();
            
            // Supprimer le fichier temporaire
            @unlink($tempFile);
            
        } catch (\ParseError $e) {
            $error = 'Erreur de syntaxe : ' . $e->getMessage();
            $output = ob_get_clean();
        } catch (\Error $e) {
            $error = 'Erreur : ' . $e->getMessage();
            $output = ob_get_clean();
        } catch (\Exception $e) {
            $error = 'Exception : ' . $e->getMessage();
            $output = ob_get_clean();
        } catch (\Throwable $e) {
            $error = 'Erreur : ' . $e->getMessage();
            $output = ob_get_clean();
        }
        
        // Nettoyer le fichier temporaire en cas d'erreur
        if (isset($tempFile) && file_exists($tempFile)) {
            @unlink($tempFile);
        }
        
        return response()->json([
            'output' => $output,
            'error' => $error
        ]);
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
        $allExercises = [
            'html5' => [
                1 => [
                    'title' => 'Les balises de base',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Ajoutez un titre "Bienvenue" à la page HTML ci-dessous en utilisant la balise de titre de niveau 1.',
                    'description' => 'Les balises de titre HTML permettent de structurer le contenu. La balise <h1> représente le titre principal de la page. Elle est importante pour le SEO et l\'accessibilité. Complétez le code pour afficher "Bienvenue" comme titre principal.',
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
                    'hint' => 'Utilisez la balise <h1> pour créer un titre de niveau 1. La structure est : <h1>Bienvenue</h1>'
                ],
                2 => [
                    'title' => 'Les paragraphes',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Ajoutez un paragraphe avec le texte "Ceci est un paragraphe." en utilisant la balise appropriée.',
                    'description' => 'Les paragraphes en HTML permettent de structurer le texte. La balise <p> crée un bloc de texte séparé avec un espacement automatique. C\'est l\'élément de base pour afficher du contenu textuel sur une page web.',
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
                    'hint' => 'Utilisez la balise <p> pour créer un paragraphe. La structure est : <p>Ceci est un paragraphe.</p>'
                ],
                3 => [
                    'title' => 'Les liens',
                    'difficulty' => 'Facile',
                    'points' => 15,
                    'instruction' => 'Créez un lien hypertexte vers "https://www.google.com" avec le texte "Aller sur Google". Le lien doit s\'ouvrir dans un nouvel onglet.',
                    'description' => 'Les liens HTML permettent de naviguer entre les pages. La balise <a> avec l\'attribut href crée un lien. L\'attribut target="_blank" ouvre le lien dans un nouvel onglet. Les liens sont essentiels pour la navigation web.',
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
                    'hint' => 'Utilisez <a href="URL" target="_blank">Texte</a> pour créer un lien qui s\'ouvre dans un nouvel onglet. La structure est : <a href="https://www.google.com" target="_blank">Aller sur Google</a>'
                ],
                6 => [
                    'title' => 'Les images',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Ajoutez une image avec la source "logo.png", le texte alternatif "Logo du site" et une largeur de 200 pixels.',
                    'description' => 'Les images enrichissent le contenu web. La balise <img> est auto-fermante et nécessite les attributs src (source) et alt (texte alternatif pour l\'accessibilité). L\'attribut width permet de contrôler la taille de l\'image.',
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
                    'hint' => 'Utilisez <img src="..." alt="..." width="..."> pour ajouter une image. La structure est : <img src="logo.png" alt="Logo du site" width="200">'
                ],
                7 => [
                    'title' => 'Les listes',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Créez une liste non ordonnée avec trois éléments : "HTML", "CSS", "JavaScript". Ajoutez un titre "Langages web" avant la liste.',
                    'description' => 'Les listes permettent d\'organiser l\'information. <ul> crée une liste non ordonnée (à puces), <ol> crée une liste ordonnée (numérotée). Chaque élément utilise la balise <li>. Les listes améliorent la lisibilité et la structure du contenu.',
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
                    'hint' => 'Utilisez <ul> pour la liste non ordonnée et <li> pour chaque élément. La structure est : <ul><li>HTML</li><li>CSS</li><li>JavaScript</li></ul>'
                ],
                8 => [
                    'title' => 'Les tableaux HTML5',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Créez un tableau avec 2 colonnes (Nom, Age) et 3 lignes de données. Utilisez les balises <table>, <thead>, <tbody>, <tr>, <th> et <td>.',
                    'description' => 'Les tableaux HTML permettent d\'organiser des données en lignes et colonnes. <table> crée le tableau, <thead> pour l\'en-tête, <tbody> pour le corps, <tr> pour les lignes, <th> pour les cellules d\'en-tête, et <td> pour les cellules de données.',
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
                    'hint' => 'Structurez le tableau avec <table><thead><tr><th>Nom</th><th>Age</th></tr></thead><tbody><tr><td>...</td></tr></tbody></table>'
                ],
                11 => [
                    'title' => 'Formulaires HTML5 avancés',
                    'difficulty' => 'Difficile',
                    'points' => 25,
                    'instruction' => 'Créez un formulaire complet avec : un champ texte (nom), un email (email), un champ date (date de naissance), un select (pays), une checkbox (conditions), et un bouton submit. Utilisez les attributs HTML5 required, placeholder et type appropriés.',
                    'description' => 'Les formulaires HTML5 offrent de nouveaux types d\'input (email, date, etc.) et attributs (required, placeholder, pattern) pour améliorer l\'expérience utilisateur et la validation côté client. La balise <form> contient tous les champs, et <label> améliore l\'accessibilité.',
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
                    'hint' => 'Utilisez <form> avec <input type="text|email|date">, <select>, <input type="checkbox">, et <button type="submit">. Ajoutez required et placeholder aux inputs.'
                ],
                12 => [
                    'title' => 'Éléments sémantiques HTML5',
                    'difficulty' => 'Difficile',
                    'points' => 25,
                    'instruction' => 'Créez une structure de page complète en utilisant les éléments sémantiques HTML5 : <header>, <nav>, <main>, <article>, <section>, <aside>, et <footer>. Ajoutez du contenu dans chaque section.',
                    'description' => 'Les éléments sémantiques HTML5 (<header>, <nav>, <main>, <article>, <section>, <aside>, <footer>) donnent du sens au contenu et améliorent le SEO et l\'accessibilité. Ils remplacent les <div> génériques par des balises significatives.',
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
                    'hint' => 'Structurez avec <header>, <nav>, <main>, <article>, <section>, <aside>, et <footer>. Chaque élément a un rôle sémantique spécifique.'
                ],
                13 => [
                    'title' => 'Accessibilité HTML5',
                    'difficulty' => 'Difficile',
                    'points' => 30,
                    'instruction' => 'Créez une page accessible avec : attributs ARIA appropriés (aria-label, role), balises <label> liées aux inputs, attribut alt sur les images, et structure de titres hiérarchique (h1, h2, h3).',
                    'description' => 'L\'accessibilité web garantit que tous les utilisateurs peuvent accéder au contenu. Utilisez les attributs ARIA, les labels, les textes alternatifs, et une structure sémantique claire. C\'est essentiel pour les lecteurs d\'écran et le SEO.',
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
                    'hint' => 'Ajoutez role="banner|navigation|main|contentinfo", aria-label, aria-required, et liez les <label> avec for/id. Utilisez alt descriptif sur les images.'
                ],
                4 => [
                    'title' => 'Les titres HTML',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Créez une structure de titres hiérarchique avec h1, h2 et h3. Le h1 doit contenir "Titre principal", le h2 "Sous-titre" et le h3 "Sous-sous-titre".',
                    'description' => 'Les titres HTML (h1 à h6) structurent le contenu de manière hiérarchique. h1 est le titre principal (un seul par page), h2 pour les sections, h3 pour les sous-sections. La hiérarchie est importante pour le SEO et l\'accessibilité.',
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
                    'hint' => 'Utilisez <h1>Titre principal</h1>, <h2>Sous-titre</h2>, et <h3>Sous-sous-titre</h3> pour créer la hiérarchie des titres.'
                ],
                5 => [
                    'title' => 'Les sauts de ligne',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Créez deux lignes de texte séparées en utilisant la balise de saut de ligne. La première ligne doit dire "Première ligne" et la deuxième "Deuxième ligne".',
                    'description' => 'La balise <br> crée un saut de ligne dans le texte. C\'est une balise auto-fermante (<br> ou <br />). Contrairement à <p>, <br> ne crée pas d\'espacement vertical, juste un retour à la ligne. Utilisez-la pour forcer un saut de ligne dans un paragraphe.',
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
                    'hint' => 'Utilisez <br> entre les deux lignes pour créer un saut de ligne. La structure est : <p>Première ligne<br>Deuxième ligne</p>'
                ],
                9 => [
                    'title' => 'Les formulaires de base',
                    'difficulty' => 'Moyen',
                    'points' => 18,
                    'instruction' => 'Créez un formulaire avec un champ texte (nom), un champ email, et un bouton submit. Utilisez les balises <form>, <input>, <label> et <button>.',
                    'description' => 'Les formulaires HTML permettent de collecter des données utilisateur. <form> contient les champs, <label> améliore l\'accessibilité, <input> crée les champs (type="text", type="email"), et <button type="submit"> envoie le formulaire. L\'attribut for dans <label> doit correspondre à l\'id de l\'input.',
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
                    'hint' => 'Utilisez <form> pour contenir le formulaire, <label for="id"> pour les étiquettes, <input type="text|email" id="..." name="..."> pour les champs, et <button type="submit"> pour le bouton.'
                ],
                10 => [
                    'title' => 'Les citations et abréviations',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Créez une citation avec <blockquote> et une abréviation avec <abbr>. La citation doit dire "Le code est poésie" et l\'abréviation "HTML" avec le titre "HyperText Markup Language".',
                    'description' => 'Les balises sémantiques enrichissent le sens du contenu. <blockquote> crée une citation longue (bloc), <q> pour les citations courtes (inline), <abbr> pour les abréviations avec l\'attribut title pour la définition complète. Ces balises améliorent l\'accessibilité et le SEO.',
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
                    'hint' => 'Utilisez <blockquote>Le code est poésie</blockquote> pour la citation et <abbr title="HyperText Markup Language">HTML</abbr> pour l\'abréviation.'
                ],
                14 => [
                    'title' => 'Métadonnées et SEO HTML5',
                    'difficulty' => 'Difficile',
                    'points' => 28,
                    'instruction' => 'Créez une page HTML5 optimisée pour le SEO avec : meta description, meta keywords, Open Graph (og:title, og:description), et un lien canonique. Ajoutez aussi un schema.org de type "Article".',
                    'description' => 'Le SEO (Search Engine Optimization) améliore la visibilité dans les moteurs de recherche. Les meta tags (description, keywords) fournissent des informations aux moteurs. Open Graph améliore le partage sur les réseaux sociaux. Schema.org structure les données pour les moteurs de recherche. C\'est essentiel pour le référencement moderne.',
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
                    'hint' => 'Ajoutez <meta name="description">, <meta name="keywords">, <link rel="canonical">, <meta property="og:title|og:description">, et un <script type="application/ld+json"> avec le schema.org.'
                ],
                15 => [
                    'title' => 'Multimédia HTML5 (audio/vidéo)',
                    'difficulty' => 'Difficile',
                    'points' => 30,
                    'instruction' => 'Intégrez une vidéo HTML5 avec les attributs controls, autoplay, loop, et poster. Ajoutez aussi une piste audio avec <audio> et controls. Utilisez <source> pour plusieurs formats (mp4, webm pour vidéo).',
                    'description' => 'HTML5 introduit <video> et <audio> pour intégrer du multimédia natif. controls affiche les contrôles, autoplay démarre automatiquement, loop répète, poster définit une image de prévisualisation. <source> permet de spécifier plusieurs formats pour la compatibilité. C\'est la méthode moderne pour le multimédia web.',
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
                    'hint' => 'Utilisez <video controls autoplay loop poster="..."> avec <source> pour les formats, et <audio controls> avec <source> pour l\'audio. Ajoutez un texte de fallback entre les balises.'
                ],
            ],
            'css3' => [
                1 => [
                    'title' => 'Les sélecteurs CSS',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Changez la couleur du texte du paragraphe en bleu en utilisant la propriété CSS color.',
                    'description' => 'Les sélecteurs CSS permettent de cibler des éléments HTML pour leur appliquer des styles. La propriété color définit la couleur du texte. Les valeurs peuvent être des noms de couleurs (blue, red), des codes hexadécimaux (#0000FF), ou des valeurs RGB. Complétez le code pour que le paragraphe soit bleu.',
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
                    'hint' => 'Utilisez color: blue; dans le sélecteur p. La syntaxe est : color: valeur;'
                ],
                6 => [
                    'title' => 'Flexbox - Centrage',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Centrez horizontalement et verticalement la div bleue dans son conteneur en utilisant Flexbox.',
                    'description' => 'Flexbox est un système de mise en page CSS moderne qui facilite l\'alignement et la distribution de l\'espace. display: flex active Flexbox, justify-content centre horizontalement, et align-items centre verticalement. C\'est la méthode moderne pour centrer des éléments.',
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
                    'hint' => 'Utilisez display: flex pour activer Flexbox, justify-content: center pour centrer horizontalement, et align-items: center pour centrer verticalement.'
                ],
                7 => [
                    'title' => 'Grid Layout',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Créez une grille CSS avec 3 colonnes de largeur égale pour organiser les éléments.',
                    'description' => 'CSS Grid est un système de mise en page bidimensionnel puissant. display: grid active Grid, et grid-template-columns définit les colonnes. L\'unité fr (fraction) distribue l\'espace disponible. Grid est idéal pour créer des layouts complexes.',
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
                    'hint' => 'Utilisez display: grid pour activer Grid, et grid-template-columns: 1fr 1fr 1fr pour créer 3 colonnes égales.'
                ],
                11 => [
                    'title' => 'Animations CSS',
                    'difficulty' => 'Difficile',
                    'points' => 25,
                    'instruction' => 'Créez une animation CSS qui fait tourner la div violette en continu (rotation 360 degrés).',
                    'description' => 'Les animations CSS permettent de créer des effets visuels fluides sans JavaScript. @keyframes définit les étapes de l\'animation, et la propriété animation applique l\'animation avec durée, timing-function et répétition. linear assure une vitesse constante, infinite répète indéfiniment.',
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
                    'hint' => 'Complétez le @keyframes avec to { transform: rotate(360deg); } et ajoutez animation: rotate 2s linear infinite; à .box.'
                ],
                8 => [
                    'title' => 'Responsive Design',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Rendez le texte rouge sur les petits écrans (largeur maximale 600px) en utilisant une media query.',
                    'description' => 'Le responsive design adapte le design aux différentes tailles d\'écran. Les media queries (@media) permettent d\'appliquer des styles conditionnels selon la largeur de l\'écran. max-width définit la largeur maximale pour laquelle les styles s\'appliquent.',
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
                    'hint' => 'Utilisez @media (max-width: 600px) { p { color: red; } } pour appliquer le style rouge sur les écrans de moins de 600px.'
                ],
                12 => [
                    'title' => 'CSS Variables',
                    'difficulty' => 'Difficile',
                    'points' => 25,
                    'instruction' => 'Créez des variables CSS (--primary-color: blue, --spacing: 20px) et utilisez-les pour styliser les éléments. Changez la couleur de fond et le padding en utilisant var().',
                    'description' => 'Les variables CSS (custom properties) permettent de stocker des valeurs réutilisables. Définies avec --nom-variable, elles sont accessibles via var(). Elles facilitent la maintenance et permettent de créer des thèmes dynamiques. Les variables sont héritées et peuvent être redéfinies.',
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
                    'hint' => 'Définissez les variables dans :root avec --primary-color: blue; et --spacing: 20px; puis utilisez-les avec var(--primary-color) et var(--spacing).'
                ],
                13 => [
                    'title' => 'Advanced Grid Layout',
                    'difficulty' => 'Difficile',
                    'points' => 30,
                    'instruction' => 'Créez une grille CSS complexe avec 3 colonnes (200px, 1fr, 200px), une zone header qui spanne 3 colonnes, et une zone sidebar qui spanne 2 lignes. Utilisez grid-template-areas pour nommer les zones.',
                    'description' => 'CSS Grid avancé permet de créer des layouts complexes avec grid-template-areas pour nommer les zones, grid-column et grid-row pour le spanning, et des tailles mixtes (px, fr, auto). C\'est la méthode moderne pour créer des layouts de type magazine.',
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
                    'hint' => 'Utilisez grid-template-columns: 200px 1fr 200px; grid-template-areas pour nommer les zones, et grid-area pour assigner les éléments aux zones.'
                ],
                2 => [
                    'title' => 'Couleurs et arrière-plans',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Changez la couleur de fond de la div en bleu et la couleur du texte en blanc.',
                    'description' => 'Les propriétés background-color et color contrôlent respectivement la couleur de fond et du texte. Les valeurs peuvent être des noms (blue, white), des codes hex (#0000FF, #FFFFFF), ou des valeurs RGB/rgba. C\'est la base du style CSS.',
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
                    'hint' => 'Utilisez background-color: blue; pour le fond et color: white; pour le texte.'
                ],
                3 => [
                    'title' => 'Marges et padding',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Ajoutez un padding de 20px et une marge de 10px à la div.',
                    'description' => 'Le padding crée un espacement interne (entre le contenu et la bordure), tandis que margin crée un espacement externe (entre l\'élément et les autres éléments). Les valeurs peuvent être en px, em, rem, ou %. C\'est fondamental pour le layout CSS.',
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
                    'hint' => 'Utilisez padding: 20px; pour l\'espacement interne et margin: 10px; pour l\'espacement externe.'
                ],
                4 => [
                    'title' => 'Bordures CSS',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Ajoutez une bordure solide de 2px de couleur rouge autour de la div.',
                    'description' => 'La propriété border crée une bordure autour d\'un élément. La syntaxe est : border: width style color. Les styles courants sont solid, dashed, dotted, double. border peut être défini globalement ou par côté (border-top, border-left, etc.).',
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
                    'hint' => 'Utilisez border: 2px solid red; pour créer une bordure de 2px, solide, rouge.'
                ],
                5 => [
                    'title' => 'Polices et texte',
                    'difficulty' => 'Facile',
                    'points' => 12,
                    'instruction' => 'Changez la police en Arial, la taille en 18px, et mettez le texte en gras.',
                    'description' => 'Les propriétés de texte CSS contrôlent l\'apparence du texte. font-family définit la police, font-size la taille, font-weight l\'épaisseur (normal, bold), font-style le style (normal, italic). C\'est essentiel pour la typographie web.',
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
                    'hint' => 'Utilisez font-family: Arial, sans-serif; font-size: 18px; font-weight: bold;'
                ],
                9 => [
                    'title' => 'Pseudo-classes et pseudo-éléments',
                    'difficulty' => 'Moyen',
                    'points' => 18,
                    'instruction' => 'Changez la couleur du lien en rouge au survol (hover) et ajoutez un soulignement à la première ligne du paragraphe avec ::first-line.',
                    'description' => 'Les pseudo-classes (:hover, :active, :focus) ciblent des états d\'éléments. Les pseudo-éléments (::before, ::after, ::first-line, ::first-letter) ciblent des parties d\'éléments. :hover s\'active au survol, ::first-line cible la première ligne. C\'est puissant pour les interactions.',
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
                    'hint' => 'Utilisez a:hover { color: red; } pour le survol et p::first-line { text-decoration: underline; } pour la première ligne.'
                ],
                10 => [
                    'title' => 'Transitions CSS',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Créez une transition fluide de 0.5s sur le changement de couleur de fond du bouton au survol.',
                    'description' => 'Les transitions CSS créent des animations fluides entre deux états. transition définit la propriété, la durée, et le timing-function. C\'est plus simple que les animations pour des changements d\'état. Les transitions améliorent l\'expérience utilisateur.',
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
                    'hint' => 'Ajoutez transition: background 0.5s; au bouton pour créer une transition fluide de 0.5 secondes sur la couleur de fond.'
                ],
                14 => [
                    'title' => 'Transformations CSS 3D',
                    'difficulty' => 'Difficile',
                    'points' => 28,
                    'instruction' => 'Créez une transformation 3D qui fait tourner la div de 45 degrés sur l\'axe Y (rotateY) avec une perspective de 1000px sur le conteneur parent.',
                    'description' => 'Les transformations 3D CSS créent des effets de profondeur. transform: rotateY() fait tourner sur l\'axe Y, perspective sur le parent crée la profondeur 3D. transform-style: preserve-3d maintient la 3D. C\'est avancé mais puissant pour les effets visuels.',
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
                    'hint' => 'Ajoutez perspective: 1000px; au conteneur et transform: rotateY(45deg); à la box pour créer la rotation 3D.'
                ],
                15 => [
                    'title' => 'CSS Architecture (BEM, SMACSS)',
                    'difficulty' => 'Difficile',
                    'points' => 30,
                    'instruction' => 'Créez une structure CSS suivant la méthodologie BEM (Block Element Modifier). Créez un bloc "card", un élément "card__title", et un modificateur "card--highlighted".',
                    'description' => 'BEM (Block Element Modifier) est une méthodologie de nommage CSS. Block = composant indépendant, Element = partie du bloc (__element), Modifier = variation (--modifier). SMACSS organise le CSS en catégories (Base, Layout, Module, State, Theme). C\'est essentiel pour maintenir du CSS à grande échelle.',
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
                    'title' => 'Variables et types',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Créez une variable "nom" avec votre prénom et affichez-la dans l\'élément avec l\'id "demo".',
                    'description' => 'Les variables JavaScript stockent des valeurs. let permet de déclarer une variable modifiable, const une constante. Les chaînes de caractères sont entre guillemets. document.getElementById() sélectionne un élément par son ID, et innerHTML modifie son contenu HTML.',
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
                    'hint' => 'Utilisez let nom = "VotreNom"; pour déclarer la variable, puis document.getElementById("demo").innerHTML = nom; pour l\'afficher.'
                ],
                2 => [
                    'title' => 'Fonctions',
                    'difficulty' => 'Facile',
                    'points' => 15,
                    'instruction' => 'Créez une fonction qui additionne deux nombres.',
                    'description' => 'Utilisez function pour créer une fonction.',
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
                    'hint' => 'Créez function additionner(a, b) { return a + b; }'
                ],
                3 => [
                    'title' => 'Conditions if/else',
                    'difficulty' => 'Facile',
                    'points' => 12,
                    'instruction' => 'Créez une condition if/else qui affiche "Majeur" si l\'âge est >= 18, sinon "Mineur".',
                    'description' => 'Les conditions if/else permettent d\'exécuter du code de manière conditionnelle. if (condition) exécute le code si la condition est vraie, else exécute le code alternatif. Les opérateurs de comparaison (==, ===, <, >, <=, >=) comparent des valeurs. C\'est fondamental pour la logique de programmation.',
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
                    'hint' => 'Utilisez if (age >= 18) { statut = "Majeur"; } else { statut = "Mineur"; } pour créer la condition.'
                ],
                4 => [
                    'title' => 'Boucles for et while',
                    'difficulty' => 'Facile',
                    'points' => 12,
                    'instruction' => 'Affichez les nombres de 1 à 5 en utilisant une boucle for.',
                    'description' => 'Les boucles répètent du code. for (initialisation; condition; incrément) répète tant que la condition est vraie. while (condition) répète aussi, mais l\'incrément doit être géré manuellement. Les boucles sont essentielles pour traiter des collections de données.',
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
                    'hint' => 'Utilisez for (let i = 1; i <= 5; i++) { texte += i + " "; } pour créer la boucle.'
                ],
                5 => [
                    'title' => 'Opérateurs JavaScript',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Utilisez les opérateurs arithmétiques pour calculer et afficher le résultat de (10 + 5) * 2.',
                    'description' => 'Les opérateurs JavaScript effectuent des opérations. + (addition), - (soustraction), * (multiplication), / (division), % (modulo). Les opérateurs de comparaison (==, ===, !=, !==, <, >, <=, >=) comparent des valeurs. Les opérateurs logiques (&&, ||, !) combinent des conditions.',
                    'startCode' => '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Opérateurs JavaScript</title>
</head>
<body>

<p id="demo"></p>

<script>
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
                    'hint' => 'Utilisez let resultat = (10 + 5) * 2; pour calculer. Les parenthèses contrôlent l\'ordre des opérations.'
                ],
                6 => [
                    'title' => 'DOM Manipulation',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Changez le texte du bouton en "Cliqué !" quand on clique dessus.',
                    'description' => 'Utilisez addEventListener et innerHTML.',
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
                    'hint' => 'Utilisez bouton.addEventListener("click", function() { bouton.innerHTML = "Cliqué !"; });'
                ],
                7 => [
                    'title' => 'Tableaux et boucles',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Affichez tous les fruits du tableau avec une boucle.',
                    'description' => 'Utilisez une boucle for ou forEach.',
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
                    'hint' => 'Utilisez for (let i = 0; i < fruits.length; i++) { texte += fruits[i] + " "; }'
                ],
                8 => [
                    'title' => 'Événements JavaScript',
                    'difficulty' => 'Moyen',
                    'points' => 18,
                    'instruction' => 'Créez un événement qui change la couleur de fond de la div en rouge quand on passe la souris dessus (mouseover) et en bleu quand on la quitte (mouseout).',
                    'description' => 'Les événements JavaScript permettent d\'interagir avec la page. addEventListener attache un gestionnaire d\'événement. mouseover se déclenche au survol, mouseout à la sortie. Les événements sont essentiels pour créer des interfaces interactives.',
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
                    'hint' => 'Utilisez addEventListener("mouseover", ...) pour le survol et addEventListener("mouseout", ...) pour la sortie. Modifiez box.style.backgroundColor.'
                ],
                9 => [
                    'title' => 'Manipulation de chaînes',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Concaténez deux chaînes "Bonjour" et "Monde" avec un espace entre elles, puis convertissez le résultat en majuscules.',
                    'description' => 'Les chaînes JavaScript ont de nombreuses méthodes. + concatène, toUpperCase() met en majuscules, toLowerCase() en minuscules, length retourne la longueur, substring() extrait une partie, indexOf() trouve une position. C\'est essentiel pour manipuler du texte.',
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
                    'hint' => 'Utilisez (chaine1 + " " + chaine2).toUpperCase() pour concaténer avec un espace et convertir en majuscules.'
                ],
                10 => [
                    'title' => 'Fonctions fléchées',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Convertissez la fonction normale en fonction fléchée (arrow function). La fonction doit multiplier deux nombres.',
                    'description' => 'Les fonctions fléchées (=>) sont une syntaxe concise introduite en ES6. (a, b) => a + b est équivalent à function(a, b) { return a + b; }. Elles n\'ont pas leur propre this, ce qui est utile pour les callbacks. C\'est la syntaxe moderne recommandée.',
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
                    'hint' => 'Remplacez function multiplier(a, b) { return a * b; } par const multiplier = (a, b) => a * b;'
                ],
                11 => [
                    'title' => 'Objets JavaScript',
                    'difficulty' => 'Difficile',
                    'points' => 30,
                    'instruction' => 'Créez un objet "personne" avec les propriétés nom et age, puis affichez le nom dans l\'élément avec l\'id "demo".',
                    'description' => 'Les objets JavaScript sont des collections de propriétés (clé-valeur). La syntaxe littérale { propriété: valeur } crée un objet. L\'accès aux propriétés se fait avec point (objet.propriété) ou crochets (objet["propriété"]). Les objets permettent de structurer des données complexes.',
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
                    'hint' => 'Créez let personne = { nom: "Marie", age: 25 }; pour définir l\'objet, puis accédez à la propriété avec personne.nom.'
                ],
                12 => [
                    'title' => 'Promises et Async/Await',
                    'difficulty' => 'Difficile',
                    'points' => 28,
                    'instruction' => 'Créez une fonction asynchrone qui simule une requête API avec fetch(). Affichez les données dans l\'élément avec l\'id "demo". Utilisez async/await pour gérer la promesse.',
                    'description' => 'Les Promises gèrent les opérations asynchrones. async/await est une syntaxe moderne pour travailler avec les promesses. fetch() fait des requêtes HTTP et retourne une Promise. await attend la résolution de la Promise. C\'est essentiel pour les appels API modernes.',
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
                    'hint' => 'Créez async function chargerDonnees() { const response = await fetch("URL"); const data = await response.json(); document.getElementById("demo").innerHTML = data.title; } puis appelez chargerDonnees();'
                ],
                13 => [
                    'title' => 'Closures et Scope',
                    'difficulty' => 'Difficile',
                    'points' => 30,
                    'instruction' => 'Créez une fonction qui retourne une autre fonction (closure). La fonction interne doit avoir accès à une variable de la fonction externe. Appelez la fonction retournée et affichez le résultat.',
                    'description' => 'Les closures permettent à une fonction interne d\'accéder aux variables de la fonction externe même après que la fonction externe soit terminée. Le scope (portée) détermine l\'accessibilité des variables. Les closures sont fondamentales pour la programmation fonctionnelle et la création de fonctions privées.',
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
                    'hint' => 'Créez function creerCompteur() { let compteur = 0; return function() { compteur++; return compteur; }; } puis const incrementer = creerCompteur(); et appelez incrementer().'
                ],
                14 => [
                    'title' => 'Destructuring et Spread',
                    'difficulty' => 'Difficile',
                    'points' => 25,
                    'instruction' => 'Utilisez le destructuring pour extraire "nom" et "age" de l\'objet personne, puis utilisez le spread operator pour copier le tableau fruits dans un nouveau tableau.',
                    'description' => 'Le destructuring extrait des valeurs d\'objets ou tableaux. const {nom, age} = personne extrait les propriétés. Le spread operator (...) copie ou fusionne des tableaux/objets. const nouveau = [...ancien] copie un tableau. C\'est une syntaxe moderne ES6 très utilisée.',
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
                    'hint' => 'Utilisez const { nom, age } = personne; pour le destructuring et const nouveauFruits = [...fruits]; pour le spread.'
                ],
                15 => [
                    'title' => 'Modules ES6 (import/export)',
                    'difficulty' => 'Difficile',
                    'points' => 28,
                    'instruction' => 'Créez un module qui exporte une fonction "multiplier" et importez-la dans le script principal. Utilisez export et import.',
                    'description' => 'Les modules ES6 permettent d\'organiser le code en fichiers séparés. export expose des fonctions/variables, import les importe. C\'est la méthode moderne pour structurer du JavaScript. Les modules améliorent la maintenabilité et permettent le tree-shaking.',
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
                    'title' => 'Syntaxe de base PHP',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Affichez "Bonjour PHP" avec echo.',
                    'description' => 'Utilisez la balise PHP et echo pour afficher du texte.',
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
                    'hint' => 'Utilisez echo "Bonjour PHP"; entre les balises PHP.'
                ],
                2 => [
                    'title' => 'Variables PHP',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Créez une variable $nom avec votre prénom et affichez-la.',
                    'description' => 'Les variables PHP commencent par $.',
                    'startCode' => '<html>
<body>

<?php


?>

</body>
</html>',
                    'solution' => '<html>
<body>

<?php
$nom = "Pierre";
echo $nom;
?>

</body>
</html>',
                    'hint' => 'Créez $nom = "Pierre"; puis echo $nom;'
                ],
                3 => [
                    'title' => 'Opérateurs PHP',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Utilisez les opérateurs arithmétiques pour calculer et afficher le résultat de (10 + 5) * 2.',
                    'description' => 'Les opérateurs PHP effectuent des opérations. + (addition), - (soustraction), * (multiplication), / (division), % (modulo). Les opérateurs de comparaison (==, ===, !=, !==, <, >, <=, >=) comparent des valeurs. Les opérateurs logiques (&&, ||, !) combinent des conditions.',
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
                    'hint' => 'Utilisez $resultat = (10 + 5) * 2; pour calculer. Les parenthèses contrôlent l\'ordre des opérations.'
                ],
                4 => [
                    'title' => 'Chaînes de caractères PHP',
                    'difficulty' => 'Facile',
                    'points' => 12,
                    'instruction' => 'Concaténez deux chaînes "Bonjour" et "PHP" avec un point (.), puis affichez le résultat en majuscules avec strtoupper().',
                    'description' => 'Les chaînes PHP peuvent être concaténées avec le point (.). strtoupper() met en majuscules, strtolower() en minuscules, strlen() retourne la longueur, substr() extrait une partie, strpos() trouve une position. C\'est essentiel pour manipuler du texte.',
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
                    'hint' => 'Utilisez $resultat = strtoupper($chaine1 . " " . $chaine2); pour concaténer avec un espace et convertir en majuscules.'
                ],
                5 => [
                    'title' => 'Commentaires PHP',
                    'difficulty' => 'Facile',
                    'points' => 8,
                    'instruction' => 'Ajoutez un commentaire sur une ligne et un commentaire sur plusieurs lignes dans le code PHP.',
                    'description' => 'Les commentaires PHP documentent le code. // crée un commentaire sur une ligne, # aussi, /* */ crée un commentaire multi-lignes. Les commentaires ne sont pas exécutés. C\'est essentiel pour la maintenabilité du code.',
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
                    'hint' => 'Utilisez // pour un commentaire sur une ligne et /* */ pour un commentaire multi-lignes.'
                ],
                6 => [
                    'title' => 'Conditions PHP',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Affichez "Majeur" si $age >= 18, sinon "Mineur".',
                    'description' => 'Utilisez if/else en PHP.',
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
                    'startCode' => '<html>
<body>

<?php


?>

</body>
</html>',
                    'solution' => '<html>
<body>

<?php
for ($i = 1; $i <= 5; $i++) {
  echo $i . " ";
}
?>

</body>
</html>',
                    'hint' => 'Utilisez for ($i = 1; $i <= 5; $i++) { echo $i . " "; }'
                ],
                8 => [
                    'title' => 'Fonctions PHP',
                    'difficulty' => 'Moyen',
                    'points' => 18,
                    'instruction' => 'Créez une fonction "calculerCarre" qui prend un nombre en paramètre et retourne son carré. Appelez la fonction avec 5 et affichez le résultat.',
                    'description' => 'Les fonctions PHP organisent le code réutilisable. function nomFonction($param) { return valeur; } définit une fonction. return retourne une valeur. Les fonctions peuvent avoir des paramètres par défaut. C\'est essentiel pour éviter la duplication de code.',
                    'startCode' => '<html>
<body>

<?php


$resultat = calculerCarre(5);
echo $resultat;
?>

</body>
</html>',
                    'solution' => '<html>
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
                    'hint' => 'Créez function calculerCarre($nombre) { return $nombre * $nombre; } puis appelez calculerCarre(5).'
                ],
                9 => [
                    'title' => 'Tableaux simples PHP',
                    'difficulty' => 'Moyen',
                    'points' => 18,
                    'instruction' => 'Créez un tableau simple avec 3 fruits, puis affichez chaque fruit avec une boucle foreach.',
                    'description' => 'Les tableaux PHP simples sont indexés numériquement. array() ou [] crée un tableau. foreach parcourt tous les éléments. count() retourne le nombre d\'éléments. Les tableaux sont fondamentaux en PHP.',
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
                    'hint' => 'Créez $fruits = array("Pomme", "Banane", "Orange"); puis foreach ($fruits as $fruit) { echo $fruit . "<br>"; }'
                ],
                10 => [
                    'title' => 'Formulaires PHP (GET/POST)',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Créez un formulaire HTML avec un champ texte "nom" et un bouton submit. Traitez le formulaire en PHP : si le formulaire est soumis (POST), affichez "Bonjour [nom]".',
                    'description' => 'Les formulaires PHP collectent des données utilisateur. $_POST contient les données POST, $_GET contient les données GET. isset() vérifie si une variable existe. Les formulaires sont essentiels pour l\'interaction utilisateur.',
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
                    'hint' => 'Utilisez session_start(); au début, puis $_SESSION["utilisateur"] = "admin"; pour stocker, et $_SESSION["utilisateur"] pour récupérer.'
                ],
                14 => [
                    'title' => 'Traitement des fichiers PHP',
                    'difficulty' => 'Difficile',
                    'points' => 28,
                    'instruction' => 'Créez un fichier "test.txt" avec le contenu "Hello PHP", puis lisez et affichez son contenu. Utilisez file_put_contents() pour écrire et file_get_contents() pour lire.',
                    'description' => 'PHP peut lire et écrire des fichiers. file_put_contents() écrit dans un fichier, file_get_contents() lit un fichier. fopen(), fwrite(), fread(), fclose() offrent plus de contrôle. file_exists() vérifie l\'existence. C\'est essentiel pour la gestion de fichiers.',
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
                    'hint' => 'Utilisez file_put_contents("test.txt", "Hello PHP"); pour écrire et $contenu = file_get_contents("test.txt"); pour lire.'
                ],
                15 => [
                    'title' => 'Exceptions et gestion d\'erreurs',
                    'difficulty' => 'Difficile',
                    'points' => 30,
                    'instruction' => 'Créez une fonction qui divise deux nombres. Utilisez try/catch pour gérer l\'exception si le diviseur est 0. Affichez un message d\'erreur approprié.',
                    'description' => 'Les exceptions PHP gèrent les erreurs de manière élégante. try exécute du code, catch capture les exceptions. throw lance une exception. Exception est la classe de base. C\'est la méthode moderne pour gérer les erreurs.',
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
                    'hint' => 'Ajoutez la classe "col" à chaque div pour créer des colonnes égales. La structure est : container > row > col.'
                ],
                2 => [
                    'title' => 'Bouton Bootstrap',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Créez un bouton bleu primaire avec Bootstrap.',
                    'description' => 'Utilisez les classes btn et btn-primary.',
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
                    'hint' => 'Ajoutez les classes "btn btn-primary" au bouton.'
                ],
                3 => [
                    'title' => 'Typographie Bootstrap',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Utilisez les classes Bootstrap pour créer un titre h1 avec la classe "display-1", un paragraphe avec "lead", et un texte en gras avec "fw-bold".',
                    'description' => 'Bootstrap fournit des classes typographiques prédéfinies. display-1 à display-6 créent de grands titres, lead met en évidence un paragraphe, fw-bold met en gras, text-muted assombrit le texte. C\'est la base de la typographie Bootstrap.',
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
                    'hint' => 'Utilisez <div class="alert alert-success|info|warning"> pour créer les alertes.'
                ],
                6 => [
                    'title' => 'Card Bootstrap',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Complétez la structure d\'une card Bootstrap.',
                    'description' => 'Ajoutez les classes card-body et card-title.',
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
                    'hint' => 'Ajoutez "card-body" à la div et "card-title" au h5.'
                ],
                7 => [
                    'title' => 'Navbar Bootstrap',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Créez une navbar Bootstrap avec un logo et des liens.',
                    'description' => 'Utilisez les classes navbar, navbar-brand et nav-link.',
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
                    'hint' => 'Ajoutez "navbar-brand" au logo et "nav-link" au lien.'
                ],
                8 => [
                    'title' => 'Formulaires Bootstrap',
                    'difficulty' => 'Moyen',
                    'points' => 18,
                    'instruction' => 'Créez un formulaire Bootstrap avec un champ texte (form-control), un label (form-label), et un bouton submit (btn-primary). Utilisez les classes form-group ou mb-3 pour l\'espacement.',
                    'description' => 'Bootstrap stylise les formulaires avec des classes dédiées. form-control stylise les inputs, form-label pour les labels, form-select pour les selects, form-check pour les checkboxes. mb-3 ajoute une marge en bas. C\'est essentiel pour des formulaires professionnels.',
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
                    'hint' => 'Utilisez <div class="mb-3">, <label class="form-label">, <input class="form-control">, et <button class="btn btn-primary">.'
                ],
                9 => [
                    'title' => 'Badges et boutons groupés',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Créez un badge "Nouveau" avec la classe badge bg-primary, et un groupe de boutons avec btn-group contenant 3 boutons.',
                    'description' => 'Les badges Bootstrap affichent de petites étiquettes. badge bg-primary|success|danger crée des badges colorés. btn-group groupe des boutons horizontalement, btn-group-vertical verticalement. C\'est utile pour les tags et les groupes d\'actions.',
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
                    'hint' => 'Utilisez <span class="badge bg-primary"> pour le badge et <div class="btn-group"> pour grouper les boutons.'
                ],
                10 => [
                    'title' => 'Listes et groupes Bootstrap',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Créez une liste groupée Bootstrap (list-group) avec 3 éléments (list-group-item). Ajoutez un élément actif (active) et un élément désactivé (disabled).',
                    'description' => 'Les listes groupées Bootstrap affichent des listes stylisées. list-group contient la liste, list-group-item chaque élément. active met en évidence, disabled désactive. list-group-flush enlève les bordures. C\'est utile pour les menus et les listes de contenu.',
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
                    'hint' => 'Utilisez <ul class="list-group"> et <li class="list-group-item active|disabled"> pour créer la liste groupée.'
                ],
                11 => [
                    'title' => 'Responsive Bootstrap',
                    'difficulty' => 'Difficile',
                    'points' => 20,
                    'instruction' => 'Créez une grille Bootstrap responsive : 12 colonnes (pleine largeur) sur mobile, 6 colonnes sur tablette (md), et 4 colonnes sur desktop (lg).',
                    'description' => 'Bootstrap utilise des breakpoints (sm, md, lg, xl) pour le responsive. col-12 = mobile (12 colonnes), col-md-6 = tablette (6 colonnes), col-lg-4 = desktop (4 colonnes). Les classes s\'appliquent à partir du breakpoint spécifié et au-dessus.',
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
                    'hint' => 'Utilisez "col-12 col-md-6 col-lg-4" pour chaque colonne. col-12 = mobile, col-md-6 = tablette, col-lg-4 = desktop.'
                ],
                12 => [
                    'title' => 'Customisation Bootstrap',
                    'difficulty' => 'Difficile',
                    'points' => 25,
                    'instruction' => 'Créez un fichier SCSS personnalisé qui override les variables Bootstrap ($primary: #06b6d4, $font-family-base: "Arial"). Compilez-le en CSS et incluez-le après Bootstrap.',
                    'description' => 'Bootstrap peut être personnalisé via SCSS en redéfinissant les variables Sass. $primary change la couleur primaire, $font-family-base change la police. Il faut compiler SCSS en CSS. C\'est la méthode recommandée pour personnaliser Bootstrap sans modifier les fichiers source.',
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
                    'hint' => 'Définissez $primary: #06b6d4; et $font-family-base: "Arial", sans-serif; avant @import. Utilisez $primary dans .custom-btn.'
                ],
                13 => [
                    'title' => 'Bootstrap avec JavaScript',
                    'difficulty' => 'Difficile',
                    'points' => 28,
                    'instruction' => 'Créez une modale Bootstrap qui s\'ouvre au clic sur un bouton. Utilisez les attributs data-bs-toggle et data-bs-target, et incluez le JavaScript Bootstrap.',
                    'description' => 'Bootstrap inclut des composants JavaScript interactifs. Les modales nécessitent data-bs-toggle="modal" et data-bs-target="#idModal". Le JavaScript Bootstrap doit être inclus. Les modales sont utiles pour les dialogues, formulaires, et confirmations.',
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
                    'hint' => 'Ajoutez data-bs-toggle="modal" data-bs-target="#maModale" au bouton, tabindex="-1" à la modale, et data-bs-dismiss="modal" aux boutons de fermeture.'
                ],
                14 => [
                    'title' => 'Carousel et composants avancés',
                    'difficulty' => 'Difficile',
                    'points' => 28,
                    'instruction' => 'Créez un carousel Bootstrap avec 3 slides. Utilisez carousel, carousel-inner, carousel-item, et les contrôles (carousel-control-prev, carousel-control-next). Ajoutez aussi les indicateurs (carousel-indicators).',
                    'description' => 'Le carousel Bootstrap crée un diaporama d\'images. carousel contient le carousel, carousel-inner les slides, carousel-item chaque slide. carousel-control-prev/next ajoutent les flèches, carousel-indicators les points. data-bs-ride="carousel" active l\'auto-play. C\'est un composant avancé très utilisé.',
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
                    'hint' => 'Utilisez carousel-indicators pour les points, carousel-inner pour les slides, carousel-item pour chaque slide, et carousel-control-prev/next pour les flèches.'
                ],
                15 => [
                    'title' => 'Système de grille avancé Bootstrap',
                    'difficulty' => 'Difficile',
                    'points' => 30,
                    'instruction' => 'Créez une grille Bootstrap complexe avec offset : 3 colonnes de 2 colonnes chacune (col-md-2), avec un offset de 2 colonnes (offset-md-2) pour la première colonne. Utilisez row et col-md-2 offset-md-2.',
                    'description' => 'Le système de grille Bootstrap avancé permet des layouts complexes. offset-* décale les colonnes, order-* change l\'ordre, align-self-* aligne verticalement. Les offsets créent des espaces sans colonnes vides. C\'est puissant pour des layouts asymétriques.',
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
                    'title' => 'Initialiser un dépôt Git',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Quelle commande initialise un nouveau dépôt Git ?',
                    'description' => 'Tapez la commande Git pour créer un nouveau dépôt.',
                    'startCode' => 'git ',
                    'solution' => 'git init',
                    'hint' => 'La commande commence par "git" et utilise "init".'
                ],
                2 => [
                    'title' => 'Ajouter des fichiers',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Quelle commande ajoute tous les fichiers au staging ?',
                    'description' => 'Utilisez la commande pour ajouter tous les fichiers modifiés.',
                    'startCode' => 'git ',
                    'solution' => 'git add .',
                    'hint' => 'Utilisez "git add" suivi d\'un point pour tout ajouter.'
                ],
                3 => [
                    'title' => 'Créer un commit',
                    'difficulty' => 'Facile',
                    'points' => 15,
                    'instruction' => 'Créez un commit avec le message "Premier commit".',
                    'description' => 'Utilisez la commande commit avec l\'option -m.',
                    'startCode' => 'git ',
                    'solution' => 'git commit -m "Premier commit"',
                    'hint' => 'Utilisez git commit -m suivi du message entre guillemets.'
                ],
                4 => [
                    'title' => 'Voir l\'historique Git',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Quelle commande affiche l\'historique des commits Git avec les messages et les auteurs ?',
                    'description' => 'git log affiche l\'historique des commits. git log --oneline affiche une version compacte, git log --graph affiche un graphique, git log --all affiche toutes les branches. C\'est essentiel pour comprendre l\'historique du projet.',
                    'startCode' => 'git ',
                    'solution' => 'git log',
                    'hint' => 'La commande est git log. Utilisez git log --oneline pour une version compacte.'
                ],
                5 => [
                    'title' => 'Vérifier le statut Git',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Quelle commande affiche le statut actuel du dépôt Git (fichiers modifiés, ajoutés, etc.) ?',
                    'description' => 'git status affiche l\'état actuel du dépôt. Il montre les fichiers modifiés, ajoutés au staging, et non suivis. C\'est la commande la plus utilisée pour voir ce qui a changé. git status -s affiche une version courte.',
                    'startCode' => 'git ',
                    'solution' => 'git status',
                    'hint' => 'La commande est git status. Utilisez git status -s pour une version courte.'
                ],
                6 => [
                    'title' => 'Créer une branche',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Créez une nouvelle branche appelée "develop" et basculez dessus en une seule commande.',
                    'description' => 'Les branches permettent de travailler sur des fonctionnalités isolées. git checkout -b crée et bascule sur une nouvelle branche. Les branches permettent de développer en parallèle sans affecter la branche principale. C\'est essentiel pour le workflow Git.',
                    'startCode' => 'git ',
                    'solution' => 'git checkout -b develop',
                    'hint' => 'Utilisez git checkout -b develop. -b crée la branche et checkout y bascule.'
                ],
                11 => [
                    'title' => 'Fusionner des branches',
                    'difficulty' => 'Difficile',
                    'points' => 20,
                    'instruction' => 'Fusionnez la branche "feature" dans la branche actuelle (main) en créant un merge commit.',
                    'description' => 'git merge combine les changements d\'une branche dans une autre. Il faut être sur la branche de destination. Git crée un merge commit si nécessaire. Les conflits doivent être résolus manuellement. C\'est la méthode standard pour intégrer des fonctionnalités.',
                    'startCode' => 'git ',
                    'solution' => 'git merge feature',
                    'hint' => 'Assurez-vous d\'être sur main, puis utilisez git merge feature pour fusionner.'
                ],
                12 => [
                    'title' => 'Résoudre les conflits',
                    'difficulty' => 'Difficile',
                    'points' => 25,
                    'instruction' => 'Après un merge conflictuel, résolvez le conflit dans le fichier "fichier.txt". Les marqueurs de conflit sont <<<<<<, ======, >>>>>>. Supprimez les marqueurs et gardez la version correcte, puis ajoutez le fichier et complétez le merge.',
                    'description' => 'Les conflits surviennent quand Git ne peut pas fusionner automatiquement. Les marqueurs <<<<<<, ======, >>>>>> indiquent les zones conflictuelles. Il faut éditer manuellement, supprimer les marqueurs, garder le code correct, puis git add et git commit pour finaliser.',
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
                    'hint' => 'Supprimez les marqueurs <<<<<<, ======, >>>>>>, gardez le code correct, puis git add fichier.txt et git commit.'
                ],
                13 => [
                    'title' => 'Git rebase',
                    'difficulty' => 'Difficile',
                    'points' => 28,
                    'instruction' => 'Effectuez un rebase de la branche "feature" sur "main" pour réécrire l\'historique et créer un historique linéaire.',
                    'description' => 'git rebase réapplique les commits d\'une branche sur une autre, créant un historique linéaire. C\'est une alternative à merge. git rebase main réapplique les commits de la branche actuelle sur main. Attention : ne jamais rebaser une branche partagée.',
                    'startCode' => '/* Vous êtes sur la branche feature */
git ',
                    'solution' => 'git rebase main',
                    'hint' => 'Sur la branche feature, utilisez git rebase main pour réappliquer les commits sur main.'
                ],
                7 => [
                    'title' => 'Changer de branche',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Basculez sur la branche "develop" en utilisant la commande appropriée.',
                    'description' => 'git checkout bascule sur une branche existante. git switch (nouvelle syntaxe) fait la même chose. git checkout -b crée et bascule. C\'est essentiel pour travailler sur différentes fonctionnalités en parallèle.',
                    'startCode' => 'git ',
                    'solution' => 'git checkout develop',
                    'hint' => 'Utilisez git checkout develop ou git switch develop pour basculer sur la branche develop.'
                ],
                8 => [
                    'title' => 'Cloner un dépôt distant',
                    'difficulty' => 'Moyen',
                    'points' => 18,
                    'instruction' => 'Clonez le dépôt distant "https://github.com/user/repo.git" dans un dossier local.',
                    'description' => 'git clone copie un dépôt distant localement. git clone URL crée un dossier avec le nom du dépôt, git clone URL nom crée un dossier avec un nom personnalisé. C\'est la première étape pour travailler sur un projet existant.',
                    'startCode' => 'git ',
                    'solution' => 'git clone https://github.com/user/repo.git',
                    'hint' => 'Utilisez git clone suivi de l\'URL du dépôt. git clone https://github.com/user/repo.git'
                ],
                9 => [
                    'title' => 'Pousser vers un dépôt distant',
                    'difficulty' => 'Moyen',
                    'points' => 18,
                    'instruction' => 'Poussez la branche "main" vers le dépôt distant "origin".',
                    'description' => 'git push envoie les commits locaux vers le dépôt distant. git push origin main pousse la branche main vers origin. git push -u origin main configure le tracking. C\'est essentiel pour partager le code.',
                    'startCode' => 'git ',
                    'solution' => 'git push origin main',
                    'hint' => 'Utilisez git push origin main pour pousser la branche main vers origin. git push -u origin main configure le tracking.'
                ],
                10 => [
                    'title' => 'Récupérer les changements (pull)',
                    'difficulty' => 'Moyen',
                    'points' => 18,
                    'instruction' => 'Récupérez et fusionnez les changements du dépôt distant "origin" dans la branche actuelle.',
                    'description' => 'git pull récupère et fusionne les changements du dépôt distant. C\'est équivalent à git fetch suivi de git merge. git pull origin main récupère depuis origin/main. C\'est essentiel pour synchroniser avec le dépôt distant.',
                    'startCode' => 'git ',
                    'solution' => 'git pull origin main',
                    'hint' => 'Utilisez git pull origin main pour récupérer et fusionner les changements. git pull est équivalent à git fetch + git merge.'
                ],
                14 => [
                    'title' => 'Git stash (mise de côté)',
                    'difficulty' => 'Difficile',
                    'points' => 25,
                    'instruction' => 'Mettez de côté les modifications en cours avec git stash, puis récupérez-les avec git stash pop.',
                    'description' => 'git stash sauvegarde temporairement les modifications non commitées. git stash sauvegarde, git stash list liste les stashes, git stash pop récupère et supprime le dernier stash, git stash apply récupère sans supprimer. C\'est utile pour changer de branche sans committer.',
                    'startCode' => '/* Vous avez des modifications non commitées */
git 

/* Changez de branche, travaillez, puis revenez */

git ',
                    'solution' => '/* Mettre de côté */
git stash

/* Après avoir changé de branche et travaillé, récupérer */
git stash pop',
                    'hint' => 'Utilisez git stash pour sauvegarder, puis git stash pop pour récupérer. git stash list affiche tous les stashes.'
                ],
                15 => [
                    'title' => 'Annuler des changements Git',
                    'difficulty' => 'Difficile',
                    'points' => 28,
                    'instruction' => 'Annulez les modifications non commitées dans le fichier "fichier.txt" avec git checkout ou git restore.',
                    'description' => 'git restore (nouveau) ou git checkout -- (ancien) annule les modifications non commitées. git reset --hard annule tout, git reset --soft annule le commit mais garde les modifications. Attention : ces commandes sont destructives. C\'est utile pour corriger des erreurs.',
                    'startCode' => '/* Vous avez modifié fichier.txt mais voulez annuler */
git ',
                    'solution' => 'git restore fichier.txt',
                    'hint' => 'Utilisez git restore fichier.txt (nouveau) ou git checkout -- fichier.txt (ancien) pour annuler les modifications. git restore . annule tout.'
                ],
            ],
            'wordpress' => [
                1 => [
                    'title' => 'The Loop WordPress',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Complétez la boucle WordPress (The Loop) pour afficher les articles avec leur titre et contenu.',
                    'description' => 'The Loop est le cœur de WordPress. have_posts() vérifie s\'il y a des articles, the_post() charge les données de l\'article courant, the_title() et the_content() affichent le titre et le contenu. C\'est la base de tous les templates WordPress.',
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
                    'hint' => 'Utilisez have_posts() dans le if et le while, puis the_post() dans la boucle pour charger les données de l\'article.'
                ],
                2 => [
                    'title' => 'Afficher le titre',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Affichez le titre de l\'article dans un élément h1 en utilisant la fonction WordPress appropriée.',
                    'description' => 'WordPress fournit des fonctions template pour afficher les données. the_title() affiche le titre de l\'article courant. Il existe aussi get_the_title() qui retourne le titre sans l\'afficher. Ces fonctions doivent être utilisées dans The Loop.',
                    'startCode' => '<h1><?php  ?></h1>',
                    'solution' => '<h1><?php the_title(); ?></h1>',
                    'hint' => 'Utilisez the_title() pour afficher le titre. Cette fonction doit être appelée dans The Loop.'
                ],
                3 => [
                    'title' => 'Afficher le contenu',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Affichez le contenu de l\'article dans un élément <div> en utilisant la fonction WordPress appropriée.',
                    'description' => 'WordPress fournit the_content() pour afficher le contenu de l\'article. Il existe aussi get_the_content() qui retourne le contenu sans l\'afficher. Ces fonctions doivent être utilisées dans The Loop. C\'est la base de l\'affichage de contenu.',
                    'startCode' => '<div><?php  ?></div>',
                    'solution' => '<div><?php the_content(); ?></div>',
                    'hint' => 'Utilisez the_content() pour afficher le contenu. Cette fonction doit être appelée dans The Loop.'
                ],
                4 => [
                    'title' => 'Afficher la date',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Affichez la date de publication de l\'article en utilisant the_date() ou get_the_date().',
                    'description' => 'WordPress fournit the_date() pour afficher la date de publication. get_the_date() retourne la date sans l\'afficher. On peut formater la date avec des paramètres. C\'est essentiel pour afficher les métadonnées des articles.',
                    'startCode' => '<p>Publié le : <?php  ?></p>',
                    'solution' => '<p>Publié le : <?php the_date(); ?></p>',
                    'hint' => 'Utilisez the_date() pour afficher la date. Utilisez the_date(\'d/m/Y\') pour formater la date.'
                ],
                5 => [
                    'title' => 'Afficher l\'auteur',
                    'difficulty' => 'Facile',
                    'points' => 12,
                    'instruction' => 'Affichez le nom de l\'auteur de l\'article en utilisant the_author() ou get_the_author().',
                    'description' => 'WordPress fournit the_author() pour afficher le nom de l\'auteur. get_the_author() retourne le nom sans l\'afficher. the_author_meta() permet d\'accéder à d\'autres informations de l\'auteur. C\'est essentiel pour afficher les crédits.',
                    'startCode' => '<p>Auteur : <?php  ?></p>',
                    'solution' => '<p>Auteur : <?php the_author(); ?></p>',
                    'hint' => 'Utilisez the_author() pour afficher le nom de l\'auteur. Cette fonction doit être appelée dans The Loop.'
                ],
                6 => [
                    'title' => 'Image à la une',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Affichez l\'image à la une (featured image) de l\'article avec une taille personnalisée "medium".',
                    'description' => 'Les images à la une enrichissent les articles. has_post_thumbnail() vérifie si une image existe, the_post_thumbnail() l\'affiche. On peut spécifier une taille (\'thumbnail\', \'medium\', \'large\', \'full\'). C\'est essentiel pour un design professionnel.',
                    'startCode' => '<?php
if (has_post_thumbnail()) {
  
}
?>',
                    'solution' => '<?php
if (has_post_thumbnail()) {
  the_post_thumbnail(\'medium\');
}
?>',
                    'hint' => 'Utilisez the_post_thumbnail(\'medium\') pour afficher l\'image avec la taille medium.'
                ],
                7 => [
                    'title' => 'Menu WordPress',
                    'difficulty' => 'Moyen',
                    'points' => 18,
                    'instruction' => 'Affichez le menu WordPress enregistré avec l\'emplacement "primary" en utilisant wp_nav_menu().',
                    'description' => 'Les menus WordPress sont gérés via l\'interface admin. wp_nav_menu() affiche un menu. theme_location correspond à l\'emplacement enregistré avec register_nav_menus(). Les menus sont essentiels pour la navigation du site.',
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
                    'hint' => 'Utilisez \'primary\' comme theme_location. Cet emplacement doit être enregistré dans functions.php avec register_nav_menus().'
                ],
                8 => [
                    'title' => 'Widgets WordPress',
                    'difficulty' => 'Moyen',
                    'points' => 18,
                    'instruction' => 'Enregistrez une zone de widgets (sidebar) avec register_sidebar() dans functions.php, puis affichez-la dans le template avec dynamic_sidebar().',
                    'description' => 'Les widgets WordPress permettent d\'ajouter du contenu dynamique dans les sidebars. register_sidebar() enregistre une zone, dynamic_sidebar() l\'affiche. is_active_sidebar() vérifie si des widgets sont actifs. C\'est essentiel pour les thèmes personnalisables.',
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
                    'hint' => 'Utilisez register_sidebar(array(\'name\' => \'...\', \'id\' => \'...\')) dans functions.php, et dynamic_sidebar(\'id\') dans le template.'
                ],
                9 => [
                    'title' => 'Catégories et tags',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Affichez les catégories et les tags de l\'article en utilisant the_category() et the_tags().',
                    'description' => 'WordPress utilise les catégories et tags pour organiser le contenu. the_category() affiche les catégories, the_tags() affiche les tags. get_the_category() et get_the_tags() retournent les données. C\'est essentiel pour la navigation et l\'organisation.',
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
                    'hint' => 'Utilisez the_category(\', \') pour afficher les catégories séparées par des virgules, et the_tags(\'Tags: \', \', \') pour les tags.'
                ],
                10 => [
                    'title' => 'Pagination WordPress',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Ajoutez la pagination WordPress en utilisant the_posts_pagination() ou paginate_links().',
                    'description' => 'La pagination WordPress divise les articles en pages. the_posts_pagination() affiche la pagination moderne, paginate_links() offre plus de contrôle. previous_posts_link() et next_posts_link() créent des liens simples. C\'est essentiel pour naviguer entre les pages d\'articles.',
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
                    'hint' => 'Utilisez the_posts_pagination() après The Loop pour afficher la pagination. previous_posts_link() et next_posts_link() créent des liens simples.'
                ],
                11 => [
                    'title' => 'Custom Post Type',
                    'difficulty' => 'Difficile',
                    'points' => 20,
                    'instruction' => 'Enregistrez un custom post type "portfolio" avec les paramètres : public => true, has_archive => true, et supports => [\'title\', \'editor\', \'thumbnail\']. Utilisez add_action(\'init\', ...).',
                    'description' => 'Les Custom Post Types étendent WordPress au-delà des articles et pages. register_post_type() crée un nouveau type de contenu. public rend accessible, has_archive active l\'archive, supports définit les fonctionnalités. C\'est essentiel pour des sites complexes.',
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
                    'hint' => 'Utilisez "portfolio" comme premier paramètre, et ajoutez has_archive => true et supports => array(\'title\', \'editor\', \'thumbnail\').'
                ],
                12 => [
                    'title' => 'Les actions et filtres',
                    'difficulty' => 'Difficile',
                    'points' => 25,
                    'instruction' => 'Créez un filtre qui modifie le contenu des articles en ajoutant "Publié par NiangProgrammeur" à la fin. Utilisez add_filter() avec le hook "the_content".',
                    'description' => 'Les hooks (actions et filtres) sont le système d\'extensibilité de WordPress. add_filter() modifie des données, add_action() exécute du code. the_content est un filtre qui permet de modifier le contenu avant affichage. C\'est la base du développement WordPress avancé.',
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
                    'hint' => 'Créez function ajouter_signature($content) { $content .= "..."; return $content; } puis add_filter(\'the_content\', \'ajouter_signature\');'
                ],
                13 => [
                    'title' => 'Créer un thème complet',
                    'difficulty' => 'Difficile',
                    'points' => 30,
                    'instruction' => 'Créez la structure minimale d\'un thème WordPress avec : style.css (avec en-tête), index.php, functions.php, et header.php. Le style.css doit contenir les informations du thème (Theme Name, Author, Version).',
                    'description' => 'Un thème WordPress nécessite au minimum style.css avec l\'en-tête du thème, index.php comme template de fallback, functions.php pour les fonctionnalités, et header.php/footer.php pour la structure. C\'est la base pour créer un thème personnalisé.',
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
                    'hint' => 'Ajoutez Theme Name, Author, Version dans style.css. Créez index.php avec The Loop. Ajoutez add_theme_support() dans functions.php.'
                ],
                14 => [
                    'title' => 'Taxonomies personnalisées',
                    'difficulty' => 'Difficile',
                    'points' => 28,
                    'instruction' => 'Enregistrez une taxonomie personnalisée "genre" pour le custom post type "livre" avec register_taxonomy(). La taxonomie doit être hiérarchique (comme les catégories).',
                    'description' => 'Les taxonomies personnalisées organisent les custom post types. register_taxonomy() crée une taxonomie, hierarchical => true crée une taxonomie hiérarchique (comme catégories), false crée une taxonomie non-hiérarchique (comme tags). C\'est essentiel pour organiser du contenu personnalisé.',
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
                    'hint' => 'Utilisez register_taxonomy(\'genre\', \'livre\', array(\'hierarchical\' => true, ...)) pour créer une taxonomie hiérarchique.'
                ],
                15 => [
                    'title' => 'Meta boxes personnalisées',
                    'difficulty' => 'Difficile',
                    'points' => 30,
                    'instruction' => 'Créez une meta box personnalisée "Prix" pour les articles en utilisant add_meta_box(), et sauvegardez les données avec save_post. Affichez le champ dans l\'éditeur.',
                    'description' => 'Les meta boxes ajoutent des champs personnalisés aux articles. add_meta_box() crée la meta box, save_post sauvegarde les données, get_post_meta() récupère les valeurs. C\'est essentiel pour ajouter des métadonnées personnalisées aux articles.',
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
                    'hint' => 'Utilisez add_meta_box() pour créer, une fonction callback pour afficher, et save_post pour sauvegarder avec update_post_meta().'
                ],
            ],
            'ia' => [
                1 => [
                    'title' => 'Concepts de base IA',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Complétez la définition : L\'IA est la simulation de l\'intelligence ___ par des machines.',
                    'description' => 'L\'Intelligence Artificielle (IA) est un domaine de l\'informatique qui vise à créer des systèmes capables d\'effectuer des tâches nécessitant normalement l\'intelligence humaine. Elle simule les processus cognitifs comme l\'apprentissage, le raisonnement et la résolution de problèmes.',
                    'startCode' => 'L\'IA est la simulation de l\'intelligence ___ par des machines.',
                    'solution' => 'L\'IA est la simulation de l\'intelligence humaine par des machines.',
                    'hint' => 'L\'IA simule l\'intelligence humaine. Réponse : humaine.'
                ],
                2 => [
                    'title' => 'Machine Learning',
                    'difficulty' => 'Facile',
                    'points' => 12,
                    'instruction' => 'Citez les 3 types principaux de Machine Learning (Apprentissage automatique).',
                    'description' => 'Le Machine Learning est un sous-domaine de l\'IA. L\'apprentissage supervisé utilise des données étiquetées, l\'apprentissage non supervisé trouve des patterns sans étiquettes, et l\'apprentissage par renforcement apprend par essais et erreurs avec des récompenses.',
                    'startCode' => '1. Apprentissage supervisé
2. Apprentissage non supervisé
3. Apprentissage ___',
                    'solution' => '1. Apprentissage supervisé
2. Apprentissage non supervisé
3. Apprentissage par renforcement',
                    'hint' => 'Le troisième type est l\'apprentissage par renforcement (reinforcement learning).'
                ],
                3 => [
                    'title' => 'Types d\'apprentissage',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Complétez : L\'apprentissage ___ utilise des données étiquetées pour entraîner un modèle.',
                    'description' => 'L\'apprentissage supervisé utilise des données étiquetées (input et output connus). L\'apprentissage non supervisé trouve des patterns sans étiquettes. L\'apprentissage par renforcement apprend par essais et erreurs avec des récompenses. C\'est la base de la classification des algorithmes ML.',
                    'startCode' => 'L\'apprentissage ___ utilise des données étiquetées pour entraîner un modèle.',
                    'solution' => 'L\'apprentissage supervisé utilise des données étiquetées pour entraîner un modèle.',
                    'hint' => 'L\'apprentissage supervisé utilise des données avec des étiquettes (labels). Réponse : supervisé.'
                ],
                4 => [
                    'title' => 'Données et datasets',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Complétez : Un ___ est un ensemble de données utilisé pour entraîner un modèle d\'IA.',
                    'description' => 'Un dataset est une collection de données structurées. Le dataset d\'entraînement entraîne le modèle, le dataset de test évalue les performances, le dataset de validation ajuste les hyperparamètres. La qualité du dataset détermine la qualité du modèle. C\'est fondamental en ML.',
                    'startCode' => 'Un ___ est un ensemble de données utilisé pour entraîner un modèle d\'IA.',
                    'solution' => 'Un dataset est un ensemble de données utilisé pour entraîner un modèle d\'IA.',
                    'hint' => 'Un dataset est un ensemble de données. Réponse : dataset.'
                ],
                5 => [
                    'title' => 'Algorithmes de base',
                    'difficulty' => 'Facile',
                    'points' => 12,
                    'instruction' => 'Citez 2 algorithmes de Machine Learning couramment utilisés pour la classification.',
                    'description' => 'Les algorithmes ML de base incluent la régression linéaire (prédiction), la classification (KNN, SVM, Decision Tree), le clustering (K-means), et les réseaux de neurones. Chaque algorithme a ses forces et faiblesses selon le type de problème.',
                    'startCode' => 'Algorithmes de classification :
1. ___
2. ___',
                    'solution' => 'Algorithmes de classification :
1. Decision Tree (Arbre de décision)
2. K-Nearest Neighbors (KNN)',
                    'hint' => 'Exemples d\'algorithmes de classification : Decision Tree, KNN, SVM, Naive Bayes, Random Forest.'
                ],
                6 => [
                    'title' => 'Réseaux de neurones',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Complétez la structure d\'un réseau de neurones : Couche d\'entrée → Couches ___ → Couche de sortie.',
                    'description' => 'Les réseaux de neurones artificiels sont inspirés du cerveau. Ils ont une couche d\'entrée (input), des couches cachées (hidden layers) qui traitent les données, et une couche de sortie (output). Plus il y a de couches cachées, plus le réseau est "profond" (deep learning).',
                    'startCode' => 'Couche d\'entrée → Couches ___ → Couche de sortie',
                    'solution' => 'Couche d\'entrée → Couches cachées → Couche de sortie',
                    'hint' => 'Les couches intermédiaires sont appelées "couches cachées" (hidden layers).'
                ],
                7 => [
                    'title' => 'Deep Learning',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Quelle bibliothèque Python open-source, développée par Google, est la plus populaire pour le Deep Learning ? Complétez : import ___.',
                    'description' => 'TensorFlow est une bibliothèque open-source développée par Google pour le machine learning et le deep learning. Elle permet de créer et entraîner des réseaux de neurones complexes. Keras est une API haut niveau qui s\'appuie sur TensorFlow.',
                    'startCode' => 'import ___',
                    'solution' => 'import tensorflow',
                    'hint' => 'La bibliothèque s\'appelle TensorFlow. Réponse : tensorflow.'
                ],
                8 => [
                    'title' => 'Entraînement d\'un modèle',
                    'difficulty' => 'Moyen',
                    'points' => 18,
                    'instruction' => 'Complétez : L\'___ est le processus d\'ajustement des paramètres d\'un modèle pour minimiser l\'erreur.',
                    'description' => 'L\'entraînement (training) ajuste les paramètres du modèle pour minimiser l\'erreur. L\'epoch est une passe complète sur le dataset. Le batch size est le nombre d\'échantillons traités avant la mise à jour. Le learning rate contrôle la vitesse d\'apprentissage. C\'est le cœur du ML.',
                    'startCode' => 'L\'___ est le processus d\'ajustement des paramètres d\'un modèle pour minimiser l\'erreur.',
                    'solution' => 'L\'entraînement (training) est le processus d\'ajustement des paramètres d\'un modèle pour minimiser l\'erreur.',
                    'hint' => 'L\'entraînement (training) ajuste les paramètres. Réponse : entraînement ou training.'
                ],
                9 => [
                    'title' => 'Évaluation de performance',
                    'difficulty' => 'Moyen',
                    'points' => 18,
                    'instruction' => 'Citez 2 métriques utilisées pour évaluer les performances d\'un modèle de classification.',
                    'description' => 'Les métriques évaluent les performances. Accuracy (précision globale), Precision (précision des prédictions positives), Recall (taux de détection), F1-score (moyenne harmonique). Pour la régression : MSE, MAE, R². Le choix dépend du problème. C\'est essentiel pour valider un modèle.',
                    'startCode' => 'Métriques de classification :
1. ___
2. ___',
                    'solution' => 'Métriques de classification :
1. Accuracy (Précision)
2. F1-score',
                    'hint' => 'Métriques courantes : Accuracy, Precision, Recall, F1-score, Confusion Matrix.'
                ],
                10 => [
                    'title' => 'Bibliothèques Python (TensorFlow, PyTorch)',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Complétez : ___ est une bibliothèque Python open-source développée par Facebook pour le Deep Learning.',
                    'description' => 'TensorFlow (Google) et PyTorch (Facebook) sont les deux principales bibliothèques de Deep Learning. TensorFlow est plus mature, PyTorch est plus flexible. Keras est une API haut niveau sur TensorFlow. Scikit-learn est pour le ML classique. C\'est essentiel pour développer en IA.',
                    'startCode' => '___ est une bibliothèque Python open-source développée par Facebook pour le Deep Learning.',
                    'solution' => 'PyTorch est une bibliothèque Python open-source développée par Facebook pour le Deep Learning.',
                    'hint' => 'PyTorch est développé par Facebook. TensorFlow est développé par Google. Réponse : PyTorch.'
                ],
                11 => [
                    'title' => 'Applications IA',
                    'difficulty' => 'Difficile',
                    'points' => 18,
                    'instruction' => 'Citez 3 applications concrètes de l\'IA dans la vie quotidienne.',
                    'description' => 'L\'IA est partout : reconnaissance vocale (Siri, Alexa), voitures autonomes (Tesla), assistants virtuels, recommandations (Netflix, Amazon), reconnaissance d\'images, traduction automatique, chatbots, diagnostic médical, etc. L\'IA transforme de nombreux secteurs.',
                    'startCode' => '1. Reconnaissance ___
2. Voitures ___
3. Assistants ___',
                    'solution' => '1. Reconnaissance vocale
2. Voitures autonomes
3. Assistants virtuels',
                    'hint' => 'Exemples : reconnaissance vocale (Siri), voitures autonomes (Tesla), assistants virtuels (ChatGPT).'
                ],
                12 => [
                    'title' => 'Natural Language Processing',
                    'difficulty' => 'Difficile',
                    'points' => 25,
                    'instruction' => 'Expliquez ce qu\'est le NLP (Natural Language Processing) et donnez 2 exemples d\'applications.',
                    'description' => 'Le NLP (Traitement du Langage Naturel) permet aux machines de comprendre et générer le langage humain. Applications : traduction automatique (Google Translate), chatbots, analyse de sentiment, résumé automatique, assistants vocaux. Les modèles modernes comme GPT utilisent le NLP.',
                    'startCode' => 'NLP signifie : ___

Applications :
1. ___
2. ___',
                    'solution' => 'NLP signifie : Natural Language Processing (Traitement du Langage Naturel)

Applications :
1. Traduction automatique
2. Chatbots et assistants vocaux',
                    'hint' => 'NLP = Natural Language Processing. Applications : traduction, chatbots, analyse de sentiment, etc.'
                ],
                13 => [
                    'title' => 'Éthique de l\'IA',
                    'difficulty' => 'Difficile',
                    'points' => 30,
                    'instruction' => 'Citez 3 principes éthiques importants dans le développement de l\'IA (ex: transparence, équité, confidentialité).',
                    'description' => 'L\'éthique de l\'IA est cruciale. Principes clés : transparence (compréhensibilité), équité (pas de biais), confidentialité (protection des données), responsabilité (qui est responsable), robustesse (sécurité). Ces principes guident le développement responsable de l\'IA.',
                    'startCode' => 'Principes éthiques de l\'IA :
1. ___
2. ___
3. ___',
                    'solution' => 'Principes éthiques de l\'IA :
1. Transparence
2. Équité (absence de biais)
3. Confidentialité et protection des données',
                    'hint' => 'Principes : transparence, équité, confidentialité, responsabilité, robustesse.'
                ],
                14 => [
                    'title' => 'Computer Vision',
                    'difficulty' => 'Difficile',
                    'points' => 28,
                    'instruction' => 'Expliquez ce qu\'est la Computer Vision et donnez 2 applications concrètes.',
                    'description' => 'La Computer Vision permet aux machines de comprendre les images. Applications : reconnaissance d\'objets, classification d\'images, détection de visages, OCR (reconnaissance de texte), voitures autonomes, imagerie médicale. Les CNN (Convolutional Neural Networks) sont la base. C\'est un domaine en pleine expansion.',
                    'startCode' => 'Computer Vision signifie : ___

Applications :
1. ___
2. ___',
                    'solution' => 'Computer Vision signifie : Vision par ordinateur (analyse et compréhension d\'images par les machines)

Applications :
1. Reconnaissance d\'objets et classification d\'images
2. Détection de visages et OCR (reconnaissance de texte)',
                    'hint' => 'Computer Vision = Vision par ordinateur. Applications : reconnaissance d\'objets, classification, OCR, voitures autonomes, imagerie médicale.'
                ],
                15 => [
                    'title' => 'Optimisation et hyperparamètres',
                    'difficulty' => 'Difficile',
                    'points' => 30,
                    'instruction' => 'Citez 3 hyperparamètres importants à ajuster lors de l\'entraînement d\'un réseau de neurones.',
                    'description' => 'Les hyperparamètres contrôlent l\'entraînement. Learning rate (vitesse d\'apprentissage), batch size (taille des lots), nombre d\'epochs (itérations), nombre de couches, nombre de neurones par couche. L\'optimisation des hyperparamètres améliore les performances. C\'est un processus itératif.',
                    'startCode' => 'Hyperparamètres importants :
1. ___
2. ___
3. ___',
                    'solution' => 'Hyperparamètres importants :
1. Learning rate (taux d\'apprentissage)
2. Batch size (taille des lots)
3. Nombre d\'epochs (itérations)',
                    'hint' => 'Hyperparamètres clés : learning rate, batch size, nombre d\'epochs, nombre de couches, nombre de neurones, fonction d\'activation.'
                ],
            ],
            'python' => [
                1 => [
                    'title' => 'Syntaxe de base Python',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Affichez le message "Bonjour Python !" en utilisant la fonction print().',
                    'description' => 'La fonction print() est la fonction de base pour afficher du texte en Python. Elle permet d\'afficher des chaînes de caractères, des variables, et des expressions. C\'est l\'une des premières fonctions que vous apprendrez en Python.',
                    'startCode' => '# Affichez "Bonjour Python !"
',
                    'solution' => '# Affichez "Bonjour Python !"
print("Bonjour Python !")',
                    'hint' => 'Utilisez print("Bonjour Python !") pour afficher le message.'
                ],
                2 => [
                    'title' => 'Variables Python',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Créez une variable nom avec la valeur "Python" et affichez-la.',
                    'description' => 'En Python, les variables sont créées simplement en leur assignant une valeur. Pas besoin de déclarer le type. Python est un langage à typage dynamique.',
                    'startCode' => '# Créez une variable nom avec la valeur "Python"
# Affichez la variable
',
                    'solution' => '# Créez une variable nom avec la valeur "Python"
nom = "Python"
# Affichez la variable
print(nom)',
                    'hint' => 'Créez la variable avec nom = "Python" puis utilisez print(nom) pour l\'afficher.'
                ],
                3 => [
                    'title' => 'Types de données Python',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Créez trois variables : un entier (age = 25), un décimal (prix = 19.99), et une chaîne (nom = "Python").',
                    'description' => 'Python a plusieurs types de données : int (entiers), float (décimaux), str (chaînes de caractères), bool (booléens), list (listes), dict (dictionnaires), etc.',
                    'startCode' => '# Créez les trois variables
',
                    'solution' => '# Créez les trois variables
age = 25
prix = 19.99
nom = "Python"',
                    'hint' => 'age = 25 (int), prix = 19.99 (float), nom = "Python" (str).'
                ],
                4 => [
                    'title' => 'Opérateurs Python',
                    'difficulty' => 'Facile',
                    'points' => 10,
                    'instruction' => 'Calculez la somme de 10 et 5, puis affichez le résultat.',
                    'description' => 'Python supporte les opérateurs arithmétiques : + (addition), - (soustraction), * (multiplication), / (division), // (division entière), % (modulo), ** (puissance).',
                    'startCode' => '# Calculez 10 + 5 et affichez le résultat
',
                    'solution' => '# Calculez 10 + 5 et affichez le résultat
resultat = 10 + 5
print(resultat)',
                    'hint' => 'Utilisez resultat = 10 + 5 puis print(resultat).'
                ],
                5 => [
                    'title' => 'Commentaires Python',
                    'difficulty' => 'Facile',
                    'points' => 8,
                    'instruction' => 'Ajoutez un commentaire expliquant ce que fait le code suivant.',
                    'description' => 'Les commentaires en Python commencent par #. Ils permettent d\'expliquer le code et ne sont pas exécutés. Les commentaires sont essentiels pour rendre le code lisible.',
                    'startCode' => 'nom = "Python"
print(nom)',
                    'solution' => '# Définit une variable nom avec la valeur "Python"
nom = "Python"
# Affiche la valeur de la variable nom
print(nom)',
                    'hint' => 'Ajoutez des commentaires avec # avant chaque ligne pour expliquer ce qu\'elle fait.'
                ],
                6 => [
                    'title' => 'Conditions if/elif/else',
                    'difficulty' => 'Moyen',
                    'points' => 15,
                    'instruction' => 'Créez une condition qui affiche "Majeur" si age >= 18, sinon "Mineur".',
                    'description' => 'Les structures conditionnelles permettent d\'exécuter du code selon certaines conditions. if teste une condition, elif teste une autre condition si la première est fausse, else exécute le code si toutes les conditions sont fausses.',
                    'startCode' => 'age = 20
# Ajoutez la condition
',
                    'solution' => 'age = 20
# Ajoutez la condition
if age >= 18:
    print("Majeur")
else:
    print("Mineur")',
                    'hint' => 'Utilisez if age >= 18: print("Majeur") else: print("Mineur"). N\'oubliez pas les deux-points et l\'indentation !'
                ],
                7 => [
                    'title' => 'Boucles for et while',
                    'difficulty' => 'Moyen',
                    'points' => 18,
                    'instruction' => 'Utilisez une boucle for pour afficher les nombres de 1 à 5.',
                    'description' => 'Les boucles permettent de répéter du code. La boucle for itère sur une séquence (liste, chaîne, range). La boucle while répète tant qu\'une condition est vraie.',
                    'startCode' => '# Affichez les nombres de 1 à 5 avec une boucle for
',
                    'solution' => '# Affichez les nombres de 1 à 5 avec une boucle for
for i in range(1, 6):
    print(i)',
                    'hint' => 'Utilisez for i in range(1, 6): print(i). range(1, 6) génère les nombres de 1 à 5.'
                ],
                8 => [
                    'title' => 'Fonctions Python',
                    'difficulty' => 'Moyen',
                    'points' => 18,
                    'instruction' => 'Créez une fonction saluer(nom) qui retourne "Bonjour, [nom] !" et appelez-la avec "Python".',
                    'description' => 'Les fonctions permettent de réutiliser du code. On définit une fonction avec def. Les fonctions peuvent prendre des paramètres et retourner des valeurs avec return.',
                    'startCode' => '# Créez la fonction saluer
# Appelez-la avec "Python"
',
                    'solution' => '# Créez la fonction saluer
def saluer(nom):
    return f"Bonjour, {nom} !"

# Appelez-la avec "Python"
print(saluer("Python"))',
                    'hint' => 'def saluer(nom): return f"Bonjour, {nom} !" puis print(saluer("Python")).'
                ],
                9 => [
                    'title' => 'Listes Python',
                    'difficulty' => 'Moyen',
                    'points' => 18,
                    'instruction' => 'Créez une liste fruits avec ["pomme", "banane", "orange"] et affichez le premier élément.',
                    'description' => 'Les listes sont des collections ordonnées et modifiables. On accède aux éléments par index (commence à 0). Les listes supportent de nombreuses méthodes : append(), remove(), sort(), etc.',
                    'startCode' => '# Créez la liste fruits
# Affichez le premier élément
',
                    'solution' => '# Créez la liste fruits
fruits = ["pomme", "banane", "orange"]
# Affichez le premier élément
print(fruits[0])',
                    'hint' => 'fruits = ["pomme", "banane", "orange"] puis print(fruits[0]) pour le premier élément.'
                ],
                10 => [
                    'title' => 'Dictionnaires Python',
                    'difficulty' => 'Moyen',
                    'points' => 20,
                    'instruction' => 'Créez un dictionnaire personne avec les clés "nom" et "age", puis affichez la valeur de "nom".',
                    'description' => 'Les dictionnaires stockent des paires clé-valeur. On accède aux valeurs par leur clé. Les dictionnaires sont très utiles pour représenter des données structurées.',
                    'startCode' => '# Créez le dictionnaire personne
# Affichez la valeur de "nom"
',
                    'solution' => '# Créez le dictionnaire personne
personne = {"nom": "Python", "age": 30}
# Affichez la valeur de "nom"
print(personne["nom"])',
                    'hint' => 'personne = {"nom": "Python", "age": 30} puis print(personne["nom"]).'
                ],
                11 => [
                    'title' => 'Programmation Orientée Objet',
                    'difficulty' => 'Difficile',
                    'points' => 30,
                    'instruction' => 'Créez une classe Personne avec un constructeur __init__ qui prend nom et age, puis créez un objet personne1.',
                    'description' => 'La POO permet de créer des classes et des objets. Une classe est un modèle, un objet est une instance. Le constructeur __init__ est appelé lors de la création d\'un objet. self représente l\'instance.',
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
                    'hint' => 'class Personne: def __init__(self, nom, age): self.nom = nom; self.age = age puis personne1 = Personne("Python", 30).'
                ],
                12 => [
                    'title' => 'Modules et packages',
                    'difficulty' => 'Difficile',
                    'points' => 25,
                    'instruction' => 'Importez le module math et utilisez math.sqrt(16) pour calculer la racine carrée de 16.',
                    'description' => 'Les modules permettent d\'organiser le code et de réutiliser des fonctions. Python a une vaste bibliothèque standard. On importe avec import. On peut aussi importer des fonctions spécifiques avec from module import fonction.',
                    'startCode' => '# Importez math et calculez sqrt(16)
',
                    'solution' => '# Importez math et calculez sqrt(16)
import math
resultat = math.sqrt(16)
print(resultat)',
                    'hint' => 'import math puis resultat = math.sqrt(16) et print(resultat).'
                ],
                13 => [
                    'title' => 'Gestion des exceptions',
                    'difficulty' => 'Difficile',
                    'points' => 28,
                    'instruction' => 'Utilisez try/except pour gérer une erreur de division par zéro.',
                    'description' => 'Les exceptions permettent de gérer les erreurs. try exécute le code, except capture les exceptions. C\'est essentiel pour créer des programmes robustes.',
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
                    'hint' => 'try: resultat = a / b; print(resultat) except ZeroDivisionError: print("Erreur : Division par zéro !").'
                ],
                14 => [
                    'title' => 'Manipulation de fichiers',
                    'difficulty' => 'Difficile',
                    'points' => 28,
                    'instruction' => 'Écrivez "Bonjour Python !" dans un fichier texte nommé "fichier.txt".',
                    'description' => 'Python permet de lire et écrire dans des fichiers. On utilise open() avec les modes "r" (lecture), "w" (écriture), "a" (ajout). Il est recommandé d\'utiliser with pour garantir la fermeture du fichier.',
                    'startCode' => '# Écrivez dans le fichier
',
                    'solution' => '# Écrivez dans le fichier
with open("fichier.txt", "w") as f:
    f.write("Bonjour Python !")',
                    'hint' => 'with open("fichier.txt", "w") as f: f.write("Bonjour Python !").'
                ],
                15 => [
                    'title' => 'Compréhensions de listes et générateurs',
                    'difficulty' => 'Difficile',
                    'points' => 30,
                    'instruction' => 'Créez une liste des carrés des nombres de 1 à 5 en utilisant une compréhension de liste.',
                    'description' => 'Les compréhensions de listes permettent de créer des listes de manière concise. Syntaxe : [expression for item in iterable]. Les générateurs sont similaires mais utilisent () au lieu de [] et sont plus efficaces en mémoire.',
                    'startCode' => '# Créez la liste des carrés avec une compréhension
',
                    'solution' => '# Créez la liste des carrés avec une compréhension
carres = [x**2 for x in range(1, 6)]
print(carres)',
                    'hint' => 'carres = [x**2 for x in range(1, 6)] crée [1, 4, 9, 16, 25].'
                ],
            ],
        ];

        return $allExercises[$language][$id] ?? null;
    }

    private function getExercisesByLanguage($language)
    {
        // Exemples d'exercices par langage
        $allExercises = [
            'html5' => [
                // Niveau Facile (5 exercices)
                ['title' => 'Les balises de base', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Les paragraphes', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Les liens', 'difficulty' => 'Facile', 'points' => 15],
                ['title' => 'Les titres HTML', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Les sauts de ligne', 'difficulty' => 'Facile', 'points' => 10],
                // Niveau Moyen (5 exercices)
                ['title' => 'Les images', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Les listes', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Les tableaux HTML5', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Les formulaires de base', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Les citations et abréviations', 'difficulty' => 'Moyen', 'points' => 15],
                // Niveau Difficile (5 exercices)
                ['title' => 'Formulaires HTML5 avancés', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'Éléments sémantiques HTML5', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'Accessibilité HTML5', 'difficulty' => 'Difficile', 'points' => 30],
                ['title' => 'Métadonnées et SEO HTML5', 'difficulty' => 'Difficile', 'points' => 28],
                ['title' => 'Multimédia HTML5 (audio/vidéo)', 'difficulty' => 'Difficile', 'points' => 30],
            ],
            'css3' => [
                // Niveau Facile (5 exercices)
                ['title' => 'Les sélecteurs CSS', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Couleurs et arrière-plans', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Marges et padding', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Bordures CSS', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Polices et texte', 'difficulty' => 'Facile', 'points' => 12],
                // Niveau Moyen (5 exercices)
                ['title' => 'Flexbox - Centrage', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Grid Layout', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Responsive Design', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Pseudo-classes et pseudo-éléments', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Transitions CSS', 'difficulty' => 'Moyen', 'points' => 20],
                // Niveau Difficile (5 exercices)
                ['title' => 'Animations CSS', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'CSS Variables', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'Advanced Grid Layout', 'difficulty' => 'Difficile', 'points' => 30],
                ['title' => 'Transformations CSS 3D', 'difficulty' => 'Difficile', 'points' => 28],
                ['title' => 'CSS Architecture (BEM, SMACSS)', 'difficulty' => 'Difficile', 'points' => 30],
            ],
            'javascript' => [
                // Niveau Facile (5 exercices)
                ['title' => 'Variables et types', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Fonctions', 'difficulty' => 'Facile', 'points' => 15],
                ['title' => 'Conditions if/else', 'difficulty' => 'Facile', 'points' => 12],
                ['title' => 'Boucles for et while', 'difficulty' => 'Facile', 'points' => 12],
                ['title' => 'Opérateurs JavaScript', 'difficulty' => 'Facile', 'points' => 10],
                // Niveau Moyen (5 exercices)
                ['title' => 'DOM Manipulation', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Tableaux et boucles', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Événements JavaScript', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Manipulation de chaînes', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Fonctions fléchées', 'difficulty' => 'Moyen', 'points' => 20],
                // Niveau Difficile (5 exercices)
                ['title' => 'Objets JavaScript', 'difficulty' => 'Difficile', 'points' => 30],
                ['title' => 'Promises et Async/Await', 'difficulty' => 'Difficile', 'points' => 28],
                ['title' => 'Closures et Scope', 'difficulty' => 'Difficile', 'points' => 30],
                ['title' => 'Destructuring et Spread', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'Modules ES6 (import/export)', 'difficulty' => 'Difficile', 'points' => 28],
            ],
            'php' => [
                // Niveau Facile (5 exercices)
                ['title' => 'Syntaxe de base PHP', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Variables PHP', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Opérateurs PHP', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Chaînes de caractères PHP', 'difficulty' => 'Facile', 'points' => 12],
                ['title' => 'Commentaires PHP', 'difficulty' => 'Facile', 'points' => 8],
                // Niveau Moyen (5 exercices)
                ['title' => 'Conditions PHP', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Boucles PHP', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Fonctions PHP', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Tableaux simples PHP', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Formulaires PHP (GET/POST)', 'difficulty' => 'Moyen', 'points' => 20],
                // Niveau Difficile (5 exercices)
                ['title' => 'Tableaux PHP', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'POO en PHP', 'difficulty' => 'Difficile', 'points' => 30],
                ['title' => 'Les sessions PHP', 'difficulty' => 'Difficile', 'points' => 28],
                ['title' => 'Traitement des fichiers PHP', 'difficulty' => 'Difficile', 'points' => 28],
                ['title' => 'Exceptions et gestion d\'erreurs', 'difficulty' => 'Difficile', 'points' => 30],
            ],
            'bootstrap' => [
                // Niveau Facile (5 exercices)
                ['title' => 'Grille Bootstrap', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Bouton Bootstrap', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Typographie Bootstrap', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Couleurs Bootstrap', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Alertes Bootstrap', 'difficulty' => 'Facile', 'points' => 12],
                // Niveau Moyen (5 exercices)
                ['title' => 'Card Bootstrap', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Navbar Bootstrap', 'difficulty' => 'Moyen', 'points' => 20],
                ['title' => 'Formulaires Bootstrap', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Badges et boutons groupés', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Listes et groupes Bootstrap', 'difficulty' => 'Moyen', 'points' => 15],
                // Niveau Difficile (5 exercices)
                ['title' => 'Responsive Bootstrap', 'difficulty' => 'Difficile', 'points' => 20],
                ['title' => 'Customisation Bootstrap', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'Bootstrap avec JavaScript', 'difficulty' => 'Difficile', 'points' => 28],
                ['title' => 'Carousel et composants avancés', 'difficulty' => 'Difficile', 'points' => 28],
                ['title' => 'Système de grille avancé Bootstrap', 'difficulty' => 'Difficile', 'points' => 30],
            ],
            'git' => [
                // Niveau Facile (5 exercices)
                ['title' => 'Initialiser un dépôt Git', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Ajouter des fichiers', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Créer un commit', 'difficulty' => 'Facile', 'points' => 15],
                ['title' => 'Voir l\'historique Git', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Vérifier le statut Git', 'difficulty' => 'Facile', 'points' => 10],
                // Niveau Moyen (5 exercices)
                ['title' => 'Créer une branche', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Changer de branche', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Cloner un dépôt distant', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Pousser vers un dépôt distant', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Récupérer les changements (pull)', 'difficulty' => 'Moyen', 'points' => 18],
                // Niveau Difficile (5 exercices)
                ['title' => 'Fusionner des branches', 'difficulty' => 'Difficile', 'points' => 20],
                ['title' => 'Résoudre les conflits', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'Git rebase', 'difficulty' => 'Difficile', 'points' => 28],
                ['title' => 'Git stash (mise de côté)', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'Annuler des changements Git', 'difficulty' => 'Difficile', 'points' => 28],
            ],
            'wordpress' => [
                // Niveau Facile (5 exercices)
                ['title' => 'The Loop WordPress', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Afficher le titre', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Afficher le contenu', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Afficher la date', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Afficher l\'auteur', 'difficulty' => 'Facile', 'points' => 12],
                // Niveau Moyen (5 exercices)
                ['title' => 'Image à la une', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Menu WordPress', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Widgets WordPress', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Catégories et tags', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Pagination WordPress', 'difficulty' => 'Moyen', 'points' => 20],
                // Niveau Difficile (5 exercices)
                ['title' => 'Custom Post Type', 'difficulty' => 'Difficile', 'points' => 20],
                ['title' => 'Les actions et filtres', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'Créer un thème complet', 'difficulty' => 'Difficile', 'points' => 30],
                ['title' => 'Taxonomies personnalisées', 'difficulty' => 'Difficile', 'points' => 28],
                ['title' => 'Meta boxes personnalisées', 'difficulty' => 'Difficile', 'points' => 30],
            ],
            'ia' => [
                // Niveau Facile (5 exercices)
                ['title' => 'Concepts de base IA', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Machine Learning', 'difficulty' => 'Facile', 'points' => 12],
                ['title' => 'Types d\'apprentissage', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Données et datasets', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Algorithmes de base', 'difficulty' => 'Facile', 'points' => 12],
                // Niveau Moyen (5 exercices)
                ['title' => 'Réseaux de neurones', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Deep Learning', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Entraînement d\'un modèle', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Évaluation de performance', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Bibliothèques Python (TensorFlow, PyTorch)', 'difficulty' => 'Moyen', 'points' => 20],
                // Niveau Difficile (5 exercices)
                ['title' => 'Applications IA', 'difficulty' => 'Difficile', 'points' => 18],
                ['title' => 'Natural Language Processing', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'Éthique de l\'IA', 'difficulty' => 'Difficile', 'points' => 30],
                ['title' => 'Computer Vision', 'difficulty' => 'Difficile', 'points' => 28],
                ['title' => 'Optimisation et hyperparamètres', 'difficulty' => 'Difficile', 'points' => 30],
            ],
            'python' => [
                // Niveau Facile (5 exercices)
                ['title' => 'Syntaxe de base Python', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Variables Python', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Types de données Python', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Opérateurs Python', 'difficulty' => 'Facile', 'points' => 10],
                ['title' => 'Commentaires Python', 'difficulty' => 'Facile', 'points' => 8],
                // Niveau Moyen (5 exercices)
                ['title' => 'Conditions if/elif/else', 'difficulty' => 'Moyen', 'points' => 15],
                ['title' => 'Boucles for et while', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Fonctions Python', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Listes Python', 'difficulty' => 'Moyen', 'points' => 18],
                ['title' => 'Dictionnaires Python', 'difficulty' => 'Moyen', 'points' => 20],
                // Niveau Difficile (5 exercices)
                ['title' => 'Programmation Orientée Objet', 'difficulty' => 'Difficile', 'points' => 30],
                ['title' => 'Modules et packages', 'difficulty' => 'Difficile', 'points' => 25],
                ['title' => 'Gestion des exceptions', 'difficulty' => 'Difficile', 'points' => 28],
                ['title' => 'Manipulation de fichiers', 'difficulty' => 'Difficile', 'points' => 28],
                ['title' => 'Compréhensions de listes et générateurs', 'difficulty' => 'Difficile', 'points' => 30],
            ],
        ];

        return $allExercises[$language] ?? [];
    }

    public function quiz()
    {
        $languages = [
            ['name' => 'HTML5', 'slug' => 'html5', 'icon' => 'fab fa-html5', 'color' => 'orange', 'questions' => 20],
            ['name' => 'CSS3', 'slug' => 'css3', 'icon' => 'fab fa-css3-alt', 'color' => 'blue', 'questions' => 20],
            ['name' => 'JavaScript', 'slug' => 'javascript', 'icon' => 'fab fa-js', 'color' => 'yellow', 'questions' => 20],
            ['name' => 'PHP', 'slug' => 'php', 'icon' => 'fab fa-php', 'color' => 'purple', 'questions' => 20],
            ['name' => 'Bootstrap', 'slug' => 'bootstrap', 'icon' => 'fab fa-bootstrap', 'color' => 'purple', 'questions' => 15],
            ['name' => 'Git', 'slug' => 'git', 'icon' => 'fab fa-git-alt', 'color' => 'red', 'questions' => 15],
            ['name' => 'WordPress', 'slug' => 'wordpress', 'icon' => 'fab fa-wordpress', 'color' => 'blue', 'questions' => 15],
            ['name' => 'IA', 'slug' => 'ia', 'icon' => 'fas fa-robot', 'color' => 'green', 'questions' => 15],
            ['name' => 'Python', 'slug' => 'python', 'icon' => 'fab fa-python', 'color' => 'blue', 'questions' => 20],
        ];
        
        return view('quiz', compact('languages'));
    }

    public function quizLanguage($language)
    {
        $questions = $this->getQuizQuestions($language);
        
        if (empty($questions)) {
            abort(404);
        }
        
        return view('quiz-language', compact('language', 'questions'));
    }

    public function quizSubmit(Request $request, $language)
    {
        $questions = $this->getQuizQuestions($language);
        $answers = $request->input('answers', []);
        
        $score = 0;
        $total = count($questions);
        $results = [];
        
        foreach ($questions as $index => $question) {
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
        // Récupérer les résultats depuis la session
        $sessionKey = 'quiz_results_' . $language;
        $quizData = session($sessionKey);
        
        if (!$quizData) {
            // Si pas de résultats en session, rediriger vers le quiz
            return redirect()->route('quiz.language', $language)
                ->with('error', 'Aucun résultat de quiz trouvé. Veuillez compléter le quiz d\'abord.');
        }
        
        // Supprimer les résultats de la session après récupération (une seule fois)
        session()->forget($sessionKey);
        
        return view('quiz-result', [
            'language' => $language,
            'score' => $quizData['score'],
            'total' => $quizData['total'],
            'percentage' => $quizData['percentage'],
            'results' => $quizData['results']
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
        $formations = [
            [
                'name' => 'HTML5',
                'slug' => 'html5',
                'icon' => 'fab fa-html5',
                'color' => '#e34c26',
                'description' => 'Apprenez les fondamentaux du web avec HTML5. Structure, sémantique et bonnes pratiques.',
                'route' => route('formations.html5')
            ],
            [
                'name' => 'CSS3',
                'slug' => 'css3',
                'icon' => 'fab fa-css3-alt',
                'color' => '#264de4',
                'description' => 'Créez des designs modernes et responsives avec CSS3, Flexbox et Grid.',
                'route' => route('formations.css3')
            ],
            [
                'name' => 'JavaScript',
                'slug' => 'javascript',
                'icon' => 'fab fa-js',
                'color' => '#f0db4f',
                'description' => 'Maîtrisez JavaScript ES6+, DOM manipulation et programmation asynchrone.',
                'route' => route('formations.javascript')
            ],
            [
                'name' => 'PHP',
                'slug' => 'php',
                'icon' => 'fab fa-php',
                'color' => '#8993be',
                'description' => 'Développez des applications web dynamiques avec PHP et MySQL.',
                'route' => route('formations.php')
            ],
            [
                'name' => 'Bootstrap',
                'slug' => 'bootstrap',
                'icon' => 'fab fa-bootstrap',
                'color' => '#7952b3',
                'description' => 'Créez rapidement des interfaces responsives avec le framework Bootstrap.',
                'route' => route('formations.bootstrap')
            ],
            [
                'name' => 'Git',
                'slug' => 'git',
                'icon' => 'fab fa-git-alt',
                'color' => '#f34f29',
                'description' => 'Gérez vos projets avec Git et GitHub. Versioning et collaboration.',
                'route' => route('formations.git')
            ],
            [
                'name' => 'WordPress',
                'slug' => 'wordpress',
                'icon' => 'fab fa-wordpress',
                'color' => '#21759b',
                'description' => 'Créez des sites web professionnels avec WordPress. Thèmes et plugins.',
                'route' => route('formations.wordpress')
            ],
            [
                'name' => 'Intelligence Artificielle',
                'slug' => 'ia',
                'icon' => 'fas fa-robot',
                'color' => '#06b6d4',
                'description' => 'Découvrez l\'IA, le Machine Learning et les applications pratiques.',
                'route' => route('formations.ia')
            ],
            [
                'name' => 'Python',
                'slug' => 'python',
                'icon' => 'fab fa-python',
                'color' => '#3776ab',
                'description' => 'Apprenez Python, le langage de programmation polyvalent pour le web, la data science et l\'IA.',
                'route' => route('formations.python')
            ],
        ];
        
        return view('formations.all', compact('formations'));
    }

    public function html5()
    {
        return view('formations.html5');
    }

    public function css3()
    {
        return view('formations.css3');
    }

    public function javascript()
    {
        return view('formations.javascript');
    }

    public function php()
    {
        return view('formations.php');
    }

    public function python()
    {
        return view('formations.python');
    }

    public function bootstrap()
    {
        return view('formations.bootstrap');
    }

    public function git()
    {
        return view('formations.git');
    }

    public function wordpress()
    {
        return view('formations.wordpress');
    }

    public function ia()
    {
        return view('formations.ia');
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
            
            return $query->orderBy('published_at', 'desc')->paginate(10);
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
