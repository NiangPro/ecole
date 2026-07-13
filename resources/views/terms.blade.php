@extends('layouts.app')

@section('title', 'Conditions d\'Utilisation | NiangProgrammeur')
@section('meta_description', 'Conditions d\'utilisation de NiangProgrammeur : règles d\'usage du site, propriété intellectuelle, responsabilité et coordonnées de contact.')
@push('meta')
    <link rel="canonical" href="{{ route('terms') }}">
@endpush

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
            <div class="legal-icon"><i class="fas fa-file-contract"></i></div>
        </div>
        <h1>Conditions d'Utilisation</h1>
        <p>Les règles d'usage de la plateforme NiangProgrammeur</p>
    </div>
    <nav class="legal-toc" aria-label="Sommaire des conditions d'utilisation">
        <a href="#section-1">1. Acceptation</a>
        <a href="#section-2">2. Le service</a>
        <a href="#section-3">3. Utilisation du site</a>
        <a href="#section-4">4. Propriété intellectuelle</a>
        <a href="#section-5">5. Contenu utilisateur</a>
        <a href="#section-6">6. Responsabilité</a>
        <a href="#section-7">7. Liens externes</a>
        <a href="#section-8">8. Publicité</a>
        <a href="#section-9">9. Évolutions du service</a>
        <a href="#section-10">10. Évolutions des conditions</a>
        <a href="#section-11">11. Résiliation</a>
        <a href="#section-12">12. Droit applicable</a>
        <a href="#section-13">13. Divisibilité</a>
        <a href="#section-14">14. Contact</a>
    </nav>
</section>

