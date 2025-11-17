// PWA - Service Worker Registration
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then((registration) => {
                console.log('Service Worker registered successfully:', registration.scope);
                
                // Vérifier les mises à jour du Service Worker
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            // Nouveau Service Worker disponible
                            console.log('New Service Worker available');
                            // Optionnel: Afficher une notification à l'utilisateur
                        }
                    });
                });
            })
            .catch((error) => {
                console.log('Service Worker registration failed:', error);
            });
    });
}

// Fonction pour nettoyer le cache
function cleanCache() {
    if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
        navigator.serviceWorker.controller.postMessage({
            type: 'CLEAN_CACHE'
        });
    }
}

// Nettoyer le cache toutes les heures
setInterval(cleanCache, 3600000);

