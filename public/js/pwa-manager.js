// Gestionnaire PWA amélioré pour NiangProgrammeur
class PWAManager {
    constructor() {
        this.deferredPrompt = null;
        this.serviceWorkerRegistration = null;
        this.pushSubscription = null;
        this.init();
    }

    async init() {
        if ('serviceWorker' in navigator) {
            await this.registerServiceWorker();
            this.setupInstallPrompt();
            this.setupUpdateCheck();
        }

        if ('Notification' in window) {
            this.setupPushNotifications();
        }

        this.setupOfflineDetection();
    }

    // Enregistrer le Service Worker
    async registerServiceWorker() {
        try {
            const registration = await navigator.serviceWorker.register('/sw.js', {
                scope: '/'
            });

            this.serviceWorkerRegistration = registration;
            console.log('[PWA] Service Worker enregistré:', registration.scope);

            // Écouter les mises à jour
            registration.addEventListener('updatefound', () => {
                const newWorker = registration.installing;
                newWorker.addEventListener('statechange', () => {
                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                        this.showUpdateNotification();
                    }
                });
            });

            // Vérifier les mises à jour toutes les heures
            setInterval(() => {
                registration.update();
            }, 3600000);

        } catch (error) {
            console.error('[PWA] Erreur lors de l\'enregistrement du Service Worker:', error);
        }
    }

    // Configuration de l'invite d'installation
    setupInstallPrompt() {
        if (window.matchMedia('(display-mode: standalone)').matches) {
            document.body.classList.add('pwa-installed');
            return; // app déjà installée, pas de bouton
        }
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            this.deferredPrompt = e;
            this.showInstallButton();
        });
    }

    // Afficher le bouton d'installation
    showInstallButton() {
        const existing = document.getElementById('pwa-install-btn') || document.getElementById('pwa-install-button');
        if (existing) {
            existing.style.setProperty('display', 'flex', 'important');
            return;
        }

        // Création de secours si ux-improvements.js n'est pas chargé
        const btn = document.createElement('button');
        btn.id = 'pwa-install-button';
        btn.innerHTML = '<i class="fas fa-download"></i><span>Installer l\'application</span>';
        btn.addEventListener('click', () => this.installApp());
        btn.style.cssText = `
            position: fixed !important;
            bottom: 130px !important;
            left: 20px !important;
            z-index: 9997 !important;
            display: flex !important;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: linear-gradient(135deg, #06b6d4, #14b8a6);
            color: #fff;
            border: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.82rem;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(6,182,212,0.35);
            font-family: inherit;
        `;
        document.body.appendChild(btn);
    }

    // Installer l'application
    async installApp() {
        if (!this.deferredPrompt) {
            return;
        }

        this.deferredPrompt.prompt();
        const { outcome } = await this.deferredPrompt.userChoice;

        if (outcome === 'accepted') {
            console.log('[PWA] Application installée');
            this.hideInstallButton();
        }

        this.deferredPrompt = null;
    }

    // Masquer le bouton d'installation
    hideInstallButton() {
        const button = document.getElementById('pwa-install-button');
        if (button) {
            button.remove();
        }
    }

    // Vérifier les mises à jour
    setupUpdateCheck() {
        if (this.serviceWorkerRegistration) {
            this.serviceWorkerRegistration.addEventListener('updatefound', () => {
                const newWorker = this.serviceWorkerRegistration.installing;
                newWorker.addEventListener('statechange', () => {
                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                        this.showUpdateNotification();
                    }
                });
            });
        }
    }

    // Afficher la notification de mise à jour
    showUpdateNotification() {
        if (confirm('Une nouvelle version de l\'application est disponible. Voulez-vous la charger maintenant ?')) {
            if (this.serviceWorkerRegistration && this.serviceWorkerRegistration.waiting) {
                this.serviceWorkerRegistration.waiting.postMessage({ type: 'SKIP_WAITING' });
                window.location.reload();
            }
        }
    }

    // Configuration des notifications push
    async setupPushNotifications() {
        // Demander la permission
        if (Notification.permission === 'default') {
            // Ne pas demander immédiatement, attendre une action utilisateur
            this.createPushNotificationButton();
        } else if (Notification.permission === 'granted') {
            await this.subscribeToPush();
        }
    }

    // Créer le bouton pour activer les notifications push
    createPushNotificationButton() {
        if (document.getElementById('pwa-push-button')) {
            return;
        }

        const pushButton = document.createElement('button');
        pushButton.id = 'pwa-push-button';
        pushButton.className = 'pwa-push-button';
        pushButton.title = 'Activer les notifications';
        pushButton.setAttribute('aria-label', 'Activer les notifications push');
        pushButton.innerHTML = `<i class="fas fa-bell"></i>`;
        pushButton.addEventListener('click', () => this.requestPushPermission());

        // Style fixe en bas à gauche, aligné avec les widgets de droite
        pushButton.style.cssText = `
            position: fixed;
            bottom: 70px;
            left: 20px;
            z-index: 9998;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e293b, #334155);
            border: 2px solid rgba(6, 182, 212, 0.3);
            color: #06b6d4;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(0,0,0,0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        `;

        pushButton.addEventListener('mouseenter', () => {
            pushButton.style.transform = 'translateY(-3px)';
            pushButton.style.boxShadow = '0 8px 24px rgba(6,182,212,0.35)';
            pushButton.style.borderColor = 'rgba(6,182,212,0.7)';
        });
        pushButton.addEventListener('mouseleave', () => {
            pushButton.style.transform = '';
            pushButton.style.boxShadow = '0 4px 16px rgba(0,0,0,0.25)';
            pushButton.style.borderColor = 'rgba(6,182,212,0.3)';
        });

        document.body.appendChild(pushButton);
    }

    // Demander la permission pour les notifications push
    async requestPushPermission() {
        const permission = await Notification.requestPermission();

        if (permission === 'granted') {
            await this.subscribeToPush();
            this.hidePushButton();
        } else {
            alert('Les notifications sont nécessaires pour recevoir les mises à jour importantes.');
        }
    }

    // S'abonner aux notifications push
    async subscribeToPush() {
        if (!this.serviceWorkerRegistration) {
            console.warn('[PWA] Service Worker non enregistré');
            return;
        }

        try {
            const subscription = await this.serviceWorkerRegistration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.urlBase64ToUint8Array(this.getVapidPublicKey())
            });

            this.pushSubscription = subscription;
            await this.sendSubscriptionToServer(subscription);
            console.log('[PWA] Abonnement aux notifications push réussi');

        } catch (error) {
            console.error('[PWA] Erreur lors de l\'abonnement:', error);
        }
    }

    // Envoyer l'abonnement au serveur
    async sendSubscriptionToServer(subscription) {
        try {
            const response = await fetch('/api/push/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    subscription: subscription.toJSON()
                })
            });

            if (!response.ok) {
                throw new Error('Erreur lors de l\'envoi de l\'abonnement');
            }

            console.log('[PWA] Abonnement envoyé au serveur');

        } catch (error) {
            console.error('[PWA] Erreur lors de l\'envoi de l\'abonnement:', error);
        }
    }

    // Obtenir la clé publique VAPID (à configurer côté serveur)
    getVapidPublicKey() {
        // Cette clé doit être configurée dans les variables d'environnement
        return window.VAPID_PUBLIC_KEY || '';
    }

    // Convertir la clé VAPID en Uint8Array
    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/\-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    // Masquer le bouton push
    hidePushButton() {
        const button = document.getElementById('pwa-push-button');
        if (button) {
            button.remove();
        }
    }

    // Détection du mode hors ligne
    setupOfflineDetection() {
        window.addEventListener('online', () => {
            this.showOnlineNotification();
        });

        window.addEventListener('offline', () => {
            this.showOfflineNotification();
        });

        // Vérifier l'état initial
        if (!navigator.onLine) {
            this.showOfflineNotification();
        }
    }

    // Afficher la notification de connexion
    showOnlineNotification() {
        this.showNotification('Vous êtes de nouveau en ligne', 'success');
    }

    // Afficher la notification de déconnexion
    showOfflineNotification() {
        this.showNotification('Vous êtes hors ligne. Certaines fonctionnalités peuvent être limitées.', 'warning');
    }

    // Afficher une notification
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `pwa-notification pwa-notification-${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 24px;
            background: ${type === 'success' ? '#10b981' : type === 'warning' ? '#f59e0b' : '#06b6d4'};
            color: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 10000;
            animation: slideInRight 0.3s ease;
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Mettre en cache des URLs spécifiques
    async cacheUrls(urls) {
        if (this.serviceWorkerRegistration && this.serviceWorkerRegistration.active) {
            this.serviceWorkerRegistration.active.postMessage({
                type: 'CACHE_URLS',
                urls: urls
            });
        }
    }

    // Vider le cache
    async clearCache() {
        if (this.serviceWorkerRegistration && this.serviceWorkerRegistration.active) {
            this.serviceWorkerRegistration.active.postMessage({
                type: 'CLEAR_CACHE'
            });
        }
    }
}

// Initialiser le gestionnaire PWA
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.pwaManager = new PWAManager();
    });
} else {
    window.pwaManager = new PWAManager();
}

