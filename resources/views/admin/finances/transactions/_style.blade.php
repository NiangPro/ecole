<style>
    .txn-page {
        max-width: 1100px;
        margin: 0 auto;
    }

    /* ---------- Hero ---------- */
    .txn-hero {
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

    .txn-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 90% 10%, rgba(6, 182, 212, 0.18), transparent 60%);
        pointer-events: none;
    }

    .txn-hero-icon {
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

    .txn-hero-text { position: relative; z-index: 1; flex: 1; }

    .txn-hero-text h1 {
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

    .txn-hero-text p {
        margin: 0;
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.65);
    }

    body.light-mode .txn-hero-text p { color: rgba(30, 41, 59, 0.65); }

    .txn-hero-back {
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

    .txn-hero-back:hover { background: rgba(6, 182, 212, 0.15); border-color: rgba(6, 182, 212, 0.4); color: #06b6d4; }

    body.light-mode .txn-hero-back {
        background: rgba(255, 255, 255, 0.6);
        border-color: rgba(100, 116, 139, 0.25);
        color: #1e293b;
    }

    /* ---------- Layout ---------- */
    .txn-layout {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 1.5rem;
        align-items: start;
    }

    @media (max-width: 900px) {
        .txn-layout { grid-template-columns: 1fr; }
        .txn-preview { order: -1; }
    }

    /* ---------- Form card ---------- */
    .txn-form-card {
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        padding: 2rem;
    }

    body.light-mode .txn-form-card {
        background: rgba(255, 255, 255, 0.85);
        border-color: rgba(6, 182, 212, 0.25);
    }

    .txn-error-summary {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.35);
        color: #f87171;
        border-radius: 12px;
        padding: 0.9rem 1.1rem;
        margin-bottom: 1.5rem;
        font-size: 0.85rem;
    }

    .txn-error-summary ul { margin: 0.35rem 0 0 1.1rem; padding: 0; }

    /* Type toggle */
    .txn-type-toggle {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .txn-type-option { position: relative; flex: 1; cursor: pointer; }

    .txn-type-radio {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
        pointer-events: none;
    }

    .txn-type-box {
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

    body.light-mode .txn-type-box {
        background: rgba(255, 255, 255, 0.7);
        color: rgba(30, 41, 59, 0.55);
        border-color: rgba(100, 116, 139, 0.25);
    }

    .txn-type-radio:checked + .txn-type-box.type-income {
        border-color: #22c55e;
        background: rgba(34, 197, 94, 0.14);
        color: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
    }

    .txn-type-radio:checked + .txn-type-box.type-expense {
        border-color: #ef4444;
        background: rgba(239, 68, 68, 0.14);
        color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    /* Fields */
    .txn-field { margin-bottom: 1.35rem; }

    .txn-row { display: flex; gap: 0.9rem; }
    .txn-row .txn-field { flex: 1; margin-bottom: 1.35rem; }
    .txn-row .txn-currency { flex: 0 0 120px; }

    .txn-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #06b6d4;
        margin-bottom: 0.55rem;
    }

    .txn-input {
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

    .txn-input:focus {
        outline: none;
        border-color: #06b6d4;
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15);
    }

    .txn-input::placeholder { color: rgba(255, 255, 255, 0.3); }

    select.txn-input {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%2306b6d4' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 13px;
        padding-right: 38px;
    }

    select.txn-input option { background: #0a0a0f; color: #fff; }

    body.light-mode .txn-input {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
        color: #1e293b;
    }

    body.light-mode .txn-input::placeholder { color: rgba(30, 41, 59, 0.35); }
    body.light-mode select.txn-input option { background: #ffffff; color: #1e293b; }

    .txn-hint {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.4);
        margin-top: 0.4rem;
    }

    body.light-mode .txn-hint { color: rgba(30, 41, 59, 0.5); }

    .txn-field-error {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        color: #f87171;
        font-size: 0.78rem;
        margin-top: 0.45rem;
    }

    /* Actions */
    .txn-actions {
        display: flex;
        gap: 0.9rem;
        margin-top: 1.75rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(6, 182, 212, 0.15);
    }

    .txn-btn-submit {
        flex: 1;
        border: none;
        border-radius: 12px;
        padding: 0.85rem 1.5rem;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #04121a;
        font-weight: 800;
        font-size: 0.92rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 6px 18px rgba(6, 182, 212, 0.3);
        transition: all 0.2s ease;
    }

    .txn-btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(6, 182, 212, 0.4); }

    .txn-btn-cancel {
        padding: 0.85rem 1.4rem;
        border-radius: 12px;
        background: rgba(148, 163, 184, 0.12);
        border: 1px solid rgba(148, 163, 184, 0.25);
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
    }

    .txn-btn-cancel:hover { background: rgba(148, 163, 184, 0.2); }

    body.light-mode .txn-btn-cancel {
        background: rgba(100, 116, 139, 0.1);
        border-color: rgba(100, 116, 139, 0.25);
        color: #1e293b;
    }

    /* ---------- Preview sidebar ---------- */
    .txn-preview {
        position: sticky;
        top: 1.5rem;
        background: linear-gradient(160deg, rgba(6, 182, 212, 0.1), rgba(15, 23, 42, 0.65) 55%);
        border: 1px solid rgba(6, 182, 212, 0.25);
        border-radius: 20px;
        padding: 1.75rem;
        text-align: center;
    }

    body.light-mode .txn-preview {
        background: linear-gradient(160deg, rgba(6, 182, 212, 0.08), rgba(255, 255, 255, 0.85) 55%);
        border-color: rgba(6, 182, 212, 0.3);
    }

    .txn-preview-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255, 255, 255, 0.45);
        margin-bottom: 1rem;
    }

    body.light-mode .txn-preview-label { color: rgba(30, 41, 59, 0.5); }

    .txn-preview-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 0.9rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        background: rgba(6, 182, 212, 0.12);
        border: 1px solid rgba(6, 182, 212, 0.3);
        transition: all 0.25s ease;
    }

    .txn-preview-amount {
        font-family: 'Poppins', sans-serif;
        font-size: 1.9rem;
        font-weight: 800;
        color: #fff;
        transition: color 0.2s ease;
    }

    body.light-mode .txn-preview-amount { color: #1e293b; }

    .txn-preview-amount.is-income { color: #22c55e; }
    .txn-preview-amount.is-expense { color: #ef4444; }

    .txn-preview-converted {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.45);
        margin-top: 0.25rem;
        min-height: 1.1em;
    }

    body.light-mode .txn-preview-converted { color: rgba(30, 41, 59, 0.55); }

    .txn-preview-meta {
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid rgba(6, 182, 212, 0.15);
        text-align: left;
    }

    .txn-preview-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        padding: 0.4rem 0;
        color: rgba(255, 255, 255, 0.85);
    }

    body.light-mode .txn-preview-row { color: #1e293b; }

    .txn-preview-row span:first-child {
        color: rgba(255, 255, 255, 0.45);
        font-weight: 500;
    }

    body.light-mode .txn-preview-row span:first-child { color: rgba(30, 41, 59, 0.55); }

    .txn-preview-hint {
        margin-top: 1.25rem;
        font-size: 0.72rem;
        color: rgba(255, 255, 255, 0.35);
        line-height: 1.4;
    }

    body.light-mode .txn-preview-hint { color: rgba(30, 41, 59, 0.45); }

    @media (max-width: 640px) {
        .txn-hero { flex-wrap: wrap; padding: 1.5rem; }
        .txn-hero-back { width: 100%; justify-content: center; }
        .txn-row { flex-direction: column; }
        .txn-row .txn-currency { flex: 1; }
    }
</style>
