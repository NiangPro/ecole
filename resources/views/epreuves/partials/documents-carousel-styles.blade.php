{{-- Styles du carousel "Documents & Guides Épreuves" — partagé entre epreuves.index et epreuves.show --}}
<style>
.epr-doc-section { margin-bottom: 2.75rem; }
.epr-doc-header {
    display: flex; align-items: flex-end; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem;
}
.epr-doc-eyebrow {
    display: inline-flex; align-items: center; gap: 0.4rem;
    font-size: 0.78rem; font-weight: 800; letter-spacing: 0.06em; text-transform: uppercase;
    color: #047857; margin-bottom: 0.35rem;
}
.epr-doc-title { font-size: clamp(1.3rem, 2.6vw, 1.7rem); font-weight: 800; color: #0f172a; margin: 0 0 0.3rem; }
.epr-doc-subtitle { color: #64748b; font-size: 0.92rem; margin: 0; max-width: 520px; }
.epr-doc-actions { display: flex; align-items: center; gap: 0.6rem; }
.epr-doc-viewall {
    display: inline-flex; align-items: center; gap: 0.4rem; font-weight: 700; font-size: 0.88rem;
    color: #047857; text-decoration: none; white-space: nowrap;
}
.epr-doc-viewall svg { transition: transform 0.2s; }
.epr-doc-viewall:hover svg { transform: translateX(3px); }
.epr-doc-nav {
    display: inline-flex; align-items: center; justify-content: center;
    width: 40px; height: 40px; border-radius: 999px; border: 1px solid rgba(5,150,105,0.25);
    background: #fff; color: #047857; cursor: pointer; transition: all 0.2s; flex-shrink: 0;
    box-shadow: 0 6px 16px rgba(5,150,105,0.08);
}
.epr-doc-nav:hover { background: linear-gradient(135deg, #059669, #047857); color: #fff; border-color: transparent; transform: translateY(-2px); }
.epr-doc-nav:disabled { opacity: 0.35; cursor: default; transform: none; background: #fff; color: #047857; }
.epr-doc-viewport {
    display: flex; gap: 1.25rem; overflow-x: auto; scroll-snap-type: x mandatory;
    scroll-behavior: smooth; -webkit-overflow-scrolling: touch; padding: 0.4rem 0.2rem 1rem;
    scrollbar-width: none;
}
.epr-doc-viewport::-webkit-scrollbar { display: none; }
.epr-doc-card {
    position: relative; flex: 0 0 265px; scroll-snap-align: start;
    display: flex; flex-direction: column; border-radius: 20px; overflow: hidden;
    background: #fff; border: 1px solid rgba(226,232,240,0.9);
    box-shadow: 0 10px 34px rgba(15,23,42,0.06); text-decoration: none; color: inherit;
    transition: transform 0.35s cubic-bezier(.175,.885,.32,1.275), box-shadow 0.35s, border-color 0.3s;
}
.epr-doc-card:hover {
    transform: translateY(-6px) scale(1.015);
    box-shadow: 0 22px 55px rgba(5,150,105,0.18);
    border-color: rgba(5,150,105,0.4);
}
.epr-doc-cover { position: relative; display: block; aspect-ratio: 4/3; overflow: hidden; background: linear-gradient(135deg, #d1fae5, #a7f3d0); }
.epr-doc-cover img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
.epr-doc-card:hover .epr-doc-cover img { transform: scale(1.08); }
.epr-doc-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 2.4rem; }
.epr-doc-cover::after {
    content: ''; position: absolute; inset: auto 0 0 0; height: 55%;
    background: linear-gradient(180deg, rgba(15,23,42,0) 0%, rgba(15,23,42,0.55) 100%);
    pointer-events: none;
}
.epr-doc-badge {
    position: absolute; top: 0.7rem; left: 0.7rem; z-index: 2;
    font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em;
    padding: 0.3rem 0.65rem; border-radius: 999px; color: #fff;
    background: linear-gradient(135deg, #059669, #047857); box-shadow: 0 4px 12px rgba(5,150,105,0.35);
}
.epr-doc-price {
    position: absolute; bottom: 0.7rem; right: 0.7rem; z-index: 2;
    display: flex; align-items: baseline; gap: 0.35rem;
    background: rgba(255,255,255,0.92); backdrop-filter: blur(6px);
    padding: 0.3rem 0.65rem; border-radius: 999px; font-weight: 800; font-size: 0.82rem; color: #047857;
}
.epr-doc-price-old { font-weight: 600; font-size: 0.72rem; color: #94a3b8; text-decoration: line-through; }
.epr-doc-body { display: flex; flex-direction: column; gap: 0.5rem; padding: 1rem 1.1rem 1.15rem; flex: 1; }
.epr-doc-cat { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; color: #059669; }
.epr-doc-name { font-weight: 700; font-size: 0.95rem; line-height: 1.4; color: #0f172a; }
.epr-doc-meta { display: flex; align-items: center; gap: 0.5rem; font-size: 0.78rem; color: #94a3b8; margin-top: auto; }
.epr-doc-stars { color: #f59e0b; letter-spacing: 1px; font-size: 0.72rem; }
.epr-doc-empty-note { color: #64748b; font-size: 0.85rem; }

body.dark-mode .epr-doc-eyebrow, body.dark-mode .epr-doc-viewall { color: #34d399; }
body.dark-mode .epr-doc-title { color: #f9fafb; }
body.dark-mode .epr-doc-subtitle { color: #94a3b8; }
body.dark-mode .epr-doc-nav { background: rgba(15,23,42,0.8); border-color: rgba(52,211,153,0.3); color: #34d399; }
body.dark-mode .epr-doc-nav:hover { background: linear-gradient(135deg, #059669, #047857); color: #fff; }
body.dark-mode .epr-doc-card { background: rgba(15,23,42,0.8); border-color: rgba(148,163,184,0.15); }
body.dark-mode .epr-doc-name { color: #f1f5f9; }
body.dark-mode .epr-doc-cat { color: #34d399; }
body.dark-mode .epr-doc-price { background: rgba(2,6,23,0.85); color: #34d399; }

@media (max-width: 640px) {
    .epr-doc-nav { display: none; }
    .epr-doc-card { flex-basis: 220px; }
}
</style>
