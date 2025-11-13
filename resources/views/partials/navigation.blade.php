<!-- Navigation Ultra Moderne -->
<style>
    /* Navbar Styles */
    .navbar-modern {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        z-index: 9999;
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(6, 182, 212, 0.2);
        transition: all 0.3s ease;
    }
    
    .navbar-modern.scrolled {
        background: rgba(15, 23, 42, 0.98);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }
    
    .navbar-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 70px;
    }
    
    /* Logo */
    .navbar-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        transition: transform 0.3s ease;
    }
    
    .navbar-logo:hover {
        transform: scale(1.05);
    }
    
    .logo-image {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        filter: drop-shadow(0 0 10px rgba(6, 182, 212, 0.5));
    }
    
    .logo-text {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.05rem;
        font-weight: 800;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Desktop Menu */
    .navbar-menu {
        display: flex;
        align-items: center;
        gap: 8px;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .navbar-item {
        position: relative;
    }
    
    .navbar-link {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 8px 12px;
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .navbar-link:hover {
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
    }
    
    .navbar-link.active {
        background: rgba(6, 182, 212, 0.15);
        color: #06b6d4;
    }
    
    /* Dropdown */
    .dropdown {
        position: relative;
    }
    
    .dropdown-toggle {
        cursor: pointer;
    }
    
    .dropdown-icon {
        font-size: 0.7rem;
        transition: transform 0.3s ease;
    }
    
    .dropdown:hover .dropdown-icon {
        transform: rotate(180deg);
    }
    
    .dropdown-menu {
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        min-width: 280px;
        background: rgba(15, 23, 42, 0.98);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 16px;
        padding: 8px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    }
    
    .dropdown:hover .dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }
    
    .dropdown-item:hover {
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
        transform: translateX(5px);
    }
    
    .dropdown-item-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 1.2rem;
        transition: transform 0.3s ease;
    }
    
    .dropdown-item:hover .dropdown-item-icon {
        transform: scale(1.1);
    }
    
    .dropdown-item-content {
        flex: 1;
    }
    
    .dropdown-item-title {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 2px;
    }
    
    .dropdown-item-desc {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.5);
    }
    
    /* CTA Button */
    .navbar-cta {
        padding: 10px 20px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #000;
        text-decoration: none;
        font-weight: 700;
        border-radius: 10px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .navbar-cta:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(6, 182, 212, 0.4);
    }
    
    /* Mobile Menu Toggle */
    .mobile-toggle {
        display: none;
        flex-direction: column;
        gap: 5px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 8px;
    }
    
    .mobile-toggle-line {
        width: 25px;
        height: 2px;
        background: #06b6d4;
        border-radius: 2px;
        transition: all 0.3s ease;
    }
    
    .mobile-toggle.active .mobile-toggle-line:nth-child(1) {
        transform: rotate(45deg) translateY(7px);
    }
    
    .mobile-toggle.active .mobile-toggle-line:nth-child(2) {
        opacity: 0;
    }
    
    .mobile-toggle.active .mobile-toggle-line:nth-child(3) {
        transform: rotate(-45deg) translateY(-7px);
    }
    
    /* Mobile Menu Overlay */
    .mobile-menu-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
        z-index: 9998;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .mobile-menu-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    /* Mobile Menu */
    .mobile-menu {
        position: fixed;
        top: 0;
        right: -100%;
        width: 85%;
        max-width: 400px;
        height: 100vh;
        background: linear-gradient(180deg, rgba(15, 23, 42, 0.98) 0%, rgba(30, 41, 59, 0.98) 100%);
        backdrop-filter: blur(30px);
        border-left: 2px solid rgba(6, 182, 212, 0.3);
        z-index: 9999;
        overflow-y: auto;
        transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: -10px 0 50px rgba(0, 0, 0, 0.5);
    }
    
    .mobile-menu.active {
        right: 0;
    }
    
    .mobile-menu::-webkit-scrollbar {
        width: 6px;
    }
    
    .mobile-menu::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #06b6d4, #14b8a6);
        border-radius: 10px;
    }
    
    .mobile-menu-header {
        padding: 25px 20px;
        border-bottom: 1px solid rgba(6, 182, 212, 0.2);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(6, 182, 212, 0.05);
    }
    
    .mobile-menu-title {
        font-size: 1.3rem;
        font-weight: 800;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .mobile-menu-close {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(6, 182, 212, 0.1);
        border: none;
        color: #06b6d4;
        font-size: 1.2rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .mobile-menu-close:hover {
        background: rgba(6, 182, 212, 0.2);
        transform: rotate(90deg);
    }
    
    .mobile-menu-list {
        list-style: none;
        padding: 20px;
        margin: 0;
    }
    
    .mobile-menu-item {
        margin-bottom: 10px;
    }
    
    .mobile-menu-link {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 16px 18px;
        color: rgba(255, 255, 255, 0.95);
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        position: relative;
        overflow: hidden;
    }
    
    .mobile-menu-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.5s ease;
    }
    
    .mobile-menu-link:hover::before {
        left: 100%;
    }
    
    .mobile-menu-link:hover {
        background: rgba(6, 182, 212, 0.15);
        color: #06b6d4;
        border-color: rgba(6, 182, 212, 0.3);
        transform: translateX(5px);
    }
    
    .mobile-menu-link i {
        width: 24px;
        text-align: center;
        font-size: 1.2rem;
    }
    
    .mobile-dropdown-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 16px 18px;
        background: rgba(6, 182, 212, 0.08);
        border: 1px solid rgba(6, 182, 212, 0.2);
        color: rgba(255, 255, 255, 0.95);
        font-weight: 600;
        font-size: 1rem;
        text-align: left;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 8px;
    }
    
    .mobile-dropdown-toggle:hover {
        background: rgba(6, 182, 212, 0.15);
        color: #06b6d4;
        border-color: rgba(6, 182, 212, 0.4);
        transform: translateX(3px);
    }
    
    .mobile-dropdown-toggle i:first-child {
        margin-right: 12px;
        width: 24px;
        text-align: center;
        font-size: 1.2rem;
    }
    
    .mobile-dropdown-toggle .dropdown-icon {
        transition: transform 0.3s ease;
        font-size: 0.9rem;
    }
    
    .mobile-dropdown-toggle.active .dropdown-icon {
        transform: rotate(180deg);
    }
    
    .mobile-dropdown-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        padding-left: 25px;
        margin-top: 5px;
    }
    
    .mobile-dropdown-content.active {
        max-height: 1000px;
    }
    
    .mobile-dropdown-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        border-radius: 10px;
        margin-top: 6px;
        margin-bottom: 4px;
        transition: all 0.3s ease;
        font-weight: 500;
        border: 1px solid transparent;
    }
    
    .mobile-dropdown-item i {
        width: 20px;
        text-align: center;
        font-size: 1.1rem;
    }
    
    .mobile-dropdown-item:hover {
        background: rgba(6, 182, 212, 0.12);
        color: #06b6d4;
        border-color: rgba(6, 182, 212, 0.25);
        transform: translateX(8px);
        padding-left: 22px;
    }
    
    /* Responsive */
    @media (max-width: 968px) {
        .navbar-menu {
            display: none;
        }
        
        .mobile-toggle {
            display: flex;
        }
        
        .navbar-cta {
            display: none;
        }
    }
