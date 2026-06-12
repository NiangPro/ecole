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
        $cacheKey = 'homepage_view_v2_' . $locale;
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
            
            // Cache les documents vedettes - Optimisé avec une seule requête combinée
            $featuredDocuments = \App\Models\Document::published()
                ->active()
                ->with(['category:id,name,slug'])
                ->select('id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_type', 'category_id', 'price', 'discount_price', 'download_count', 'sales_count', 'views_count', 'published_at', 'is_featured')
                ->orderByRaw('CASE WHEN is_featured = 1 THEN 0 ELSE 1 END')
                ->orderBy('download_count', 'desc')
                ->orderBy('views_count', 'desc')
                ->take(4)
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

            return compact('latestJobs', 'categories', 'sponsoredArticles', 'sidebarAds', 'homepageAds', 'careerAdviceArticles', 'paidCourses', 'featuredArticles', 'featuredDocuments', 'latestEpreuves');
        });
        
        // Passer directement les données du cache à la vue. On garantit un défaut
        // pour chaque variable afin qu'un cache incomplet (ancienne structure) ne
        // casse jamais la page d'accueil — la vue reçoit toujours des collections.
        $defaults = [
            'latestJobs' => collect(), 'categories' => collect(), 'sponsoredArticles' => collect(),
            'sidebarAds' => collect(), 'homepageAds' => collect(), 'careerAdviceArticles' => collect(),
            'paidCourses' => collect(), 'featuredArticles' => collect(), 'featuredDocuments' => collect(),
            'latestEpreuves' => collect(),
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

}