<!-- Content -->
<section class="legal-section">
    <p class="legal-update" style="text-align:center;">Dernière mise à jour : {{ date('d/m/Y') }}</p>

    <div class="legal-card" id="section-1">
        <h2 class="legal-section-title"><i class="fas fa-circle-check"></i> 1. Acceptation des conditions</h2>
        <p class="legal-text">
            En accédant et en utilisant le site web NiangProgrammeur (ci-après "le Site"), vous acceptez d'être lié par les présentes conditions d'utilisation, toutes les lois et réglementations applicables. Si vous n'acceptez pas l'une de ces conditions, vous n'êtes pas autorisé à utiliser ou à accéder à ce Site.
        </p>
    </div>

    <div class="legal-card" id="section-2">
        <h2 class="legal-section-title"><i class="fas fa-info-circle"></i> 2. Description du service</h2>
        <p class="legal-text">
            NiangProgrammeur est une plateforme éducative qui propose des formations gratuites en développement web, incluant HTML5, CSS3, JavaScript, PHP, Bootstrap, Git, WordPress et Intelligence Artificielle. Nous fournissons du contenu éducatif, des tutoriels et des ressources pour aider les apprenants à développer leurs compétences en programmation.
        </p>
    </div>

    <div class="legal-card" id="section-3">
        <h2 class="legal-section-title"><i class="fas fa-hand-pointer"></i> 3. Utilisation du site</h2>
        <h3 class="legal-subtitle">3.1 Utilisation autorisée</h3>
        <p class="legal-text">Vous pouvez utiliser notre Site pour :</p>
        <ul class="legal-list">
            <li>Accéder aux formations et tutoriels gratuits</li>
            <li>Lire et apprendre du contenu éducatif</li>
            <li>Nous contacter pour des questions ou demandes</li>
            <li>Partager le contenu sur les réseaux sociaux (avec attribution)</li>
        </ul>

        <h3 class="legal-subtitle">3.2 Utilisation interdite</h3>
        <p class="legal-text">Vous vous engagez à ne pas :</p>
        <ul class="legal-list">
            <li>Utiliser le Site à des fins illégales ou non autorisées</li>
            <li>Copier, reproduire ou distribuer le contenu sans autorisation</li>
            <li>Tenter d'accéder à des zones non autorisées du Site</li>
            <li>Transmettre des virus, malwares ou tout code malveillant</li>
            <li>Harceler, menacer ou nuire à d'autres utilisateurs</li>
            <li>Utiliser des robots, scrapers ou autres moyens automatisés</li>
            <li>Se faire passer pour une autre personne ou entité</li>
        </ul>
    </div>

    <div class="legal-card" id="section-4">
        <h2 class="legal-section-title"><i class="fas fa-copyright"></i> 4. Propriété intellectuelle</h2>
        <h3 class="legal-subtitle">4.1 Droits d'auteur</h3>
        <p class="legal-text">
            Tout le contenu présent sur le Site, y compris mais sans s'y limiter, les textes, graphiques, logos, images, clips audio, téléchargements numériques et compilations de données, est la propriété de NiangProgrammeur ou de ses fournisseurs de contenu et est protégé par les lois internationales sur le droit d'auteur.
        </p>

        <h3 class="legal-subtitle">4.2 Licence d'utilisation</h3>
        <p class="legal-text">
            Nous vous accordons une licence limitée, non exclusive, non transférable et révocable pour accéder et utiliser le Site à des fins personnelles et éducatives uniquement. Cette licence ne vous donne pas le droit de revendre ou d'utiliser commercialement le contenu du Site.
        </p>
    </div>

    <div class="legal-card" id="section-5">
        <h2 class="legal-section-title"><i class="fas fa-comments"></i> 5. Contenu utilisateur</h2>
        <p class="legal-text">
            Si vous nous soumettez du contenu (commentaires, suggestions, messages via le formulaire de contact), vous nous accordez une licence mondiale, non exclusive, libre de redevances pour utiliser, reproduire et afficher ce contenu.
        </p>
        <p class="legal-text">
            Vous garantissez que tout contenu que vous soumettez ne viole aucun droit de tiers et ne contient aucun contenu illégal, diffamatoire ou inapproprié.
        </p>
    </div>

    <div class="legal-card" id="section-6">
        <h2 class="legal-section-title"><i class="fas fa-triangle-exclamation"></i> 6. Limitation de responsabilité</h2>
        <h3 class="legal-subtitle">6.1 Contenu "tel quel"</h3>
        <p class="legal-text">
            Le Site et son contenu sont fournis "tels quels" sans garantie d'aucune sorte, expresse ou implicite. Nous ne garantissons pas que le Site sera ininterrompu, sécurisé ou exempt d'erreurs.
        </p>

        <h3 class="legal-subtitle">6.2 Exclusion de responsabilité</h3>
        <p class="legal-text">
            En aucun cas NiangProgrammeur ne sera responsable des dommages directs, indirects, accessoires, spéciaux ou consécutifs résultant de l'utilisation ou de l'impossibilité d'utiliser le Site, même si nous avons été informés de la possibilité de tels dommages.
        </p>
    </div>

    <div class="legal-card" id="section-7">
        <h2 class="legal-section-title"><i class="fas fa-arrow-up-right-from-square"></i> 7. Liens externes</h2>
        <p class="legal-text">
            Le Site peut contenir des liens vers des sites web tiers. Ces liens sont fournis uniquement pour votre commodité. Nous n'avons aucun contrôle sur ces sites et n'assumons aucune responsabilité quant à leur contenu ou leurs pratiques de confidentialité.
        </p>
    </div>

    <div class="legal-card" id="section-8">
        <h2 class="legal-section-title"><i class="fas fa-rectangle-ad"></i> 8. Publicité</h2>
        <p class="legal-text">
            Le Site utilise Google AdSense pour afficher des publicités. En utilisant le Site, vous acceptez que des publicités tierces puissent être affichées. Ces publicités peuvent utiliser des cookies et d'autres technologies de suivi. Consultez notre <a href="{{ route('privacy-policy') }}" class="legal-link">Politique de Confidentialité</a> pour plus d'informations.
        </p>
    </div>

    <div class="legal-card" id="section-9">
        <h2 class="legal-section-title"><i class="fas fa-arrows-rotate"></i> 9. Modifications du service</h2>
        <p class="legal-text">
            Nous nous réservons le droit de modifier, suspendre ou interrompre tout ou partie du Site à tout moment, avec ou sans préavis. Nous ne serons pas responsables envers vous ou envers des tiers pour toute modification, suspension ou interruption du Site.
        </p>
    </div>

    <div class="legal-card" id="section-10">
        <h2 class="legal-section-title"><i class="fas fa-pen"></i> 10. Modifications des conditions</h2>
        <p class="legal-text">
            Nous nous réservons le droit de modifier ces conditions d'utilisation à tout moment. Les modifications entreront en vigueur dès leur publication sur le Site. Votre utilisation continue du Site après la publication des modifications constitue votre acceptation de ces modifications.
        </p>
    </div>

    <div class="legal-card" id="section-11">
        <h2 class="legal-section-title"><i class="fas fa-ban"></i> 11. Résiliation</h2>
        <p class="legal-text">
            Nous nous réservons le droit de résilier ou de suspendre votre accès au Site immédiatement, sans préavis ni responsabilité, pour quelque raison que ce soit, y compris, sans limitation, si vous violez les présentes conditions d'utilisation.
        </p>
    </div>

    <div class="legal-card" id="section-12">
        <h2 class="legal-section-title"><i class="fas fa-gavel"></i> 12. Droit applicable</h2>
        <p class="legal-text">
            Les présentes conditions d'utilisation sont régies et interprétées conformément aux lois du Sénégal. Tout litige découlant de ces conditions sera soumis à la juridiction exclusive des tribunaux sénégalais.
        </p>
    </div>

    <div class="legal-card" id="section-13">
        <h2 class="legal-section-title"><i class="fas fa-puzzle-piece"></i> 13. Divisibilité</h2>
        <p class="legal-text">
            Si une disposition des présentes conditions est jugée invalide ou inapplicable, cette disposition sera limitée ou éliminée dans la mesure minimale nécessaire, et les autres dispositions resteront pleinement en vigueur.
        </p>
    </div>

    <div class="legal-card" id="section-14">
        <h2 class="legal-section-title"><i class="fas fa-envelope"></i> 14. Contact</h2>
        <p class="legal-text">Pour toute question concernant ces conditions d'utilisation, veuillez nous contacter :</p>

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
