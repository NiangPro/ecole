@php
// Breadcrumbs par défaut si non définis
if (!isset($breadcrumbs)) {
    $breadcrumbs = [];
    $currentRoute = request()->route()->getName();
    
    // Routes où on ne veut PAS afficher de breadcrumb
    $routesWithoutBreadcrumb = [
        'formations.all',
        'formations.html5',
        'formations.css3',
        'formations.javascript',
        'formations.python',
        'formations.php',
        'formations.java',
        'formations.c',
        'formations.cpp',
        'formations.csharp',
        'formations.dart',
        'formations.git',
        'formations.sql',
        'formations.wordpress',
        'formations.bootstrap',
        'formations.ia',
        'formations.go',
        'formations.rust',
        'formations.ruby',
        'formations.cybersecurite',
        'formations.data-science',
        'formations.big-data',
        'emplois.article',
        'emplois.offres'
    ];
    
    // Si la route est dans la liste d'exclusion, ne pas générer de breadcrumb
    if (in_array($currentRoute, $routesWithoutBreadcrumb)) {
        $breadcrumbs = [];
    } else {
        // Ajouter l'accueil
        $breadcrumbs[] = [
            'name' => trans('app.nav.home', [], 'Accueil'),
            'url' => url('/')
        ];
        
        // Ajouter selon la route
        if (str_contains($currentRoute, 'formations.')) {
        $breadcrumbs[] = [
            'name' => trans('app.formations.title'),
            'url' => route('formations.all')
        ];
        if ($currentRoute !== 'formations.all') {
            $formationName = str_replace('formations.', '', $currentRoute);
            // Essayer plusieurs clés de traduction possibles
            $formationTitle = trans('app.formations.' . $formationName . '.title', [], null);
            if ($formationTitle === 'app.formations.' . $formationName . '.title') {
                // Si la traduction n'existe pas, essayer avec nav.dropdown
                $formationTitle = trans('app.nav.dropdown.formations.' . $formationName, [], null);
                if ($formationTitle === 'app.nav.dropdown.formations.' . $formationName) {
                    // Fallback : utiliser le nom de la formation en majuscule
                    $formationTitle = ucfirst($formationName);
                }
            }
            $breadcrumbs[] = [
                'name' => $formationTitle,
                'url' => route($currentRoute)
            ];
        }
    } elseif (str_contains($currentRoute, 'emplois.')) {
        // Essayer plusieurs clés de traduction possibles pour "Emplois"
        $emploisTitle = trans('app.nav.jobs', [], null);
        if ($emploisTitle === 'app.nav.jobs') {
            $emploisTitle = trans('app.nav.dropdown.jobs.all', [], null);
            if ($emploisTitle === 'app.nav.dropdown.jobs.all') {
                $emploisTitle = 'Emplois'; // Fallback
            }
        }
        
        $breadcrumbs[] = [
            'name' => $emploisTitle,
            'url' => route('emplois')
        ];
        if ($currentRoute === 'emplois.article' && isset($article)) {
            $breadcrumbs[] = [
                'name' => $article->title,
                'url' => route('emplois.article', $article->slug)
            ];
        }
    } elseif (str_contains($currentRoute, 'exercices.')) {
        // Ne pas afficher le breadcrumb pour cybersecurite
        $language = request()->route('language') ?? request()->segment(2);
        if ($language && $language !== 'cybersecurite') {
            $breadcrumbs[] = [
                'name' => trans('app.exercices.title', [], 'Exercices'),
                'url' => route('exercices')
            ];
        }
    } elseif (str_contains($currentRoute, 'quiz.')) {
        $breadcrumbs[] = [
            'name' => trans('app.quiz.title', [], 'Quiz'),
            'url' => route('quiz')
        ];
    }
    } // Fermeture du else
}
@endphp

