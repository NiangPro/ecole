<!-- Navigation Fixe -->
<nav style="position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; width: 100% !important; z-index: 99999 !important; background: linear-gradient(135deg, rgba(0, 0, 0, 0.95), rgba(10, 10, 20, 0.95)) !important; backdrop-filter: blur(30px) !important; border-bottom: 1px solid rgba(6, 182, 212, 0.15) !important; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4) !important;">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-80 transition">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 48px; height: 48px; border-radius: 12px; filter: drop-shadow(0 0 15px rgba(6, 182, 212, 0.6));">
                <span style="font-size: 1.5rem; font-weight: 800; background: linear-gradient(135deg, #06b6d4, #14b8a6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">NiangProgrammeur</span>
            </a>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="nav-link text-gray-300 hover:text-cyan-400 font-semibold transition">Accueil</a>
                
                <!-- Dropdown Formations -->
                <div class="relative group">
                    <button class="nav-link text-gray-300 hover:text-cyan-400 font-semibold flex items-center gap-1 transition">
                        Formations
                        <svg class="w-4 h-4 transform group-hover:rotate-180 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="absolute left-0 mt-3 w-72 bg-black/95 backdrop-blur-2xl rounded-2xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 border border-cyan-500/20 overflow-hidden" style="z-index: 10000;">
                        <div class="py-3">
                            <a href="{{ route('formations.html5') }}" class="flex items-center px-6 py-3.5 hover:bg-gradient-to-r hover:from-cyan-500/10 hover:to-transparent transition-all group/item">
                                <div class="w-10 h-10 rounded-lg bg-orange-500/10 flex items-center justify-center mr-3 group-hover/item:scale-110 transition-transform">
                                    <i class="fab fa-html5 text-orange-400 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <span class="block font-semibold text-white group-hover/item:text-cyan-400 transition-colors">HTML5</span>
                                    <span class="text-xs text-gray-500">Structure web</span>
                                </div>
                            </a>
                            <a href="{{ route('formations.css3') }}" class="flex items-center px-6 py-3.5 hover:bg-gradient-to-r hover:from-cyan-500/10 hover:to-transparent transition-all group/item">
                                <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center mr-3 group-hover/item:scale-110 transition-transform">
                                    <i class="fab fa-css3-alt text-blue-400 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <span class="block font-semibold text-white group-hover/item:text-cyan-400 transition-colors">CSS3</span>
                                    <span class="text-xs text-gray-500">Style & design</span>
                                </div>
                            </a>
                            <a href="{{ route('formations.javascript') }}" class="flex items-center px-6 py-3.5 hover:bg-gradient-to-r hover:from-cyan-500/10 hover:to-transparent transition-all group/item">
                                <div class="w-10 h-10 rounded-lg bg-yellow-500/10 flex items-center justify-center mr-3 group-hover/item:scale-110 transition-transform">
                                    <i class="fab fa-js text-yellow-400 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <span class="block font-semibold text-white group-hover/item:text-cyan-400 transition-colors">JavaScript</span>
                                    <span class="text-xs text-gray-500">Interactivité</span>
                                </div>
                            </a>
                            <a href="{{ route('formations.php') }}" class="flex items-center px-6 py-3.5 hover:bg-gradient-to-r hover:from-cyan-500/10 hover:to-transparent transition-all group/item">
                                <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center mr-3 group-hover/item:scale-110 transition-transform">
                                    <i class="fab fa-php text-purple-400 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <span class="block font-semibold text-white group-hover/item:text-cyan-400 transition-colors">PHP</span>
                                    <span class="text-xs text-gray-500">Backend</span>
                                </div>
                            </a>
                            <a href="{{ route('formations.bootstrap') }}" class="flex items-center px-6 py-3.5 hover:bg-gradient-to-r hover:from-cyan-500/10 hover:to-transparent transition-all group/item">
                                <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center mr-3 group-hover/item:scale-110 transition-transform">
                                    <i class="fab fa-bootstrap text-purple-400 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <span class="block font-semibold text-white group-hover/item:text-cyan-400 transition-colors">Bootstrap</span>
                                    <span class="text-xs text-gray-500">Framework CSS</span>
                                </div>
                            </a>
                            <a href="{{ route('formations.git') }}" class="flex items-center px-6 py-3.5 hover:bg-gradient-to-r hover:from-cyan-500/10 hover:to-transparent transition-all group/item">
                                <div class="w-10 h-10 rounded-lg bg-red-500/10 flex items-center justify-center mr-3 group-hover/item:scale-110 transition-transform">
                                    <i class="fab fa-git-alt text-red-400 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <span class="block font-semibold text-white group-hover/item:text-cyan-400 transition-colors">Git</span>
                                    <span class="text-xs text-gray-500">Versioning</span>
                                </div>
                            </a>
                            <a href="{{ route('formations.wordpress') }}" class="flex items-center px-6 py-3.5 hover:bg-gradient-to-r hover:from-cyan-500/10 hover:to-transparent transition-all group/item">
                                <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center mr-3 group-hover/item:scale-110 transition-transform">
                                    <i class="fab fa-wordpress text-blue-400 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <span class="block font-semibold text-white group-hover/item:text-cyan-400 transition-colors">WordPress</span>
                                    <span class="text-xs text-gray-500">CMS</span>
                                </div>
                            </a>
                            <a href="{{ route('formations.ia') }}" class="flex items-center px-6 py-3.5 hover:bg-gradient-to-r hover:from-cyan-500/10 hover:to-transparent transition-all group/item">
                                <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center mr-3 group-hover/item:scale-110 transition-transform">
                                    <i class="fas fa-robot text-green-400 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <span class="block font-semibold text-white group-hover/item:text-cyan-400 transition-colors">Intelligence Artificielle</span>
                                    <span class="text-xs text-gray-500">IA & ML</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('about') }}" class="nav-link text-gray-300 hover:text-cyan-400 font-semibold transition">À propos</a>
                <a href="{{ route('exercices') }}" class="nav-link text-gray-300 hover:text-cyan-400 font-semibold transition">Exercices</a>
                <a href="{{ route('contact') }}" class="px-6 py-2.5 bg-gradient-to-r from-cyan-500 to-teal-600 text-white font-bold rounded-lg hover:shadow-lg hover:scale-105 transition">
                    <i class="fas fa-envelope mr-2"></i>Contact
                </a>
            </div>

            <!-- Burger Menu Mobile -->
            <button id="burgerBtn" class="md:hidden flex flex-col gap-1.5 w-8 h-8 justify-center items-center">
                <span class="burger-line w-full h-0.5 bg-cyan-400 rounded transition-all"></span>
                <span class="burger-line w-full h-0.5 bg-cyan-400 rounded transition-all"></span>
                <span class="burger-line w-full h-0.5 bg-cyan-400 rounded transition-all"></span>
            </button>
        </div>
    </div>
