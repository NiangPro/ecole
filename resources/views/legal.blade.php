@extends('layouts.app')

@section('title', 'Mentions Légales | NiangProgrammeur')
@section('meta_description', 'Mentions légales de NiangProgrammeur : éditeur du site, hébergement, propriété intellectuelle, droit applicable et coordonnées de contact.')
@section('canonical', route('legal'))

@section('styles')
<style>
    body { overflow-x: hidden; }

    body:not(.dark-mode) { background: #ffffff !important; }
    body.dark-mode { background: #0a0a0f !important; }

    /* ── Hero ─────────────────────────────────────────────── */
    .legal-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
        padding: 120px 20px 70px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    body:not(.dark-mode) .legal-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.05) 0%, rgba(20, 184, 166, 0.05) 100%) !important;
    }

    .legal-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        animation: legal-rotate 20s linear infinite;
    }

    @keyframes legal-rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .legal-hero-content {
        position: relative;
        z-index: 1;
        max-width: 900px;
        margin: 0 auto;
    }

    .legal-icon-wrapper {
        display: inline-block;
        margin-bottom: 30px;
        animation: legal-float 3s ease-in-out infinite;
    }

    @keyframes legal-float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    .legal-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #000;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.3);
    }

    .legal-hero h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 3.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: legal-shimmer 3s linear infinite;
        margin-bottom: 20px;
    }

    @keyframes legal-shimmer {
        to { background-position: 200% center; }
    }

    body:not(.dark-mode) .legal-hero h1 {
        -webkit-text-fill-color: transparent !important;
    }

    .legal-hero p {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 400;
    }

    body:not(.dark-mode) .legal-hero p {
        color: rgba(30, 41, 59, 0.7) !important;
    }

    /* ── Quick nav ────────────────────────────────────────── */
    .legal-toc {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
        max-width: 1000px;
        margin: 0 auto;
        padding: 30px 20px 0;
    }

    .legal-toc a {
        padding: 9px 18px;
        border-radius: 999px;
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.25);
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s ease;
        white-space: nowrap;
    }

    body:not(.dark-mode) .legal-toc a {
        color: rgba(30, 41, 59, 0.8) !important;
        background: rgba(6, 182, 212, 0.06);
    }

    .legal-toc a:hover {
        background: #06b6d4;
        border-color: #06b6d4;
        color: #04141a !important;
        transform: translateY(-2px);
    }

    /* ── Section ──────────────────────────────────────────── */
    .legal-section {
        padding: 60px 20px 80px;
        max-width: 1000px;
        margin: 0 auto;
    }

    .legal-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 50px;
        margin-bottom: 30px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        scroll-margin-top: 100px;
    }

    body:not(.dark-mode) .legal-card {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
    }

    .legal-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.6s;
    }

    .legal-card:hover::before { left: 100%; }

    .legal-card:hover {
        border-color: rgba(6, 182, 212, 0.4);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.2);
    }

    .legal-update {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.9rem;
        margin-bottom: 10px;
    }

    body:not(.dark-mode) .legal-update {
        color: rgba(30, 41, 59, 0.6) !important;
    }

    .legal-section-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.7rem;
        font-weight: 800;
        color: #06b6d4;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    body:not(.dark-mode) .legal-section-title { color: #06b6d4 !important; }

    .legal-section-title i { font-size: 1.3rem; }

    .legal-subtitle {
        font-family: 'Poppins', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.9);
        margin-top: 20px;
        margin-bottom: 12px;
    }

    body:not(.dark-mode) .legal-subtitle { color: rgba(30, 41, 59, 0.9) !important; }

    .legal-text {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.9;
        font-size: 1rem;
        margin-bottom: 16px;
    }

    body:not(.dark-mode) .legal-text { color: rgba(30, 41, 59, 0.8) !important; }

    .legal-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 16px;
    }

    .legal-list li {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.9;
        font-size: 1rem;
        margin-bottom: 10px;
        padding-left: 28px;
        position: relative;
    }

    body:not(.dark-mode) .legal-list li { color: rgba(30, 41, 59, 0.8) !important; }

    .legal-list li::before {
        content: '▸';
        position: absolute;
        left: 0;
        color: #06b6d4;
        font-weight: 700;
    }

    .legal-link {
        color: #06b6d4;
        text-decoration: underline;
        transition: all 0.3s ease;
    }

    .legal-link:hover {
        color: #14b8a6;
        text-decoration: none;
    }

    /* ── Contact card ─────────────────────────────────────── */
    .legal-contact {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 20px;
        padding: 40px;
        margin-top: 10px;
        position: relative;
        overflow: hidden;
    }

    body:not(.dark-mode) .legal-contact {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.05), rgba(20, 184, 166, 0.05)) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
    }

    .legal-contact-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.05rem;
    }

    .legal-contact-item:last-child { margin-bottom: 0; }

    body:not(.dark-mode) .legal-contact-item { color: rgba(30, 41, 59, 0.8) !important; }

    .legal-contact-item i {
        color: #06b6d4;
        font-size: 1.25rem;
        width: 28px;
        flex-shrink: 0;
    }

    .legal-contact-item strong {
        color: #06b6d4;
        margin-right: 8px;
    }

    @media (max-width: 768px) {
        .legal-hero { padding: 100px 20px 50px; }
        .legal-hero h1 { font-size: 2.3rem; }
        .legal-hero p { font-size: 1rem; }
        .legal-card { padding: 28px 20px; }
        .legal-section-title { font-size: 1.35rem; }
        .legal-subtitle { font-size: 1.05rem; }
    }
