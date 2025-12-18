/**
 * Fonctionnalités Sociales - NiangProgrammeur
 * - Favoris
 * - Notifications en temps réel
 * - Partage social
 */

(function() {
    'use strict';

    // ============================================
    // 1. SYSTÈME DE FAVORIS
    // ============================================
    
    class FavoriteManager {
        constructor() {
            this.init();
        }

        init() {
            // Vérifier si l'utilisateur est authentifié AVANT d'initialiser
            if (typeof window.isAuthenticated === 'undefined') {
                window.isAuthenticated = document.body.dataset.authenticated === 'true' || 
                                         document.querySelector('[data-user-id]') !== null;
            }
            
            // Initialiser les boutons favoris existants
            document.querySelectorAll('[data-favorite]').forEach(btn => {
                // Ne vérifier le statut que si l'utilisateur est connecté
                // Utiliser setTimeout pour s'assurer que window.isAuthenticated est bien défini
                if (window.isAuthenticated) {
                    // Délai pour s'assurer que l'authentification est bien vérifiée
                    setTimeout(() => {
                        if (window.isAuthenticated) {
                            this.checkFavoriteStatus(btn);
                        }
                    }, 100);
                }
                btn.addEventListener('click', (e) => this.toggleFavorite(e, btn));
            });
        }

        async checkFavoriteStatus(button) {
            const type = button.dataset.favoriteType;
            const slug = button.dataset.favoriteSlug;

            if (!type || !slug) return;

            // Ne pas vérifier si l'utilisateur n'est pas connecté
            if (!window.isAuthenticated) {
                return;
            }

            try {
                const response = await fetch(`/api/favorites/check?type=${type}&slug=${encodeURIComponent(slug)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });

                // Ne pas afficher d'erreur si 401 (utilisateur non connecté)
                if (response.status === 401) {
                    return;
                }

                if (response.ok) {
                    const data = await response.json();
                    this.updateButtonState(button, data.is_favorite);
                }
            } catch (error) {
                // Erreur silencieuse pour les utilisateurs non connectés
            }
        }

        async toggleFavorite(event, button) {
            event.preventDefault();
            event.stopPropagation();

            const type = button.dataset.favoriteType;
            const slug = button.dataset.favoriteSlug;
            const name = button.dataset.favoriteName || slug;

            if (!type || !slug) return;

            // Vérifier si l'utilisateur est connecté
            if (!window.isAuthenticated) {
                if (window.feedbackManager) {
                    window.feedbackManager.showInfo('Connectez-vous pour ajouter aux favoris', 'Connexion requise');
                }
                // Rediriger vers la page de connexion
                window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname);
                return;
            }

            try {
                const response = await fetch('/api/favorites/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({ type, slug, name })
                });

                const data = await response.json();

                if (data.success) {
                    this.updateButtonState(button, data.is_favorite);
                    
                    if (window.feedbackManager) {
                        window.feedbackManager.showSuccess(data.message, 'Favoris');
                    }
                } else {
                    if (data.action === 'login_required') {
                        window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname);
                    } else if (window.feedbackManager) {
                        window.feedbackManager.showError(data.message || 'Erreur lors de l\'ajout aux favoris');
                    }
                }
            } catch (error) {
                if (window.feedbackManager) {
                    window.feedbackManager.showError('Une erreur est survenue');
                }
            }
        }

        updateButtonState(button, isFavorite) {
            if (!button) return;
            
            const icon = button.querySelector('i');
            if (icon) {
                try {
                    if (isFavorite) {
                        icon.classList.remove('far', 'fa-heart');
                        icon.classList.add('fas', 'fa-heart');
                        button.classList.add('favorite-active');
                    } else {
                        icon.classList.remove('fas', 'fa-heart');
                        icon.classList.add('far', 'fa-heart');
                        button.classList.remove('favorite-active');
                    }
                } catch (error) {
                    // Erreur silencieuse
                }
            }
        }
    }

    // ============================================
    // 2. NOTIFICATIONS EN TEMPS RÉEL
    // ============================================
    
    class NotificationManager {
        constructor() {
            this.pollInterval = 10000; // 10 secondes (réduit de 30s pour un chargement plus rapide)
            this.intervalId = null;
            this.init();
        }

        init() {
            if (!window.isAuthenticated) return;

            // Supprimer immédiatement tout widget flottant existant en bas à droite
            const removeOldWidget = () => {
                const oldWidget = document.getElementById('notification-widget');
                if (oldWidget) {
                    oldWidget.remove();
                }
            };
            
            // Supprimer immédiatement si le DOM est prêt
            removeOldWidget();
            
            // Attendre que le DOM soit prêt
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    removeOldWidget();
                    this.initializeWidget();
                });
            } else {
                removeOldWidget();
                this.initializeWidget();
            }
        }
        
        initializeWidget() {
            // Créer/attacher le widget de notifications
            this.createNotificationWidget();

            // Charger les notifications initiales immédiatement
            this.loadNotifications();

            // Démarrer le polling après un court délai
            setTimeout(() => {
                this.startPolling();
            }, 2000);
        }

        createNotificationWidget() {
            // Supprimer tout widget flottant existant en bas à droite
            const oldWidget = document.getElementById('notification-widget');
            if (oldWidget) {
                oldWidget.remove();
            }
            
            // Vérifier si le widget existe déjà dans la navbar
            const existingContainer = document.getElementById('notification-widget-container');
            if (!existingContainer) {
                // Pas de widget dans la navbar, ne rien créer
                return;
            }
            
            // Le widget est dans la navbar, on attache les événements
            const bell = document.getElementById('notificationBell');
            const dropdown = document.getElementById('notificationDropdown');
            const markAllReadBtn = dropdown?.querySelector('#markAllRead');
            
            if (bell) {
                // Attacher l'événement de clic (en plus de l'onclick inline)
                bell.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.toggleDropdown();
                });
            }
            
            if (markAllReadBtn) {
                markAllReadBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    this.markAllAsRead();
                });
            }
            
            // Fermer au clic extérieur
            const clickHandler = (e) => {
                if (existingContainer && !existingContainer.contains(e.target)) {
                    this.closeDropdown();
                }
            };
            
            // Attacher le listener pour fermer au clic extérieur
            setTimeout(() => {
                document.addEventListener('click', clickHandler);
            }, 100);
        }

        async loadNotifications() {
            if (!window.isAuthenticated) {
                console.log('NotificationManager: Utilisateur non authentifié');
                return;
            }

            try {
                const response = await fetch('/api/notifications/unread', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Cache-Control': 'no-cache'
                    },
                    cache: 'no-store'
                });

                if (response.ok) {
                    const data = await response.json();
                    console.log('NotificationManager: Notifications chargées', data);
                    this.updateNotifications(data.notifications || [], data.count || 0);
                } else {
                    console.error('NotificationManager: Erreur HTTP', response.status);
                }
            } catch (error) {
                console.error('NotificationManager: Erreur lors du chargement', error);
            }
        }

        updateNotifications(notifications, count) {
            const badge = this.getBadgeElement();
            const list = this.getListElement();

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
            if (list) {
                if (notifications.length === 0) {
                    list.innerHTML = '<div class="notification-empty">Aucune notification</div>';
                } else {
                    list.innerHTML = notifications.map(notif => `
                        <div class="notification-item ${notif.is_read ? 'read' : 'unread'}" data-id="${notif.id}">
                            <div class="notification-icon">
                                <i class="fas ${this.getNotificationIcon(notif.type)}"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">${this.escapeHtml(notif.title)}</div>
                                <div class="notification-message">${this.escapeHtml(notif.message)}</div>
                                <div class="notification-time">${this.formatTime(notif.created_at)}</div>
                            </div>
                            ${notif.link ? `<a href="${notif.link}" class="notification-link"></a>` : ''}
                        </div>
                    `).join('');

                    // Ajouter les événements de clic
                    list.querySelectorAll('.notification-item').forEach(item => {
                        item.addEventListener('click', () => {
                            const id = item.dataset.id;
                            if (id) {
                                this.markAsRead(id);
                            }
                        });
                    });
                }
            }
        }

        getNotificationIcon(type) {
            const icons = {
                'comment': 'fa-comment',
                'reply': 'fa-reply',
                'favorite': 'fa-heart',
                'system': 'fa-info-circle'
            };
            return icons[type] || 'fa-bell';
        }

        formatTime(dateString) {
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
        }

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        async markAsRead(id) {
            try {
                const response = await fetch(`/api/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });

                if (response.ok) {
                    this.loadNotifications();
                }
            } catch (error) {
                // Erreur silencieuse
            }
        }

        async markAllAsRead() {
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
                    this.loadNotifications();
                }
            } catch (error) {
                // Erreur silencieuse
            }
        }

        toggleDropdown() {
            const dropdown = document.getElementById('notificationDropdown');
            if (!dropdown) return;
            
            try {
                const isActive = dropdown.classList.contains('active');
                if (isActive) {
                    dropdown.classList.remove('active');
                } else {
                    dropdown.classList.add('active');
                    // Charger les notifications si le dropdown est vide ou en chargement
                    const list = this.getListElement();
                    if (list && (list.innerHTML.includes('Chargement') || list.innerHTML.includes('Aucune notification'))) {
                        this.loadNotifications();
                    }
                }
            } catch (error) {
                // Erreur silencieuse
            }
        }

        closeDropdown() {
            const dropdown = document.getElementById('notificationDropdown');
            if (!dropdown) return;
            
            try {
                dropdown.classList.remove('active');
            } catch (error) {
                // Erreur silencieuse
            }
        }
        
        getDropdownElement() {
            return document.getElementById('notificationDropdown');
        }
        
        getBadgeElement() {
            return document.getElementById('notificationBadge');
        }
        
        getListElement() {
            return document.getElementById('notificationList');
        }

        startPolling() {
            if (this.intervalId) return;
            this.intervalId = setInterval(() => this.loadNotifications(), this.pollInterval);
        }

        stopPolling() {
            if (this.intervalId) {
                clearInterval(this.intervalId);
                this.intervalId = null;
            }
        }
    }

    // ============================================
    // 3. PARTAGE SOCIAL
    // ============================================
    
    class SocialShareManager {
        constructor() {
            this.init();
        }

        init() {
            document.querySelectorAll('[data-share]').forEach(btn => {
                btn.addEventListener('click', (e) => this.share(e, btn));
            });
        }

        share(event, button) {
            event.preventDefault();
            const platform = button.dataset.share;
            const url = button.dataset.shareUrl || window.location.href;
            const title = button.dataset.shareTitle || document.title;
            const text = button.dataset.shareText || '';

            switch(platform) {
                case 'facebook':
                    this.shareFacebook(url);
                    break;
                case 'twitter':
                    this.shareTwitter(url, title, text);
                    break;
                case 'linkedin':
                    this.shareLinkedIn(url, title, text);
                    break;
                case 'whatsapp':
                    this.shareWhatsApp(url, title, text);
                    break;
                case 'email':
                    this.shareEmail(url, title, text);
                    break;
                case 'copy':
                    this.copyLink(url, button);
                    break;
                default:
                    this.shareNative(url, title, text);
            }
        }

        shareFacebook(url) {
            // Facebook utilise automatiquement les meta tags Open Graph
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank', 'width=600,height=400');
        }

        shareTwitter(url, title, text) {
            // Twitter utilise les meta tags Twitter Card, mais on peut aussi ajouter le texte
            const tweetText = `${title} ${text}`.substring(0, 200).trim();
            window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(tweetText)}`, '_blank', 'width=600,height=400');
        }

        shareLinkedIn(url, title, text) {
            // LinkedIn utilise automatiquement les meta tags Open Graph
            // On peut aussi utiliser l'ancienne méthode avec les paramètres pour plus de contrôle
            const summary = text ? text.substring(0, 200) : '';
            window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`, '_blank', 'width=600,height=400');
        }

        shareWhatsApp(url, title, text) {
            const message = `${title}\n${text}\n${url}`.trim();
            window.open(`https://wa.me/?text=${encodeURIComponent(message)}`, '_blank');
        }

        shareEmail(url, title, text) {
            const subject = encodeURIComponent(title);
            const body = encodeURIComponent(`${text}\n\n${url}`);
            window.location.href = `mailto:?subject=${subject}&body=${body}`;
        }

        async copyLink(url, button) {
            try {
                await navigator.clipboard.writeText(url);
                if (window.feedbackManager) {
                    window.feedbackManager.showSuccess('Lien copié dans le presse-papiers !', 'Partage');
                }
                
                // Animation de confirmation
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i> Copié !';
                button.style.color = '#10b981';
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.style.color = '';
                }, 2000);
            } catch (error) {
                if (window.feedbackManager) {
                    window.feedbackManager.showError('Impossible de copier le lien');
                }
            }
        }

        async shareNative(url, title, text) {
            if (navigator.share) {
                try {
                    await navigator.share({
                        title: title,
                        text: text,
                        url: url
                    });
                } catch (error) {
                    // Ignorer les erreurs d'annulation utilisateur
                    if (error.name !== 'AbortError') {
                        // Erreur silencieuse
                    }
                }
            } else {
                // Fallback: copier le lien
                this.copyLink(url);
            }
        }
    }

    // ============================================
    // INITIALISATION
    // ============================================
    
    // Exporter les classes globalement pour qu'elles soient accessibles
    window.SocialShareManager = SocialShareManager;
    window.FavoriteManager = FavoriteManager;
    window.NotificationManager = NotificationManager;
    
    function initializeManagers() {
        // Vérifier si l'utilisateur est authentifié
        window.isAuthenticated = document.body.dataset.authenticated === 'true' || 
                                 document.querySelector('[data-user-id]') !== null;

        // Initialiser les managers
        if (!window.favoriteManager) {
            window.favoriteManager = new FavoriteManager();
        }
        if (!window.notificationManager && window.isAuthenticated) {
            window.notificationManager = new NotificationManager();
        }
        if (!window.socialShareManager) {
            window.socialShareManager = new SocialShareManager();
        }
    }
    
    // Initialiser selon l'état du DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeManagers);
    } else {
        // DOM déjà chargé
        initializeManagers();
    }
    
    // Fallback: réinitialiser après le chargement complet
    window.addEventListener('load', function() {
        if (!window.socialShareManager) {
            window.socialShareManager = new SocialShareManager();
        }
    });

})();