</nav>

<!-- Overlay Mobile -->
<div id="mobileOverlay" class="fixed inset-0 bg-black/80 backdrop-blur-sm opacity-0 invisible transition-all duration-300" style="z-index: 9998;"></div>

<!-- Menu Mobile -->
<div id="mobileMenu" class="fixed top-0 right-0 h-full w-80 max-w-[85%] bg-black/98 backdrop-blur-xl border-l border-cyan-500/30 transform translate-x-full transition-transform duration-300 overflow-y-auto" style="z-index: 9999;">
    <div class="p-6">
        <!-- Header Mobile -->
        <div class="flex items-center justify-between mb-8">
            <span class="text-xl font-bold bg-gradient-to-r from-cyan-400 to-teal-500 bg-clip-text text-transparent">Menu</span>
            <button id="closeMenuBtn" class="w-10 h-10 rounded-full bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center hover:bg-cyan-500/20 transition">
                <i class="fas fa-times text-cyan-400"></i>
            </button>
        </div>

        <!-- Items Mobile -->
        <div class="space-y-2">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-cyan-500/10 transition">
                <i class="fas fa-home text-cyan-400"></i>
                <span class="text-white font-semibold">Accueil</span>
            </a>

            <!-- Formations Mobile -->
            <div>
                <button id="formationsToggle" class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-lg hover:bg-cyan-500/10 transition">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-graduation-cap text-cyan-400"></i>
                        <span class="text-white font-semibold">Formations</span>
                    </div>
                    <i class="fas fa-chevron-down text-cyan-400 transition-transform" id="formationsIcon"></i>
                </button>
                <div id="formationsSubmenu" class="max-h-0 overflow-hidden transition-all duration-300">
                    <div class="pl-12 space-y-1 mt-2">
                        <a href="{{ route('formations.html5') }}" class="block py-2 text-gray-300 hover:text-cyan-400 transition">HTML5</a>
                        <a href="{{ route('formations.css3') }}" class="block py-2 text-gray-300 hover:text-cyan-400 transition">CSS3</a>
                        <a href="{{ route('formations.javascript') }}" class="block py-2 text-gray-300 hover:text-cyan-400 transition">JavaScript</a>
                        <a href="{{ route('formations.php') }}" class="block py-2 text-gray-300 hover:text-cyan-400 transition">PHP</a>
                        <a href="{{ route('formations.bootstrap') }}" class="block py-2 text-gray-300 hover:text-cyan-400 transition">Bootstrap</a>
                        <a href="{{ route('formations.git') }}" class="block py-2 text-gray-300 hover:text-cyan-400 transition">Git</a>
                        <a href="{{ route('formations.wordpress') }}" class="block py-2 text-gray-300 hover:text-cyan-400 transition">WordPress</a>
                        <a href="{{ route('formations.ia') }}" class="block py-2 text-gray-300 hover:text-cyan-400 transition">Intelligence Artificielle</a>
                    </div>
                </div>
            </div>

            <a href="{{ route('about') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-cyan-500/10 transition">
                <i class="fas fa-user text-cyan-400"></i>
                <span class="text-white font-semibold">À propos</span>
            </a>

            <a href="{{ route('exercices') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-cyan-500/10 transition">
                <i class="fas fa-dumbbell text-cyan-400"></i>
                <span class="text-white font-semibold">Exercices</span>
            </a>

            <a href="{{ route('contact') }}" class="flex items-center gap-3 px-4 py-3 mt-4 bg-gradient-to-r from-cyan-500 to-teal-600 rounded-lg hover:shadow-lg transition">
                <i class="fas fa-envelope text-white"></i>
                <span class="text-white font-bold">Contact</span>
            </a>
        </div>
    </div>
