@extends('layouts.app')

@section('title', 'Conditions d\'Utilisation | NiangProgrammeur')
@section('meta_description', 'Conditions d\'utilisation de NiangProgrammeur : formations, documents et cours payants, propriété intellectuelle, responsabilité et coordonnées de contact.')
@section('canonical', route('terms'))

@section('styles')
<style>
    body { overflow-x: hidden; }

    body:not(.dark-mode) { background: #ffffff !important; }
    body.dark-mode { background: #0a0a0f !important; }

    /* ── Progress bar ─────────────────────────────────────── */
    #legalProgress {
        position: fixed; top: 0; left: 0; height: 3px;
        background: linear-gradient(90deg, #06b6d4, #14b8a6);
        width: 0%; z-index: 1000;
        transition: width 0.1s ease;
    }

    /* ── Hero ─────────────────────────────────────────────── */
    .legal-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
        padding: 120px 20px 60px;
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
        top: -50%; right: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        animation: legal-rotate 20s linear infinite;
    }

    @keyframes legal-rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    @keyframes legal-float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
    @keyframes legal-shimmer { to { background-position: 200% center; } }

    .legal-hero-content { position: relative; z-index: 1; max-width: 900px; margin: 0 auto; }

    .legal-icon-wrapper { display: inline-block; margin-bottom: 25px; animation: legal-float 3s ease-in-out infinite; }

    .legal-icon {
        width: 90px; height: 90px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 2.6rem; color: #000;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.3);
    }

    .legal-hero h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 3.2rem; font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
        background-size: 200% auto;
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        animation: legal-shimmer 3s linear infinite;
        margin-bottom: 16px;
    }

    .legal-hero p {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 400;
        margin-bottom: 10px;
    }

    body:not(.dark-mode) .legal-hero p { color: rgba(30, 41, 59, 0.7) !important; }

    .legal-hero-actions { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-top: 20px; }

    .legal-hero-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 20px; border-radius: 999px;
        background: rgba(255, 255, 255, 0.06);
        border: 1.5px solid rgba(6, 182, 212, 0.35);
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.85rem; font-weight: 600;
        text-decoration: none; cursor: pointer;
        transition: all 0.2s ease;
    }

    .legal-hero-btn:hover { background: #06b6d4; border-color: #06b6d4; color: #04141a; transform: translateY(-2px); }

    body:not(.dark-mode) .legal-hero-btn { background: rgba(255, 255, 255, 0.7); color: rgba(30, 41, 59, 0.8); }

    /* ── Layout: sidebar + content ────────────────────────── */
    .legal-layout {
        max-width: 1180px; margin: 0 auto;
        padding: 50px 20px 90px;
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 40px;
        align-items: start;
    }

    @media (max-width: 900px) {
        .legal-layout { grid-template-columns: 1fr; }
        .legal-sidebar { display: none; }
    }

    /* ── Sidebar TOC ──────────────────────────────────────── */
    .legal-sidebar { position: sticky; top: 90px; max-height: calc(100vh - 110px); overflow-y: auto; }

    .legal-sidebar-title {
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;
        color: rgba(255, 255, 255, 0.4);
        margin-bottom: 14px;
    }

    body:not(.dark-mode) .legal-sidebar-title { color: rgba(30, 41, 59, 0.5) !important; }

    .legal-sidebar a {
        display: block;
        padding: 8px 12px;
        border-left: 2px solid rgba(6, 182, 212, 0.15);
        color: rgba(255, 255, 255, 0.55);
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    body:not(.dark-mode) .legal-sidebar a { color: rgba(30, 41, 59, 0.6) !important; }

    .legal-sidebar a:hover { color: #06b6d4; border-left-color: rgba(6, 182, 212, 0.5); }

    .legal-sidebar a.is-active {
        color: #06b6d4 !important;
        border-left-color: #06b6d4;
        background: rgba(6, 182, 212, 0.06);
        font-weight: 700;
    }

    /* ── Mobile TOC pills ─────────────────────────────────── */
    .legal-toc-mobile {
        display: none;
        flex-wrap: wrap; gap: 8px; justify-content: center;
        max-width: 1000px; margin: 0 auto; padding: 25px 20px 0;
    }

    @media (max-width: 900px) { .legal-toc-mobile { display: flex; } }

    .legal-toc-mobile a {
        padding: 8px 16px; border-radius: 999px;
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.25);
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.8rem; font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
    }

    body:not(.dark-mode) .legal-toc-mobile a { color: rgba(30, 41, 59, 0.8) !important; background: rgba(6, 182, 212, 0.06); }

    /* ── Section cards ────────────────────────────────────── */
    .legal-content { min-width: 0; }

    .legal-update {
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.85rem;
        margin-bottom: 24px;
    }

    body:not(.dark-mode) .legal-update { color: rgba(30, 41, 59, 0.55) !important; }

    .legal-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 22px;
        padding: 40px;
        margin-bottom: 22px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        scroll-margin-top: 100px;
    }

    body:not(.dark-mode) .legal-card { background: rgba(255, 255, 255, 0.9) !important; border-color: rgba(6, 182, 212, 0.25) !important; }

    .legal-card:hover { border-color: rgba(6, 182, 212, 0.4); box-shadow: 0 15px 40px rgba(6, 182, 212, 0.15); }

    .legal-card.is-highlight { border-color: rgba(20, 184, 166, 0.4); background: linear-gradient(160deg, rgba(20, 184, 166, 0.08), rgba(15, 23, 42, 0.7) 60%); }
    body:not(.dark-mode) .legal-card.is-highlight { background: linear-gradient(160deg, rgba(20, 184, 166, 0.06), rgba(255, 255, 255, 0.9) 60%) !important; }

    .legal-section-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.4rem; font-weight: 800;
        color: #06b6d4;
        margin-bottom: 18px;
        display: flex; align-items: center; gap: 14px;
    }

    body:not(.dark-mode) .legal-section-title { color: #06b6d4 !important; }

    .legal-num-badge {
        width: 36px; height: 36px; flex-shrink: 0;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border: 1px solid rgba(6, 182, 212, 0.3);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.95rem; font-weight: 800;
    }

    .legal-subtitle {
        font-family: 'Poppins', sans-serif;
        font-size: 1.08rem; font-weight: 700;
        color: rgba(255, 255, 255, 0.9);
        margin-top: 18px; margin-bottom: 10px;
    }

    body:not(.dark-mode) .legal-subtitle { color: rgba(30, 41, 59, 0.9) !important; }

    .legal-text {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.85;
        font-size: 0.98rem;
        margin-bottom: 14px;
    }

    body:not(.dark-mode) .legal-text { color: rgba(30, 41, 59, 0.8) !important; }

    .legal-list { list-style: none; padding-left: 0; margin-bottom: 14px; }

    .legal-list li {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.85;
        font-size: 0.98rem;
        margin-bottom: 8px;
        padding-left: 26px;
        position: relative;
    }

    body:not(.dark-mode) .legal-list li { color: rgba(30, 41, 59, 0.8) !important; }

    .legal-list li::before { content: '▸'; position: absolute; left: 0; color: #06b6d4; font-weight: 700; }

    .legal-link { color: #06b6d4; text-decoration: underline; transition: all 0.3s ease; }
    .legal-link:hover { color: #14b8a6; text-decoration: none; }

    .legal-note {
        display: flex; gap: 12px;
        background: rgba(245, 158, 11, 0.08);
        border: 1px solid rgba(245, 158, 11, 0.3);
        border-radius: 12px;
        padding: 14px 18px;
        margin-top: 14px;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.75);
    }

    body:not(.dark-mode) .legal-note { color: rgba(30, 41, 59, 0.8) !important; }
    .legal-note i { color: #f59e0b; flex-shrink: 0; margin-top: 2px; }

    /* ── Contact card ─────────────────────────────────────── */
    .legal-contact {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 18px;
        padding: 32px;
        margin-top: 8px;
    }

    body:not(.dark-mode) .legal-contact { background: linear-gradient(135deg, rgba(6, 182, 212, 0.05), rgba(20, 184, 166, 0.05)) !important; border-color: rgba(6, 182, 212, 0.25) !important; }

    .legal-contact-item { display: flex; align-items: center; gap: 15px; margin-bottom: 14px; color: rgba(255, 255, 255, 0.8); font-size: 1rem; }
    .legal-contact-item:last-child { margin-bottom: 0; }
    body:not(.dark-mode) .legal-contact-item { color: rgba(30, 41, 59, 0.8) !important; }

    .legal-contact-item i { color: #06b6d4; font-size: 1.2rem; width: 26px; flex-shrink: 0; }
    .legal-contact-item strong { color: #06b6d4; margin-right: 8px; }

    /* ── Back to top ──────────────────────────────────────── */
    #legalBackTop {
        position: fixed; bottom: 30px; right: 30px;
        width: 46px; height: 46px; border-radius: 50%;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #04141a;
        display: none; align-items: center; justify-content: center;
        font-size: 1.1rem; cursor: pointer;
        box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
        border: none; z-index: 500;
        transition: transform 0.2s ease;
    }

    #legalBackTop:hover { transform: translateY(-3px); }
    #legalBackTop.is-visible { display: flex; }

    @media print {
        .faq-hero, .legal-hero-actions, .legal-toc-mobile, .legal-sidebar, #legalProgress, #legalBackTop { display: none !important; }
    }

    @media (max-width: 768px) {
        .legal-hero { padding: 100px 20px 40px; }
        .legal-hero h1 { font-size: 2.2rem; }
        .legal-hero p { font-size: 0.98rem; }
        .legal-layout { padding: 30px 16px 60px; }
        .legal-card { padding: 24px 18px; }
        .legal-section-title { font-size: 1.15rem; }
        .legal-subtitle { font-size: 1rem; }
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

    $sections = [
        '1'  => '1. Acceptation',
        '2'  => '2. Le service',
        '3'  => '3. Utilisation du site',
        '4'  => '4. Achats & contenus payants',
        '5'  => '5. Propriété intellectuelle',
        '6'  => '6. Contenu utilisateur',
        '7'  => '7. Responsabilité',
        '8'  => '8. Liens externes',
        '9'  => '9. Publicité',
        '10' => '10. Évolutions du service',
        '11' => '11. Évolutions des conditions',
        '12' => '12. Résiliation',
        '13' => '13. Droit applicable',
        '14' => '14. Divisibilité',
        '15' => '15. Contact',
    ];
@endphp

<div id="legalProgress"></div>

<!-- Hero -->
<section class="legal-hero">
    <div class="legal-hero-content">
        <div class="legal-icon-wrapper">
            <div class="legal-icon"><i class="fas fa-file-contract"></i></div>
        </div>
        <h1>Conditions d'Utilisation</h1>
        <p>Les règles d'usage de la plateforme NiangProgrammeur : formations, documents, épreuves, cours payants et abonnements.</p>
        <div class="legal-hero-actions">
            <button type="button" class="legal-hero-btn" onclick="window.print()"><i class="fas fa-print"></i> Imprimer</button>
            <a href="{{ route('privacy-policy') }}" class="legal-hero-btn"><i class="fas fa-user-shield"></i> Politique de confidentialité</a>
        </div>
    </div>
</section>

<nav class="legal-toc-mobile" aria-label="Sommaire des conditions d'utilisation">
    @foreach($sections as $id => $label)
    <a href="#section-{{ $id }}">{{ $label }}</a>
    @endforeach
</nav>

<div class="legal-layout">

    <!-- Sidebar (desktop) -->
    <aside class="legal-sidebar">
        <div class="legal-sidebar-title">Sommaire</div>
        <nav id="legalSidebarNav" aria-label="Sommaire des conditions d'utilisation">
            @foreach($sections as $id => $label)
            <a href="#section-{{ $id }}" data-target="section-{{ $id }}">{{ $label }}</a>
            @endforeach
        </nav>
    </aside>

    <!-- Content -->
    <div class="legal-content">
        <p class="legal-update">Dernière mise à jour : {{ date('d/m/Y') }}</p>

        <div class="legal-card" id="section-1">
            <h2 class="legal-section-title"><span class="legal-num-badge">1</span> Acceptation des conditions</h2>
            <p class="legal-text">
                En accédant et en utilisant le site web NiangProgrammeur (ci-après "le Site"), vous acceptez d'être lié par les présentes conditions d'utilisation, toutes les lois et réglementations applicables. Si vous n'acceptez pas l'une de ces conditions, vous n'êtes pas autorisé à utiliser ou à accéder à ce Site.
            </p>
        </div>

        <div class="legal-card" id="section-2">
            <h2 class="legal-section-title"><span class="legal-num-badge">2</span> Description du service</h2>
            <p class="legal-text">
                NiangProgrammeur est une plateforme éducative sénégalaise proposant :
            </p>
            <ul class="legal-list">
                <li>Des <strong>formations gratuites</strong> en développement web et informatique (HTML5, CSS3, JavaScript, PHP, Python, Java et bien d'autres), avec exercices pratiques et quiz interactifs</li>
                <li>Des <strong>documents téléchargeables</strong> (fiches de cours, papiers administratifs), gratuits ou payants, ainsi que des packs groupés</li>
                <li>Des <strong>épreuves et corrigés d'examens</strong> classés par niveau et matière</li>
                <li>Des <strong>cours payants</strong> et un <strong>abonnement Premium</strong> offrant un contenu approfondi et un certificat à la complétion</li>
                <li>Un système de <strong>badges</strong> valorisant la progression des apprenants</li>
                <li>Un <strong>forum communautaire</strong> d'entraide entre apprenants</li>
                <li>Une section <strong>Emplois</strong> (offres, stages, bourses, concours)</li>
                <li>Des options de <strong>dons</strong> et un <strong>programme d'affiliation</strong></li>
            </ul>
        </div>

        <div class="legal-card" id="section-3">
            <h2 class="legal-section-title"><span class="legal-num-badge">3</span> Utilisation du site</h2>
            <h3 class="legal-subtitle">3.1 Utilisation autorisée</h3>
            <p class="legal-text">Vous pouvez utiliser notre Site pour :</p>
            <ul class="legal-list">
                <li>Accéder aux formations, exercices et quiz gratuits</li>
                <li>Consulter et acheter des documents, épreuves ou cours proposés sur le Site</li>
                <li>Participer au forum communautaire dans le respect des règles de courtoisie</li>
                <li>Nous contacter pour des questions ou demandes</li>
                <li>Partager le contenu sur les réseaux sociaux (avec attribution)</li>
            </ul>

            <h3 class="legal-subtitle">3.2 Utilisation interdite</h3>
            <p class="legal-text">Vous vous engagez à ne pas :</p>
            <ul class="legal-list">
                <li>Utiliser le Site à des fins illégales ou non autorisées</li>
                <li>Copier, reproduire, revendre ou redistribuer le contenu (formations, documents, épreuves) sans autorisation</li>
                <li>Partager votre compte, vos accès ou vos documents/cours achetés avec des tiers non autorisés</li>
                <li>Tenter d'accéder à des zones non autorisées du Site</li>
                <li>Transmettre des virus, malwares ou tout code malveillant</li>
                <li>Harceler, menacer ou nuire à d'autres utilisateurs, y compris sur le forum</li>
                <li>Utiliser des robots, scrapers ou autres moyens automatisés</li>
                <li>Se faire passer pour une autre personne ou entité</li>
            </ul>
        </div>

        <div class="legal-card is-highlight" id="section-4">
            <h2 class="legal-section-title"><span class="legal-num-badge">4</span> Achats et contenus payants</h2>
            <h3 class="legal-subtitle">4.1 Paiement</h3>
            <p class="legal-text">
                Les documents, packs, épreuves/corrigés, cours et abonnements payants sont réglables via les moyens de paiement proposés sur le Site (notamment Wave, Orange Money et/ou carte bancaire selon disponibilité). L'accès au contenu numérique acheté est généralement fourni immédiatement après confirmation du paiement.
            </p>

            <h3 class="legal-subtitle">4.2 Politique de remboursement</h3>
            <p class="legal-text">
                S'agissant de contenus numériques immédiatement accessibles après achat, les commandes ne sont, sauf erreur technique de notre part ou disposition légale contraire, pas remboursables une fois le contenu consulté ou téléchargé. En cas de problème (paiement débité sans accès accordé, document corrompu, etc.), contactez-nous : nous traiterons votre demande au cas par cas.
            </p>

            <h3 class="legal-subtitle">4.3 Abonnements</h3>
            <p class="legal-text">
                L'abonnement Premium se renouvelle selon la périodicité choisie lors de la souscription. Vous pouvez le résilier à tout moment depuis votre espace personnel ; la résiliation prend effet à la fin de la période déjà payée.
            </p>

            <div class="legal-note">
                <i class="fas fa-triangle-exclamation"></i>
                <span>Cette section décrit nos pratiques générales de vente de contenus numériques. Pour toute question sur une commande précise, contactez-nous directement via la section 15.</span>
            </div>
        </div>

        <div class="legal-card" id="section-5">
            <h2 class="legal-section-title"><span class="legal-num-badge">5</span> Propriété intellectuelle</h2>
            <h3 class="legal-subtitle">5.1 Droits d'auteur</h3>
            <p class="legal-text">
                Tout le contenu présent sur le Site (textes, graphiques, logos, images, documents PDF, épreuves, corrigés, vidéos et compilations de données) est la propriété de NiangProgrammeur ou de ses fournisseurs de contenu et est protégé par les lois internationales sur le droit d'auteur.
            </p>

            <h3 class="legal-subtitle">5.2 Licence d'utilisation</h3>
            <p class="legal-text">
                Nous vous accordons une licence limitée, non exclusive, non transférable et révocable pour accéder et utiliser le Site (y compris les documents et cours achetés) à des fins personnelles et éducatives uniquement. Cette licence ne vous donne pas le droit de revendre, redistribuer ou utiliser commercialement le contenu du Site.
            </p>
        </div>

        <div class="legal-card" id="section-6">
            <h2 class="legal-section-title"><span class="legal-num-badge">6</span> Contenu utilisateur</h2>
            <p class="legal-text">
                Si vous nous soumettez du contenu (commentaires, messages du forum, suggestions, formulaire de contact), vous nous accordez une licence mondiale, non exclusive, libre de redevances pour utiliser, reproduire et afficher ce contenu dans le cadre du fonctionnement du Site.
            </p>
            <p class="legal-text">
                Vous garantissez que tout contenu que vous soumettez ne viole aucun droit de tiers et ne contient aucun contenu illégal, diffamatoire ou inapproprié. Les publications du forum contraires à ces règles pourront être modérées ou supprimées.
            </p>
        </div>

        <div class="legal-card" id="section-7">
            <h2 class="legal-section-title"><span class="legal-num-badge">7</span> Limitation de responsabilité</h2>
            <h3 class="legal-subtitle">7.1 Contenu "tel quel"</h3>
            <p class="legal-text">
                Le Site et son contenu sont fournis "tels quels" sans garantie d'aucune sorte, expresse ou implicite. Nous ne garantissons pas que le Site sera ininterrompu, sécurisé ou exempt d'erreurs.
            </p>

            <h3 class="legal-subtitle">7.2 Exclusion de responsabilité</h3>
            <p class="legal-text">
                En aucun cas NiangProgrammeur ne sera responsable des dommages directs, indirects, accessoires, spéciaux ou consécutifs résultant de l'utilisation ou de l'impossibilité d'utiliser le Site, même si nous avons été informés de la possibilité de tels dommages.
            </p>
        </div>

        <div class="legal-card" id="section-8">
            <h2 class="legal-section-title"><span class="legal-num-badge">8</span> Liens externes</h2>
            <p class="legal-text">
                Le Site peut contenir des liens vers des sites web tiers. Ces liens sont fournis uniquement pour votre commodité. Nous n'avons aucun contrôle sur ces sites et n'assumons aucune responsabilité quant à leur contenu ou leurs pratiques de confidentialité.
            </p>
        </div>

        <div class="legal-card" id="section-9">
            <h2 class="legal-section-title"><span class="legal-num-badge">9</span> Publicité</h2>
            <p class="legal-text">
                Le Site utilise Google AdSense pour afficher des publicités. En utilisant le Site, vous acceptez que des publicités tierces puissent être affichées. Ces publicités peuvent utiliser des cookies et d'autres technologies de suivi. Consultez notre <a href="{{ route('privacy-policy') }}" class="legal-link">Politique de Confidentialité</a> pour plus d'informations.
            </p>
        </div>

        <div class="legal-card" id="section-10">
            <h2 class="legal-section-title"><span class="legal-num-badge">10</span> Modifications du service</h2>
            <p class="legal-text">
                Nous nous réservons le droit de modifier, suspendre ou interrompre tout ou partie du Site (y compris les fonctionnalités payantes) à tout moment, avec ou sans préavis. Nous ne serons pas responsables envers vous ou envers des tiers pour toute modification, suspension ou interruption du Site.
            </p>
        </div>

        <div class="legal-card" id="section-11">
            <h2 class="legal-section-title"><span class="legal-num-badge">11</span> Modifications des conditions</h2>
            <p class="legal-text">
                Nous nous réservons le droit de modifier ces conditions d'utilisation à tout moment. Les modifications entreront en vigueur dès leur publication sur le Site. Votre utilisation continue du Site après la publication des modifications constitue votre acceptation de ces modifications.
            </p>
        </div>

        <div class="legal-card" id="section-12">
            <h2 class="legal-section-title"><span class="legal-num-badge">12</span> Résiliation</h2>
            <p class="legal-text">
                Nous nous réservons le droit de résilier ou de suspendre votre accès au Site immédiatement, sans préavis ni responsabilité, pour quelque raison que ce soit, y compris, sans limitation, si vous violez les présentes conditions d'utilisation.
            </p>
        </div>

        <div class="legal-card" id="section-13">
            <h2 class="legal-section-title"><span class="legal-num-badge">13</span> Droit applicable</h2>
            <p class="legal-text">
                Les présentes conditions d'utilisation sont régies et interprétées conformément aux lois du Sénégal. Tout litige découlant de ces conditions sera soumis à la juridiction exclusive des tribunaux sénégalais.
            </p>
        </div>

        <div class="legal-card" id="section-14">
            <h2 class="legal-section-title"><span class="legal-num-badge">14</span> Divisibilité</h2>
            <p class="legal-text">
                Si une disposition des présentes conditions est jugée invalide ou inapplicable, cette disposition sera limitée ou éliminée dans la mesure minimale nécessaire, et les autres dispositions resteront pleinement en vigueur.
            </p>
        </div>

        <div class="legal-card" id="section-15">
            <h2 class="legal-section-title"><span class="legal-num-badge">15</span> Contact</h2>
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
    </div>
</div>

<button type="button" id="legalBackTop" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Retour en haut">
    <i class="fas fa-arrow-up"></i>
</button>
@endsection

@push('scripts')
<script>
(function () {
    const progress = document.getElementById('legalProgress');
    const backTop = document.getElementById('legalBackTop');
    const sidebarLinks = Array.from(document.querySelectorAll('#legalSidebarNav a'));
    const cards = Array.from(document.querySelectorAll('.legal-card'));

    function onScroll() {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        progress.style.width = docHeight > 0 ? (scrollTop / docHeight * 100) + '%' : '0%';
        backTop.classList.toggle('is-visible', scrollTop > 500);
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    if ('IntersectionObserver' in window && sidebarLinks.length) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    sidebarLinks.forEach(a => a.classList.remove('is-active'));
                    const link = sidebarLinks.find(a => a.dataset.target === entry.target.id);
                    if (link) link.classList.add('is-active');
                }
            });
        }, { rootMargin: '-20% 0px -70% 0px' });

        cards.forEach(card => observer.observe(card));
    }
})();
</script>
@endpush
