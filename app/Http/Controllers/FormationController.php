<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Concerns\LocaleTrait;

class FormationController extends Controller
{
    use LocaleTrait;

    /**
     * Liste de toutes les formations
     */
    public function index()
    {
        $this->ensureLocale();
        
        $formations = [
            [
                'name' => trans('app.formations.languages.html5'),
                'slug' => 'html5',
                'icon' => 'fab fa-html5',
                'color' => '#e34c26',
                'description' => trans('app.formations.html5.description'),
                'route' => route('formations.html5'),
                'category' => 'frontend'
            ],
            [
                'name' => trans('app.formations.languages.css3'),
                'slug' => 'css3',
                'icon' => 'fab fa-css3-alt',
                'color' => '#264de4',
                'description' => trans('app.formations.css3.description'),
                'route' => route('formations.css3'),
                'category' => 'frontend'
            ],
            [
                'name' => trans('app.formations.languages.javascript'),
                'slug' => 'javascript',
                'icon' => 'fab fa-js',
                'color' => '#f7df1e',
                'description' => trans('app.formations.javascript.description'),
                'route' => route('formations.javascript'),
                'category' => 'frontend'
            ],
            [
                'name' => trans('app.formations.languages.php'),
                'slug' => 'php',
                'icon' => 'fab fa-php',
                'color' => '#777bb4',
                'description' => trans('app.formations.php.description'),
                'route' => route('formations.php'),
                'category' => 'backend'
            ],
            [
                'name' => trans('app.formations.languages.python'),
                'slug' => 'python',
                'icon' => 'fab fa-python',
                'color' => '#3776ab',
                'description' => trans('app.formations.python.description'),
                'route' => route('formations.python'),
                'category' => 'backend'
            ],
            [
                'name' => trans('app.formations.languages.java'),
                'slug' => 'java',
                'icon' => 'fab fa-java',
                'color' => '#ed8b00',
                'description' => trans('app.formations.java.description'),
                'route' => route('formations.java'),
                'category' => 'backend'
            ],
            [
                'name' => trans('app.formations.languages.sql'),
                'slug' => 'sql',
                'icon' => 'fas fa-database',
                'color' => '#336791',
                'description' => trans('app.formations.sql.description'),
                'route' => route('formations.sql'),
                'category' => 'database'
            ],
            [
                'name' => trans('app.formations.languages.c'),
                'slug' => 'c',
                'icon' => 'fab fa-c',
                'color' => '#a8b9cc',
                'description' => trans('app.formations.c.description'),
                'route' => route('formations.c'),
                'category' => 'backend'
            ],
            [
                'name' => trans('app.formations.languages.bootstrap'),
                'slug' => 'bootstrap',
                'icon' => 'fab fa-bootstrap',
                'color' => '#7952b3',
                'description' => trans('app.formations.bootstrap.description'),
                'route' => route('formations.bootstrap'),
                'category' => 'frontend'
            ],
            [
                'name' => trans('app.formations.languages.git'),
                'slug' => 'git',
                'icon' => 'fab fa-git-alt',
                'color' => '#f05032',
                'description' => trans('app.formations.git.description'),
                'route' => route('formations.git'),
                'category' => 'tools'
            ],
            [
                'name' => trans('app.formations.languages.wordpress'),
                'slug' => 'wordpress',
                'icon' => 'fab fa-wordpress',
                'color' => '#21759b',
                'description' => trans('app.formations.wordpress.description'),
                'route' => route('formations.wordpress'),
                'category' => 'tools'
            ],
            [
                'name' => trans('app.formations.languages.ia'),
                'slug' => 'ia',
                'icon' => 'fas fa-robot',
                'color' => '#00d9ff',
                'description' => trans('app.formations.ia.description'),
                'route' => route('formations.ia'),
                'category' => 'ai'
            ],
            [
                'name' => trans('app.formations.languages.cpp'),
                'slug' => 'cpp',
                'icon' => 'fab fa-cuttlefish',
                'color' => '#00599c',
                'description' => trans('app.formations.cpp.description'),
                'route' => route('formations.cpp'),
                'category' => 'backend'
            ],
            [
                'name' => trans('app.formations.languages.csharp'),
                'slug' => 'csharp',
                'icon' => 'fab fa-microsoft',
                'color' => '#239120',
                'description' => trans('app.formations.csharp.description'),
                'route' => route('formations.csharp'),
                'category' => 'backend'
            ],
            [
                'name' => trans('app.formations.languages.dart'),
                'slug' => 'dart',
                'icon' => 'fas fa-feather-alt',
                'color' => '#0175c2',
                'description' => trans('app.formations.dart.description'),
                'route' => route('formations.dart'),
                'category' => 'backend'
            ],
            [
                'name' => trans('app.formations.languages.go'),
                'slug' => 'go',
                'icon' => 'fab fa-golang',
                'color' => '#00add8',
                'description' => trans('app.formations.go.description'),
                'route' => route('formations.go'),
                'category' => 'backend'
            ],
            [
                'name' => trans('app.formations.languages.rust'),
                'slug' => 'rust',
                'icon' => 'fab fa-rust',
                'color' => '#000000',
                'description' => trans('app.formations.rust.description'),
                'route' => route('formations.rust'),
                'category' => 'backend'
            ],
            [
                'name' => trans('app.formations.languages.ruby'),
                'slug' => 'ruby',
                'icon' => 'fas fa-gem',
                'color' => '#cc342d',
                'description' => trans('app.formations.ruby.description'),
                'route' => route('formations.ruby'),
                'category' => 'backend'
            ],
            [
                'name' => trans('app.formations.languages.cybersecurite'),
                'slug' => 'cybersecurite',
                'icon' => 'fas fa-shield-alt',
                'color' => '#ff6b35',
                'description' => trans('app.formations.cybersecurite.description'),
                'route' => route('formations.cybersecurite'),
                'category' => 'security'
            ],
            [
                'name' => trans('app.formations.languages.data-science'),
                'slug' => 'data-science',
                'icon' => 'fas fa-chart-line',
                'color' => '#00a8ff',
                'description' => trans('app.formations.data-science.description'),
                'route' => route('formations.data-science'),
                'category' => 'data'
            ],
            [
                'name' => trans('app.formations.languages.big-data'),
                'slug' => 'big-data',
                'icon' => 'fas fa-database',
                'color' => '#6c5ce7',
                'description' => trans('app.formations.big-data.description'),
                'route' => route('formations.big-data'),
                'category' => 'data'
            ],
            [
                'name' => trans('app.formations.languages.swift'),
                'slug' => 'swift',
                'icon' => 'fab fa-swift',
                'color' => '#fa7343',
                'description' => trans('app.formations.swift.description'),
                'route' => route('formations.swift'),
                'category' => 'backend'
            ],
            [
                'name' => trans('app.formations.languages.perl'),
                'slug' => 'perl',
                'icon' => 'fas fa-code',
                'color' => '#39457e',
                'description' => trans('app.formations.perl.description'),
                'route' => route('formations.perl'),
                'category' => 'backend'
            ],
            [
                'name' => trans('app.formations.languages.typescript'),
                'slug' => 'typescript',
                'icon' => 'fab fa-js-square',
                'color' => '#3178c6',
                'description' => trans('app.formations.typescript.description'),
                'route' => route('formations.typescript'),
                'category' => 'frontend'
            ],
        ];
        
        return view('formations.all', compact('formations'));
    }