@if(count($breadcrumbs) > 1)
<style>
.breadcrumb-nav {
    position: relative;
    padding: 1rem 2rem;
    background: transparent !important;
    margin: 0;
}

/* Positionnement après la section hero - chevauchement partiel pour effet visuel */
.tutorial-header + .breadcrumb-nav,
.hero-section + .breadcrumb-nav,
.article-hero + .breadcrumb-nav {
    position: relative;
    margin-top: -80px;
    margin-bottom: 2rem;
    z-index: 10;
    padding-top: 1.5rem;
}

/* Si le breadcrumb est dans un conteneur après la section hero */
.tutorial-header ~ .breadcrumb-nav,
.hero-section ~ .breadcrumb-nav,
.article-hero ~ .breadcrumb-nav {
    position: relative;
    margin-top: -80px;
    margin-bottom: 2rem;
    z-index: 10;
    padding-top: 1.5rem;
}

/* Si le breadcrumb est avant la section hero dans le DOM, utiliser position absolue pour le placer après visuellement */
main:has(.tutorial-header) .breadcrumb-nav:not(.tutorial-header ~ .breadcrumb-nav):not(.tutorial-header + .breadcrumb-nav),
main:has(.hero-section) .breadcrumb-nav:not(.hero-section ~ .breadcrumb-nav):not(.hero-section + .breadcrumb-nav),
main:has(.article-hero) .breadcrumb-nav:not(.article-hero ~ .breadcrumb-nav):not(.article-hero + .breadcrumb-nav) {
    position: absolute;
    top: auto;
    bottom: auto;
    margin-top: 0;
}

/* Si pas de section hero, position relative normale */
main:not(:has(.tutorial-header)):not(:has(.hero-section)):not(:has(.article-hero)) .breadcrumb-nav {
    position: relative;
    padding: 1rem 2rem;
    margin-bottom: 1rem;
}

.breadcrumb-list {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.5rem;
    list-style: none;
    padding: 0;
    margin: 0;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    pointer-events: auto;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
}

