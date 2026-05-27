<style>
    #ck-banner {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 9999;
        transform: translateY(110%);
        transition: transform 0.45s cubic-bezier(0.34, 1.56, 0.64, 1);
        pointer-events: none;
    }

    #ck-banner.ck-visible {
        transform: translateY(0);
        pointer-events: all;
    }

    .ck-inner {
        margin: 0 auto;
        max-width: 1200px;
        padding: 0 1rem 1rem;
    }

    .ck-card {
        background: rgba(8, 8, 20, 0.92);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(6, 182, 212, 0.25);
        border-radius: 16px 16px 0 0;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        box-shadow: 0 -8px 40px rgba(0, 0, 0, 0.5), 0 -1px 0 rgba(6, 182, 212, 0.4) inset;
        flex-wrap: wrap;
    }

    .ck-icon-wrap {
        flex-shrink: 0;
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.15));
        border: 1px solid rgba(6, 182, 212, 0.35);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        animation: ck-wiggle 2.5s ease-in-out infinite;
    }

    @keyframes ck-wiggle {
        0%, 100% { transform: rotate(-4deg); }
        50%       { transform: rotate(4deg); }
    }

    .ck-text {
        flex: 1;
        min-width: 200px;
    }

    .ck-text strong {
        display: block;
        color: #f1f5f9;
        font-size: .9rem;
        font-weight: 700;
        margin-bottom: .2rem;
        letter-spacing: .01em;
    }

    .ck-text p {
        color: #94a3b8;
        font-size: .78rem;
        line-height: 1.5;
        margin: 0;
    }

    .ck-text a {
        color: #22d3ee;
        text-decoration: underline;
        text-underline-offset: 2px;
        transition: color .2s;
    }

    .ck-text a:hover { color: #67e8f9; }

    .ck-actions {
        display: flex;
        align-items: center;
        gap: .6rem;
        flex-shrink: 0;
        flex-wrap: wrap;
    }

    .ck-btn-accept {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .55rem 1.25rem;
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        color: #fff;
        font-size: .82rem;
        font-weight: 700;
        border: none;
        border-radius: 9px;
        cursor: pointer;
        letter-spacing: .02em;
        transition: transform .2s, box-shadow .2s;
        box-shadow: 0 4px 16px rgba(6, 182, 212, 0.35);
        white-space: nowrap;
    }

    .ck-btn-accept:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 24px rgba(6, 182, 212, 0.5);
    }

    .ck-btn-refuse {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .55rem 1rem;
        background: rgba(255, 255, 255, 0.06);
        color: #94a3b8;
        font-size: .82rem;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 9px;
        cursor: pointer;
        transition: background .2s, color .2s, border-color .2s;
        white-space: nowrap;
    }

    .ck-btn-refuse:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #cbd5e1;
        border-color: rgba(255, 255, 255, 0.2);
    }

    .ck-close {
        flex-shrink: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        color: #475569;
        font-size: 1rem;
        cursor: pointer;
        border-radius: 6px;
        transition: color .2s, background .2s;
        margin-left: .25rem;
    }

    .ck-close:hover {
        color: #94a3b8;
        background: rgba(255, 255, 255, 0.07);
    }

    /* Progress bar top accent */
    .ck-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, #06b6d4 30%, #14b8a6 70%, transparent);
        border-radius: 16px 16px 0 0;
        opacity: .8;
    }

    .ck-card { position: relative; }

    @media (max-width: 640px) {
        .ck-card {
            padding: 1rem;
            gap: .875rem;
            border-radius: 12px 12px 0 0;
        }
        .ck-text strong { font-size: .85rem; }
        .ck-text p { font-size: .75rem; }
        .ck-btn-accept, .ck-btn-refuse { font-size: .78rem; padding: .5rem .9rem; }
    }
</style>

<div id="ck-banner" role="dialog" aria-label="Consentement cookies" aria-live="polite">
    <div class="ck-inner">
        <div class="ck-card">
            <div class="ck-icon-wrap" aria-hidden="true">🍪</div>

            <div class="ck-text">
                <strong>Nous utilisons des cookies</strong>
                <p>
                    Pour améliorer votre expérience, analyser le trafic et diffuser des publicités via Google AdSense.
                    <a href="{{ route('privacy-policy') }}">Politique de confidentialité</a>
                </p>
            </div>

            <div class="ck-actions">
                <button class="ck-btn-accept" onclick="ckAccept()">
                    <i class="fas fa-check" aria-hidden="true"></i>
                    Tout accepter
                </button>
                <button class="ck-btn-refuse" onclick="ckRefuse()">
                    <i class="fas fa-times" aria-hidden="true"></i>
                    Refuser
                </button>
            </div>

            <button class="ck-close" onclick="ckRefuse()" title="Fermer" aria-label="Fermer la bannière">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    var STORAGE_KEY = 'cookieConsent';
    var STORAGE_DATE = 'cookieConsentDate';

    function ckShow() {
        var el = document.getElementById('ck-banner');
        if (el) el.classList.add('ck-visible');
    }

    function ckHide() {
        var el = document.getElementById('ck-banner');
        if (el) el.classList.remove('ck-visible');
    }

    function ckUpdateGtag(granted) {
        try {
            if (typeof gtag === 'function') {
                gtag('consent', 'update', {
                    analytics_storage: granted ? 'granted' : 'denied',
                    ad_storage:        granted ? 'granted' : 'denied'
                });
            }
        } catch (e) {}
    }

    window.ckAccept = function () {
        try {
            localStorage.setItem(STORAGE_KEY, 'accepted');
            localStorage.setItem(STORAGE_DATE, new Date().toISOString());
        } catch (e) {}
        ckUpdateGtag(true);
        ckHide();
        setTimeout(function () { location.reload(); }, 400);
    };

    window.ckRefuse = function () {
        try {
            localStorage.setItem(STORAGE_KEY, 'refused');
            localStorage.setItem(STORAGE_DATE, new Date().toISOString());
        } catch (e) {}
        ckUpdateGtag(false);
        ckHide();
    };

    window.resetCookieConsent = function () {
        try {
            localStorage.removeItem(STORAGE_KEY);
            localStorage.removeItem(STORAGE_DATE);
        } catch (e) {}
    };

    function init() {
        try {
            var consent = localStorage.getItem(STORAGE_KEY);
            if (!consent) {
                setTimeout(ckShow, 1500);
            }
        } catch (e) {
            setTimeout(ckShow, 1500);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    /* Ignorer les erreurs d'extensions navigateur */
    window.addEventListener('unhandledrejection', function (ev) {
        try {
            var src = String((ev.reason && (ev.reason.stack || ev.reason)) || '').toLowerCase();
            if (src.includes('content.js') || src.includes('extension://') || src.includes('unknown error')) {
                ev.preventDefault();
            }
        } catch (e) {}
    }, { capture: true, passive: false });
}());
</script>
