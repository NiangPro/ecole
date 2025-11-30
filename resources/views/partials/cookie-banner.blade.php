<style>
    .cookie-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .cookie-modal-overlay.show {
        opacity: 1;
        visibility: visible;
    }
    
    .cookie-modal {
        background: linear-gradient(135deg, rgba(10, 10, 26, 0.98), rgba(0, 0, 0, 0.98));
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 20px;
        padding: 2rem;
        max-width: 600px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }
    
    .cookie-modal-overlay.show .cookie-modal {
        transform: scale(1);
    }
</style>

<div id="cookieModal" class="cookie-modal-overlay">
    <div class="cookie-modal">
        <div class="text-center mb-6">
            <div class="w-20 h-20 bg-cyan-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-cookie-bite text-cyan-400 text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-3">🍪 Nous utilisons des cookies</h3>
        </div>
        
        <p class="text-gray-300 text-center leading-relaxed mb-6">
            Nous utilisons des cookies pour améliorer votre expérience, analyser le trafic et afficher des publicités personnalisées via Google AdSense. 
            En continuant à utiliser ce site, vous acceptez notre utilisation des cookies.
        </p>
        
        <div class="bg-cyan-500/10 border border-cyan-500/30 rounded-lg p-4 mb-6">
            <p class="text-sm text-gray-300 text-center">
                <i class="fas fa-info-circle text-cyan-400 mr-2"></i>
                Vous pouvez modifier vos préférences à tout moment dans notre 
                <a href="{{ route('privacy-policy') }}" class="text-cyan-400 hover:text-cyan-300 underline font-semibold">politique de confidentialité</a>
            </p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <button onclick="acceptCookies()" class="px-8 py-3 bg-gradient-to-r from-cyan-500 to-teal-600 text-white font-bold rounded-lg hover:shadow-lg hover:scale-105 transition">
                <i class="fas fa-check mr-2"></i>Accepter tous les cookies
            </button>
            <button onclick="refuseCookies()" class="px-8 py-3 bg-gray-700 hover:bg-gray-600 text-white font-bold rounded-lg transition">
                <i class="fas fa-times mr-2"></i>Refuser
            </button>
        </div>
    </div>
</div>

<script>
    // Vérifier si l'utilisateur a déjà fait un choix
    function checkCookieConsent() {
        try {
            const consent = localStorage.getItem('cookieConsent');
            const modal = document.getElementById('cookieModal');
            
            if (!consent && modal) {
                // Afficher le modal après 10 secondes
                setTimeout(() => {
                    if (modal) {
                        modal.classList.add('show');
                        console.log('Modal cookies affiché');
                    }
                }, 10000);
            }
            // Ne pas logger le consentement en production pour éviter le bruit dans la console
        } catch (error) {
            console.error('Erreur lors de la vérification du consentement:', error);
        }
    }
    
    // Accepter les cookies
    function acceptCookies() {
        try {
            localStorage.setItem('cookieConsent', 'accepted');
            localStorage.setItem('cookieConsentDate', new Date().toISOString());
            hideBanner();
            
            // Activer Google Analytics et AdSense si configurés
            if (typeof gtag !== 'undefined' && typeof window.gtag === 'function') {
                try {
                    gtag('consent', 'update', {
                        'analytics_storage': 'granted',
                        'ad_storage': 'granted'
                    });
                } catch (gtagError) {
                    console.warn('Erreur lors de la mise à jour du consentement gtag:', gtagError);
                }
            }
            
            // Recharger la page pour activer Analytics
            setTimeout(() => {
                location.reload();
            }, 500);
        } catch (error) {
            console.error('Erreur lors de l\'acceptation des cookies:', error);
        }
    }
    
    // Refuser les cookies
    function refuseCookies() {
        try {
            localStorage.setItem('cookieConsent', 'refused');
            localStorage.setItem('cookieConsentDate', new Date().toISOString());
            hideBanner();
            
            // Désactiver Google Analytics et AdSense
            if (typeof gtag !== 'undefined' && typeof window.gtag === 'function') {
                try {
                    gtag('consent', 'update', {
                        'analytics_storage': 'denied',
                        'ad_storage': 'denied'
                    });
                } catch (gtagError) {
                    console.warn('Erreur lors de la mise à jour du consentement gtag:', gtagError);
                }
            }
        } catch (error) {
            console.error('Erreur lors du refus des cookies:', error);
        }
    }
    
    // Masquer le modal
    function hideBanner() {
        const modal = document.getElementById('cookieModal');
        if (modal) {
            modal.classList.remove('show');
        }
    }
    
    // Fonction pour réinitialiser le consentement (pour tester)
    function resetCookieConsent() {
        localStorage.removeItem('cookieConsent');
        localStorage.removeItem('cookieConsentDate');
        console.log('Consentement réinitialisé. Rechargez la page pour voir le modal.');
    }
    
    // Vérifier au chargement de la page
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            try {
                checkCookieConsent();
            } catch (error) {
                console.error('Erreur lors de l\'initialisation du consentement:', error);
            }
        });
    } else {
        try {
            checkCookieConsent();
        } catch (error) {
            console.error('Erreur lors de l\'initialisation du consentement:', error);
        }
    }
    
    // Gestion globale des erreurs non capturées dans les promesses
    // Ignorer les erreurs provenant d'extensions de navigateur (content.js)
    window.addEventListener('unhandledrejection', function(event) {
        if (event.reason && typeof event.reason === 'object') {
            const errorSource = event.reason.stack || event.reason.toString() || '';
            
            // Si l'erreur provient de content.js (extension de navigateur), l'ignorer
            if (errorSource.includes('content.js') || 
                errorSource.includes('extension://') || 
                errorSource.includes('chrome-extension://') ||
                errorSource.includes('moz-extension://')) {
                event.preventDefault(); // Empêcher l'affichage dans la console
                return;
            }
        }
        
        // Ne pas logger les erreurs d'extensions pour éviter le bruit dans la console
        // Les erreurs de notre code sont déjà gérées avec try/catch
    });
</script>