</style>

<nav class="navbar-modern" id="navbar">
    <div class="navbar-container">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="navbar-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-image">
            <span class="logo-text">NiangProgrammeur</span>
        </a>
        
        <!-- Desktop Menu -->
        <ul class="navbar-menu">
            <li class="navbar-item">
                <a href="{{ route('home') }}" class="navbar-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    Accueil
                </a>
            </li>
            
            <!-- Dropdown Formations -->
            <li class="navbar-item dropdown">
                <a href="#" class="navbar-link dropdown-toggle">
                    <i class="fas fa-graduation-cap"></i>
                    Formations
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <div class="dropdown-menu">
                    <a href="{{ route('formations.html5') }}" class="dropdown-item">
                        <div class="dropdown-item-icon" style="background: rgba(227, 76, 38, 0.1);">
                            <i class="fab fa-html5" style="color: #e34c26;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">HTML5</div>
                            <div class="dropdown-item-desc">Structure web</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.css3') }}" class="dropdown-item">
                        <div class="dropdown-item-icon" style="background: rgba(38, 77, 228, 0.1);">
                            <i class="fab fa-css3-alt" style="color: #264de4;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">CSS3</div>
                            <div class="dropdown-item-desc">Style & design</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.javascript') }}" class="dropdown-item">
                        <div class="dropdown-item-icon" style="background: rgba(240, 219, 79, 0.1);">
                            <i class="fab fa-js" style="color: #f0db4f;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">JavaScript</div>
                            <div class="dropdown-item-desc">Interactivité</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.php') }}" class="dropdown-item">
                        <div class="dropdown-item-icon" style="background: rgba(137, 147, 190, 0.1);">
                            <i class="fab fa-php" style="color: #8993be;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">PHP</div>
                            <div class="dropdown-item-desc">Backend</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.bootstrap') }}" class="dropdown-item">
                        <div class="dropdown-item-icon" style="background: rgba(121, 82, 179, 0.1);">
                            <i class="fab fa-bootstrap" style="color: #7952b3;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">Bootstrap</div>
                            <div class="dropdown-item-desc">Framework CSS</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.git') }}" class="dropdown-item">
                        <div class="dropdown-item-icon" style="background: rgba(243, 79, 41, 0.1);">
                            <i class="fab fa-git-alt" style="color: #f34f29;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">Git</div>
                            <div class="dropdown-item-desc">Versioning</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.wordpress') }}" class="dropdown-item">
                        <div class="dropdown-item-icon" style="background: rgba(33, 117, 155, 0.1);">
                            <i class="fab fa-wordpress" style="color: #21759b;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">WordPress</div>
                            <div class="dropdown-item-desc">CMS</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.ia') }}" class="dropdown-item">
                        <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.1);">
                            <i class="fas fa-robot" style="color: #06b6d4;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">Intelligence Artificielle</div>
                            <div class="dropdown-item-desc">IA & ML</div>
                        </div>
                    </a>
                </div>
            </li>
            
            <!-- Dropdown Pratique -->
            <li class="navbar-item dropdown">
                <a href="#" class="navbar-link dropdown-toggle">
                    <i class="fas fa-code"></i>
                    Pratique
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <div class="dropdown-menu">
                    <a href="{{ route('exercices') }}" class="dropdown-item">
                        <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.1);">
                            <i class="fas fa-laptop-code" style="color: #06b6d4;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">Exercices</div>
                            <div class="dropdown-item-desc">Pratiquer le code</div>
                        </div>
                    </a>
                    <a href="{{ route('quiz') }}" class="dropdown-item">
                        <div class="dropdown-item-icon" style="background: rgba(168, 85, 247, 0.1);">
                            <i class="fas fa-question-circle" style="color: #a855f7;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">Quiz</div>
                            <div class="dropdown-item-desc">Tester vos connaissances</div>
                        </div>
                    </a>
                </div>
            </li>
            
            <!-- Dropdown Emplois -->
            <li class="navbar-item dropdown">
                <a href="#" class="navbar-link dropdown-toggle">
                    <i class="fas fa-briefcase"></i>
                    Emplois
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <div class="dropdown-menu">
                    <a href="{{ route('emplois') }}" class="dropdown-item">
                        <div class="dropdown-item-icon" style="background: rgba(34, 197, 94, 0.1);">
                            <i class="fas fa-search" style="color: #22c55e;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">Toutes les opportunités</div>
                            <div class="dropdown-item-desc">Vue d'ensemble</div>
                        </div>
                    </a>
                    @if(isset($jobCategories) && $jobCategories->count() > 0)
                        @foreach($jobCategories as $category)
                        <a href="{{ route('emplois.offres') }}?category={{ $category->slug }}" class="dropdown-item">
                            <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.1);">
                                @if($category->icon)
                                    <i class="{{ $category->icon }}" style="color: #06b6d4;"></i>
                                @else
                                    <i class="fas fa-folder" style="color: #06b6d4;"></i>
                                @endif
                            </div>
                            <div class="dropdown-item-content">
                                <div class="dropdown-item-title">{{ $category->name }}</div>
                                <div class="dropdown-item-desc">{{ $category->published_articles_count ?? 0 }} articles</div>
                            </div>
                        </a>
                        @endforeach
                    @else
                        <a href="{{ route('emplois.offres') }}" class="dropdown-item">
                            <div class="dropdown-item-icon" style="background: rgba(59, 130, 246, 0.1);">
                                <i class="fas fa-briefcase" style="color: #3b82f6;"></i>
                            </div>
                            <div class="dropdown-item-content">
                                <div class="dropdown-item-title">Offres d'Emploi</div>
                                <div class="dropdown-item-desc">Emplois disponibles</div>
                            </div>
                        </a>
                        <a href="{{ route('emplois.bourses') }}" class="dropdown-item">
                            <div class="dropdown-item-icon" style="background: rgba(168, 85, 247, 0.1);">
                                <i class="fas fa-graduation-cap" style="color: #a855f7;"></i>
                            </div>
                            <div class="dropdown-item-content">
                                <div class="dropdown-item-title">Bourses d'Études</div>
                                <div class="dropdown-item-desc">Financez vos études</div>
                            </div>
                        </a>
                        <a href="{{ route('emplois.candidature') }}" class="dropdown-item">
                            <div class="dropdown-item-icon" style="background: rgba(236, 72, 153, 0.1);">
                                <i class="fas fa-paper-plane" style="color: #ec4899;"></i>
                            </div>
                            <div class="dropdown-item-content">
                                <div class="dropdown-item-title">Candidature Spontanée</div>
                                <div class="dropdown-item-desc">Faites-vous connaître</div>
                            </div>
                        </a>
                        <a href="{{ route('emplois.opportunites') }}" class="dropdown-item">
                            <div class="dropdown-item-icon" style="background: rgba(251, 191, 36, 0.1);">
                                <i class="fas fa-star" style="color: #fbbf24;"></i>
                            </div>
                            <div class="dropdown-item-content">
                                <div class="dropdown-item-title">Opportunités</div>
                                <div class="dropdown-item-desc">Stages & freelance</div>
                            </div>
                        </a>
                    @endif
                </div>
            </li>
            
            <li class="navbar-item">
                <a href="{{ route('about') }}" class="navbar-link {{ request()->routeIs('about') ? 'active' : '' }}">
                    <i class="fas fa-info-circle"></i>
                    À propos
                </a>
            </li>
        </ul>
        
        <!-- CTA Button -->
        <a href="{{ route('contact') }}" class="navbar-cta">
            <i class="fas fa-envelope"></i>
            Contact
        </a>
        
        <!-- Mobile Toggle -->
        <button class="mobile-toggle" id="mobileToggle">
            <span class="mobile-toggle-line"></span>
            <span class="mobile-toggle-line"></span>
            <span class="mobile-toggle-line"></span>
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
                Accueil
            </a>
        </li>
        
        <!-- Mobile Dropdown Formations -->
        <li class="mobile-menu-item">
            <button class="mobile-dropdown-toggle" onclick="toggleMobileDropdown('formations')">
                <span><i class="fas fa-graduation-cap"></i> Formations</span>
                <i class="fas fa-chevron-down dropdown-icon" id="formations-icon"></i>
            </button>
            <div class="mobile-dropdown-content" id="formations-dropdown">
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
                <a href="{{ route('formations.git') }}" class="mobile-dropdown-item">
                    <i class="fab fa-git-alt" style="color: #f34f29;"></i> Git
                </a>
                <a href="{{ route('formations.wordpress') }}" class="mobile-dropdown-item">
                    <i class="fab fa-wordpress" style="color: #21759b;"></i> WordPress
                </a>
                <a href="{{ route('formations.ia') }}" class="mobile-dropdown-item">
                    <i class="fas fa-robot" style="color: #06b6d4;"></i> Intelligence Artificielle
                </a>
            </div>
        </li>
        
        <!-- Mobile Dropdown Pratique -->
        <li class="mobile-menu-item">
            <button class="mobile-dropdown-toggle" onclick="toggleMobileDropdown('pratique')">
                <span><i class="fas fa-code"></i> Pratique</span>
                <i class="fas fa-chevron-down dropdown-icon" id="pratique-icon"></i>
            </button>
            <div class="mobile-dropdown-content" id="pratique-dropdown">
                <a href="{{ route('exercices') }}" class="mobile-dropdown-item">
                    <i class="fas fa-laptop-code" style="color: #06b6d4;"></i> Exercices
                </a>
                <a href="{{ route('quiz') }}" class="mobile-dropdown-item">
                    <i class="fas fa-question-circle" style="color: #a855f7;"></i> Quiz
                </a>
            </div>
        </li>
        
        <!-- Mobile Dropdown Emplois -->
        <li class="mobile-menu-item">
            <button class="mobile-dropdown-toggle" onclick="toggleMobileDropdown('emplois')">
                <span><i class="fas fa-briefcase"></i> Emplois</span>
                <i class="fas fa-chevron-down dropdown-icon" id="emplois-icon"></i>
            </button>
            <div class="mobile-dropdown-content" id="emplois-dropdown">
                <a href="{{ route('emplois') }}" class="mobile-dropdown-item">
                    <i class="fas fa-search" style="color: #22c55e;"></i> Toutes les opportunités
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
                        <i class="fas fa-briefcase" style="color: #3b82f6;"></i> Offres d'Emploi
                    </a>
                    <a href="{{ route('emplois.bourses') }}" class="mobile-dropdown-item">
                        <i class="fas fa-graduation-cap" style="color: #a855f7;"></i> Bourses d'Études
                    </a>
                    <a href="{{ route('emplois.candidature') }}" class="mobile-dropdown-item">
                        <i class="fas fa-paper-plane" style="color: #ec4899;"></i> Candidature Spontanée
                    </a>
                    <a href="{{ route('emplois.opportunites') }}" class="mobile-dropdown-item">
                        <i class="fas fa-star" style="color: #fbbf24;"></i> Opportunités
                    </a>
                @endif
            </div>
        </li>
        
        <li class="mobile-menu-item">
            <a href="{{ route('about') }}" class="mobile-menu-link">
                <i class="fas fa-info-circle"></i>
                À propos
            </a>
        </li>
        
        <li class="mobile-menu-item">
            <a href="{{ route('contact') }}" class="mobile-menu-link" style="background: linear-gradient(135deg, #06b6d4, #14b8a6); color: #000; font-weight: 700;">
                <i class="fas fa-envelope"></i>
                Contact
            </a>
        </li>
    </ul>
</div>

<script>
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    
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
    
    // Close on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
            closeMobileMenu();
        }
    });
</script>