/* Styles pour breadcrumb après section hero (fond sombre) */
.tutorial-header ~ .breadcrumb-nav .breadcrumb-item a,
.hero-section ~ .breadcrumb-nav .breadcrumb-item a,
.article-hero ~ .breadcrumb-nav .breadcrumb-item a,
.tutorial-header + .breadcrumb-nav .breadcrumb-item a,
.hero-section + .breadcrumb-nav .breadcrumb-item a,
.article-hero + .breadcrumb-nav .breadcrumb-item a {
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    transition: all 0.3s ease;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.tutorial-header ~ .breadcrumb-nav .breadcrumb-item a:hover,
.hero-section ~ .breadcrumb-nav .breadcrumb-item a:hover,
.article-hero ~ .breadcrumb-nav .breadcrumb-item a:hover,
.tutorial-header + .breadcrumb-nav .breadcrumb-item a:hover,
.hero-section + .breadcrumb-nav .breadcrumb-item a:hover,
.article-hero + .breadcrumb-nav .breadcrumb-item a:hover {
    background: rgba(255, 255, 255, 0.2);
    color: rgba(255, 255, 255, 1);
    transform: translateY(-2px);
}

.tutorial-header ~ .breadcrumb-nav .breadcrumb-separator,
.hero-section ~ .breadcrumb-nav .breadcrumb-separator,
.article-hero ~ .breadcrumb-nav .breadcrumb-separator,
.tutorial-header + .breadcrumb-nav .breadcrumb-separator,
.hero-section + .breadcrumb-nav .breadcrumb-separator,
.article-hero + .breadcrumb-nav .breadcrumb-separator {
    margin: 0 0.5rem;
    color: rgba(255, 255, 255, 0.7);
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.tutorial-header ~ .breadcrumb-nav .breadcrumb-current,
.hero-section ~ .breadcrumb-nav .breadcrumb-current,
.article-hero ~ .breadcrumb-nav .breadcrumb-current,
.tutorial-header + .breadcrumb-nav .breadcrumb-current,
.hero-section + .breadcrumb-nav .breadcrumb-current,
.article-hero + .breadcrumb-nav .breadcrumb-current {
    color: rgba(255, 255, 255, 1);
    font-weight: 600;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

/* Styles par défaut pour pages sans hero */
.breadcrumb-item a {
    color: #06b6d4;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    background: rgba(6, 182, 212, 0.1);
    border: 1px solid rgba(6, 182, 212, 0.2);
}

.breadcrumb-item a:hover {
    background: rgba(6, 182, 212, 0.2);
    color: #06b6d4;
    transform: translateY(-2px);
}

.breadcrumb-separator {
    margin: 0 0.5rem;
    color: #94a3b8;
}

.breadcrumb-current {
    color: #64748b;
    font-weight: 600;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    background: rgba(100, 116, 139, 0.1);
    border: 1px solid rgba(100, 116, 139, 0.2);
}


/* Responsive */
@media (max-width: 768px) {
    .breadcrumb-nav {
        padding: 0.75rem 1rem;
    }
    
    .tutorial-header + .breadcrumb-nav,
    .hero-section + .breadcrumb-nav,
    .article-hero + .breadcrumb-nav,
    .tutorial-header ~ .breadcrumb-nav,
    .hero-section ~ .breadcrumb-nav,
    .article-hero ~ .breadcrumb-nav {
        margin-top: -40px;
        margin-bottom: 1.5rem;
    }
    
    .breadcrumb-list {
        gap: 0.25rem;
        font-size: 0.875rem;
    }
    
    .breadcrumb-item a,
    .breadcrumb-item .breadcrumb-current {
        padding: 0.375rem 0.5rem;
        font-size: 0.875rem;
    }
}
</style>
<nav aria-label="Breadcrumb" class="breadcrumb-nav">
    <ol class="breadcrumb-list" itemscope itemtype="https://schema.org/BreadcrumbList">
        @foreach($breadcrumbs as $index => $breadcrumb)
        <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            @if($index < count($breadcrumbs) - 1)
            <a href="{{ $breadcrumb['url'] }}" itemprop="item">
                <span itemprop="name">{{ $breadcrumb['name'] }}</span>
            </a>
            <meta itemprop="position" content="{{ $index + 1 }}">
            <span class="breadcrumb-separator">/</span>
            @else
            <span itemprop="name" class="breadcrumb-current">{{ $breadcrumb['name'] }}</span>
            <meta itemprop="position" content="{{ $index + 1 }}">
            @endif
        </li>
        @endforeach
    </ol>
</nav>
<script>
// Déplacer le breadcrumb après la section hero si elle existe
(function() {
    function moveBreadcrumbAfterHero() {
        const breadcrumbNav = document.querySelector('.breadcrumb-nav');
        if (!breadcrumbNav) return;
        
        // Chercher la section hero (formations, exercices, quiz, ou articles)
        const heroSection = document.querySelector('.tutorial-header') || 
                           document.querySelector('.hero-section') || 
                           document.querySelector('.article-hero') ||
                           document.querySelector('.article-hero-fallback');
        
        if (heroSection) {
            // Vérifier si le breadcrumb est déjà après la section hero
            const isAfterHero = heroSection.nextElementSibling === breadcrumbNav || 
                               (heroSection.compareDocumentPosition(breadcrumbNav) & Node.DOCUMENT_POSITION_FOLLOWING);
            
            if (!isAfterHero) {
                // Déplacer le breadcrumb après la section hero
                heroSection.parentNode.insertBefore(breadcrumbNav, heroSection.nextSibling);
            }
        }
    }
    
    // Exécuter immédiatement si le DOM est déjà chargé
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', moveBreadcrumbAfterHero);
    } else {
        moveBreadcrumbAfterHero();
    }
})();
</script>
@endif

