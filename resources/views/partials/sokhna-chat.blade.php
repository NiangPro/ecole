{{--
    Widget chatbot « Sokhna » (assistante IA, propulsée par Groq).
    Placé à droite, au-dessus de WhatsApp (à la place du bouton mode sombre, déplacé en bas à gauche — voir widgets.css).
    Les messages passent par POST /api/sokhna/chat (Api\SokhnaController) : la clé Groq ne quitte jamais le serveur.
    L'historique est gardé dans sessionStorage (onglet courant uniquement), rien n'est stocké côté serveur.
--}}
@php
    $sokhnaEn = app()->getLocale() === 'en';
    $sokhnaText = $sokhnaEn ? [
        'open'        => 'Chat with Sokhna, our AI assistant',
        'tip_title'   => 'Got a question? Ask Sokhna',
        'tip_sub'     => 'Online · AI assistant, instant answers',
        'subtitle'    => 'AI assistant · NiangProgrammeur',
        'welcome'     => "Hi, I'm **Sokhna** 👋 I can help you learn to code, choose a course or find a job, competitive exam or scholarship in Senegal. What can I do for you?",
        'placeholder' => 'Ask Sokhna a question…',
        'send'        => 'Send',
        'close'       => 'Close',
        'reset'       => 'New conversation',
        'disclaimer'  => 'Sokhna is an AI and can make mistakes.',
        'error'       => 'Connection problem. Please try again.',
        'chips'       => ['Which course should I start with?', 'How do I find a job in Dakar?', 'Explain a PHP function to me'],
    ] : [
        'open'        => 'Discuter avec Sokhna, notre assistante IA',
        'tip_title'   => 'Une question ? Demande à Sokhna',
        'tip_sub'     => 'En ligne · Assistante IA, réponse immédiate',
        'subtitle'    => 'Assistante IA · NiangProgrammeur',
        'welcome'     => "Bonjour, je suis **Sokhna** 👋 Je peux t'aider à apprendre à coder, choisir une formation ou trouver une offre d'emploi, un concours ou une bourse au Sénégal. Que puis-je faire pour toi ?",
        'placeholder' => 'Pose ta question à Sokhna…',
        'send'        => 'Envoyer',
        'close'       => 'Fermer',
        'reset'       => 'Nouvelle conversation',
        'disclaimer'  => 'Sokhna est une IA et peut se tromper.',
        'error'       => 'Problème de connexion. Réessaie dans un instant.',
        'chips'       => ['Par quelle formation commencer ?', 'Comment trouver un emploi à Dakar ?', 'Explique-moi une fonction PHP'],
    ];
