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
                'color' => '#f7df1e',
                'description' => trans('app.formations.javascript.description'),
                'route' => route('formations.javascript')
            ],
            [
                'name' => trans('app.formations.languages.php'),
                'slug' => 'php',
                'icon' => 'fab fa-php',
                'color' => '#777bb4',
                'description' => trans('app.formations.php.description'),
                'route' => route('formations.php')
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
                'color' => '#f05032',
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
                'color' => '#00d9ff',
                'description' => trans('app.formations.ia.description'),
                'route' => route('formations.ia')
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
     * Afficher une formation spécifique
     */
    private function showFormation(string $slug, string $view)
    {
        $this->ensureLocale();
        
        $progress = null;
        if (Auth::check()) {
            $progress = Auth::user()->getProgressForFormation($slug);
        }
        
        return view($view, compact('progress'));
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
}

