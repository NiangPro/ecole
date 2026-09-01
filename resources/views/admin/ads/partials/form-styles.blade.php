<style>
    /* Fonts chargées via preload dans admin.layout - pas de @import bloquant */

    .ads-form-wrapper {
        max-width: 1400px;
        margin: 0 auto;
    }

    .form-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 24px;
        padding: 50px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }

    .form-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .form-hero-content {
        position: relative;
        z-index: 1;
    }

    .form-hero h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 3rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: shimmer 3s linear infinite;
        margin-bottom: 15px;
    }

    @keyframes shimmer {
        to { background-position: 200% center; }
    }

    .form-hero p {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 400;
        transition: color 0.3s ease;
    }

    body.light-mode .form-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.05) 0%, rgba(20, 184, 166, 0.05) 100%);
        border-color: rgba(6, 182, 212, 0.4);
    }

    body.light-mode .form-hero p {
        color: rgba(30, 41, 59, 0.8);
    }

    .form-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }

    .form-main {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .form-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        padding: 35px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }

    body.light-mode .form-card {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
    }

    body.light-mode .form-card:hover {
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.15);
    }

    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.6s;
    }

    .form-card:hover::before {
        left: 100%;
    }

    .form-card:hover {
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.2);
        transform: translateY(-3px);
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
    }

    .card-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #06b6d4;
        border: 1px solid rgba(6, 182, 212, 0.3);
    }

    .card-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
        transition: color 0.3s ease;
    }

    body.light-mode .card-title {
        color: #1e293b;
    }

    .field-group {
        margin-bottom: 25px;
    }

    .field-label {
        display: block;
        font-weight: 600;
        color: #06b6d4;
        margin-bottom: 10px;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .field-label .required {
        color: #ef4444;
        font-size: 1.2rem;
    }

    .field-input {
        width: 100%;
        padding: 0.7rem 1rem;
        background: rgba(10, 10, 26, 0.8);
        border: 2px solid rgba(6, 182, 212, 0.25);
        border-radius: 10px;
        color: #fff;
        font-size: 0.9rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
    }

    body.light-mode .field-input {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
        color: #1e293b;
    }

    .field-input:focus {
        outline: none;
        border-color: #06b6d4;
        background: rgba(10, 10, 26, 0.95);
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15);
    }

    body.light-mode .field-input:focus {
        background: rgba(255, 255, 255, 1);
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15);
    }

    .field-input::placeholder {
        color: rgba(255, 255, 255, 0.3);
    }

    body.light-mode .field-input::placeholder {
        color: rgba(30, 41, 59, 0.5);
    }

    .field-select {
        width: 100%;
        padding: 0.7rem 1rem;
        background: rgba(10, 10, 26, 0.8);
        border: 2px solid rgba(6, 182, 212, 0.25);
        border-radius: 10px;
        color: #fff;
        font-size: 0.9rem;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='%2306b6d4' d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 14px;
        padding-right: 40px;
    }

    body.light-mode .field-select {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
        color: #1e293b;
    }

    .field-select:focus {
        outline: none;
        border-color: #06b6d4;
        background-color: rgba(10, 10, 26, 0.95);
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15);
    }

    body.light-mode .field-select:focus {
        background-color: rgba(255, 255, 255, 1);
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15);
    }

    .field-select option {
        background: #0a0a0f;
        color: #fff;
        padding: 12px;
    }

    body.light-mode .field-select option {
        background: #ffffff;
        color: #1e293b;
    }

    .field-help {
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.85rem;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: color 0.3s ease;
    }

    body.light-mode .field-help {
        color: rgba(30, 41, 59, 0.6);
    }

    body.light-mode .field-help a {
        color: #06b6d4;
    }

    body.light-mode .field-help a:hover {
        color: #14b8a6;
    }

    .field-help i {
        color: #06b6d4;
        font-size: 0.9rem;
    }

    .image-preview-box {
        margin-top: 20px;
        border-radius: 16px;
        overflow: hidden;
        border: 2px solid rgba(6, 182, 212, 0.3);
        background: rgba(10, 10, 26, 0.6);
        position: relative;
        transition: all 0.3s ease;
    }

    body.light-mode .image-preview-box {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.4);
    }

    .image-preview-box img {
        width: 100%;
        max-height: 450px;
        object-fit: contain;
        display: block;
    }

    .sidebar-panel {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .sidebar-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        padding: 30px;
        transition: all 0.3s ease;
    }

    body.light-mode .sidebar-card {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
    }

    .sidebar-card:hover {
        border-color: rgba(6, 182, 212, 0.4);
    }

    body.light-mode .sidebar-card:hover {
        border-color: rgba(6, 182, 212, 0.5);
    }

    .location-warning {
        background: rgba(239, 68, 68, 0.1);
        border: 2px solid rgba(239, 68, 68, 0.3);
        border-radius: 12px;
        padding: 15px;
        margin-top: 15px;
        transition: all 0.3s ease;
    }

    body.light-mode .location-warning {
        background: rgba(239, 68, 68, 0.15);
        border-color: rgba(239, 68, 68, 0.4);
    }

    .location-warning i {
        color: #ef4444;
        margin-right: 8px;
    }

    .location-warning-text {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
        line-height: 1.6;
        transition: color 0.3s ease;
    }

    body.light-mode .location-warning-text {
        color: rgba(30, 41, 59, 0.9);
    }

    body.light-mode .location-warning-text strong {
        color: #1e293b;
    }

    /* ── Statistiques (page d'édition uniquement) ────────────── */
    .stats-card {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(20, 184, 166, 0.15));
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 16px;
        padding: 25px;
        transition: all 0.3s ease;
    }

    body.light-mode .stats-card {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
        border-color: rgba(6, 182, 212, 0.4);
    }

    .stats-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: #06b6d4;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .stat-box {
        text-align: center;
        padding: 20px;
        background: rgba(10, 10, 26, 0.6);
        border-radius: 12px;
        border: 1px solid rgba(6, 182, 212, 0.2);
        transition: all 0.3s ease;
    }

    body.light-mode .stat-box {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.3);
    }

    .stat-value {
        font-size: 2.2rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.6);
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: color 0.3s ease;
    }

    body.light-mode .stat-label {
        color: rgba(30, 41, 59, 0.7);
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid rgba(6, 182, 212, 0.2);
        transition: border-color 0.3s ease;
    }

    body.light-mode .action-buttons {
        border-top-color: rgba(6, 182, 212, 0.3);
    }

    .btn-save {
        flex: 1;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border: none;
        padding: 0.7rem 1.5rem;
        border-radius: 10px;
        color: #000;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
        font-family: 'Inter', sans-serif;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(6, 182, 212, 0.5);
    }

    .btn-cancel {
        padding: 0.7rem 1.5rem;
        background: rgba(100, 100, 100, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        color: #fff;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-family: 'Inter', sans-serif;
    }

    body.light-mode .btn-cancel {
        background: rgba(148, 163, 184, 0.2);
        border-color: rgba(148, 163, 184, 0.4);
        color: #1e293b;
    }

    .btn-cancel:hover {
        background: rgba(100, 100, 100, 0.3);
        border-color: rgba(255, 255, 255, 0.2);
    }

    body.light-mode .btn-cancel:hover {
        background: rgba(148, 163, 184, 0.3);
        border-color: rgba(148, 163, 184, 0.6);
    }

    .error-message {
        color: #ef4444;
        font-size: 0.9rem;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .error-message i {
        font-size: 0.85rem;
    }

    /* ── Format toggle (Image / Vidéo YouTube) ───────────────── */
    .format-toggle {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 25px;
    }

    .format-toggle-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 1rem;
        background: rgba(10, 10, 26, 0.6);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 14px;
        color: rgba(255, 255, 255, 0.6);
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.25s ease;
        font-family: 'Inter', sans-serif;
    }

    body.light-mode .format-toggle-btn {
        background: rgba(255, 255, 255, 0.7);
        color: rgba(30, 41, 59, 0.6);
    }

    .format-toggle-btn i {
        font-size: 1.15rem;
    }

    .format-toggle-btn:hover {
        border-color: rgba(6, 182, 212, 0.5);
        color: #fff;
    }

    body.light-mode .format-toggle-btn:hover {
        color: #1e293b;
    }

    .format-toggle-btn.active {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.25), rgba(20, 184, 166, 0.25));
        border-color: #06b6d4;
        color: #fff;
        box-shadow: 0 4px 16px rgba(6, 182, 212, 0.25);
    }

    body.light-mode .format-toggle-btn.active {
        color: #0e7490;
    }

    .format-toggle-btn[data-format="video"].active {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(6, 182, 212, 0.25));
        border-color: #ef4444;
    }

    /* ── Aperçu vidéo YouTube ─────────────────────────────────── */
    .youtube-url-row {
        display: flex;
        gap: 10px;
    }

    .youtube-url-row .field-input {
        flex: 1;
    }

    .btn-analyze {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0 1.25rem;
        background: linear-gradient(135deg, #ef4444, #06b6d4);
        border: none;
        border-radius: 10px;
        color: #fff;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.25s ease;
        font-family: 'Inter', sans-serif;
    }

    .btn-analyze:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(239, 68, 68, 0.35);
    }

    .btn-analyze:disabled {
        opacity: 0.6;
        cursor: wait;
        transform: none;
    }

    .btn-analyze .fa-spin {
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .youtube-error {
        margin-top: -12px;
        margin-bottom: 20px;
        padding: 12px 16px;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 10px;
        color: #f87171;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .youtube-preview-box {
        display: flex;
        gap: 16px;
        padding: 16px;
        border-radius: 16px;
        border: 2px solid rgba(6, 182, 212, 0.3);
        background: rgba(10, 10, 26, 0.6);
        margin-bottom: 25px;
    }

    body.light-mode .youtube-preview-box {
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(6, 182, 212, 0.4);
    }

    .youtube-preview-thumb {
        flex-shrink: 0;
        width: 160px;
        height: 90px;
        border-radius: 12px;
        background: linear-gradient(160deg, #1e293b, #0f172a) center / cover no-repeat;
        border: 1px solid rgba(6, 182, 212, 0.25);
    }

    .youtube-preview-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 6px;
        min-width: 0;
    }

    .youtube-preview-info strong {
        color: #fff;
        font-size: 0.95rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    body.light-mode .youtube-preview-info strong {
        color: #1e293b;
    }

    .youtube-preview-info span {
        color: #06b6d4;
        font-size: 0.85rem;
        font-weight: 600;
    }

    @media (max-width: 1200px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-hero {
            padding: 35px;
        }

        .form-hero h1 {
            font-size: 2.2rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .form-hero {
            padding: 25px;
        }

        .form-hero h1 {
            font-size: 1.8rem;
        }

        .form-card {
            padding: 25px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .format-toggle {
            grid-template-columns: 1fr;
        }

        .youtube-url-row {
            flex-direction: column;
        }
    }
</style>
