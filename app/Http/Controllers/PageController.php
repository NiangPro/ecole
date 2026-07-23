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
        
        // Cache de la vue complète pour améliorer les performances (15 minutes).
        // Le suffixe de version (v2) garantit qu'un cache écrit par une version
        // antérieure du contrôleur — avec moins de variables — est ignoré après
        // déploiement, au lieu de provoquer une erreur compact()/Undefined variable.
        $cacheKey = 'homepage_view_v4_' . $locale;
        $cachedView = \Illuminate\Support\Facades\Cache::remember($cacheKey, 900, function () use ($locale) {
            // Cache les 12 derniers articles publiés - Optimisé avec eager loading
            $latestJobs = \App\Models\JobArticle::published()
                ->where('is_sponsored', false)
                ->with(['category:id,name,slug'])
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'created_at', 'views')
                ->orderBy('created_at', 'desc')
                ->take(12)
                ->get();
            
            // Cache les catégories actives - Optimisé avec eager loading - Limité à 9
            $categories = \App\Models\Category::where('is_active', true)
                ->withCount(['publishedArticles' => function ($query) {
                    $query->published();
                }])
                ->orderBy('order', 'asc')
                ->orderBy('name', 'asc')
                ->take(9)
                ->get();
            
            // Cache les articles sponsorisés - Optimisé avec eager loading
            $sponsoredArticles = \App\Models\JobArticle::published()
                ->where('is_sponsored', true)
                ->with(['category:id,name,slug'])
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->take(3)
                ->get();
            
            // Cache les publicités pour la position "content"
            $sidebarAds = \App\Models\Ad::active()
                ->forPosition('content')
                ->whereNull('location')
                ->orderBy('order')
                ->get();
            
            // Cache les publicités pour l'emplacement "homepage_after_exercises"
            $homepageAds = \App\Models\Ad::active()
                ->forLocation('homepage_after_exercises')
                ->orderBy('order')
                ->get();
            
            // Cache la catégorie conseils-carriere (1 heure) pour éviter les requêtes répétées
            $conseilsCategoryId = \Illuminate\Support\Facades\Cache::remember('category_conseils_carriere_id', 3600, function () {
                $cat = \App\Models\Category::where('slug', 'conseils-carriere')->select('id')->first();
                return $cat ? $cat->id : null;
            });
            
            // Cache les articles conseils et carrières pour la sidebar - Optimisé
            $careerAdviceArticles = collect();
            if ($conseilsCategoryId) {
                $careerAdviceArticles = \App\Models\JobArticle::published()
                    ->where('category_id', $conseilsCategoryId)
                    ->with('category:id,name,slug')
                    ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                    ->orderBy('published_at', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->take(9)
                    ->get();
            }
            
            // Cache les cours payants publiés - Optimisé avec eager loading
            $paidCourses = \App\Models\PaidCourse::where('status', 'published')
                ->select('id', 'title', 'slug', 'description', 'cover_image', 'cover_type', 'price', 'currency', 'discount_price', 'discount_start', 'discount_end', 'duration_hours', 'students_count', 'rating', 'reviews_count', 'created_at')
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();
            
            // Cache les articles vedettes - Optimisé avec eager loading
            $featuredArticles = \App\Models\JobArticle::published()
                ->where('is_featured', true)
                ->with(['category:id,name,slug'])
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'published_at', 'views')
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();
            
            // Cache les 10 derniers documents de la catégorie Épreuves
            $featuredDocuments = \App\Models\Document::published()
                ->active()
                ->with(['category:id,name,slug'])
                ->whereHas('category', fn($q) => $q->where('slug', 'epreuves'))
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'price', 'discount_price', 'download_count', 'sales_count', 'views_count', 'published_at', 'is_featured')
                ->withAvg('approvedReviews as average_rating', 'rating')
                ->withCount('approvedReviews as reviews_count')
                ->orderBy('published_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
            
            // Cache les épreuves récentes (BAC, BFEM…) — en vedette d'abord, puis les plus récentes
            $latestEpreuves = \App\Models\Epreuve::published()
                ->with('matiere:id,name')
                ->select('id', 'title', 'slug', 'type', 'exam', 'level', 'matiere_id', 'serie', 'year', 'downloads_count', 'is_featured')
                ->orderByDesc('is_featured')   // les épreuves « en vedette » d'abord
                ->orderByDesc('year')
                ->orderByDesc('created_at')
                ->take(8)
                ->get();

            // Cache les packs (bundles) vedettes
            $featuredBundles = \App\Models\DocumentBundle::active()
                ->with('items.itemable')
                ->orderBy('is_featured', 'desc')
                ->orderBy('sales_count', 'desc')
                ->take(3)
                ->get();

            // Cache les avis récents approuvés (preuve sociale, tous documents confondus)
            $latestReviews = \App\Models\DocumentReview::approved()
                ->with('document:id,title,slug')
                ->whereHas('document')
                ->latest()
                ->take(6)
                ->get();

            // Stats globales des avis pour l'en-tête de la section (note moyenne, total)
            $reviewsStats = [
                'count' => \App\Models\DocumentReview::approved()->count(),
                'average' => (float) (\App\Models\DocumentReview::approved()->avg('rating') ?? 0),
            ];

            return compact('latestJobs', 'categories', 'sponsoredArticles', 'sidebarAds', 'homepageAds', 'careerAdviceArticles', 'paidCourses', 'featuredArticles', 'featuredDocuments', 'latestEpreuves', 'featuredBundles', 'latestReviews', 'reviewsStats');
        });
        
        // Passer directement les données du cache à la vue. On garantit un défaut
        // pour chaque variable afin qu'un cache incomplet (ancienne structure) ne
        // casse jamais la page d'accueil — la vue reçoit toujours des collections.
        $defaults = [
            'latestJobs' => collect(), 'categories' => collect(), 'sponsoredArticles' => collect(),
            'sidebarAds' => collect(), 'homepageAds' => collect(), 'careerAdviceArticles' => collect(),
            'paidCourses' => collect(), 'featuredArticles' => collect(), 'featuredDocuments' => collect(),
            'latestEpreuves' => collect(), 'featuredBundles' => collect(), 'latestReviews' => collect(),
            'reviewsStats' => ['count' => 0, 'average' => 0],
        ];

        return view('index', array_merge($defaults, $cachedView));
    }

    public function about()
    {
        $this->ensureLocale();
        $achievements = \App\Models\Achievement::visible()->ordered()->get();
        $showAchievementsSection = \App\Models\SiteSetting::get('show_achievements_section', true);
        $siteSettings = \Illuminate\Support\Facades\Cache::remember('site_settings', 3600, function () {
            return \App\Models\SiteSetting::first();
        });
        return view('about', compact('achievements', 'showAchievementsSection', 'siteSettings'));
    }
    
    public function contact()
    {
        $this->ensureLocale();
        $siteSettings = \Illuminate\Support\Facades\Cache::remember('site_settings', 3600, function () {
            return \App\Models\SiteSetting::first();
        });
        return view('contact', compact('siteSettings'));
    }
    
    public function maintenance()
    {
        $this->ensureLocale();
        $settings = \Illuminate\Support\Facades\Cache::remember('site_settings', 3600, function () {
            return \App\Models\SiteSetting::first();
        });
        return view('maintenance', compact('settings'));
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
        $recentArticles = \App\Models\JobArticle::published()
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

    /**
     * Exécuter du code utilisateur dans un bac à sable (sandbox) et renvoyer le résultat.
     * Supporte PHP nativement (sandboxé via php-cli) ; les autres langages sont exécutés
     * si l'interpréteur/compilateur correspondant est disponible sur le serveur.
     */
    public function runCode(Request $request, $language)
    {
        $code = (string) $request->input('code', '');
        $language = strtolower(trim($language));

        if (trim($code) === '') {
            return response()->json(['error' => 'Aucun code à exécuter.']);
        }

        if (mb_strlen($code) > 20000) {
            return response()->json(['error' => 'Le code est trop long.']);
        }

        $timeoutSeconds = 5;

        try {
            switch ($language) {
                case 'php':
                    $result = $this->runPhpCode($code, $timeoutSeconds);
                    break;
                case 'python':
                    $result = $this->runPythonCode($code, $timeoutSeconds);
                    break;
                case 'java':
                    $result = $this->runJavaCode($code, $timeoutSeconds);
                    break;
                case 'c':
                    $result = $this->runCompiledCCode($code, 'c', $timeoutSeconds);
                    break;
                case 'cpp':
                case 'c++':
                    $result = $this->runCompiledCCode($code, 'cpp', $timeoutSeconds);
                    break;
                case 'dart':
                    $result = $this->runDartCode($code, $timeoutSeconds + 5);
                    break;
                case 'csharp':
                case 'c#':
                    $result = ['error' => "L'exécution C# n'est pas disponible sur ce serveur pour le moment."];
                    break;
                default:
                    $result = ['error' => "Langage non supporté pour l'exécution : {$language}"];
            }
        } catch (\Throwable $e) {
            report($e);
            $result = ['error' => "Une erreur interne est survenue lors de l'exécution du code."];
        }

        return response()->json($result);
    }

    /**
     * Exécute du code PHP dans un sous-processus php-cli restreint :
     * fonctions réseau/processus désactivées, accès fichiers limité à un dossier temporaire.
     */
    private function runPhpCode(string $code, int $timeoutSeconds): array
    {
        $phpBinary = $this->locatePhpBinary();
        if (!$phpBinary) {
            return ['error' => "L'exécution PHP n'est pas disponible sur ce serveur."];
        }

        // Les exercices fournissent parfois un template HTML complet avec du PHP
        // deja embarque dans des balises : dans ce cas on n'ajoute rien. Sinon on
        // encapsule le code brut dans des balises PHP.
        if (!str_contains($code, '<?php') && !str_contains($code, '<?=')) {
            $code = "<?php\n" . $code;
        }

        $tmpDir = storage_path('app/code-sandbox');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0700, true);
        }

        $filePath = $tmpDir . '/exec_' . bin2hex(random_bytes(8)) . '.php';
        file_put_contents($filePath, $code);

        $disabledFunctions = implode(',', [
            'exec', 'shell_exec', 'system', 'passthru', 'popen',
            'proc_open', 'proc_close', 'proc_terminate', 'proc_nice',
            'pcntl_exec', 'pcntl_fork', 'dl', 'putenv',
            'mail', 'syslog', 'symlink', 'link',
            'curl_exec', 'curl_multi_exec',
            'fsockopen', 'pfsockopen', 'stream_socket_client', 'stream_socket_server',
            'socket_create', 'socket_connect',
        ]);

        $command = [
            $phpBinary,
            '-d', 'disable_functions=' . $disabledFunctions,
            '-d', 'allow_url_fopen=0',
            '-d', 'allow_url_include=0',
            '-d', 'max_execution_time=' . $timeoutSeconds,
            '-d', 'memory_limit=64M',
            '-d', 'display_errors=1',
            '-d', 'open_basedir=' . $tmpDir,
            $filePath,
        ];

        $result = $this->runProcessWithTimeout($command, $tmpDir, $timeoutSeconds);
        @unlink($filePath);

        return $this->formatSandboxResult($result, $timeoutSeconds, $filePath);
    }

    /**
     * Exécute du code Python via python3, si disponible.
     */
    private function runPythonCode(string $code, int $timeoutSeconds): array
    {
        $binary = $this->locateBinary('python3') ?: $this->locateBinary('python');
        if (!$binary) {
            return ['error' => "L'exécution Python n'est pas disponible sur ce serveur."];
        }

        $tmpDir = storage_path('app/code-sandbox');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0700, true);
        }

        $filePath = $tmpDir . '/exec_' . bin2hex(random_bytes(8)) . '.py';
        file_put_contents($filePath, $code);

        $result = $this->runProcessWithTimeout([$binary, '-I', '-B', $filePath], $tmpDir, $timeoutSeconds);
        @unlink($filePath);

        return $this->formatSandboxResult($result, $timeoutSeconds, $filePath);
    }

    /**
     * Compile et exécute du code Java (javac + java), si le JDK est disponible.
     */
    private function runJavaCode(string $code, int $timeoutSeconds): array
    {
        $javac = $this->locateBinary('javac');
        $java = $this->locateBinary('java');
        if (!$javac || !$java) {
            return ['error' => "L'exécution Java n'est pas disponible sur ce serveur."];
        }

        if (preg_match('/public\s+class\s+([A-Za-z_][A-Za-z0-9_]*)/', $code, $m)) {
            $className = $m[1];
        } elseif (!str_contains($code, 'class ')) {
            $className = 'Main';
            $code = "public class Main {\n    public static void main(String[] args) throws Exception {\n" . $code . "\n    }\n}\n";
        } else {
            $className = 'Main';
            $code = preg_replace('/\bclass\s+([A-Za-z_][A-Za-z0-9_]*)/', 'class Main', $code, 1);
        }

        $tmpDir = storage_path('app/code-sandbox/java_' . bin2hex(random_bytes(6)));
        mkdir($tmpDir, 0700, true);
        file_put_contents($tmpDir . "/{$className}.java", $code);

        $compile = $this->runProcessWithTimeout([$javac, "{$className}.java"], $tmpDir, $timeoutSeconds);
        if ($compile['exitCode'] !== 0) {
            $message = trim($compile['error'] ?: $compile['output']) ?: 'Erreur de compilation Java.';
            $this->cleanupDir($tmpDir);
            return ['error' => $message];
        }

        $run = $this->runProcessWithTimeout([$java, $className], $tmpDir, $timeoutSeconds);
        $this->cleanupDir($tmpDir);

        return $this->formatSandboxResult($run, $timeoutSeconds, $tmpDir);
    }

    /**
     * Compile et exécute du code C/C++ (gcc/g++), si disponible.
     */
    private function runCompiledCCode(string $code, string $variant, int $timeoutSeconds): array
    {
        $compilerBin = $variant === 'cpp'
            ? ($this->locateBinary('g++') ?: $this->locateBinary('clang++'))
            : ($this->locateBinary('gcc') ?: $this->locateBinary('clang'));

        if (!$compilerBin) {
            return ['error' => ($variant === 'cpp' ? "L'exécution C++" : "L'exécution C") . " n'est pas disponible sur ce serveur."];
        }

        $tmpDir = storage_path('app/code-sandbox/c_' . bin2hex(random_bytes(6)));
        mkdir($tmpDir, 0700, true);

        $ext = $variant === 'cpp' ? 'cpp' : 'c';
        $srcPath = $tmpDir . "/main.{$ext}";
        file_put_contents($srcPath, $code);
        $binPath = $tmpDir . '/program';

        $compile = $this->runProcessWithTimeout([$compilerBin, $srcPath, '-O0', '-o', $binPath], $tmpDir, $timeoutSeconds);
        if ($compile['exitCode'] !== 0 || !is_file($binPath)) {
            $message = trim($compile['error'] ?: $compile['output']) ?: 'Erreur de compilation.';
            $this->cleanupDir($tmpDir);
            return ['error' => $message];
        }

        $run = $this->runProcessWithTimeout([$binPath], $tmpDir, $timeoutSeconds);
        $this->cleanupDir($tmpDir);

        return $this->formatSandboxResult($run, $timeoutSeconds, $tmpDir);
    }

    /**
     * Exécute du code Dart via `dart run`, si le SDK Dart est disponible.
     */
    private function runDartCode(string $code, int $timeoutSeconds): array
    {
        $binary = $this->locateBinary('dart');
        if (!$binary) {
            return ['error' => "L'exécution Dart n'est pas disponible sur ce serveur."];
        }

        $tmpDir = storage_path('app/code-sandbox');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0700, true);
        }

        $filePath = $tmpDir . '/exec_' . bin2hex(random_bytes(8)) . '.dart';
        file_put_contents($filePath, $code);

        $result = $this->runProcessWithTimeout([$binary, 'run', $filePath], $tmpDir, $timeoutSeconds);
        @unlink($filePath);

        return $this->formatSandboxResult($result, $timeoutSeconds, $filePath);
    }

    /**
     * Résout le chemin absolu d'un exécutable PHP CLI utilisable en sous-processus
     * (PHP_BINARY pointe parfois vers php-fpm/apache, qui ne convient pas ici).
     */
    private function locatePhpBinary(): ?string
    {
        if (defined('PHP_BINARY') && PHP_BINARY !== ''
            && str_contains(PHP_BINARY, 'php')
            && !str_contains($binaryLower = strtolower(PHP_BINARY), 'fpm')
            && !str_contains($binaryLower, 'apache')) {
            return PHP_BINARY;
        }

        return $this->locateBinary('php');
    }

    /**
     * Résout le chemin absolu d'un exécutable via `command -v` (mise en cache par requête).
     * $name est toujours une valeur fixe choisie dans le code, jamais une entrée utilisateur.
     */
    private function locateBinary(string $name): ?string
    {
        static $cache = [];

        if (array_key_exists($name, $cache)) {
            return $cache[$name];
        }

        $path = trim((string) @shell_exec('command -v ' . escapeshellarg($name) . ' 2>/dev/null'));

        return $cache[$name] = ($path !== '' ? $path : null);
    }

    /**
     * Lance un sous-processus avec un dossier de travail dédié et applique un timeout
     * (arrêt forcé du processus si dépassé) sans dépendre d'un binaire `timeout` externe.
     */
    private function runProcessWithTimeout(array $command, string $cwd, int $timeoutSeconds): array
    {
        $descriptorSpec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($command, $descriptorSpec, $pipes, $cwd, null);

        if (!is_resource($process)) {
            return ['output' => '', 'error' => "Impossible de démarrer le processus d'exécution.", 'exitCode' => -1, 'timedOut' => false];
        }

        fclose($pipes[0]);
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);

        $stdout = '';
        $stderr = '';
        $start = microtime(true);
        $timedOut = false;

        while (true) {
            $status = proc_get_status($process);

            $stdout .= stream_get_contents($pipes[1]);
            $stderr .= stream_get_contents($pipes[2]);

            if (!$status['running']) {
                break;
            }

            if ((microtime(true) - $start) > $timeoutSeconds) {
                $timedOut = true;
                proc_terminate($process, 9);
                usleep(100000);
                break;
            }

            usleep(20000);
        }

        $stdout .= stream_get_contents($pipes[1]);
        $stderr .= stream_get_contents($pipes[2]);

        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        return ['output' => $stdout, 'error' => $stderr, 'exitCode' => $exitCode, 'timedOut' => $timedOut];
    }

    /**
     * Normalise le résultat d'exécution en réponse JSON attendue par le frontend
     * ({output} en cas de succès, {error} sinon), en masquant les chemins serveur.
     */
    private function formatSandboxResult(array $result, int $timeoutSeconds, ?string $sourcePath = null): array
    {
        if ($result['timedOut']) {
            return ['error' => "Temps d'exécution dépassé ({$timeoutSeconds}s). Vérifiez les boucles infinies."];
        }

        if ($result['exitCode'] !== 0) {
            $message = trim($result['output'] . "\n" . $result['error']);
            $message = $message !== '' ? $this->hideSandboxPaths($message, $sourcePath) : 'Erreur lors de l\'exécution du code.';
            return ['error' => $message];
        }

        $output = $result['output'];
        if (trim($result['error']) !== '') {
            $output .= ($output !== '' ? "\n" : '') . $result['error'];
        }

        return ['output' => $this->hideSandboxPaths($output, $sourcePath)];
    }

    /**
     * Remplace les chemins absolus du serveur (dossier de sandbox, fichier source)
     * par des repères neutres avant de renvoyer un message à l'utilisateur.
     */
    private function hideSandboxPaths(string $text, ?string $sourcePath = null): string
    {
        if ($sourcePath) {
            $text = str_replace($sourcePath, basename($sourcePath), $text);
        }

        $sandboxRoot = storage_path('app/code-sandbox');
        $text = str_replace($sandboxRoot . '/', '', $text);

        return $text;
    }

    /**
     * Supprime récursivement un dossier temporaire de sandbox.
     */
    private function cleanupDir(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        foreach (scandir($dir) ?: [] as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . '/' . $item;
            is_dir($path) ? $this->cleanupDir($path) : @unlink($path);
        }

        @rmdir($dir);
    }

}