    /**
     * Afficher une formation spécifique
     */
    private function showFormation(string $slug, string $view)
    {
        $this->ensureLocale();
        
        $progress = null;
        if (Auth::check()) {
            $progress = Auth::user()->getProgressForFormation($slug);
        }
        
        // Récupérer les annonces AdSense pour cette formation
        $formationAds = \App\Models\FormationAdSenseUnit::getAdsForFormation($slug);
        $headerAds = \App\Models\FormationAdSenseUnit::getAdsForFormation($slug, 'header');
        $contentAds = \App\Models\FormationAdSenseUnit::getAdsForFormation($slug, 'content');
        $sidebarAds = \App\Models\FormationAdSenseUnit::getAdsForFormation($slug, 'sidebar');
        $footerAds = \App\Models\FormationAdSenseUnit::getAdsForFormation($slug, 'footer');
        
        return view($view, compact('progress', 'formationAds', 'headerAds', 'contentAds', 'sidebarAds', 'footerAds'));
    }

    public function html5()
    {
        return $this->showFormation('html5', 'formations.html5');
    }

    public function css3()
    {
        return $this->showFormation('css3', 'formations.css3');
    }

    public function javascript()
    {
        return $this->showFormation('javascript', 'formations.javascript');
    }

    public function php()
    {
        $locale = $this->ensureLocale();
        view()->share('currentLocale', $locale);
        return $this->showFormation('php', 'formations.php');
    }

    public function python()
    {
        return $this->showFormation('python', 'formations.python');
    }

    public function java()
    {
        return $this->showFormation('java', 'formations.java');
    }

    public function sql()
    {
        return $this->showFormation('sql', 'formations.sql');
    }

    public function c()
    {
        return $this->showFormation('c', 'formations.c');
    }

    public function bootstrap()
    {
        return $this->showFormation('bootstrap', 'formations.bootstrap');
    }

    public function git()
    {
        return $this->showFormation('git', 'formations.git');
    }

    public function wordpress()
    {
        return $this->showFormation('wordpress', 'formations.wordpress');
    }

    public function ia()
    {
        return $this->showFormation('ia', 'formations.ia');
    }

    public function cpp()
    {
        return $this->showFormation('cpp', 'formations.cpp');
    }

    public function csharp()
    {
        return $this->showFormation('csharp', 'formations.csharp');
    }

    public function dart()
    {
        return $this->showFormation('dart', 'formations.dart');
    }

    public function go()
    {
        return $this->showFormation('go', 'formations.go');
    }

    public function rust()
    {
        return $this->showFormation('rust', 'formations.rust');
    }

    public function ruby()
    {
        return $this->showFormation('ruby', 'formations.ruby');
    }

    public function cybersecurite()
    {
        return $this->showFormation('cybersecurite', 'formations.cybersecurite');
    }

    public function dataScience()
    {
        return $this->showFormation('data-science', 'formations.data-science');
    }

    public function bigData()
    {
        return $this->showFormation('big-data', 'formations.big-data');
    }

    public function swift()
    {
        return $this->showFormation('swift', 'formations.swift');
    }

    public function perl()
    {
        return $this->showFormation('perl', 'formations.perl');
    }

    public function typescript()
    {
        return $this->showFormation('typescript', 'formations.typescript');
    }
}

