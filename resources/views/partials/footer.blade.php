<style>
    .footer-modern {
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.95) 0%, rgba(0, 0, 0, 1) 100%);
        border-top: 2px solid rgba(6, 182, 212, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    body:not(.dark-mode) .footer-modern {
        background: linear-gradient(180deg, rgba(248, 250, 252, 0.95) 0%, rgba(241, 245, 249, 1) 100%) !important;
    }
    
    .footer-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, #06b6d4, transparent);
        animation: shimmer 3s infinite;
    }
    
    @keyframes shimmer {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 1; }
    }
    
    .footer-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr 2fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }
    
    @media (max-width: 1024px) {
        .footer-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .footer-section h3 {
        font-size: 1.25rem;
        font-weight: 700;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1.5rem;
        font-family: 'Orbitron', sans-serif;
    }
    
    .footer-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        color: #9ca3af;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        padding-left: 1.5rem;
    }
    
    .footer-link::before {
        content: '▸';
        position: absolute;
        left: 0;
        color: #06b6d4;
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.3s ease;
    }
    
    .footer-link:hover {
        color: #06b6d4;
        padding-left: 2rem;
    }
    
    .footer-link:hover::before {
        opacity: 1;
        transform: translateX(0);
    }
    
    .social-links {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .social-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: rgba(6, 182, 212, 0.1);
        border: 2px solid rgba(6, 182, 212, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #06b6d4;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .social-icon::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .social-icon:hover {
        transform: translateY(-5px) scale(1.1);
        box-shadow: 0 10px 25px rgba(6, 182, 212, 0.4);
        border-color: #06b6d4;
    }
    
    .social-icon:hover::before {
        opacity: 0.2;
    }
    
    .social-icon i {
        position: relative;
        z-index: 1;
        font-size: 1.25rem;
    }
    
    .newsletter-form {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .newsletter-input {
        flex: 1;
        padding: 0.875rem 1.25rem;
        background: rgba(6, 182, 212, 0.05);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        color: white;
        outline: none;
        transition: all 0.3s ease;
    }
    
    body:not(.dark-mode) .newsletter-input {
        background: rgba(255, 255, 255, 0.9) !important;
        color: rgba(30, 41, 59, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
    
    .newsletter-input:focus {
        border-color: #06b6d4;
        background: rgba(6, 182, 212, 0.1);
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.2);
    }
    
    body:not(.dark-mode) .newsletter-input:focus {
        background: rgba(255, 255, 255, 1) !important;
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.15) !important;
    }
    
    body:not(.dark-mode) .newsletter-input::placeholder {
        color: rgba(30, 41, 59, 0.5) !important;
    }
    
    .newsletter-btn {
        padding: 0.875rem 2rem;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border: none;
        border-radius: 12px;
        color: #000;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }
    
    .newsletter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(6, 182, 212, 0.4);
    }
    
    .footer-bottom {
        border-top: 1px solid rgba(6, 182, 212, 0.1);
        padding-top: 2rem;
        margin-top: 3rem;
    }
    
    .legal-links {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .legal-link {
        color: #6b7280;
        text-decoration: none;
        font-size: 0.875rem;
        transition: color 0.3s ease;
        position: relative;
    }
    
    .legal-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 1px;
        background: #06b6d4;
        transition: width 0.3s ease;
    }
    
    .legal-link:hover {
        color: #06b6d4;
    }
    
    .legal-link:hover::after {
        width: 100%;
    }
    
    .copyright-text {
        text-align: center;
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    body:not(.dark-mode) .copyright-text {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    body:not(.dark-mode) .copyright-text strong {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    .adsense-notice {
        background: rgba(6, 182, 212, 0.05);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 12px;
        padding: 1rem;
        margin-top: 1rem;
        text-align: center;
        font-size: 0.75rem;
        color: #9ca3af;
    }
    
    body:not(.dark-mode) .adsense-notice {
        background: rgba(6, 182, 212, 0.05) !important;
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    /* Force text colors in light mode for footer */
    body:not(.dark-mode) .footer-link {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    body:not(.dark-mode) .footer-link:hover {
        color: #06b6d4 !important;
    }
    
    body:not(.dark-mode) .text-gray-400 {
        color: rgba(30, 41, 59, 0.6) !important;
    }
    
    body:not(.dark-mode) .legal-link {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    body:not(.dark-mode) .text-gray-700 {
        color: rgba(30, 41, 59, 0.5) !important;
    }
    
    body:not(.dark-mode) .text-green-400 {
        color: rgba(34, 197, 94, 0.8) !important;
    }
    
    body:not(.dark-mode) .text-red-400 {
        color: rgba(239, 68, 68, 0.8) !important;
    }
    
    @media (max-width: 768px) {
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .newsletter-form {
            flex-direction: column;
        }
        
        .newsletter-btn {
            width: 100%;
        }
    }
</style>

@php
    $siteSettings = \App\Models\SiteSetting::first();
@endphp

<!-- Footer Ultra Moderne -->
<footer class="footer-modern py-16">
    <div class="container mx-auto px-6">
        <!-- Grid principale -->
        <div class="footer-grid">
            <!-- Section À propos -->
            <div class="footer-section">
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-12 rounded-lg" style="filter: drop-shadow(0 0 10px rgba(6, 182, 212, 0.8));">
                    <h3 style="margin: 0;">{{ $siteSettings->site_name ?? 'NiangProgrammeur' }}</h3>
                </div>
                <p class="text-gray-400 mb-4 leading-relaxed">
                    {{ $siteSettings->site_description ?? 'Plateforme de formation gratuite en développement web. Apprenez HTML, CSS, JavaScript, PHP et bien plus encore.' }}
                </p>
                <div class="social-links">
                    @if($siteSettings && $siteSettings->facebook_url)
                    <a href="{{ $siteSettings->facebook_url }}" target="_blank" class="social-icon" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    @endif
                    
                    @if($siteSettings && $siteSettings->tiktok_url)
                    <a href="{{ $siteSettings->tiktok_url }}" target="_blank" class="social-icon" aria-label="TikTok">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    @endif
                    
                    @if($siteSettings && $siteSettings->linkedin_url)
                    <a href="{{ $siteSettings->linkedin_url }}" target="_blank" class="social-icon" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    @endif
                    
                    @if($siteSettings && $siteSettings->instagram_url)
                    <a href="{{ $siteSettings->instagram_url }}" target="_blank" class="social-icon" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    @endif
                    
                    @if($siteSettings && $siteSettings->youtube_url)
                    <a href="{{ $siteSettings->youtube_url }}" target="_blank" class="social-icon" aria-label="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                    @endif
                    
                    @if($siteSettings && $siteSettings->github_url)
                    <a href="{{ $siteSettings->github_url }}" target="_blank" class="social-icon" aria-label="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                    @endif
                </div>
            </div>
            
            <!-- Section Liens rapides -->
            <div class="footer-section">
                <h3>Liens rapides</h3>
                <a href="{{ route('home') }}" class="footer-link">
                    <i class="fas fa-home mr-2"></i> Accueil
                </a>
                <a href="{{ route('about') }}" class="footer-link">
                    <i class="fas fa-info-circle mr-2"></i> À propos
                </a>
                <a href="{{ route('contact') }}" class="footer-link">
                    <i class="fas fa-envelope mr-2"></i> Contact
                </a>
                <a href="{{ route('faq') }}" class="footer-link">
                    <i class="fas fa-question-circle mr-2"></i> FAQ
                </a>
            </div>
            
            <!-- Section Newsletter -->
            <div class="footer-section">
                <h3>Newsletter</h3>
                <p class="text-gray-400 mb-4">
                    Recevez nos dernières formations et actualités directement dans votre boîte mail.
                </p>
                <form id="newsletterForm" class="newsletter-form">
                    @csrf
                    <input type="email" name="email" id="newsletterEmail" placeholder="Votre email" class="newsletter-input" required>
                    <button type="submit" class="newsletter-btn" id="newsletterBtn">
                        <i class="fas fa-paper-plane mr-2"></i>S'abonner
                    </button>
                </form>
                <div id="newsletterMessage" class="mt-3 text-sm"></div>
            </div>
        </div>
        
        <!-- Bas du footer -->
        <div class="footer-bottom">
            <!-- Liens légaux -->
            <div class="legal-links">
                <a href="{{ route('legal') }}" class="legal-link">
                    <i class="fas fa-balance-scale mr-1"></i>Mentions légales
                </a>
                <span class="text-gray-700">•</span>
                <a href="{{ route('privacy-policy') }}" class="legal-link">
                    <i class="fas fa-shield-alt mr-1"></i>Politique de confidentialité
                </a>
                <span class="text-gray-700">•</span>
                <a href="{{ route('terms') }}" class="legal-link">
                    <i class="fas fa-file-contract mr-1"></i>Conditions d'utilisation
                </a>
                <span class="text-gray-700">•</span>
                <a href="{{ route('privacy-policy') }}#cookies" class="legal-link">
                    <i class="fas fa-cookie-bite mr-1"></i>Politique des cookies
                </a>
            </div>
            
            <!-- Copyright -->
            <div class="copyright-text">
                <p>&copy; {{ date('Y') }} <strong>{{ $siteSettings->site_name ?? 'NiangProgrammeur' }}</strong>. Tous droits réservés.</p>
                <p class="mt-2 text-xs">
                    @if($siteSettings && $siteSettings->contact_email)
                        Contact: <a href="mailto:{{ $siteSettings->contact_email }}" class="text-cyan-400 hover:text-cyan-300">{{ $siteSettings->contact_email }}</a>
                        @if($siteSettings->contact_phone)
                            | Tél: <a href="tel:{{ $siteSettings->contact_phone }}" class="text-cyan-400 hover:text-cyan-300">{{ $siteSettings->contact_phone }}</a>
                        @endif
                    @else
                        Formation professionnelle en développement web | Contenu 100% gratuit
                    @endif
                </p>
            </div>
        </div>
    </div>
</footer>

<script>
    // Gestion du formulaire newsletter
    document.getElementById('newsletterForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = this;
        const email = document.getElementById('newsletterEmail').value;
        const btn = document.getElementById('newsletterBtn');
        const message = document.getElementById('newsletterMessage');
        const csrfToken = document.querySelector('input[name="_token"]').value;
        
        // Désactiver le bouton
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Envoi...';
        
        try {
            const response = await fetch('{{ route("newsletter.subscribe") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email: email })
            });
            
            const data = await response.json();
            
            if (response.ok) {
                message.innerHTML = '<div class="text-green-400"><i class="fas fa-check-circle mr-2"></i>' + data.message + '</div>';
                form.reset();
            } else {
                const errorMessage = data.errors?.email?.[0] || data.message || 'Une erreur est survenue.';
                message.innerHTML = '<div class="text-red-400"><i class="fas fa-exclamation-circle mr-2"></i>' + errorMessage + '</div>';
            }
        } catch (error) {
            message.innerHTML = '<div class="text-red-400"><i class="fas fa-exclamation-circle mr-2"></i>Erreur de connexion. Veuillez réessayer.</div>';
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>S\'abonner';
            
            // Effacer le message après 5 secondes
            setTimeout(() => {
                message.innerHTML = '';
            }, 5000);
        }
    });
</script>
