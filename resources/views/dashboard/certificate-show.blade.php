@extends('dashboard.layout')

@php
    $pageTitle = 'Mon certificat';
    $pageDescription = $certificate->display_name;

    // Génération procédurale d'une couronne de laurier (SVG) : deux branches
    // symétriques de feuilles réparties sur une courbe, du pied vers le sommet.
    $leafCount = 9;
    $wreathLeaves = [];
    for ($i = 0; $i < $leafCount; $i++) {
        $t = $i / ($leafCount - 1);
        $bulge = sin($t * M_PI) * 27;
        $wreathLeaves[] = [
            'x' => 5 + $bulge,
            'y' => 80 - $t * 70,
            'angle' => -12 - $t * 68,
            'scale' => 0.55 + 0.45 * sin($t * M_PI),
        ];
    }
@endphp

@section('dashboard-content')
<div class="certificate-show-page">
    <a href="{{ route('dashboard.certificates') }}" class="certificate-back-link">
        <i class="fas fa-arrow-left"></i>
        <span>Retour à mes certificats</span>
    </a>

    <div class="cert-wrap">
        <div class="cert-card">
            <div class="cert-corner cert-corner--tl"></div>
            <div class="cert-corner cert-corner--br"></div>

            <div class="cert-seal">
                <div class="cert-seal-ribbon cert-seal-ribbon--left"></div>
                <div class="cert-seal-ribbon cert-seal-ribbon--right"></div>
                <div class="cert-seal-circle"><i class="fas fa-check"></i></div>
            </div>

            <div class="cert-inner">
                <div class="cert-brand">NiangProgrammeur</div>

                <h1 class="cert-title">Certificat</h1>
                <div class="cert-subtitle">De Réussite</div>

                <div class="cert-divider">
                    <span></span>
                    <i class="cert-divider-dot"></i>
                    <span></span>
                </div>

                <p class="cert-presented-to">Ce certificat est fièrement décerné à</p>
                <div class="cert-name">{{ $user->name }}</div>

                <p class="cert-body">
                    Pour avoir terminé avec succès la formation<br>
                    <strong>{{ $certificate->display_name }}</strong>
                    @if(!is_null($certificate->score))
                        avec un score de <strong>{{ $certificate->score }}%</strong>
                    @endif
                </p>

                <svg class="cert-wreath" viewBox="0 0 100 90" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <defs>
                        <linearGradient id="certGoldLeaf" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#f3d98a"/>
                            <stop offset="100%" stop-color="#a9791f"/>
                        </linearGradient>
                    </defs>
                    <circle cx="50" cy="83" r="4.5" fill="url(#certGoldLeaf)" />
                    @foreach($wreathLeaves as $leaf)
                        <path d="M0,0 C-4,-7 -4,-15 0,-22 C4,-15 4,-7 0,0 Z" fill="url(#certGoldLeaf)"
                              transform="translate({{ 50 - $leaf['x'] }}, {{ $leaf['y'] }}) rotate({{ $leaf['angle'] }}) scale({{ $leaf['scale'] }})" />
                    @endforeach
                    @foreach($wreathLeaves as $leaf)
                        <path d="M0,0 C-4,-7 -4,-15 0,-22 C4,-15 4,-7 0,0 Z" fill="url(#certGoldLeaf)"
                              transform="translate({{ 50 + $leaf['x'] }}, {{ $leaf['y'] }}) rotate({{ -$leaf['angle'] }}) scale({{ -$leaf['scale'] }}, {{ $leaf['scale'] }})" />
                    @endforeach
                </svg>

                <div class="cert-footer">
                    <div class="cert-footer-item">
                        <span class="cert-footer-value">{{ $certificate->completed_date->format('d/m/Y') }}</span>
                        <span class="cert-footer-line"></span>
                        <span class="cert-footer-label">Date d'obtention</span>
                    </div>
                    <div class="cert-footer-item">
                        <span class="cert-footer-value">{{ $certificate->certificate_number }}</span>
                        <span class="cert-footer-line"></span>
                        <span class="cert-footer-label">Numéro de certificat</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="certificate-actions">
        <a href="{{ route('dashboard.certificates.download', $certificate->id) }}" class="certificate-download-btn">
            <i class="fas fa-download"></i>
            <span>Télécharger le PDF</span>
        </a>
    </div>
</div>

<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Playfair+Display:ital,wght@1,700&display=swap" rel="stylesheet">

<style>
.certificate-show-page {
    max-width: 980px;
    margin: 0 auto;
}

.certificate-back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #06b6d4;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 24px;
}

/* ============ Cadre du certificat ============ */
.cert-wrap {
    --cert-green-dark: #052e21;
    --cert-green-mid: #036b46;
    --cert-green: #04AA6D;
    --cert-gold: #d4af37;
    --cert-gold-light: #f3d98a;
    --cert-gold-dark: #a9791f;
}

.cert-card {
    position: relative;
    background: linear-gradient(135deg, var(--cert-green-dark), var(--cert-green-mid) 55%, var(--cert-green-dark));
    border-radius: 20px;
    padding: 14px;
    box-shadow: 0 20px 60px rgba(4, 51, 31, 0.35);
    overflow: hidden;
}

