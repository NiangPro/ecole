<style>
    .cat-page { max-width: 1100px; margin: 0 auto; }

    /* ---------- Hero ---------- */
    .cat-hero {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.12) 0%, rgba(20, 184, 166, 0.1) 100%);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 22px;
        padding: 2rem 2.25rem;
        margin-bottom: 1.75rem;
        position: relative;
        overflow: hidden;
    }

    .cat-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 90% 10%, rgba(6, 182, 212, 0.18), transparent 60%);
        pointer-events: none;
    }

    .cat-hero-icon {
        width: 62px;
        height: 62px;
        flex-shrink: 0;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.7rem;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.25), rgba(20, 184, 166, 0.25));
        border: 1px solid rgba(6, 182, 212, 0.35);
        position: relative;
        z-index: 1;
    }

    .cat-hero-text { position: relative; z-index: 1; flex: 1; }

    .cat-hero-text h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.9rem;
        font-weight: 800;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 60%, #06b6d4 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 0.35rem;
    }

    .cat-hero-text p { margin: 0; font-size: 0.95rem; color: rgba(255, 255, 255, 0.65); }
    body.light-mode .cat-hero-text p { color: rgba(30, 41, 59, 0.65); }

    .cat-hero-back {
        position: relative;
        z-index: 1;
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.1rem;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.25s ease;
    }

    .cat-hero-back:hover { background: rgba(6, 182, 212, 0.15); border-color: rgba(6, 182, 212, 0.4); color: #06b6d4; }

    body.light-mode .cat-hero-back {
        background: rgba(255, 255, 255, 0.6);
        border-color: rgba(100, 116, 139, 0.25);
        color: #1e293b;
    }

    /* ---------- Layout ---------- */
    .cat-layout { display: grid; grid-template-columns: 1fr 320px; gap: 1.5rem; align-items: start; }

    @media (max-width: 900px) {
        .cat-layout { grid-template-columns: 1fr; }
        .cat-preview { order: -1; }
    }

    .cat-form-card {
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        padding: 2rem;
    }

    body.light-mode .cat-form-card {
        background: rgba(255, 255, 255, 0.85);
        border-color: rgba(6, 182, 212, 0.25);
    }

    .cat-error-summary {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.35);
        color: #f87171;
        border-radius: 12px;
        padding: 0.9rem 1.1rem;
        margin-bottom: 1.5rem;
        font-size: 0.85rem;
    }

    .cat-error-summary ul { margin: 0.35rem 0 0 1.1rem; padding: 0; }

    /* Type toggle (réutilise le style des transactions) */
    .cat-type-toggle { display: flex; gap: 0.75rem; margin-bottom: 1.5rem; }
    .cat-type-option { position: relative; flex: 1; cursor: pointer; }
    .cat-type-radio { position: absolute; opacity: 0; width: 0; height: 0; pointer-events: none; }

    .cat-type-box {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        padding: 0.9rem;
        border-radius: 14px;
        border: 2px solid rgba(148, 163, 184, 0.25);
        background: rgba(10, 10, 26, 0.5);
        color: rgba(255, 255, 255, 0.55);
        font-weight: 700;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    body.light-mode .cat-type-box {
        background: rgba(255, 255, 255, 0.7);
        color: rgba(30, 41, 59, 0.55);
        border-color: rgba(100, 116, 139, 0.25);
    }

    .cat-type-radio:checked + .cat-type-box.type-income {
        border-color: #22c55e; background: rgba(34, 197, 94, 0.14); color: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
    }

    .cat-type-radio:checked + .cat-type-box.type-expense {
        border-color: #ef4444; background: rgba(239, 68, 68, 0.14); color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    /* Fields */
    .cat-field { margin-bottom: 1.35rem; }
    .cat-row { display: flex; gap: 0.9rem; }
    .cat-row .cat-field { flex: 1; margin-bottom: 1.35rem; }

    .cat-label {
        display: flex; align-items: center; gap: 0.5rem;
        font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;
        color: #06b6d4; margin-bottom: 0.55rem;
    }

    .cat-input {
        width: 100%;
        padding: 0.7rem 1rem;
        background: rgba(10, 10, 26, 0.75);
        border: 2px solid rgba(6, 182, 212, 0.22);
        border-radius: 12px;
        color: #fff;
        font-size: 0.9rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.2s ease;
    }

    .cat-input:focus { outline: none; border-color: #06b6d4; box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15); }
    .cat-input::placeholder { color: rgba(255, 255, 255, 0.3); }

    body.light-mode .cat-input {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
        color: #1e293b;
    }

    body.light-mode .cat-input::placeholder { color: rgba(30, 41, 59, 0.35); }

    .cat-hint { font-size: 0.75rem; color: rgba(255, 255, 255, 0.4); margin-top: 0.4rem; }
    body.light-mode .cat-hint { color: rgba(30, 41, 59, 0.5); }

    .cat-field-error { display: flex; align-items: center; gap: 0.4rem; color: #f87171; font-size: 0.78rem; margin-top: 0.45rem; }

    /* Color picker */
    .cat-color-row { display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap; }

    .cat-color-native {
        width: 46px; height: 46px;
        border-radius: 12px;
        border: 2px solid rgba(6, 182, 212, 0.25);
        background: transparent;
        cursor: pointer;
        padding: 2px;
        flex-shrink: 0;
    }

    .cat-swatch {
        width: 28px; height: 28px;
        border-radius: 50%;
        border: 2px solid transparent;
        cursor: pointer;
        transition: transform 0.15s ease, border-color 0.15s ease;
        flex-shrink: 0;
    }

    .cat-swatch:hover { transform: scale(1.15); }
    .cat-swatch.is-active { border-color: #fff; box-shadow: 0 0 0 2px rgba(6, 182, 212, 0.6); }
    body.light-mode .cat-swatch.is-active { border-color: #1e293b; }

    /* Icon picker */
    .cat-icon-row { display: flex; gap: 0.45rem; flex-wrap: wrap; margin-top: 0.6rem; }

    .cat-icon-choice {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem;
        background: rgba(10, 10, 26, 0.5);
        border: 2px solid rgba(148, 163, 184, 0.2);
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .cat-icon-choice:hover { border-color: rgba(6, 182, 212, 0.5); }
    .cat-icon-choice.is-active { border-color: #06b6d4; background: rgba(6, 182, 212, 0.15); }

    body.light-mode .cat-icon-choice { background: rgba(255, 255, 255, 0.7); border-color: rgba(100, 116, 139, 0.2); }

    /* Toggle switch (actif/inactif) */
    .cat-switch-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.9rem 1.1rem;
        border-radius: 14px;
        border: 2px solid rgba(148, 163, 184, 0.2);
        background: rgba(10, 10, 26, 0.4);
    }

    body.light-mode .cat-switch-row { background: rgba(255, 255, 255, 0.6); border-color: rgba(100, 116, 139, 0.2); }

    .cat-switch-text strong { display: block; font-size: 0.9rem; color: #fff; }
    body.light-mode .cat-switch-text strong { color: #1e293b; }
    .cat-switch-text span { font-size: 0.78rem; color: rgba(255, 255, 255, 0.5); }
    body.light-mode .cat-switch-text span { color: rgba(30, 41, 59, 0.6); }

    .cat-switch { position: relative; width: 46px; height: 26px; flex-shrink: 0; }
    .cat-switch input { position: absolute; opacity: 0; width: 100%; height: 100%; margin: 0; cursor: pointer; z-index: 1; }
    .cat-switch-track {
        position: absolute; inset: 0; border-radius: 999px;
        background: rgba(148, 163, 184, 0.35);
        transition: background 0.2s ease;
    }
    .cat-switch-track::before {
        content: ''; position: absolute; top: 3px; left: 3px;
        width: 20px; height: 20px; border-radius: 50%;
        background: #fff; transition: transform 0.2s ease;
    }
    .cat-switch input:checked ~ .cat-switch-track { background: #22c55e; }
    .cat-switch input:checked ~ .cat-switch-track::before { transform: translateX(20px); }

    /* Actions */
    .cat-actions { display: flex; gap: 0.9rem; margin-top: 1.75rem; padding-top: 1.5rem; border-top: 1px solid rgba(6, 182, 212, 0.15); }

    .cat-btn-submit {
        flex: 1; border: none; border-radius: 12px; padding: 0.85rem 1.5rem;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #04121a; font-weight: 800; font-size: 0.92rem; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        box-shadow: 0 6px 18px rgba(6, 182, 212, 0.3);
        transition: all 0.2s ease;
    }

    .cat-btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(6, 182, 212, 0.4); }

    .cat-btn-cancel {
        padding: 0.85rem 1.4rem; border-radius: 12px;
        background: rgba(148, 163, 184, 0.12);
        border: 1px solid rgba(148, 163, 184, 0.25);
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none; font-weight: 600; font-size: 0.9rem;
        display: flex; align-items: center;
        transition: all 0.2s ease;
    }

    .cat-btn-cancel:hover { background: rgba(148, 163, 184, 0.2); }

    body.light-mode .cat-btn-cancel { background: rgba(100, 116, 139, 0.1); border-color: rgba(100, 116, 139, 0.25); color: #1e293b; }

    /* ---------- Preview sidebar ---------- */
    .cat-preview {
        position: sticky; top: 1.5rem;
        background: linear-gradient(160deg, rgba(6, 182, 212, 0.1), rgba(15, 23, 42, 0.65) 55%);
        border: 1px solid rgba(6, 182, 212, 0.25);
        border-radius: 20px;
        padding: 1.75rem;
        text-align: center;
    }

    body.light-mode .cat-preview {
        background: linear-gradient(160deg, rgba(6, 182, 212, 0.08), rgba(255, 255, 255, 0.85) 55%);
        border-color: rgba(6, 182, 212, 0.3);
    }

    .cat-preview-label {
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;
        color: rgba(255, 255, 255, 0.45); margin-bottom: 1.1rem;
    }

    body.light-mode .cat-preview-label { color: rgba(30, 41, 59, 0.5); }

    .cat-preview-icon {
        width: 72px; height: 72px; margin: 0 auto 1rem;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem;
        background: rgba(6, 182, 212, 0.15);
        border: 2px solid rgba(6, 182, 212, 0.4);
        transition: all 0.2s ease;
    }

    .cat-preview-name {
        font-family: 'Poppins', sans-serif;
        font-size: 1.2rem; font-weight: 800; color: #fff;
    }

    body.light-mode .cat-preview-name { color: #1e293b; }

    .cat-preview-pill {
        display: inline-block;
        margin-top: 0.6rem;
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .cat-preview-meta {
        margin-top: 1.5rem; padding-top: 1.25rem;
        border-top: 1px solid rgba(6, 182, 212, 0.15);
        text-align: left;
    }

    .cat-preview-row { display: flex; justify-content: space-between; font-size: 0.85rem; padding: 0.4rem 0; color: rgba(255, 255, 255, 0.85); }
    body.light-mode .cat-preview-row { color: #1e293b; }
    .cat-preview-row span:first-child { color: rgba(255, 255, 255, 0.45); font-weight: 500; }
    body.light-mode .cat-preview-row span:first-child { color: rgba(30, 41, 59, 0.55); }

    @media (max-width: 640px) {
        .cat-hero { flex-wrap: wrap; padding: 1.5rem; }
        .cat-hero-back { width: 100%; justify-content: center; }
        .cat-row { flex-direction: column; }
    }
</style>
