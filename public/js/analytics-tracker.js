// Système de tracking analytics pour NiangProgrammeur
class AnalyticsTracker {
    constructor() {
        this.sessionId = this.getOrCreateSessionId();
        this.pageLoadTime = Date.now();
        this.init();
    }

    init() {
        // Tracker le chargement de la page
        this.trackPageView();
        
        // Tracker les clics
        this.trackClicks();
        
        // Tracker les soumissions de formulaires
        this.trackFormSubmissions();
        
        // Tracker le scroll
        this.trackScroll();
        
        // Tracker les heatmaps
        this.trackHeatmap();
        
        // Initialiser les tests A/B
        this.initABTests();
    }

    /**
     * Obtenir ou créer un session ID
     */
    getOrCreateSessionId() {
        let sessionId = sessionStorage.getItem('analytics_session_id');
        if (!sessionId) {
            sessionId = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            sessionStorage.setItem('analytics_session_id', sessionId);
        }
        return sessionId;
    }

    /**
     * Tracker une vue de page
     */
    trackPageView() {
        const data = {
            event_type: 'page_view',
            event_name: 'Page View',
            page_url: window.location.href,
            page_title: document.title,
            metadata: {
                referrer: document.referrer,
                load_time: Date.now() - this.pageLoadTime,
                viewport_width: window.innerWidth,
                viewport_height: window.innerHeight,
            }
        };
        
        this.sendEvent(data);
    }

    /**
     * Tracker les clics
     */
    trackClicks() {
        document.addEventListener('click', (e) => {
            const target = e.target;
            const element = target.closest('a, button, [role="button"], input[type="submit"]');
            
            if (element) {
                const data = {
                    event_type: 'click',
                    event_name: 'Click',
                    page_url: window.location.href,
                    page_title: document.title,
                    element_id: element.id || null,
                    element_class: element.className || null,
                    element_text: element.textContent?.trim().substring(0, 100) || null,
                    metadata: {
                        tag_name: element.tagName,
                        href: element.href || null,
                    }
                };
                
                this.sendEvent(data);
            }
        }, true);
    }

    /**
     * Tracker les soumissions de formulaires
     */
    trackFormSubmissions() {
        document.addEventListener('submit', (e) => {
            const form = e.target;
            const data = {
                event_type: 'form_submit',
                event_name: 'Form Submit',
                page_url: window.location.href,
                page_title: document.title,
                element_id: form.id || null,
                element_class: form.className || null,
                metadata: {
                    form_action: form.action || null,
                    form_method: form.method || null,
                }
            };
            
            this.sendEvent(data);
        });
    }

    /**
     * Tracker le scroll
     */
    trackScroll() {
        let maxScroll = 0;
        let scrollTracked = false;
        
        window.addEventListener('scroll', () => {
            const scrollPercent = Math.round(
                (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100
            );
            
            if (scrollPercent > maxScroll) {
                maxScroll = scrollPercent;
            }
            
            // Tracker à 25%, 50%, 75%, 100%
            if (!scrollTracked && (scrollPercent >= 25 || scrollPercent >= 50 || scrollPercent >= 75 || scrollPercent >= 100)) {
                this.trackHeatmap({
                    interaction_type: 'scroll',
                    scroll_depth: scrollPercent
                });
                
                if (scrollPercent >= 100) {
                    scrollTracked = true;
                }
            }
        }, { passive: true });
    }

    /**
     * Tracker les heatmaps (clics)
     */
    trackHeatmap(additionalData = {}) {
        document.addEventListener('click', (e) => {
            const rect = e.target.getBoundingClientRect();
            const data = {
                page_url: window.location.href,
                page_title: document.title,
                x_position: Math.round(e.clientX),
                y_position: Math.round(e.clientY),
                viewport_width: window.innerWidth,
                viewport_height: window.innerHeight,
                element_selector: this.getElementSelector(e.target),
                element_type: e.target.tagName.toLowerCase(),
                interaction_type: additionalData.interaction_type || 'click',
                scroll_depth: additionalData.scroll_depth || Math.round(
                    (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100
                ),
            };
            
            this.sendHeatmap(data);
        }, true);
    }

    /**
     * Obtenir le sélecteur CSS d'un élément
     */
    getElementSelector(element) {
        if (element.id) {
            return '#' + element.id;
        }
        
        let selector = element.tagName.toLowerCase();
        if (element.className) {
            selector += '.' + element.className.split(' ').join('.');
        }
        
        return selector;
    }

    /**
     * Initialiser les tests A/B
     */
    async initABTests() {
        // Récupérer les tests A/B actifs pour cette page
        const currentUrl = window.location.pathname;
        
        try {
            const response = await fetch(`/api/analytics/ab-tests?url=${encodeURIComponent(currentUrl)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });
            
            if (response.ok) {
                const tests = await response.json();
                for (const test of tests) {
                    await this.assignABTestVariant(test.id);
                }
            }
        } catch (error) {
            console.warn('[Analytics] Erreur lors de la récupération des tests A/B:', error);
        }
    }

    /**
     * Assigner un variant A/B
     */
    async assignABTestVariant(testId) {
        try {
            const response = await fetch(`/api/analytics/ab-test/${testId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                this.applyABTestVariant(testId, data.variant);
            }
        } catch (error) {
            console.warn('[Analytics] Erreur lors de l\'assignation du variant:', error);
        }
    }

    /**
     * Appliquer un variant A/B
     */
    applyABTestVariant(testId, variant) {
        // Stocker le variant
        sessionStorage.setItem(`ab_test_${testId}`, variant);
        
        // Déclencher un événement pour que le code puisse réagir
        window.dispatchEvent(new CustomEvent('ab-test-assigned', {
            detail: { testId, variant }
        }));
    }

    /**
     * Marquer une conversion pour un test A/B
     */
    async markABTestConversion(testId) {
        try {
            await fetch('/api/analytics/ab-test/conversion', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ test_id: testId })
            });
        } catch (error) {
            console.warn('[Analytics] Erreur lors du marquage de conversion:', error);
        }
    }

    /**
     * Tracker une étape de funnel
     */
    async trackFunnelStep(funnelId, stepNumber, completed = false) {
        try {
            await fetch('/api/analytics/funnel', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    funnel_id: funnelId,
                    step_number: stepNumber,
                    completed: completed
                })
            });
        } catch (error) {
            console.warn('[Analytics] Erreur lors du tracking du funnel:', error);
        }
    }

    /**
     * Envoyer un événement
     */
    async sendEvent(data) {
        try {
            await fetch('/api/analytics/track', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify(data)
            });
        } catch (error) {
            // Ignorer les erreurs silencieusement
            console.debug('[Analytics] Erreur lors de l\'envoi de l\'événement:', error);
        }
    }

    /**
     * Envoyer des données de heatmap
     */
    async sendHeatmap(data) {
        try {
            await fetch('/api/analytics/heatmap', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify(data)
            });
        } catch (error) {
            console.debug('[Analytics] Erreur lors de l\'envoi du heatmap:', error);
        }
    }
}

// Initialiser le tracker
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.analyticsTracker = new AnalyticsTracker();
    });
} else {
    window.analyticsTracker = new AnalyticsTracker();
}