</style>
@endsection

@section('content')
@php
    $siteSettings = \App\Models\SiteSetting::instance();
    $contactEmail = $siteSettings->contact_email ?? 'NiangProgrammeur@gmail.com';
    $contactPhone = $siteSettings->contact_phone ?? '+221 78 312 36 57';
    $contactAddress = $siteSettings->contact_address ?? 'Dakar, Sénégal';
    $phoneDigits = preg_replace('/[^0-9]/', '', $contactPhone);
@endphp

<!-- Hero -->
<section class="legal-hero">
    <div class="legal-hero-content">
        <div class="legal-icon-wrapper">
            <div class="legal-icon"><i class="fas fa-gavel"></i></div>
        </div>
        <h1>Mentions Légales</h1>
        <p>Éditeur du site, hébergement et informations légales de NiangProgrammeur</p>
    </div>
    <nav class="legal-toc" aria-label="Sommaire des mentions légales">
        <a href="#section-1">1. Informations légales</a>
        <a href="#section-2">2. Hébergement</a>
        <a href="#section-3">3. Propriété intellectuelle</a>
        <a href="#section-4">4. Responsabilité</a>
        <a href="#section-5">5. Données personnelles</a>
        <a href="#section-6">6. Cookies</a>
        <a href="#section-7">7. Droit applicable</a>
        <a href="#section-8">8. Contact</a>
    </nav>
</section>