.cert-card::before {
    content: '';
    position: absolute;
    inset: 0;
    opacity: 0.08;
    background-image:
        linear-gradient(45deg, #fff 25%, transparent 25%),
        linear-gradient(-45deg, #fff 25%, transparent 25%);
    background-size: 46px 46px;
    pointer-events: none;
}

.cert-corner {
    position: absolute;
    width: 130px;
    height: 130px;
    overflow: hidden;
    pointer-events: none;
    z-index: 1;
}

.cert-corner--tl { top: 0; left: 0; }
.cert-corner--br { bottom: 0; right: 0; transform: rotate(180deg); }

.cert-corner::before {
    content: '';
    position: absolute;
    width: 190px;
    height: 12px;
    top: 30px;
    left: -50px;
    transform: rotate(-45deg);
    background: linear-gradient(90deg, var(--cert-gold-dark), var(--cert-gold-light) 50%, var(--cert-gold-dark));
    box-shadow: 0 0 14px rgba(212, 175, 55, 0.5);
}

.cert-seal {
    position: absolute;
    top: 6px;
    right: 46px;
    width: 74px;
    z-index: 5;
}

.cert-seal-ribbon {
    position: absolute;
    top: 50px;
    width: 20px;
    height: 48px;
    background: linear-gradient(180deg, var(--cert-green), var(--cert-green-dark));
    clip-path: polygon(0 0, 100% 0, 100% 82%, 50% 100%, 0 82%);
}

.cert-seal-ribbon--left { left: 6px; transform: rotate(-14deg); }
.cert-seal-ribbon--right { right: 6px; transform: rotate(14deg); }

.cert-seal-circle {
    position: relative;
    width: 64px;
    height: 64px;
    margin: 0 auto;
    border-radius: 50%;
    background: radial-gradient(circle at 35% 30%, var(--cert-gold-light), var(--cert-gold) 55%, var(--cert-gold-dark) 100%);
    border: 3px solid var(--cert-green-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
}

.cert-seal-circle i {
    color: var(--cert-green-dark);
    font-size: 1.4rem;
}

.cert-inner {
    position: relative;
    z-index: 2;
    background: #fdfcf8;
    border-radius: 12px;
    border: 2px solid var(--cert-gold);
    box-shadow: inset 0 0 0 5px #fdfcf8, inset 0 0 0 7px var(--cert-gold);
    padding: 56px 64px 44px;
    text-align: center;
}

.cert-brand {
    font-family: 'Cinzel', serif;
    letter-spacing: 4px;
    text-transform: uppercase;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--cert-green-mid);
    margin-bottom: 10px;
}

.cert-title {
    font-family: 'Cinzel', serif;
    font-weight: 900;
    font-size: clamp(2.1rem, 5vw, 3.2rem);
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #1e293b;
    margin: 0;
    line-height: 1;
}

.cert-subtitle {
    font-family: 'Cinzel', serif;
    font-weight: 600;
    letter-spacing: 7px;
    text-transform: uppercase;
    color: var(--cert-gold-dark);
    font-size: 1.05rem;
    margin-top: 8px;
}

.cert-divider {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin: 20px auto;
    max-width: 340px;
}

.cert-divider span {
    flex: 1;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--cert-gold));
}

.cert-divider span:last-child {
    background: linear-gradient(90deg, var(--cert-gold), transparent);
}

.cert-divider-dot {
    width: 9px;
    height: 9px;
    background: var(--cert-gold);
    transform: rotate(45deg);
    display: inline-block;
    flex-shrink: 0;
}

.cert-presented-to {
    font-size: 0.8rem;
    letter-spacing: 2px;
    text-transform: uppercase;
    font-weight: 700;
    color: #475569;
    margin: 0 0 16px 0;
}

.cert-name {
    font-family: 'Playfair Display', serif;
    font-style: italic;
    font-weight: 700;
    font-size: clamp(1.5rem, 4vw, 2.3rem);
    color: var(--cert-green);
    display: inline-block;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--cert-gold);
    min-width: 320px;
    margin-bottom: 22px;
    word-break: break-word;
}

.cert-body {
    color: #475569;
    font-size: 1rem;
    line-height: 1.7;
    max-width: 540px;
    margin: 0 auto 6px;
}

.cert-body strong {
    color: #1e293b;
}

.cert-wreath {
    width: 84px;
    display: block;
    margin: 14px auto 4px;
}

.cert-footer {
    display: flex;
    justify-content: center;
    align-items: flex-end;
    gap: 60px;
    margin-top: 22px;
}

.cert-footer-item {
    flex: 0 1 220px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.cert-footer-value {
    font-weight: 700;
    color: #1e293b;
    font-size: 0.95rem;
    margin-bottom: 8px;
}

.cert-footer-line {
    width: 100%;
    border-top: 1px dashed var(--cert-gold-dark);
    margin-bottom: 6px;
}

.cert-footer-label {
    font-size: 0.7rem;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #64748b;
}

/* ============ Actions ============ */
.certificate-actions {
    display: flex;
    justify-content: center;
    margin-top: 30px;
}

.certificate-download-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    background: linear-gradient(135deg, #04AA6D, #06b6d4);
    color: white;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 700;
    box-shadow: 0 8px 20px rgba(4, 170, 109, 0.3);
    transition: all 0.25s ease;
}

.certificate-download-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(4, 170, 109, 0.4);
}

@media (max-width: 768px) {
    .cert-inner {
        padding: 44px 24px 32px;
    }

    .cert-footer {
        gap: 24px;
    }

    .cert-seal {
        width: 56px;
        right: 24px;
    }

    .cert-seal-circle {
        width: 48px;
        height: 48px;
    }

    .cert-seal-circle i {
        font-size: 1.1rem;
    }

    .cert-seal-ribbon {
        top: 38px;
        height: 36px;
        width: 15px;
    }
}

@media (max-width: 560px) {
    .cert-name {
        min-width: 0;
        width: 100%;
    }

    .cert-footer {
        flex-direction: column;
        gap: 18px;
    }

    .cert-footer-item {
        flex: 0 1 auto;
        width: 100%;
        max-width: 260px;
    }
}
</style>
@endsection
