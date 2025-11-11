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
        font-size: 1.3rem;
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
        gap: 6px;
        padding: 10px 16px;
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        border-radius: 10px;
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
        gap: 12px;
        padding: 12px 16px;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.3s ease;
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
    
    /* Mobile Menu */
    .mobile-menu {
        position: fixed;
        top: 70px;
        left: 0;
        right: 0;
        background: rgba(15, 23, 42, 0.98);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(6, 182, 212, 0.2);
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease;
    }
    
    .mobile-menu.active {
        max-height: calc(100vh - 70px);
        overflow-y: auto;
    }
    
    .mobile-menu-list {
        list-style: none;
        padding: 20px;
        margin: 0;
    }
    
    .mobile-menu-item {
        margin-bottom: 8px;
    }
    
    .mobile-menu-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .mobile-menu-link:hover {
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
    }
    
    .mobile-dropdown-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 14px 16px;
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
        font-size: 1rem;
        text-align: left;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .mobile-dropdown-toggle:hover {
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
    }
    
    .mobile-dropdown-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        padding-left: 20px;
    }
    
    .mobile-dropdown-content.active {
        max-height: 1000px;
    }
    
    .mobile-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        border-radius: 8px;
        margin-top: 4px;
        transition: all 0.3s ease;
    }
    
    .mobile-dropdown-item:hover {
        background: rgba(6, 182, 212, 0.1);
        color: #06b6d4;
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

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
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
    
    mobileToggle.addEventListener('click', () => {
        mobileToggle.classList.toggle('active');
        mobileMenu.classList.toggle('active');
    });
    
    // Mobile dropdown toggle
    function toggleMobileDropdown(id) {
        const dropdown = document.getElementById(id + '-dropdown');
        const icon = document.getElementById(id + '-icon');
        dropdown.classList.toggle('active');
        icon.style.transform = dropdown.classList.contains('active') ? 'rotate(180deg)' : 'rotate(0deg)';
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.navbar-modern') && !e.target.closest('.mobile-menu')) {
            mobileToggle.classList.remove('active');
            mobileMenu.classList.remove('active');
        }
    });
</script>