<!-- Content -->
<section class="legal-section">
    <p class="legal-update" style="text-align:center;">Dernière mise à jour : {{ date('d/m/Y') }}</p>

    <div class="legal-card" id="section-1">
        <h2 class="legal-section-title"><i class="fas fa-id-card"></i> 1. Informations légales</h2>
        <h3 class="legal-subtitle">1.1 Éditeur du site</h3>
        <p class="legal-text">Le site <strong>NiangProgrammeur</strong> (ci-après "le Site") est édité par :</p>
        <ul class="legal-list">
            <li><strong>Nom :</strong> Bassirou Niang ({{ $siteSettings->site_name ?? 'NiangProgrammeur' }})</li>
            <li><strong>Statut :</strong> Auto-entrepreneur / Développeur indépendant</li>
            <li><strong>Adresse :</strong> {{ $contactAddress }}</li>
            <li><strong>Email :</strong> <a href="mailto:{{ $contactEmail }}" class="legal-link">{{ $contactEmail }}</a></li>
            <li><strong>Téléphone :</strong> <a href="tel:+{{ $phoneDigits }}" class="legal-link">{{ $contactPhone }}</a></li>
        </ul>

        <h3 class="legal-subtitle">1.2 Directeur de publication</h3>
        <p class="legal-text">Le directeur de la publication du Site est <strong>Bassirou Niang</strong>.</p>
    </div>

    <div class="legal-card" id="section-2">
        <h2 class="legal-section-title"><i class="fas fa-server"></i> 2. Hébergement</h2>
        <h3 class="legal-subtitle">2.1 Hébergeur du site</h3>
        <p class="legal-text">Le Site est hébergé par :</p>
        <ul class="legal-list">
            <li><strong>Nom :</strong> Infomaniak Network SA</li>
            <li><strong>Adresse :</strong> Rue Eugène-Marziano 25, 1227 Genève, Suisse</li>
            <li><strong>Site web :</strong> <a href="https://www.infomaniak.com" target="_blank" rel="noopener" class="legal-link">www.infomaniak.com</a></li>
        </ul>
    </div>

    <div class="legal-card" id="section-3">
        <h2 class="legal-section-title"><i class="fas fa-copyright"></i> 3. Propriété intellectuelle</h2>
        <h3 class="legal-subtitle">3.1 Droits d'auteur</h3>
        <p class="legal-text">
            L'ensemble du contenu présent sur le Site, incluant mais ne se limitant pas aux textes, images, graphismes, logos, icônes, vidéos, sons, logiciels et bases de données, est la propriété exclusive de NiangProgrammeur ou de ses partenaires et est protégé par les lois françaises et internationales relatives à la propriété intellectuelle.
        </p>

        <h3 class="legal-subtitle">3.2 Utilisation du contenu</h3>
        <p class="legal-text">
            Toute reproduction, représentation, modification, publication, adaptation de tout ou partie des éléments du Site, quel que soit le moyen ou le procédé utilisé, est interdite, sauf autorisation écrite préalable de NiangProgrammeur.
        </p>

        <h3 class="legal-subtitle">3.3 Marques et logos</h3>
        <p class="legal-text">
            Les marques, logos, signes et tout autre contenu du Site font l'objet d'une protection par le Code de la propriété intellectuelle. Toute reproduction totale ou partielle de ces marques ou de ces logos sans l'autorisation expresse de NiangProgrammeur est interdite.
        </p>
    </div>

    <div class="legal-card" id="section-4">
        <h2 class="legal-section-title"><i class="fas fa-triangle-exclamation"></i> 4. Responsabilité</h2>
        <h3 class="legal-subtitle">4.1 Contenu du site</h3>
        <p class="legal-text">
            NiangProgrammeur s'efforce de fournir sur le Site des informations aussi précises que possible. Toutefois, il ne pourra être tenu responsable des omissions, des inexactitudes et des carences dans la mise à jour, qu'elles soient de son fait ou du fait des tiers partenaires qui lui fournissent ces informations.
        </p>

        <h3 class="legal-subtitle">4.2 Disponibilité du site</h3>
        <p class="legal-text">
            NiangProgrammeur ne peut être tenu responsable en cas d'interruption du Site, de survenance de bugs ou de tout dommage résultant d'une intrusion frauduleuse d'un tiers ayant entraîné une modification des informations mises à disposition sur le Site.
        </p>

        <h3 class="legal-subtitle">4.3 Liens hypertextes</h3>
        <p class="legal-text">
            Le Site peut contenir des liens hypertextes vers d'autres sites. NiangProgrammeur n'exerce aucun contrôle sur ces sites et décline toute responsabilité quant à leur contenu.
        </p>
    </div>

    <div class="legal-card" id="section-5">
        <h2 class="legal-section-title"><i class="fas fa-user-shield"></i> 5. Protection des données personnelles</h2>
        <p class="legal-text">
            Le traitement des données personnelles collectées sur le Site est régi par notre <a href="{{ route('privacy-policy') }}" class="legal-link">Politique de Confidentialité</a>.
        </p>
        <p class="legal-text">
            Conformément à la loi "Informatique et Libertés" du 6 janvier 1978 modifiée et au Règlement Général sur la Protection des Données (RGPD), vous disposez d'un droit d'accès, de rectification, de suppression et d'opposition aux données personnelles vous concernant.
        </p>
    </div>

    <div class="legal-card" id="section-6">
        <h2 class="legal-section-title"><i class="fas fa-cookie-bite"></i> 6. Cookies</h2>
        <p class="legal-text">
            Le Site utilise des cookies pour améliorer l'expérience utilisateur et pour des fins statistiques. Pour plus d'informations sur l'utilisation des cookies, veuillez consulter notre <a href="{{ route('privacy-policy') }}" class="legal-link">Politique de Confidentialité</a>.
        </p>
    </div>

    <div class="legal-card" id="section-7">
        <h2 class="legal-section-title"><i class="fas fa-gavel"></i> 7. Droit applicable et juridiction</h2>
        <p class="legal-text">
            Les présentes mentions légales sont régies par le droit sénégalais. En cas de litige et à défaut d'accord amiable, le litige sera porté devant les tribunaux sénégalais conformément aux règles de compétence en vigueur.
        </p>
    </div>

    <div class="legal-card" id="section-8">
        <h2 class="legal-section-title"><i class="fas fa-envelope"></i> 8. Contact</h2>
        <p class="legal-text">Pour toute question concernant ces mentions légales ou pour exercer vos droits, vous pouvez nous contacter :</p>

        <div class="legal-contact">
            <div class="legal-contact-item">
                <i class="fas fa-envelope"></i>
                <div><strong>Email :</strong> <a href="mailto:{{ $contactEmail }}" class="legal-link">{{ $contactEmail }}</a></div>
            </div>
            <div class="legal-contact-item">
                <i class="fas fa-phone"></i>
                <div><strong>Téléphone :</strong> <a href="tel:+{{ $phoneDigits }}" class="legal-link">{{ $contactPhone }}</a></div>
            </div>
            <div class="legal-contact-item">
                <i class="fab fa-whatsapp"></i>
                <div><strong>WhatsApp :</strong> <a href="https://wa.me/{{ $phoneDigits }}" target="_blank" rel="noopener" class="legal-link">{{ $contactPhone }}</a></div>
            </div>
            <div class="legal-contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <div><strong>Adresse :</strong> {{ $contactAddress }}</div>
            </div>
        </div>
    </div>
</section>
@endsection
