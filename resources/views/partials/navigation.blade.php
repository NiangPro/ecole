@php
    // Requêtes cachées 30 min — évite 2+ DB calls par page
    $hasPaidCourses = \Illuminate\Support\Facades\Cache::remember('nav_has_paid_courses', 1800,
        fn() => \App\Models\PaidCourse::where('status', 'published')->exists()
    );
    $userDocumentsCount = 0;
    if (\Illuminate\Support\Facades\Auth::check()) {
        $userDocumentsCount = \Illuminate\Support\Facades\Cache::remember(
            'user_docs_count_' . \Illuminate\Support\Facades\Auth::id(), 1800,
            fn() => \App\Models\DocumentPurchase::where('user_id', \Illuminate\Support\Facades\Auth::id())
                ->where('status', 'completed')->count()
        );
    }
@endphp

<!-- Navigation Ultra Moderne -->

<nav class="navbar-modern" id="navigation" role="navigation" aria-label="Navigation principale">
    <div class="navbar-container">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="navbar-logo">
            <picture>
                <source srcset="{{ asset('images/logo.webp') }}" type="image/webp">
                <img src="{{ asset('images/logo.png') }}" alt="Logo NiangProgrammeur" class="logo-image" width="44" height="44" loading="eager" fetchpriority="high">
            </picture>
            <span class="logo-text">NiangProgrammeur</span>
        </a>
        
        <!-- Desktop Menu -->
        <ul class="navbar-menu">
            <li class="navbar-item">
                <a href="{{ route('home') }}" class="navbar-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    {{ trans('app.nav.home') }}
                </a>
            </li>
            
            <!-- Dropdown Formations -->
            <li class="navbar-item dropdown">
                <a href="#" class="navbar-link dropdown-toggle">
                    {{ trans('app.nav.formations') }}
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <div class="dropdown-menu">
                    @if($hasPaidCourses)
                        <!-- Formations Payantes -->
                        <a href="{{ route('monetization.courses') }}" class="dropdown-item" data-parent-active="formations">
                            <div class="dropdown-item-icon" style="background: rgba(251, 191, 36, 0.15); border: 1px solid rgba(251, 191, 36, 0.3);">
                                <i class="fas fa-crown" style="color: #fbbf24;"></i>
                            </div>
                            <div class="dropdown-item-content">
                                <div class="dropdown-item-title">
                                    Formations Payantes
                                    <span style="font-size: 0.7rem; color: #fbbf24; margin-left: 5px;">
                                        <i class="fas fa-star"></i> Premium
                                    </span>
                                </div>
                                <div class="dropdown-item-desc">Découvrez nos formations premium</div>
                            </div>
                        </a>
                        
                        <!-- Séparateur visuel -->
                        <div style="height: 1px; background: rgba(6, 182, 212, 0.2); margin: 8px 0;"></div>
                    @endif
                    
                    <a href="{{ route('formations.all') }}" class="dropdown-item" data-parent-active="formations">
                        <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.1);">
                            <i class="fas fa-graduation-cap" style="color: #06b6d4;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.formations.all') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.formations.all_desc') }}</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.html5') }}" class="dropdown-item" data-parent-active="formations">
                        <div class="dropdown-item-icon" style="background: rgba(227, 76, 38, 0.1);">
                            <i class="fab fa-html5" style="color: #e34c26;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.formations.html5') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.formations.html5_desc') }}</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.css3') }}" class="dropdown-item" data-parent-active="formations">
                        <div class="dropdown-item-icon" style="background: rgba(38, 77, 228, 0.1);">
                            <i class="fab fa-css3-alt" style="color: #264de4;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.formations.css3') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.formations.css3_desc') }}</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.javascript') }}" class="dropdown-item" data-parent-active="formations">
                        <div class="dropdown-item-icon" style="background: rgba(240, 219, 79, 0.1);">
                            <i class="fab fa-js" style="color: #f0db4f;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.formations.javascript') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.formations.javascript_desc') }}</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.php') }}" class="dropdown-item" data-parent-active="formations">
                        <div class="dropdown-item-icon" style="background: rgba(137, 147, 190, 0.1);">
                            <i class="fab fa-php" style="color: #8993be;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.formations.php') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.formations.php_desc') }}</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.bootstrap') }}" class="dropdown-item" data-parent-active="formations">
                        <div class="dropdown-item-icon" style="background: rgba(121, 82, 179, 0.1);">
                            <i class="fab fa-bootstrap" style="color: #7952b3;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.formations.bootstrap') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.formations.bootstrap_desc') }}</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.java') }}" class="dropdown-item" data-parent-active="formations">
                        <div class="dropdown-item-icon" style="background: rgba(237, 139, 0, 0.1);">
                            <i class="fab fa-java" style="color: #ed8b00;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.formations.java') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.formations.java_desc') }}</div>
                        </div>
                    </a>
                </div>
            </li>
            
            <!-- Dropdown Pratique -->
            <li class="navbar-item dropdown">
                <a href="#" class="navbar-link dropdown-toggle">
                    {{ trans('app.nav.practice') }}
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <div class="dropdown-menu">
                    <a href="{{ route('exercices') }}" class="dropdown-item" data-parent-active="pratique">
                        <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.1);">
                            <i class="fas fa-laptop-code" style="color: #06b6d4;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.practice.exercices') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.practice.exercices_desc') }}</div>
                        </div>
                    </a>
                    <a href="{{ route('quiz') }}" class="dropdown-item" data-parent-active="pratique">
                        <div class="dropdown-item-icon" style="background: rgba(168, 85, 247, 0.1);">
                            <i class="fas fa-question-circle" style="color: #a855f7;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.practice.quiz') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.practice.quiz_desc') }}</div>
                        </div>
                    </a>
                </div>
            </li>
            
            <!-- Dropdown Emplois -->
            <li class="navbar-item dropdown">
                <a href="#" class="navbar-link dropdown-toggle">
                    {{ trans('app.nav.jobs') }}
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <div class="dropdown-menu">
                    <a href="{{ route('emplois') }}" class="dropdown-item" data-parent-active="emplois">
                        <div class="dropdown-item-icon" style="background: rgba(34, 197, 94, 0.1);">
                            <i class="fas fa-search" style="color: #22c55e;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.jobs.all') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.jobs.all_desc') }}</div>
                        </div>
                    </a>
                    @if(isset($jobCategories) && $jobCategories->count() > 0)
                        @foreach($jobCategories as $category)
                        <a href="{{ route('emplois.offres') }}?category={{ $category->slug }}" class="dropdown-item" data-parent-active="emplois">
                            <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.1);">
                                @if($category->icon)
                                    <i class="{{ $category->icon }}" style="color: #06b6d4;"></i>
                                @else
                                    <i class="fas fa-folder" style="color: #06b6d4;"></i>
                                @endif
                            </div>
                            <div class="dropdown-item-content">
                                <div class="dropdown-item-title">{{ $category->name }}</div>
                                <div class="dropdown-item-desc">{{ $category->published_articles_count ?? 0 }} {{ trans('app.nav.dropdown.jobs.articles') }}</div>
                            </div>
                        </a>
                        @endforeach
                    @else
                        <a href="{{ route('emplois.offres') }}" class="dropdown-item" data-parent-active="emplois">
                            <div class="dropdown-item-icon" style="background: rgba(59, 130, 246, 0.1);">
                                <i class="fas fa-briefcase" style="color: #3b82f6;"></i>
                            </div>
                            <div class="dropdown-item-content">
                                <div class="dropdown-item-title">{{ trans('app.nav.dropdown.jobs.offers') }}</div>
                                <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.jobs.offers_desc') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('emplois.bourses') }}" class="dropdown-item" data-parent-active="emplois">
                            <div class="dropdown-item-icon" style="background: rgba(168, 85, 247, 0.1);">
                                <i class="fas fa-graduation-cap" style="color: #a855f7;"></i>
                            </div>
                            <div class="dropdown-item-content">
                                <div class="dropdown-item-title">{{ trans('app.nav.dropdown.jobs.scholarships') }}</div>
                                <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.jobs.scholarships_desc') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('emplois.candidature') }}" class="dropdown-item" data-parent-active="emplois">
                            <div class="dropdown-item-icon" style="background: rgba(236, 72, 153, 0.1);">
                                <i class="fas fa-paper-plane" style="color: #ec4899;"></i>
                            </div>
                            <div class="dropdown-item-content">
                                <div class="dropdown-item-title">{{ trans('app.nav.dropdown.jobs.application') }}</div>
                                <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.jobs.application_desc') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('emplois.opportunites') }}" class="dropdown-item" data-parent-active="emplois">
                            <div class="dropdown-item-icon" style="background: rgba(251, 191, 36, 0.1);">
                                <i class="fas fa-star" style="color: #fbbf24;"></i>
                            </div>
                            <div class="dropdown-item-content">
                                <div class="dropdown-item-title">{{ trans('app.nav.dropdown.jobs.opportunities') }}</div>
                                <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.jobs.opportunities_desc') }}</div>
                            </div>
                        </a>
                    @endif
                </div>
            </li>
            
            <!-- Dropdown À propos / Contact -->
            <li class="navbar-item dropdown">
                <a href="#" class="navbar-link dropdown-toggle {{ request()->routeIs(['about', 'contact', 'monetization.index', 'monetization.affiliates', 'monetization.affiliates.dashboard', 'docs', 'documents.*', 'forum.*', 'admin-docs.*']) ? 'active' : '' }}">
                    {{ trans('app.nav.about') }}
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <div class="dropdown-menu">
                    @php
                        $hasActiveSubscriptionPlans = \App\Models\SubscriptionPlan::active()->exists();
                        $hasPublishedDocuments = \App\Models\Document::published()->active()->exists();
                    @endphp
                    <a href="{{ route('admin-docs.index') }}" class="dropdown-item" data-parent-active="admin-docs">
                        <div class="dropdown-item-icon" style="background: rgba(56, 189, 248, 0.1);">
                            <i class="fas fa-id-card" style="color: #38bdf8;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">Documents administratifs</div>
                            <div class="dropdown-item-desc">Papiers & démarches au Sénégal</div>
                        </div>
                    </a>
                    <a href="{{ route('epreuves.index') }}" class="dropdown-item" data-parent-active="epreuves">
                        <div class="dropdown-item-icon" style="background: rgba(16, 185, 129, 0.1);">
                            <i class="fas fa-graduation-cap" style="color: #10b981;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">Épreuves &amp; Corrigés</div>
                            <div class="dropdown-item-desc">CFEE, BFEM, BAC — PDF gratuits</div>
                        </div>
                    </a>
                    @if($hasPublishedDocuments)
                    <a href="{{ route('documents.index') }}" class="dropdown-item" data-parent-active="documents">
                        <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.1);">
                            <i class="fas fa-file-alt" style="color: #06b6d4;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">Documents</div>
                            <div class="dropdown-item-desc">Guides, tutoriels et ressources</div>
                        </div>
                    </a>
                    @endif
                    @if($hasActiveSubscriptionPlans)
                    <a href="{{ route('monetization.index') }}" class="dropdown-item" data-parent-active="monetization">
                        <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.1);">
                            <i class="fas fa-crown" style="color: #06b6d4;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">Abonnement</div>
                            <div class="dropdown-item-desc">Abonnez-vous à nos services</div>
                        </div>
                    </a>
                    @endif
                    <a href="{{ route('about') }}" class="dropdown-item" data-parent-active="about">
                        <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.1);">
                            <i class="fas fa-info-circle" style="color: #06b6d4;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">Apropos</div>
                            <div class="dropdown-item-desc">En savoir plus sur NiangProgrammeur</div>
                        </div>
                    </a>
                    <a href="{{ route('contact') }}" class="dropdown-item" data-parent-active="contact">
                        <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.1);">
                            <i class="fas fa-envelope" style="color: #06b6d4;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.contact') }}</div>
                            <div class="dropdown-item-desc">Contactez-nous</div>
                        </div>
                    </a>
                    <a href="{{ route('monetization.affiliates') }}" class="dropdown-item" data-parent-active="affiliates">
                        <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.1);">
                            <i class="fas fa-users" style="color: #06b6d4;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">Programme d'Affiliation</div>
                            <div class="dropdown-item-desc">Gagnez des commissions</div>
                        </div>
                    </a>
                    <a href="{{ route('docs') }}" class="dropdown-item" data-parent-active="docs">
                        <div class="dropdown-item-icon" style="background: rgba(34, 197, 94, 0.1);">
                            <i class="fas fa-book" style="color: #22c55e;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">Documentation</div>
                            <div class="dropdown-item-desc">Documentation complète du projet</div>
                        </div>
                    </a>
                    <a href="{{ route('forum.index') }}" class="dropdown-item" data-parent-active="forum">
                        <div class="dropdown-item-icon" style="background: rgba(168, 85, 247, 0.1);">
                            <i class="fas fa-comments" style="color: #a855f7;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">Forum</div>
                            <div class="dropdown-item-desc">Rejoignez la communauté</div>
                        </div>
                    </a>
                </div>
            </li>
        </ul>
        
        <!-- Language Toggle (dans la navbar) -->
        @php
            $showLanguageWidget = request()->routeIs([
                'home',
                'about',
                'contact',
                'login',
                'register',
                'formations.all',
                'formations.html5',
                'formations.css3',
                'formations.javascript',
                'formations.php',
                'formations.bootstrap',
                'formations.java',
                'formations.sql',
                'formations.c',
                'formations.cpp',
                'formations.csharp',
                'formations.dart',
                'formations.wordpress',
                'formations.ia',
                'formations.python',
                'formations.go',
                'formations.rust',
                'formations.ruby',
                'formations.swift',
                'formations.perl',
                'formations.typescript',
                'formations.cybersecurite',
                'formations.data-science',
                'formations.big-data',
                'exercices',
                'exercices.language',
                'exercices.detail',
                'exercices.run',
                'quiz',
                'quiz.language',
                'quiz.result',
                'monetization.index',
                'monetization.donations',
                'monetization.donations.alias',
                'monetization.affiliates',
                'monetization.courses',
                'monetization.course.show',
                'payment.wave',
                'docs',
                'documents.index',
                'documents.category',
                'documents.show',
                'dashboard.*'
            ]);
        @endphp
        
        @if($showLanguageWidget)
        @php
            $currentLang = app()->getLocale();
            $tooltipText = $currentLang === 'fr' ? 'Changer en anglais' : 'Switch to French';
        @endphp
        <div class="navbar-language-widget">
            <button id="language-toggle" class="navbar-language-button" onclick="toggleLanguage()" title="{{ $tooltipText }}" aria-label="{{ $tooltipText }}">
                @if($currentLang === 'fr')
                <!-- Drapeau français -->
                <svg class="navbar-language-flag" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg">
                    <g fill-rule="evenodd" stroke-width="1pt">
                        <path fill="#fff" d="M0 0h640v480H0z"/>
                        <path fill="#00267f" d="M0 0h213.3v480H0z"/>
                        <path fill="#f31830" d="M426.7 0H640v480H426.7z"/>
                    </g>
                </svg>
                @else
                <!-- Drapeau américain simplifié -->
                <svg class="navbar-language-flag" viewBox="0 0 7410 3900" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#b22234" d="M0 0h7410v3900H0z"/>
                    <path d="M0 450h7410m0 600H0m0 600h7410m0 600H0m0 600h7410m0 600H0m0 600h7410m0 600H0" stroke="#fff" stroke-width="300"/>
                    <path fill="#3c3b6e" d="M0 0h2964v2100H0z"/>
                    <g fill="#fff">
                        <g id="star">
                            <path d="M247 90l70.534 217.082-184.66-134.164h228.253L176.466 307.082z"/>
                        </g>
                        <use href="#star" x="988" y="210"/>
                        <use href="#star" x="1976" y="420"/>
                        <use href="#star" x="494" y="420"/>
                        <use href="#star" x="1482" y="630"/>
                        <use href="#star" x="2470" y="630"/>
                        <use href="#star" x="988" y="840"/>
                        <use href="#star" x="1976" y="840"/>
                        <use href="#star" x="494" y="1050"/>
                        <use href="#star" x="1482" y="1260"/>
                        <use href="#star" x="2470" y="1260"/>
                        <use href="#star" x="988" y="1470"/>
                        <use href="#star" x="1976" y="1470"/>
                        <use href="#star" x="494" y="1680"/>
                        <use href="#star" x="1482" y="1890"/>
                        <use href="#star" x="2470" y="1890"/>
                    </g>
                </svg>
                @endif
                <span class="navbar-language-tooltip" id="language-tooltip">{{ $tooltipText }}</span>
            </button>
        </div>
        @endif
        
        <!-- Search Icon -->
        <div class="navbar-search-container" style="position: relative;">
            <button type="button" class="navbar-search-icon" id="searchIcon" aria-label="{{ trans('app.home.search.button') }}" aria-expanded="false" aria-controls="searchForm">
                <i class="fas fa-search" aria-hidden="true"></i>
                <span class="sr-only">{{ trans('app.home.search.button') }}</span>
            </button>
            
            <!-- Search Form (hidden by default) -->
            <form action="{{ route('search') }}" method="GET" class="navbar-search-form" id="searchForm" role="search">
                <div class="search-header">
                    <div class="search-title">
                        <i class="fas fa-search"></i>
                        <span>{{ trans('app.home.search.title') }}</span>
                    </div>
                    <button type="button" class="search-close" id="searchClose" aria-label="{{ trans('app.home.search.close') }}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="search-wrapper">
                    <input type="search" name="q" id="navbarSearch" 
                           value="{{ request('q') }}" 
                           placeholder="{{ trans('app.home.search.placeholder') }}" 
                           class="search-input"
                           aria-label="{{ trans('app.home.search.label') }}"
                           autocomplete="off">
                    <button type="submit" class="search-button" aria-label="{{ trans('app.home.search.submit') }}">
                        <i class="fas fa-search"></i>
                        <span>{{ trans('app.home.search.button') }}</span>
                    </button>
                </div>
                <div class="search-hint">
                    <i class="fas fa-lightbulb"></i>
                    <span>{{ trans('app.home.search.hint') }}</span>
                </div>
            </form>
        </div>
        
        <!-- Cart Widget (dans la navbar) - Visible sur les pages documents -->
        @php
            $showCartWidget = request()->routeIs(['documents.*']) || 
                             request()->is('documents*') || 
                             request()->is('documents/creem*');
            $cartItemsCount = 0;
            $cartTotal = 0;
            if ($showCartWidget) {
                try {
                    $cartItems = \App\Models\DocumentCart::getCurrentCart();
                    $cartItemsCount = $cartItems->count();
                    $cartTotal = \App\Models\DocumentCart::getTotal($cartItems);
                } catch (\Exception $e) {
                    $cartItemsCount = 0;
                    $cartTotal = 0;
                }
            }
        @endphp
        @if($showCartWidget)
        <div class="navbar-item" style="position: relative;">
            <a href="{{ route('documents.cart') }}" class="navbar-cart-icon" aria-label="Panier" style="
                background: none;
                border: none;
                color: rgba(255, 255, 255, 0.9);
                font-size: 1.3rem;
                cursor: pointer;
                padding: 10px 14px;
                border-radius: 10px;
                transition: all 0.3s ease;
                position: relative;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                min-width: 44px;
                min-height: 44px;
            " onmouseover="this.style.background='rgba(6, 182, 212, 0.15)'; this.style.color='#06b6d4'; this.style.transform='scale(1.1)';" 
               onmouseout="this.style.background='none'; this.style.color='rgba(255, 255, 255, 0.9)'; this.style.transform='scale(1)';">
                <i class="fas fa-shopping-cart" style="display: inline-block; font-size: 1.3rem; line-height: 1;"></i>
                @if($cartItemsCount > 0)
                <span class="navbar-cart-badge" style="
                    position: absolute;
                    top: 6px;
                    right: 6px;
                    background: linear-gradient(135deg, #06b6d4, #14b8a6);
                    color: white;
                    border-radius: 50%;
                    min-width: 22px;
                    height: 22px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 0.7rem;
                    font-weight: 800;
                    border: 2px solid rgba(15, 23, 42, 0.95);
                    box-shadow: 0 2px 8px rgba(6, 182, 212, 0.5);
                    animation: pulse 2s infinite;
                ">{{ $cartItemsCount }}</span>
                @endif
            </a>
        </div>
        @endif
        
        <!-- Notification Widget (dans la navbar) -->
        @auth
        <div class="navbar-item" id="notification-widget-container" style="position: relative;">
            <button type="button" class="navbar-notification-bell" id="notificationBell" aria-label="Notifications" onclick="toggleNotificationDropdown(event);" style="
                background: none;
                border: none;
                color: rgba(255, 255, 255, 0.9);
                font-size: 1.2rem;
                cursor: pointer;
                padding: 8px 12px;
                border-radius: 8px;
                transition: all 0.3s ease;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
            ">
                <i class="fas fa-bell"></i>
                <span class="navbar-notification-badge" id="notificationBadge" style="
                    position: absolute;
                    top: 4px;
                    right: 4px;
                    background: #ef4444;
                    color: white;
                    border-radius: 50%;
                    width: 18px;
                    height: 18px;
                    display: none;
                    align-items: center;
                    justify-content: center;
                    font-size: 0.65rem;
                    font-weight: 700;
                    border: 2px solid rgba(15, 23, 42, 0.95);
                    animation: pulse 2s infinite;
                ">0</span>
            </button>
            <div class="notification-dropdown navbar-notification-dropdown" id="notificationDropdown" style="
                position: absolute;
                top: calc(100% + 15px);
                right: 0;
                width: 380px;
                max-height: 500px;
                background: white;
                border-radius: 16px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
                display: none;
                flex-direction: column;
                overflow: hidden;
                transform: translateY(20px);
                opacity: 0;
                transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                z-index: 10000;
            ">
                <div class="notification-header">
                    <h4>Notifications</h4>
                    <button type="button" class="mark-all-read" id="markAllRead" onclick="markAllNotificationsAsRead(event);">Tout marquer comme lu</button>
                </div>
                <div class="notification-list" id="notificationList">
                    <div class="notification-empty">Aucune notification</div>
                </div>
                <div class="notification-footer">
                    <a href="/dashboard/notifications">Voir toutes les notifications</a>
                </div>
            </div>
        </div>
        @endauth
        
        <!-- User Dropdown -->
        @auth
        <div class="navbar-item dropdown" id="userDropdown">
            <a href="#" class="navbar-cta dropdown-toggle" aria-label="Menu utilisateur" onclick="event.preventDefault(); toggleUserDropdown();" style="padding: 6px 10px; min-width: auto; height: auto;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <div style="width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg, #06b6d4, #14b8a6); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.75rem; flex-shrink: 0;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.75rem;">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down dropdown-icon" style="font-size: 0.6rem; margin-left: 2px;"></i>
                </div>
            </a>
            <div class="dropdown-menu" id="userDropdownMenu" style="right: 0; left: auto; min-width: 220px;">
                <a href="{{ route('dashboard.overview') }}" class="dropdown-item">
                    <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.2); color: #06b6d4;">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <div class="dropdown-item-content">
                        <div class="dropdown-item-title">Dashboard</div>
                    </div>
                </a>
                <a href="{{ route('dashboard.my-documents') }}" class="dropdown-item">
                    <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.2); color: #06b6d4;">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="dropdown-item-content">
                        <div class="dropdown-item-title">Mes Documents</div>
                        @if($userDocumentsCount > 0)
                            <div class="dropdown-item-desc">{{ $userDocumentsCount }} document(s)</div>
                        @endif
                    </div>
                </a>
                <a href="{{ route('dashboard.profile') }}" class="dropdown-item">
                    <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.2); color: #06b6d4;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="dropdown-item-content">
                        <div class="dropdown-item-title">Profil</div>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item danger" style="width: 100%; border: none; background: none; text-align: left; cursor: pointer; padding: 12px 16px;">
                        <div class="dropdown-item-icon" style="background: rgba(239, 68, 68, 0.2); color: #ef4444;">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title" style="color: #ef4444;">Déconnexion</div>
                        </div>
                    </button>
                </form>
            </div>
        </div>
        @else
        <a href="{{ route('login') }}" class="navbar-cta" aria-label="Connexion">
            <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
            {{ trans('app.nav.login') ?? 'Connexion' }}
        </a>
        @endauth
        
        <!-- Mobile Toggle -->
        <button class="mobile-toggle" id="mobileToggle" aria-label="Ouvrir le menu mobile" aria-expanded="false">
            <span class="mobile-toggle-line" aria-hidden="true"></span>
            <span class="mobile-toggle-line" aria-hidden="true"></span>
            <span class="mobile-toggle-line" aria-hidden="true"></span>
        </button>
    </div>
