@extends('dashboard.layout')

@php
    $pageTitle = 'Mon certificat';
    $pageDescription = $certificate->display_name;
@endphp

@section('dashboard-content')
<div class="certificate-show-page">
    <a href="{{ route('dashboard.certificates') }}" class="certificate-back-link">
        <i class="fas fa-arrow-left"></i>
        <span>Retour à mes certificats</span>
    </a>

    <div class="certificate-preview">
        <div class="certificate-preview-brand">NiangProgrammeur</div>
        <div class="certificate-preview-kicker">Certificat de réussite</div>
        <h1 class="certificate-preview-title">CERTIFICAT</h1>

        <p class="certificate-preview-lead">Ce certificat est fièrement décerné à</p>
        <div class="certificate-preview-name">{{ $user->name }}</div>

        <p class="certificate-preview-lead">pour avoir terminé avec succès la formation</p>
        <div class="certificate-preview-course">{{ $certificate->display_name }}</div>

        <div class="certificate-preview-footer">
            <div>
                <strong>{{ $certificate->completed_date->format('d/m/Y') }}</strong>
                <span>Date d'obtention</span>
            </div>
            <div class="certificate-preview-seal">
                <i class="fas fa-check"></i>
            </div>
            <div>
                <strong>{{ $certificate->certificate_number }}</strong>
                <span>Numéro de certificat</span>
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

<style>
.certificate-show-page {
    max-width: 900px;
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

.certificate-preview {
    background: white;
    border: 3px solid #04AA6D;
    border-radius: 20px;
    padding: 50px 40px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
}

body.dark-mode .certificate-preview {
    background: rgba(15, 23, 42, 0.7);
}

.certificate-preview-brand {
    font-size: 0.85rem;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #06b6d4;
    font-weight: 800;
    margin-bottom: 6px;
}

.certificate-preview-kicker {
    font-size: 0.95rem;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #64748b;
    margin-bottom: 18px;
}

body.dark-mode .certificate-preview-kicker {
    color: rgba(255, 255, 255, 0.6);
}

.certificate-preview-title {
    font-size: 2.5rem;
    font-weight: 900;
    color: #1e293b;
    margin: 0 0 26px 0;
}

body.dark-mode .certificate-preview-title {
    color: white;
}

.certificate-preview-lead {
    color: #64748b;
    margin: 0 0 8px 0;
}

body.dark-mode .certificate-preview-lead {
    color: rgba(255, 255, 255, 0.65);
}

.certificate-preview-name {
    font-size: 2rem;
    font-weight: 800;
    color: #04AA6D;
    padding-bottom: 16px;
    margin-bottom: 24px;
    border-bottom: 2px solid #e2e8f0;
    display: inline-block;
    min-width: 320px;
}

.certificate-preview-course {
    font-size: 1.4rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 30px;
}

body.dark-mode .certificate-preview-course {
    color: white;
}

.certificate-preview-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 50px;
    margin-top: 20px;
}

.certificate-preview-footer div {
    display: flex;
    flex-direction: column;
    font-size: 0.8rem;
    color: #64748b;
}

body.dark-mode .certificate-preview-footer div {
    color: rgba(255, 255, 255, 0.6);
}

.certificate-preview-footer strong {
    font-size: 1rem;
    color: #1e293b;
    margin-bottom: 3px;
}

body.dark-mode .certificate-preview-footer strong {
    color: white;
}

.certificate-preview-seal {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    border: 3px solid #04AA6D;
    color: #04AA6D;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}

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

@media (max-width: 640px) {
    .certificate-preview {
        padding: 34px 20px;
    }

    .certificate-preview-name {
        min-width: 0;
        width: 100%;
    }

    .certificate-preview-footer {
        gap: 24px;
    }
}
</style>
@endsection