@endphp
<style>
.sokhna{--sk-accent:#0891b2;--sk-accent-2:#14b8a6;--sk-bg:#ffffff;--sk-surface:#f1f5f9;--sk-text:#0f172a;--sk-muted:#64748b;--sk-border:rgba(15,23,42,.1);--sk-user:#0891b2;--sk-user-text:#ffffff;--sk-code:#0f172a;--sk-code-text:#e2e8f0;position:fixed;inset-block-end:70px;inset-inline-end:20px;z-index:9998;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif}
.sokhna.is-open{z-index:10001}
body.dark-mode .sokhna{--sk-bg:#0f172a;--sk-surface:#1e293b;--sk-text:#e2e8f0;--sk-muted:#94a3b8;--sk-border:rgba(255,255,255,.1);--sk-code:#020617}
.sokhna-launcher{display:flex;align-items:center;gap:.55rem;border:0;cursor:pointer;padding:.25rem 1rem .25rem .25rem;border-radius:999px;background:linear-gradient(135deg,var(--sk-accent),var(--sk-accent-2));color:#fff;font-weight:700;font-size:.95rem;box-shadow:0 10px 30px rgba(8,145,178,.35);transition:transform .2s ease,box-shadow .2s ease}
.sokhna-launcher:hover{transform:translateY(-2px);box-shadow:0 14px 34px rgba(8,145,178,.45)}
.sokhna-launcher:focus-visible,.sokhna button:focus-visible,.sokhna textarea:focus-visible{outline:3px solid #f59e0b;outline-offset:2px}
/* Infobulle au survol du lanceur : carte en verre dépoli, bordure dégradée, entrée glissée. */
.sokhna-launcher{position:relative}
.sokhna-tip{--sk-tip-bg:rgba(255,255,255,.86);--sk-tip-solid:#f5fdff;position:absolute;inset-inline-end:calc(100% + 16px);inset-block-start:50%;translate:10px -50%;scale:.96;display:flex;align-items:center;gap:.7rem;padding:.65rem .95rem .65rem .65rem;border-radius:16px;border:1px solid transparent;background:linear-gradient(var(--sk-tip-bg),var(--sk-tip-bg)) padding-box,linear-gradient(135deg,#22d3ee,#14b8a6 55%,#a78bfa) border-box;-webkit-backdrop-filter:blur(16px) saturate(170%);backdrop-filter:blur(16px) saturate(170%);box-shadow:0 22px 45px -14px rgba(8,145,178,.5),0 2px 8px rgba(2,6,23,.1);color:var(--sk-text);font-weight:400;text-align:start;white-space:nowrap;opacity:0;visibility:hidden;pointer-events:none;transition:opacity .25s ease,translate .3s cubic-bezier(.2,.9,.25,1.2),scale .3s cubic-bezier(.2,.9,.25,1.2),visibility .25s}
.sokhna-tip::after{content:"";position:absolute;inset-inline-end:-7px;inset-block-start:50%;inline-size:12px;block-size:12px;background:var(--sk-tip-solid);border-block-start:1px solid #14b8a6;border-inline-end:1px solid #14b8a6;border-start-end-radius:3px;transform:translateY(-50%) rotate(45deg)}
body.dark-mode .sokhna-tip{--sk-tip-bg:rgba(15,23,42,.84);--sk-tip-solid:#0f1b2d}
.sokhna-tip-icon{display:grid;place-items:center;inline-size:34px;block-size:34px;flex-shrink:0;border-radius:11px;background:linear-gradient(135deg,#0891b2,#14b8a6 60%,#8b5cf6);box-shadow:0 6px 16px -4px rgba(20,184,166,.6)}
.sokhna-tip-icon svg{inline-size:18px;block-size:18px;animation:sokhna-sparkle 2.4s ease-in-out infinite}
.sokhna-tip-body{display:flex;flex-direction:column;gap:.15rem}
.sokhna-tip-title{font-size:.88rem;font-weight:800;letter-spacing:-.01em;background:linear-gradient(90deg,#0e7490,#0d9488 60%,#7c3aed);-webkit-background-clip:text;background-clip:text;color:transparent}
body.dark-mode .sokhna-tip-title{background-image:linear-gradient(90deg,#67e8f9,#5eead4 60%,#c4b5fd)}
.sokhna-tip-sub{display:flex;align-items:center;gap:.4rem;font-size:.72rem;color:var(--sk-muted)}
.sokhna-dot{position:relative;inline-size:7px;block-size:7px;border-radius:50%;background:#22c55e}
.sokhna-dot::after{content:"";position:absolute;inset:0;border-radius:50%;background:#22c55e;animation:sokhna-ping 1.8s cubic-bezier(0,0,.2,1) infinite}
@keyframes sokhna-ping{75%,100%{transform:scale(2.4);opacity:0}}
@keyframes sokhna-sparkle{0%,100%{transform:rotate(0) scale(1)}50%{transform:rotate(12deg) scale(1.12)}}
@media (hover:hover){.sokhna-launcher:hover .sokhna-tip{opacity:1;visibility:visible;translate:0 -50%;scale:1}}
.sokhna-launcher:focus-visible .sokhna-tip{opacity:1;visibility:visible;translate:0 -50%;scale:1}
.sokhna.is-open .sokhna-tip{display:none}
.sokhna-avatar{display:block;inline-size:40px;block-size:40px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,.85);background:#e2e8f0;flex-shrink:0}
.sokhna-launcher .sokhna-avatar{box-shadow:0 0 0 2px rgba(8,145,178,.35)}
.sokhna-panel{position:absolute;inset-block-end:60px;inset-inline-end:0;inline-size:min(390px,calc(100vw - 32px));block-size:min(580px,calc(100vh - 210px));display:flex;flex-direction:column;background:var(--sk-bg);color:var(--sk-text);border:1px solid var(--sk-border);border-radius:18px;box-shadow:0 24px 60px rgba(2,6,23,.3);overflow:hidden;opacity:0;transform:translateY(12px) scale(.98);pointer-events:none;transition:opacity .2s ease,transform .2s ease}
.sokhna.is-open .sokhna-panel{opacity:1;transform:none;pointer-events:auto}
.sokhna-header{display:flex;align-items:center;gap:.7rem;padding:.85rem 1rem;background:linear-gradient(135deg,var(--sk-accent),var(--sk-accent-2));color:#fff}
.sokhna-header .sokhna-avatar{inline-size:38px;block-size:38px}
.sokhna-title{flex:1;min-inline-size:0}
.sokhna-name{font-weight:800;font-size:1rem;line-height:1.2}
.sokhna-sub{font-size:.75rem;opacity:.9}
.sokhna-icon-btn{display:grid;place-items:center;inline-size:34px;block-size:34px;border:0;border-radius:10px;background:rgba(255,255,255,.15);color:#fff;cursor:pointer;font-size:.9rem}
.sokhna-icon-btn:hover{background:rgba(255,255,255,.28)}
.sokhna-log{flex:1;overflow-y:auto;padding:1rem;display:flex;flex-direction:column;gap:.75rem;overscroll-behavior:contain}
.sokhna-msg{max-inline-size:88%;padding:.65rem .85rem;border-radius:14px;font-size:.9rem;line-height:1.55;overflow-wrap:anywhere}
.sokhna-msg--bot{align-self:flex-start;background:var(--sk-surface);border-end-start-radius:4px}
.sokhna-msg--user{align-self:flex-end;background:var(--sk-user);color:var(--sk-user-text);border-end-end-radius:4px;white-space:pre-wrap}
.sokhna-msg--error{align-self:flex-start;background:rgba(220,38,38,.1);color:#dc2626;font-size:.85rem}
.sokhna-msg p{margin:0 0 .5rem}.sokhna-msg p:last-child{margin:0}
.sokhna-msg ul,.sokhna-msg ol{margin:.3rem 0 .5rem;padding-inline-start:1.2rem}
.sokhna-msg a{color:var(--sk-accent);font-weight:600;text-decoration:underline}
body.dark-mode .sokhna-msg--bot a{color:#22d3ee}
.sokhna-msg code{font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;font-size:.82em;background:rgba(8,145,178,.12);padding:.1rem .3rem;border-radius:4px}
.sokhna-msg pre{margin:.5rem 0;padding:.7rem .8rem;background:var(--sk-code);color:var(--sk-code-text);border-radius:10px;overflow-x:auto;font-size:.8rem;line-height:1.5}
.sokhna-msg pre code{background:none;padding:0;color:inherit;font-size:inherit}
.sokhna-typing{display:inline-flex;gap:4px;padding:.2rem 0}
.sokhna-typing span{inline-size:7px;block-size:7px;border-radius:50%;background:var(--sk-muted);animation:sokhna-dot 1.2s infinite ease-in-out}
.sokhna-typing span:nth-child(2){animation-delay:.15s}.sokhna-typing span:nth-child(3){animation-delay:.3s}
@keyframes sokhna-dot{0%,80%,100%{opacity:.3;transform:translateY(0)}40%{opacity:1;transform:translateY(-3px)}}
.sokhna-chips{display:flex;flex-wrap:wrap;gap:.4rem}
.sokhna-chip{border:1px solid var(--sk-accent);background:transparent;color:var(--sk-accent);border-radius:999px;padding:.35rem .75rem;font-size:.8rem;font-weight:600;cursor:pointer}
body.dark-mode .sokhna-chip{color:#22d3ee;border-color:#22d3ee}
.sokhna-chip:hover{background:rgba(8,145,178,.1)}
.sokhna-form{display:flex;align-items:flex-end;gap:.5rem;padding:.7rem .8rem;border-block-start:1px solid var(--sk-border)}
.sokhna-input{flex:1;resize:none;max-block-size:120px;min-block-size:42px;padding:.6rem .75rem;border:1px solid var(--sk-border);border-radius:12px;background:var(--sk-surface);color:var(--sk-text);font:inherit;font-size:.9rem;line-height:1.4}
.sokhna-send{display:grid;place-items:center;inline-size:42px;block-size:42px;flex-shrink:0;border:0;border-radius:12px;background:linear-gradient(135deg,var(--sk-accent),var(--sk-accent-2));color:#fff;cursor:pointer}
.sokhna-send:disabled{opacity:.5;cursor:not-allowed}
.sokhna-disclaimer{margin:0;padding:0 .8rem .6rem;font-size:.7rem;color:var(--sk-muted);text-align:center}
@media (max-width:768px){
  .sokhna{inset-block-end:65px;inset-inline-end:12px}
  .sokhna-launcher{padding:.4rem}
  .sokhna-launcher-label{display:none}
  .sokhna.is-open{inset:0}
  .sokhna.is-open .sokhna-launcher{display:none}
  .sokhna-panel{position:fixed;inset:0;inline-size:100%;block-size:100%;block-size:100dvh;border-radius:0;border:0}
}
@media (prefers-reduced-motion:reduce){.sokhna-panel,.sokhna-launcher,.sokhna-tip{transition:none}.sokhna-typing span,.sokhna-tip-icon svg,.sokhna-dot::after{animation:none}}
</style>

<div class="sokhna" id="sokhna" data-endpoint="{{ url('/api/sokhna/chat') }}" data-locale="{{ $sokhnaEn ? 'en' : 'fr' }}">
    <button type="button" class="sokhna-launcher" id="sokhna-launcher" aria-expanded="false" aria-controls="sokhna-panel" aria-label="{{ $sokhnaText['open'] }}">
        <img class="sokhna-avatar" src="{{ asset('images/sokhna-80.webp') }}" width="40" height="40" alt="" aria-hidden="true" decoding="async">
        <span class="sokhna-launcher-label">Sokhna</span>
        <span class="sokhna-tip" aria-hidden="true">
            <span class="sokhna-tip-icon">
                <svg viewBox="0 0 24 24" fill="#fff"><path d="M12 2l1.9 5.6L19.5 9.5l-5.6 1.9L12 17l-1.9-5.6L4.5 9.5l5.6-1.9L12 2z"/><path d="M19 14l.9 2.6 2.6.9-2.6.9L19 21l-.9-2.6-2.6-.9 2.6-.9L19 14z" opacity=".75"/></svg>
            </span>
            <span class="sokhna-tip-body">
                <span class="sokhna-tip-title">{{ $sokhnaText['tip_title'] }}</span>
                <span class="sokhna-tip-sub"><span class="sokhna-dot"></span>{{ $sokhnaText['tip_sub'] }}</span>
            </span>
        </span>
    </button>

    <section class="sokhna-panel" id="sokhna-panel" role="dialog" aria-label="Sokhna" aria-hidden="true">
        <header class="sokhna-header">
            <img class="sokhna-avatar" src="{{ asset('images/sokhna-80.webp') }}" width="40" height="40" alt="" aria-hidden="true" decoding="async">
            <div class="sokhna-title">
                <div class="sokhna-name">Sokhna</div>
                <div class="sokhna-sub">{{ $sokhnaText['subtitle'] }}</div>
            </div>
            <button type="button" class="sokhna-icon-btn" id="sokhna-reset" title="{{ $sokhnaText['reset'] }}" aria-label="{{ $sokhnaText['reset'] }}"><i class="fas fa-rotate-right" aria-hidden="true"></i></button>
            <button type="button" class="sokhna-icon-btn" id="sokhna-close" title="{{ $sokhnaText['close'] }}" aria-label="{{ $sokhnaText['close'] }}"><i class="fas fa-times" aria-hidden="true"></i></button>
        </header>

        <div class="sokhna-log" id="sokhna-log" aria-live="polite"></div>

        {{-- method="post" : si le JS échoue, la question ne part jamais dans l'URL (audit FCT-03) --}}
        <form class="sokhna-form" id="sokhna-form" method="post" action="#">
            <label for="sokhna-input" class="sr-only" style="position:absolute;inline-size:1px;block-size:1px;overflow:hidden;clip:rect(0 0 0 0)">{{ $sokhnaText['placeholder'] }}</label>
            <textarea class="sokhna-input" id="sokhna-input" rows="1" maxlength="2000" placeholder="{{ $sokhnaText['placeholder'] }}"></textarea>
            <button type="submit" class="sokhna-send" id="sokhna-send" aria-label="{{ $sokhnaText['send'] }}"><i class="fas fa-paper-plane" aria-hidden="true"></i></button>
        </form>
        <p class="sokhna-disclaimer">{{ $sokhnaText['disclaimer'] }}</p>
    </section>
</div>

<script>
(function () {
    var root = document.getElementById('sokhna');
    if (!root) return;

    var T = @json($sokhnaText);
    var STORAGE_KEY = 'sokhna_history_v1';
    var endpoint = root.dataset.endpoint;
    var launcher = document.getElementById('sokhna-launcher');
    var panel = document.getElementById('sokhna-panel');
    var log = document.getElementById('sokhna-log');
    var form = document.getElementById('sokhna-form');
    var input = document.getElementById('sokhna-input');
    var sendBtn = document.getElementById('sokhna-send');
    var history = [];
    var busy = false;

    function loadHistory() {
        try { history = JSON.parse(sessionStorage.getItem(STORAGE_KEY)) || []; } catch (e) { history = []; }
        if (!Array.isArray(history)) history = [];
    }
    function saveHistory() {
        try { sessionStorage.setItem(STORAGE_KEY, JSON.stringify(history.slice(-30))); } catch (e) {}
    }

    function escapeHtml(s) {
        return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    // Markdown minimal : le texte est échappé AVANT toute mise en forme, donc aucun HTML du modèle n'est interprété.
    function safeHref(url) {
        if (/^\/(?!\/)/.test(url)) return url;
        try {
            var u = new URL(url);
            return (u.protocol === 'https:' || u.protocol === 'http:') ? u.href : null;
        } catch (e) { return null; }
    }
    function inline(text) {
        return text
            .replace(/`([^`]+)`/g, '<code>$1</code>')
            .replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>')
            .replace(/(^|[^*])\*([^*\n]+)\*/g, '$1<em>$2</em>')
            .replace(/\[([^\]]+)\]\(([^)\s]+)\)/g, function (m, label, url) {
                var href = safeHref(url.replace(/&amp;/g, '&'));
                if (!href) return label;
                var external = href.charAt(0) !== '/' && new URL(href).host !== location.host;
                return '<a href="' + escapeHtml(href) + '"' + (external ? ' target="_blank" rel="noopener nofollow noreferrer"' : '') + '>' + label + '</a>';
            });
    }
    function renderMarkdown(src) {
        var parts = escapeHtml(src).split(/```/);
        var html = '';
        parts.forEach(function (part, i) {
            if (i % 2 === 1) {
                html += '<pre><code>' + part.replace(/^[\w+-]*\n/, '') + '</code></pre>';
                return;
            }
            part.split(/\n{2,}/).forEach(function (block) {
                block = block.trim();
                if (!block) return;
                var lines = block.split('\n');
                if (lines.every(function (l) { return /^\s*[-*•]\s+/.test(l); })) {
                    html += '<ul>' + lines.map(function (l) { return '<li>' + inline(l.replace(/^\s*[-*•]\s+/, '')) + '</li>'; }).join('') + '</ul>';
                } else if (lines.every(function (l) { return /^\s*\d+[.)]\s+/.test(l); })) {
                    html += '<ol>' + lines.map(function (l) { return '<li>' + inline(l.replace(/^\s*\d+[.)]\s+/, '')) + '</li>'; }).join('') + '</ol>';
                } else {
                    html += '<p>' + inline(lines.join('<br>').replace(/^#{1,6}\s+/gm, '')) + '</p>';
                }
            });
        });
        return html;
    }

    function addMessage(role, content) {
        var el = document.createElement('div');
        el.className = 'sokhna-msg sokhna-msg--' + role;
        if (role === 'bot') el.innerHTML = renderMarkdown(content);
        else el.textContent = content;
        log.appendChild(el);
        log.scrollTop = log.scrollHeight;
        return el;
    }

    function renderAll() {
        log.innerHTML = '';
        addMessage('bot', T.welcome);
        if (!history.length) {
            var chips = document.createElement('div');
            chips.className = 'sokhna-chips';
            T.chips.forEach(function (label) {
                var b = document.createElement('button');
                b.type = 'button';
                b.className = 'sokhna-chip';
                b.textContent = label;
                b.addEventListener('click', function () { send(label); });
                chips.appendChild(b);
            });
            log.appendChild(chips);
        }
        history.forEach(function (m) { addMessage(m.role === 'user' ? 'user' : 'bot', m.content); });
    }

    function setOpen(open) {
        root.classList.toggle('is-open', open);
        launcher.setAttribute('aria-expanded', open ? 'true' : 'false');
        panel.setAttribute('aria-hidden', open ? 'false' : 'true');
        if (open) {
            if (!log.childElementCount) renderAll();
            input.focus();
        } else {
            launcher.focus();
        }
    }

    function autosize() {
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 120) + 'px';
    }

    function send(text) {
        text = (text || '').trim();
        if (!text || busy) return;
        busy = true;
        sendBtn.disabled = true;

        var chips = log.querySelector('.sokhna-chips');
        if (chips) chips.remove();

        history.push({ role: 'user', content: text });
        saveHistory();
        addMessage('user', text);
        input.value = '';
        autosize();

        var typing = addMessage('bot', '');
        typing.innerHTML = '<span class="sokhna-typing" aria-label="Sokhna écrit"><span></span><span></span><span></span></span>';

        fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ messages: history.slice(-12), page: document.title.slice(0, 200), locale: root.dataset.locale })
        })
            .then(function (res) { return res.json().catch(function () { return {}; }).then(function (data) { return { ok: res.ok, data: data }; }); })
            .then(function (r) {
                typing.remove();
                if (r.ok && r.data.reply) {
                    history.push({ role: 'assistant', content: r.data.reply });
                    saveHistory();
                    addMessage('bot', r.data.reply);
                } else {
                    // Le message non répondu est retiré de l'historique pour pouvoir le renvoyer tel quel.
                    history.pop();
                    saveHistory();
                    addMessage('error', r.data.error || T.error);
                    input.value = text;
                    autosize();
                }
            })
            .catch(function () {
                typing.remove();
                history.pop();
                saveHistory();
                addMessage('error', T.error);
                input.value = text;
                autosize();
            })
            .finally(function () {
                busy = false;
                sendBtn.disabled = false;
                input.focus();
            });
    }

    launcher.addEventListener('click', function () { setOpen(!root.classList.contains('is-open')); });
    document.getElementById('sokhna-close').addEventListener('click', function () { setOpen(false); });
    document.getElementById('sokhna-reset').addEventListener('click', function () {
        history = [];
        saveHistory();
        renderAll();
        input.focus();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && root.classList.contains('is-open')) setOpen(false);
    });
    form.addEventListener('submit', function (e) { e.preventDefault(); send(input.value); });
    input.addEventListener('input', autosize);
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey && !e.isComposing) { e.preventDefault(); send(input.value); }
    });

    loadHistory();
})();
</script>