</div>

<script>
    // Menu Mobile
    const burgerBtn = document.getElementById('burgerBtn');
    const closeMenuBtn = document.getElementById('closeMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const formationsToggle = document.getElementById('formationsToggle');
    const formationsSubmenu = document.getElementById('formationsSubmenu');
    const formationsIcon = document.getElementById('formationsIcon');

    function openMobileMenu() {
        mobileMenu.classList.remove('translate-x-full');
        mobileOverlay.classList.remove('opacity-0', 'invisible');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        mobileMenu.classList.add('translate-x-full');
        mobileOverlay.classList.add('opacity-0', 'invisible');
        document.body.style.overflow = '';
    }

    burgerBtn.addEventListener('click', openMobileMenu);
    closeMenuBtn.addEventListener('click', closeMobileMenu);
    mobileOverlay.addEventListener('click', closeMobileMenu);

    // Toggle Formations
    formationsToggle.addEventListener('click', () => {
        const isOpen = formationsSubmenu.style.maxHeight && formationsSubmenu.style.maxHeight !== '0px';
        if (isOpen) {
            formationsSubmenu.style.maxHeight = '0';
            formationsIcon.style.transform = 'rotate(0deg)';
        } else {
            formationsSubmenu.style.maxHeight = formationsSubmenu.scrollHeight + 'px';
            formationsIcon.style.transform = 'rotate(180deg)';
        }
    });

    // Scroll effect
    window.addEventListener('scroll', () => {
        const nav = document.querySelector('nav');
        if (window.scrollY > 50) {
            nav.style.background = 'rgba(0, 0, 0, 0.98)';
            nav.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.8)';
        } else {
            nav.style.background = 'linear-gradient(135deg, rgba(0, 0, 0, 0.95), rgba(10, 10, 20, 0.95))';
            nav.style.boxShadow = '0 8px 32px rgba(0, 0, 0, 0.4)';
        }
    });
</script>

<style>
    .nav-link {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .nav-link:hover {
        background: rgba(6, 182, 212, 0.1);
    }
    
    #burgerBtn.active .burger-line:nth-child(1) {
        transform: rotate(45deg) translateY(8px);
    }
    
    #burgerBtn.active .burger-line:nth-child(2) {
        opacity: 0;
    }
    
    #burgerBtn.active .burger-line:nth-child(3) {
        transform: rotate(-45deg) translateY(-8px);
    }
</style>