</nav>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-menu-header">
        <div class="mobile-menu-title">Menu</div>
        <button class="mobile-menu-close" onclick="closeMobileMenu()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <ul class="mobile-menu-list">
        <li class="mobile-menu-item">
            <a href="{{ route('home') }}" class="mobile-menu-link">
                <i class="fas fa-home"></i>
                {{ trans('app.nav.home') }}
            </a>
        </li>
        
        <!-- Mobile Dropdown Formations -->
        <li class="mobile-menu-item">
            <button class="mobile-dropdown-toggle" onclick="toggleMobileDropdown('formations')">
                <span><i class="fas fa-graduation-cap"></i> {{ trans('app.nav.formations') }}</span>
                <i class="fas fa-chevron-down dropdown-icon" id="formations-icon"></i>
            </button>
            <div class="mobile-dropdown-content" id="formations-dropdown">
                @if($hasPaidCourses)
                    <!-- Formations Payantes Mobile -->
                    <a href="{{ route('monetization.courses') }}" class="mobile-dropdown-item">
                        <i class="fas fa-crown" style="color: #fbbf24;"></i> Formations Payantes <span style="font-size: 0.7rem; color: #fbbf24;">Premium</span>
                    </a>
                    
                    <!-- Séparateur -->
                    <div style="height: 1px; background: rgba(6, 182, 212, 0.2); margin: 8px 0;"></div>
                @endif
                
                <a href="{{ route('formations.all') }}" class="mobile-dropdown-item">
                    <i class="fas fa-graduation-cap" style="color: #06b6d4;"></i> {{ trans('app.nav.dropdown.formations.all') }}
                </a>
                <a href="{{ route('formations.html5') }}" class="mobile-dropdown-item">
                    <i class="fab fa-html5" style="color: #e34c26;"></i> HTML5
                </a>
                <a href="{{ route('formations.css3') }}" class="mobile-dropdown-item">
                    <i class="fab fa-css3-alt" style="color: #264de4;"></i> CSS3
                </a>
                <a href="{{ route('formations.javascript') }}" class="mobile-dropdown-item">
                    <i class="fab fa-js" style="color: #f0db4f;"></i> JavaScript
                </a>
                <a href="{{ route('formations.php') }}" class="mobile-dropdown-item">
                    <i class="fab fa-php" style="color: #8993be;"></i> PHP
                </a>
                <a href="{{ route('formations.bootstrap') }}" class="mobile-dropdown-item">
                    <i class="fab fa-bootstrap" style="color: #7952b3;"></i> Bootstrap
                </a>
                <a href="{{ route('formations.java') }}" class="mobile-dropdown-item">
                    <i class="fab fa-java" style="color: #ed8b00;"></i> Java
                </a>
            </div>
        </li>
        
        <!-- Mobile Dropdown Pratique -->
        <li class="mobile-menu-item">
            <button class="mobile-dropdown-toggle" onclick="toggleMobileDropdown('pratique')">
                <span><i class="fas fa-code"></i> {{ trans('app.nav.practice') }}</span>
                <i class="fas fa-chevron-down dropdown-icon" id="pratique-icon"></i>
            </button>
            <div class="mobile-dropdown-content" id="pratique-dropdown">
                <a href="{{ route('exercices') }}" class="mobile-dropdown-item">
                    <i class="fas fa-laptop-code" style="color: #06b6d4;"></i> {{ trans('app.nav.dropdown.practice.exercices') }}
                </a>
                <a href="{{ route('quiz') }}" class="mobile-dropdown-item">
                    <i class="fas fa-question-circle" style="color: #a855f7;"></i> {{ trans('app.nav.dropdown.practice.quiz') }}
                </a>
            </div>
        </li>
        
        <!-- Mobile Dropdown Emplois -->
        <li class="mobile-menu-item">
            <button class="mobile-dropdown-toggle" onclick="toggleMobileDropdown('emplois')">
                <span><i class="fas fa-briefcase"></i> {{ trans('app.nav.jobs') }}</span>
                <i class="fas fa-chevron-down dropdown-icon" id="emplois-icon"></i>
            </button>
            <div class="mobile-dropdown-content" id="emplois-dropdown">
                <a href="{{ route('emplois') }}" class="mobile-dropdown-item">
                    <i class="fas fa-search" style="color: #22c55e;"></i> {{ trans('app.nav.dropdown.jobs.all') }}
                </a>
                @if(isset($jobCategories) && $jobCategories->count() > 0)
                    @foreach($jobCategories as $category)
                    <a href="{{ route('emplois.offres') }}?category={{ $category->slug }}" class="mobile-dropdown-item">
                        @if($category->icon)
                            <i class="{{ $category->icon }}" style="color: #06b6d4;"></i>
                        @else
                            <i class="fas fa-folder" style="color: #06b6d4;"></i>
                        @endif
                        {{ $category->name }}
                    </a>
                    @endforeach
                @else
                    <a href="{{ route('emplois.offres') }}" class="mobile-dropdown-item">
                        <i class="fas fa-briefcase" style="color: #3b82f6;"></i> {{ trans('app.nav.dropdown.jobs.offers') }}
                    </a>
                    <a href="{{ route('emplois.bourses') }}" class="mobile-dropdown-item">
                        <i class="fas fa-graduation-cap" style="color: #a855f7;"></i> {{ trans('app.nav.dropdown.jobs.scholarships') }}
                    </a>
                    <a href="{{ route('emplois.candidature') }}" class="mobile-dropdown-item">
                        <i class="fas fa-paper-plane" style="color: #ec4899;"></i> {{ trans('app.nav.dropdown.jobs.application') }}
                    </a>
                    <a href="{{ route('emplois.opportunites') }}" class="mobile-dropdown-item">
                        <i class="fas fa-star" style="color: #fbbf24;"></i> {{ trans('app.nav.dropdown.jobs.opportunities') }}
                    </a>
                @endif
            </div>
        </li>
        
        <!-- Recherche (équivalent à l'icône search du desktop) -->
        <li class="mobile-menu-item">
            <a href="#" class="mobile-menu-link" onclick="closeMobileMenu(); setTimeout(function(){ document.getElementById('searchIcon')?.click(); }, 300); return false;">
                <i class="fas fa-search"></i> {{ trans('app.home.search.button') }}
            </a>
        </li>
        
        <!-- Panier (visible sur les pages documents, comme le desktop) -->
        @if(isset($showCartWidget) && $showCartWidget)
        <li class="mobile-menu-item">
            <a href="{{ route('documents.cart') }}" class="mobile-menu-link">
                <i class="fas fa-shopping-cart"></i> Panier
                @if($cartItemsCount > 0)
                <span style="margin-left: auto; background: linear-gradient(135deg, #06b6d4, #14b8a6); color: #000; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem; font-weight: 700;">{{ $cartItemsCount }}</span>
                @endif
            </a>
        </li>
        @endif
        
        <!-- Mobile Dropdown À propos (conforme au menu desktop) -->
        <li class="mobile-menu-item">
            <button class="mobile-dropdown-toggle" onclick="toggleMobileDropdown('apropos')">
                <span><i class="fas fa-info-circle"></i> {{ trans('app.nav.about') }}</span>
                <i class="fas fa-chevron-down dropdown-icon" id="apropos-icon"></i>
            </button>
            <div class="mobile-dropdown-content" id="apropos-dropdown">
                @php
                    $hasActiveSubscriptionPlansMobile = \App\Models\SubscriptionPlan::active()->exists();
                    $hasPublishedDocumentsMobile = \App\Models\Document::published()->active()->exists();
                @endphp
                <a href="{{ route('admin-docs.index') }}" class="mobile-dropdown-item">
                    <i class="fas fa-id-card" style="color: #38bdf8;"></i> Documents administratifs
                </a>
                <a href="{{ route('epreuves.index') }}" class="mobile-dropdown-item">
                    <i class="fas fa-graduation-cap" style="color: #10b981;"></i> Épreuves &amp; Corrigés
                </a>
                @if($hasPublishedDocumentsMobile)
                <a href="{{ route('documents.index') }}" class="mobile-dropdown-item">
                    <i class="fas fa-file-alt" style="color: #06b6d4;"></i> Documents
                </a>
                @endif
                @if($hasActiveSubscriptionPlansMobile)
                <a href="{{ route('monetization.index') }}" class="mobile-dropdown-item">
                    <i class="fas fa-crown" style="color: #06b6d4;"></i> Abonnement
                </a>
                @endif
                <a href="{{ route('about') }}" class="mobile-dropdown-item">
                    <i class="fas fa-info-circle" style="color: #06b6d4;"></i> {{ trans('app.nav.about') }}
                </a>
                <a href="{{ route('contact') }}" class="mobile-dropdown-item">
                    <i class="fas fa-envelope" style="color: #06b6d4;"></i> {{ trans('app.nav.contact') }}
                </a>
                <a href="{{ route('monetization.affiliates') }}" class="mobile-dropdown-item">
                    <i class="fas fa-users" style="color: #06b6d4;"></i> Programme d'Affiliation
                </a>
                <a href="{{ route('docs') }}" class="mobile-dropdown-item">
                    <i class="fas fa-book" style="color: #22c55e;"></i> Documentation
                </a>
                <a href="{{ route('forum.index') }}" class="mobile-dropdown-item">
                    <i class="fas fa-comments" style="color: #a855f7;"></i> Forum
                </a>
            </div>
        </li>
        
        <!-- Language Toggle dans le menu mobile -->
        @php
            $showLanguageWidget = request()->routeIs([
                'home',
                'about',
                'contact',
                'formations.all',
                'formations.html5',
                'formations.css3',
                'formations.javascript',
                'formations.php',
                'formations.bootstrap',
                'formations.java',
                'formations.sql',
                'formations.c',
                'formations.cpp',
                'formations.csharp',
                'formations.dart',
                'formations.wordpress',
                'formations.ia',
                'formations.python',
                'formations.go',
                'formations.rust',
                'formations.ruby',
                'formations.swift',
                'formations.perl',
                'formations.typescript',
                'formations.cybersecurite',
                'formations.data-science',
                'formations.big-data',
                'exercices',
                'exercices.language',
                'exercices.detail',
                'exercices.run',
                'quiz',
                'quiz.language',
                'quiz.result',
                'monetization.index',
                'monetization.donations',
                'monetization.donations.alias',
                'monetization.affiliates',
                'monetization.courses',
                'monetization.course.show',
                'payment.wave',
                'docs',
                'documents.index',
                'documents.category',
                'documents.show',
                'dashboard.*'
            ]);
        @endphp
        
        @if($showLanguageWidget)
        @php
            $currentLang = app()->getLocale();
            $tooltipText = $currentLang === 'fr' ? 'Changer en anglais' : 'Switch to French';
        @endphp
        <li class="mobile-menu-item">
            <a href="javascript:void(0)" class="mobile-menu-link" onclick="toggleLanguage()" style="background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.3);">
                @if($currentLang === 'fr')
                <!-- Drapeau français -->
                <svg class="mobile-language-flag" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 18px; margin-right: 12px;">
                    <g fill-rule="evenodd" stroke-width="1pt">
                        <path fill="#fff" d="M0 0h640v480H0z"/>
                        <path fill="#00267f" d="M0 0h213.3v480H0z"/>
                        <path fill="#f31830" d="M426.7 0H640v480H426.7z"/>
                    </g>
                </svg>
                @else
                <!-- Drapeau américain simplifié -->
                <svg class="mobile-language-flag" viewBox="0 0 7410 3900" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 18px; margin-right: 12px;">
                    <path fill="#b22234" d="M0 0h7410v3900H0z"/>
                    <path d="M0 450h7410m0 600H0m0 600h7410m0 600H0m0 600h7410m0 600H0m0 600h7410m0 600H0" stroke="#fff" stroke-width="300"/>
                    <path fill="#3c3b6e" d="M0 0h2964v2100H0z"/>
                    <g fill="#fff">
                        <g id="star">
                            <path d="M247 90l70.534 217.082-184.66-134.164h228.253L176.466 307.082z"/>
                        </g>
                        <use href="#star" x="988" y="210"/>
                        <use href="#star" x="1976" y="420"/>
                        <use href="#star" x="494" y="420"/>
                        <use href="#star" x="1482" y="630"/>
                        <use href="#star" x="2470" y="630"/>
                        <use href="#star" x="988" y="840"/>
                        <use href="#star" x="1976" y="840"/>
                        <use href="#star" x="494" y="1050"/>
                        <use href="#star" x="1482" y="1260"/>
                        <use href="#star" x="2470" y="1260"/>
                        <use href="#star" x="988" y="1470"/>
                        <use href="#star" x="1976" y="1470"/>
                        <use href="#star" x="494" y="1680"/>
                        <use href="#star" x="1482" y="1890"/>
                        <use href="#star" x="2470" y="1890"/>
                    </g>
                </svg>
                @endif
                <span>{{ $tooltipText }}</span>
            </a>
        </li>
        @endif
        
        @auth
        <li class="mobile-menu-item">
            <a href="{{ route('dashboard.overview') }}" class="mobile-menu-link" style="background: linear-gradient(135deg, #06b6d4, #14b8a6); color: #000; font-weight: 700;">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
        </li>
        <li class="mobile-menu-item">
            <a href="{{ route('dashboard.my-documents') }}" class="mobile-menu-link">
                <i class="fas fa-file-alt"></i>
                Mes Documents
            </a>
        </li>
        <li class="mobile-menu-item">
            <a href="{{ route('dashboard.profile') }}" class="mobile-menu-link">
                <i class="fas fa-user"></i>
                Profil
            </a>
        </li>
        <li class="mobile-menu-item">
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="mobile-menu-link" style="width: 100%; border: none; background: rgba(239, 68, 68, 0.1); color: #ef4444; text-align: left; cursor: pointer;">
                    <i class="fas fa-sign-out-alt"></i>
                    Déconnexion
                </button>
            </form>
        </li>
        @else
        <li class="mobile-menu-item">
            <a href="{{ route('login') }}" class="mobile-menu-link" style="background: linear-gradient(135deg, #06b6d4, #14b8a6); color: #000; font-weight: 700;">
                <i class="fas fa-sign-in-alt"></i>
                {{ trans('app.nav.login') ?? 'Connexion' }}
            </a>
        </li>
        @endauth
    </ul>
</div>

<script>
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        try {
        const navbar = document.getElementById('navbar');
            if (navbar && navbar.classList) {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
            }
        } catch (error) {
            // Ignorer silencieusement
        }
    }, { passive: true });
    
    // Mobile menu toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
    
    function openMobileMenu() {
        mobileToggle.classList.add('active');
        mobileMenu.classList.add('active');
        mobileMenuOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeMobileMenu() {
        mobileToggle.classList.remove('active');
        mobileMenu.classList.remove('active');
        mobileMenuOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    mobileToggle.addEventListener('click', () => {
        if (mobileMenu.classList.contains('active')) {
            closeMobileMenu();
        } else {
            openMobileMenu();
        }
    });
    
    mobileMenuOverlay.addEventListener('click', closeMobileMenu);
    
    // Mobile dropdown toggle
    function toggleMobileDropdown(id) {
        const dropdown = document.getElementById(id + '-dropdown');
        const icon = document.getElementById(id + '-icon');
        const toggle = event.target.closest('.mobile-dropdown-toggle');
        
        dropdown.classList.toggle('active');
        if (toggle) {
            toggle.classList.toggle('active');
        }
        if (icon) {
        icon.style.transform = dropdown.classList.contains('active') ? 'rotate(180deg)' : 'rotate(0deg)';
        }
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.navbar-modern') && !e.target.closest('.mobile-menu') && !e.target.closest('.mobile-menu-overlay')) {
            closeMobileMenu();
        }
    });
    
    // User Dropdown Toggle
    function toggleUserDropdown() {
        const dropdown = document.getElementById('userDropdown');
        const menu = document.getElementById('userDropdownMenu');
        if (dropdown && menu) {
            const isActive = dropdown.classList.contains('active');
            if (isActive) {
                dropdown.classList.remove('active');
                menu.style.opacity = '0';
                menu.style.visibility = 'hidden';
                menu.style.transform = 'translateY(-15px) scale(0.95)';
            } else {
                dropdown.classList.add('active');
                menu.style.opacity = '1';
                menu.style.visibility = 'visible';
                menu.style.transform = 'translateY(0) scale(1)';
            }
        }
    }
    
    // Close user dropdown when clicking outside
    document.addEventListener('click', (e) => {
        const userDropdown = document.getElementById('userDropdown');
        const userDropdownMenu = document.getElementById('userDropdownMenu');
        if (userDropdown && userDropdownMenu && !userDropdown.contains(e.target)) {
            userDropdown.classList.remove('active');
            userDropdownMenu.style.opacity = '0';
            userDropdownMenu.style.visibility = 'hidden';
            userDropdownMenu.style.transform = 'translateY(-15px) scale(0.95)';
        }
    });
    
    // Close on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
            closeMobileMenu();
        }
        if (e.key === 'Escape') {
            const searchForm = document.getElementById('searchForm');
            if (searchForm && searchForm.classList.contains('active')) {
                closeSearchForm();
            }
            const userDropdown = document.getElementById('userDropdown');
            const userDropdownMenu = document.getElementById('userDropdownMenu');
            if (userDropdown && userDropdownMenu && userDropdown.classList.contains('active')) {
                userDropdown.classList.remove('active');
                userDropdownMenu.style.opacity = '0';
                userDropdownMenu.style.visibility = 'hidden';
                userDropdownMenu.style.transform = 'translateY(-15px) scale(0.95)';
            }
        }
    });
    
    // Search Form Toggle
    const searchIcon = document.getElementById('searchIcon');
    const searchForm = document.getElementById('searchForm');
    const searchClose = document.getElementById('searchClose');
    const navbarSearch = document.getElementById('navbarSearch');
    
    function openSearchForm() {
        if (searchForm) {
            searchForm.classList.add('active');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                if (navbarSearch) {
                    navbarSearch.focus();
                }
            }, 100);
        }
    }
    
    function closeSearchForm() {
        if (searchForm) {
            searchForm.classList.remove('active');
            document.body.style.overflow = '';
            if (navbarSearch) {
                navbarSearch.blur();
            }
        }
    }
    
    if (searchIcon) {
        searchIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            if (searchForm && searchForm.classList.contains('active')) {
                closeSearchForm();
            } else {
                openSearchForm();
            }
        });
    }
    
    if (searchClose) {
        searchClose.addEventListener('click', function(e) {
            e.stopPropagation();
            closeSearchForm();
        });
    }
    
    // Fermer le formulaire de recherche si on clique en dehors
    document.addEventListener('click', function(e) {
        if (searchForm && searchIcon) {
            if (!searchForm.contains(e.target) && !searchIcon.contains(e.target)) {
                closeSearchForm();
            }
        }
    });
    
    // Empêcher la fermeture quand on clique dans le formulaire
    if (searchForm) {
        searchForm.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Ajouter la classe active sur le parent du dropdown quand on clique sur un élément
    document.querySelectorAll('.dropdown-item[data-parent-active]').forEach(item => {
        item.addEventListener('click', function() {
            const parentId = this.getAttribute('data-parent-active');
            const parentDropdown = this.closest('.dropdown');
            
            if (parentDropdown) {
                // Retirer active de tous les autres dropdowns
                document.querySelectorAll('.dropdown').forEach(dropdown => {
                    dropdown.querySelector('.navbar-link')?.classList.remove('active');
                });
                
                // Ajouter active au parent
                const parentLink = parentDropdown.querySelector('.navbar-link');
                if (parentLink) {
                    parentLink.classList.add('active');
                }
            }
        });
    });
    
    // Vérifier l'URL actuelle et activer le parent si nécessaire
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        
        // Vérifier les routes de formations
        if (currentPath.includes('/formations/')) {
            const formationsDropdown = document.querySelector('.dropdown:has([href*="/formations/"])');
            if (formationsDropdown) {
                const parentLink = formationsDropdown.querySelector('.navbar-link');
                if (parentLink) {
                    parentLink.classList.add('active');
                }
            }
        }
        
        // Vérifier les routes de pratique (exercices/quiz)
        if (currentPath.includes('/exercices/') || currentPath.includes('/quiz/')) {
            const pratiqueDropdown = document.querySelector('.dropdown:has([href*="/exercices"], [href*="/quiz"])');
            if (pratiqueDropdown) {
                const parentLink = pratiqueDropdown.querySelector('.navbar-link');
                if (parentLink) {
                    parentLink.classList.add('active');
                }
            }
        }
        
        // Vérifier les routes d'emplois
        if (currentPath.includes('/emplois/')) {
            const emploisDropdown = document.querySelector('.dropdown:has([href*="/emplois"])');
            if (emploisDropdown) {
                const parentLink = emploisDropdown.querySelector('.navbar-link');
                if (parentLink) {
                    parentLink.classList.add('active');
                }
            }
        }
    });
    
    // Fonction globale pour toggle le dropdown de notifications
    window.toggleNotificationDropdown = function(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        const dropdown = document.getElementById('notificationDropdown');
        if (!dropdown) {
            console.error('Dropdown de notifications non trouvé');
            return;
        }
        
        const isActive = dropdown.classList.contains('active');
        if (isActive) {
            dropdown.classList.remove('active');
        } else {
            dropdown.classList.add('active');
            
            // Charger les notifications si nécessaire
            const list = document.getElementById('notificationList');
            if (list && (list.innerHTML.includes('Chargement') || list.innerHTML.includes('Aucune notification'))) {
                // Utiliser le manager s'il existe, sinon charger directement
                if (window.notificationManager && typeof window.notificationManager.loadNotifications === 'function') {
                    window.notificationManager.loadNotifications();
                } else {
                    // Charger les notifications directement
                    loadNotificationsDirectly();
                }
            }
        }
    };
    
    // Fonction pour charger les notifications directement
    window.loadNotificationsDirectly = async function() {
        if (!window.isAuthenticated) return;
        
        const list = document.getElementById('notificationList');
        const badge = document.getElementById('notificationBadge');
        
        if (!list) return;
        
        try {
            const response = await fetch('/api/notifications/unread', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                const notifications = data.notifications || data || [];
                const count = data.count !== undefined ? data.count : notifications.length;
                
                // Mettre à jour le badge
                if (badge) {
                    if (count > 0) {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                }
                
                // Mettre à jour la liste
                if (notifications.length === 0) {
                    list.innerHTML = '<div class="notification-empty">Aucune notification</div>';
                } else {
                    list.innerHTML = notifications.map(notif => {
                        const icon = getNotificationIcon(notif.type);
                        const time = formatNotificationTime(notif.created_at);
                        const message = notif.message || notif.title || 'Nouvelle notification';
                        const link = notif.link || '/dashboard/notifications';
                        return `
                            <div class="notification-item" onclick="window.location.href='${link}'">
                                <div class="notification-icon"><i class="fas ${icon}"></i></div>
                                <div class="notification-content">
                                    <p>${message}</p>
                                    <span>${time}</span>
                                </div>
                            </div>
                        `;
                    }).join('');
                }
            }
        } catch (error) {
            console.error('Erreur chargement notifications:', error);
            list.innerHTML = '<div class="notification-error">Erreur de chargement</div>';
        }
    };
    
    window.getNotificationIcon = function(type) {
        const icons = {
            'comment_reply': 'fa-reply',
            'new_badge': 'fa-award',
            'formation_completed': 'fa-trophy',
            'default': 'fa-bell'
        };
        return icons[type] || icons.default;
    };
    
    window.formatNotificationTime = function(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diff = now - date;
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);
        
        if (minutes < 1) return 'À l\'instant';
        if (minutes < 60) return `Il y a ${minutes} min`;
        if (hours < 24) return `Il y a ${hours}h`;
        if (days < 7) return `Il y a ${days}j`;
        return date.toLocaleDateString('fr-FR');
    };
    
    // Fonction pour marquer toutes les notifications comme lues
    window.markAllNotificationsAsRead = async function(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        try {
            const response = await fetch('/api/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });
            
            if (response.ok) {
                // Recharger les notifications
                loadNotificationsDirectly();
                
                // Utiliser le manager s'il existe
                if (window.notificationManager) {
                    window.notificationManager.loadNotifications();
                }
            }
        } catch (error) {
            console.error('Erreur marquer tout comme lu:', error);
        }
    };
    
    // Fonction pour mettre à jour le badge du panier
    function updateCartBadge() {
        @if($showCartWidget ?? false)
        fetch('{{ route("documents.cart.total") }}')
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('.navbar-cart-badge');
                if (data.count > 0) {
                    if (badge) {
                        badge.textContent = data.count;
                        badge.style.display = 'flex';
                    } else {
                        // Créer le badge s'il n'existe pas
                        const cartIcon = document.querySelector('.navbar-cart-icon');
                        if (cartIcon) {
                            const newBadge = document.createElement('span');
                            newBadge.className = 'navbar-cart-badge';
                            newBadge.style.cssText = `
                                position: absolute;
                                top: 4px;
                                right: 4px;
                                background: #06b6d4;
                                color: white;
                                border-radius: 50%;
                                width: 20px;
                                height: 20px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-size: 0.7rem;
                                font-weight: 700;
                                border: 2px solid rgba(15, 23, 42, 0.95);
                                animation: pulse 2s infinite;
                            `;
                            newBadge.textContent = data.count;
                            cartIcon.appendChild(newBadge);
                        }
                    }
                } else {
                    if (badge) {
                        badge.style.display = 'none';
                    }
                }
            })
            .catch(error => console.error('Erreur mise à jour panier:', error));
        @endif
    }

    // Charger les notifications au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        // Mettre à jour le badge du panier
        updateCartBadge();
        // Mettre à jour toutes les 30 secondes
        setInterval(updateCartBadge, 30000);
        
        // Charger les notifications immédiatement si l'utilisateur est connecté
        if (window.isAuthenticated) {
            // Attendre un peu pour que le NotificationManager soit initialisé
            setTimeout(function() {
                if (window.notificationManager && typeof window.notificationManager.loadNotifications === 'function') {
                    window.notificationManager.loadNotifications();
                } else {
                    // Si le manager n'est pas encore prêt, charger directement
                    loadNotificationsDirectly();
                }
            }, 500);
        }
        
        const notificationBell = document.getElementById('notificationBell');
        if (notificationBell) {
            // Supprimer les anciens listeners
            const newBell = notificationBell.cloneNode(true);
            notificationBell.parentNode.replaceChild(newBell, notificationBell);
            
            // Attacher le nouveau listener
            newBell.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (typeof window.toggleNotificationDropdown === 'function') {
                    window.toggleNotificationDropdown(e);
                } else {
                    console.error('toggleNotificationDropdown n\'est pas définie');
                }
            });
        }
    });
    
</script>
