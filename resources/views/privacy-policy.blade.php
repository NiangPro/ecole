@extends('layouts.app')

@section('title', 'Politique de Confidentialité | NiangProgrammeur')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap');
    
    body {
        overflow-x: hidden;
    }
    
    /* Body background for privacy-policy page */
    body:not(.dark-mode) {
        background: #ffffff !important;
    }
    
    body.dark-mode {
        background: #0a0a0f !important;
    }
    
    .privacy-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
        padding: 120px 20px 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    body:not(.dark-mode) .privacy-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.05) 0%, rgba(20, 184, 166, 0.05) 100%) !important;
    }
    
    .privacy-hero::before {
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
    
    .privacy-hero-content {
        position: relative;
        z-index: 1;
        max-width: 900px;
        margin: 0 auto;
    }
    
    .privacy-icon-wrapper {
        display: inline-block;
        margin-bottom: 30px;
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .privacy-icon {
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
    
    .privacy-hero h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 3.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: shimmer 3s linear infinite;
        margin-bottom: 20px;
    }
    
    @keyframes shimmer {
        to { background-position: 200% center; }
    }
    
    .privacy-hero p {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 400;
    }
    
    body:not(.dark-mode) .privacy-hero p {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    .privacy-section {
        padding: 80px 20px;
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .privacy-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 24px;
        padding: 50px;
        margin-bottom: 40px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    body:not(.dark-mode) .privacy-card {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
    }
    
    .privacy-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.6s;
    }
    
    .privacy-card:hover::before {
        left: 100%;
    }
    
    .privacy-card:hover {
        border-color: rgba(6, 182, 212, 0.4);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.2);
    }
    
    .privacy-update {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.9rem;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(6, 182, 212, 0.2);
    }
    
    body:not(.dark-mode) .privacy-update {
        color: rgba(30, 41, 59, 0.6) !important;
    }
    
    .privacy-section-title {
        font-family: 'Poppins', sans-serif;
        font-size: 2rem;
        font-weight: 800;
        color: #06b6d4;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .privacy-section-title i {
        font-size: 1.5rem;
    }
    
    .privacy-subtitle {
        font-family: 'Poppins', sans-serif;
        font-size: 1.3rem;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.9);
        margin-top: 25px;
        margin-bottom: 15px;
    }
    
    body:not(.dark-mode) .privacy-subtitle {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    .privacy-text {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.9;
        font-size: 1rem;
        margin-bottom: 20px;
    }
    
    body:not(.dark-mode) .privacy-text {
        color: rgba(30, 41, 59, 0.8) !important;
    }
    
    .privacy-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 25px;
    }
    
    .privacy-list li {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.9;
        font-size: 1rem;
        margin-bottom: 12px;
        padding-left: 30px;
        position: relative;
    }
    
    body:not(.dark-mode) .privacy-list li {
        color: rgba(30, 41, 59, 0.8) !important;
    }
    
    .privacy-list li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: #06b6d4;
        font-weight: 700;
        font-size: 1.2rem;
    }
    
    .privacy-link {
        color: #06b6d4;
        text-decoration: underline;
        transition: all 0.3s ease;
    }
    
    .privacy-link:hover {
        color: #14b8a6;
        text-decoration: none;
    }
    
    .privacy-contact {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 20px;
        padding: 40px;
        margin-top: 40px;
        position: relative;
        overflow: hidden;
    }
    
    body:not(.dark-mode) .privacy-contact {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.05), rgba(20, 184, 166, 0.05)) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
    }
    
    .privacy-contact::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        animation: rotate 15s linear infinite;
    }
    
    .privacy-contact-content {
        position: relative;
        z-index: 1;
    }
    
    .privacy-contact-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.1rem;
    }
    
    body:not(.dark-mode) .privacy-contact-item {
        color: rgba(30, 41, 59, 0.8) !important;
    }
    
    /* Force text colors in light mode */
    body:not(.dark-mode) h1,
    body:not(.dark-mode) h2,
    body:not(.dark-mode) h3,
    body:not(.dark-mode) p,
    body:not(.dark-mode) li,
    body:not(.dark-mode) strong {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    body:not(.dark-mode) .privacy-hero h1 {
        -webkit-text-fill-color: transparent !important;
    }
    
    body:not(.dark-mode) .privacy-section-title {
        color: #06b6d4 !important;
    }
    
    .privacy-contact-item i {
        color: #06b6d4;
        font-size: 1.3rem;
        width: 30px;
    }
    
    .privacy-contact-item strong {
        color: #06b6d4;
        margin-right: 10px;
    }
    
    @media (max-width: 768px) {
        .privacy-hero {
            padding: 100px 20px 60px;
        }
        
        .privacy-hero h1 {
            font-size: 2.5rem;
        }
        
        .privacy-hero p {
            font-size: 1rem;
        }
        
        .privacy-card {
            padding: 30px 20px;
        }
        
        .privacy-section-title {
            font-size: 1.5rem;
        }
        
        .privacy-subtitle {
            font-size: 1.1rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="privacy-hero">
    <div class="privacy-hero-content">
        <div class="privacy-icon-wrapper">
            <div class="privacy-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
        </div>
        <h1>Politique de Confidentialité</h1>
        <p>Protection de vos données personnelles sur NiangProgrammeur</p>
    </div>
</section>

<!-- Privacy Policy Content -->
<section class="privacy-section">
    <div class="privacy-card">
        <p class="privacy-update">Dernière mise à jour : {{ date('d/m/Y') }}</p>
        
        <h2 class="privacy-section-title">
            <i class="fas fa-info-circle"></i>
            1. Introduction
        </h2>
        <p class="privacy-text">
            Chez <strong>NiangProgrammeur</strong> (ci-après "nous", "notre" ou "nos"), nous respectons votre vie privée et nous nous engageons à protéger vos données personnelles. Cette politique de confidentialité explique comment nous collectons, utilisons, partageons et protégeons vos informations personnelles lorsque vous utilisez notre site web <strong>NiangProgrammeur</strong>.
        </p>
        <p class="privacy-text">
            En utilisant notre site, vous acceptez les pratiques décrites dans cette politique de confidentialité. Si vous n'acceptez pas cette politique, veuillez ne pas utiliser notre site.
        </p>
    </div>

    <div class="privacy-card">
        <h2 class="privacy-section-title">
            <i class="fas fa-database"></i>
            2. Informations que nous collectons
        </h2>
        
        <h3 class="privacy-subtitle">2.1 Informations fournies volontairement</h3>
        <p class="privacy-text">
            Lorsque vous nous contactez via notre formulaire de contact, vous vous abonnez à notre newsletter, ou vous interagissez avec nos services, nous pouvons collecter les informations suivantes :
        </p>
        <ul class="privacy-list">
            <li>Votre nom complet</li>
            <li>Votre adresse e-mail</li>
            <li>Votre numéro de téléphone (si fourni)</li>
            <li>Votre message ou demande</li>
            <li>Votre adresse IP (pour la sécurité)</li>
        </ul>

        <h3 class="privacy-subtitle">2.2 Informations collectées automatiquement</h3>
        <p class="privacy-text">
            Lorsque vous visitez notre site <strong>NiangProgrammeur</strong>, nous collectons automatiquement certaines informations techniques, notamment :
        </p>
        <ul class="privacy-list">
            <li>Votre adresse IP</li>
            <li>Le type de navigateur et la version</li>
            <li>Le système d'exploitation</li>
            <li>Les pages visitées et le temps passé sur chaque page</li>
            <li>La date et l'heure de votre visite</li>
            <li>Les sites web référents (d'où vous venez)</li>
            <li>Votre pays et ville (géolocalisation approximative)</li>
        </ul>
        <p class="privacy-text">
            Ces informations sont collectées de manière anonyme et utilisées uniquement pour améliorer notre site et comprendre comment nos visiteurs utilisent nos services.
        </p>
    </div>

    <div class="privacy-card">
        <h2 class="privacy-section-title">
            <i class="fas fa-cogs"></i>
            3. Utilisation de vos informations
        </h2>
        <p class="privacy-text">Nous utilisons les informations collectées pour les finalités suivantes :</p>
        <ul class="privacy-list">
            <li><strong>Répondre à vos demandes</strong> : Traiter et répondre à vos questions et demandes via notre formulaire de contact</li>
            <li><strong>Améliorer notre site</strong> : Analyser l'utilisation de notre site pour améliorer nos services et votre expérience utilisateur</li>
            <li><strong>Newsletter</strong> : Vous envoyer des informations sur nos nouvelles formations, articles et actualités (uniquement si vous vous êtes abonné)</li>
            <li><strong>Sécurité</strong> : Détecter et prévenir la fraude, les abus et autres activités illégales</li>
            <li><strong>Obligations légales</strong> : Respecter nos obligations légales et réglementaires</li>
            <li><strong>Statistiques</strong> : Générer des statistiques anonymes sur l'utilisation de notre site</li>
        </ul>
    </div>

    <div class="privacy-card">
        <h2 class="privacy-section-title">
            <i class="fas fa-cookie-bite"></i>
            4. Cookies et technologies similaires
        </h2>
        <p class="privacy-text">
            Nous utilisons des cookies et des technologies similaires pour améliorer votre expérience sur notre site <strong>NiangProgrammeur</strong>. Les cookies sont de petits fichiers texte stockés sur votre appareil lorsque vous visitez notre site.
        </p>
        
        <h3 class="privacy-subtitle">4.1 Types de cookies utilisés</h3>
        <ul class="privacy-list">
            <li><strong>Cookies essentiels</strong> : Nécessaires au fonctionnement du site (ex: préférences de langue)</li>
            <li><strong>Cookies analytiques</strong> : Nous aident à comprendre comment les visiteurs utilisent notre site</li>
            <li><strong>Cookies publicitaires</strong> : Utilisés pour afficher des publicités pertinentes (Google AdSense)</li>
        </ul>
        
        <h3 class="privacy-subtitle">4.2 Gestion des cookies</h3>
        <p class="privacy-text">
            Vous pouvez configurer votre navigateur pour refuser les cookies ou être averti lorsque des cookies sont envoyés. Cependant, cela peut affecter certaines fonctionnalités du site. Pour en savoir plus sur la gestion des cookies, consultez les paramètres de votre navigateur.
        </p>
    </div>

    <div class="privacy-card">
        <h2 class="privacy-section-title">
            <i class="fas fa-ad"></i>
            5. Google AdSense et Publicités
        </h2>
        
        <h3 class="privacy-subtitle">5.1 Utilisation de Google AdSense</h3>
        <p class="privacy-text">
            Notre site <strong>NiangProgrammeur</strong> utilise Google AdSense, un service de publicité fourni par Google LLC. Google AdSense utilise des cookies et des technologies similaires pour diffuser des annonces personnalisées basées sur vos visites précédentes sur notre site ou d'autres sites web.
        </p>
        
        <h3 class="privacy-subtitle">5.2 Données collectées par Google AdSense</h3>
        <p class="privacy-text">
            Google AdSense peut collecter et utiliser les informations suivantes :
        </p>
        <ul class="privacy-list">
            <li>Cookies et identifiants d'appareil</li>
            <li>Adresse IP</li>
            <li>Type de navigateur et système d'exploitation</li>
            <li>Pages visitées et temps passé sur le site</li>
            <li>Interactions avec les annonces (clics, impressions)</li>
            <li>Intérêts et préférences publicitaires</li>
        </ul>
        
        <h3 class="privacy-subtitle">5.3 Publicité personnalisée</h3>
        <p class="privacy-text">
            Google utilise ces informations pour :
        </p>
        <ul class="privacy-list">
            <li>Diffuser des annonces pertinentes et personnalisées</li>
            <li>Mesurer l'efficacité des annonces</li>
            <li>Créer des rapports sur les performances publicitaires</li>
            <li>Améliorer l'expérience publicitaire</li>
        </ul>
        
        <h3 class="privacy-subtitle">5.4 Gestion de vos préférences publicitaires</h3>
        <p class="privacy-text">
            Vous pouvez contrôler les annonces que vous voyez de plusieurs façons :
        </p>
        <ul class="privacy-list">
            <li>Visitez les <a href="https://www.google.com/settings/ads" target="_blank" class="privacy-link">Paramètres des annonces Google</a> pour désactiver la personnalisation</li>
            <li>Utilisez le <a href="https://optout.aboutads.info/" target="_blank" class="privacy-link">NAI Consumer Opt-Out</a> pour vous désinscrire de la publicité comportementale</li>
            <li>Configurez votre navigateur pour bloquer les cookies tiers</li>
            <li>Utilisez des extensions de navigateur pour bloquer les publicités</li>
        </ul>
        
        <h3 class="privacy-subtitle">5.5 Politique de confidentialité de Google</h3>
        <p class="privacy-text">
            Pour plus d'informations sur la manière dont Google collecte et utilise vos données, consultez la <a href="https://policies.google.com/privacy" target="_blank" class="privacy-link">Politique de confidentialité de Google</a> et les <a href="https://policies.google.com/technologies/partner-sites" target="_blank" class="privacy-link">Règles de confidentialité des partenaires Google</a>.
        </p>
    </div>

    <div class="privacy-card">
        <h2 class="privacy-section-title">
            <i class="fas fa-share-alt"></i>
            6. Partage de vos informations
        </h2>
        <p class="privacy-text">
            Nous ne vendons, n'échangeons ni ne louons vos informations personnelles à des tiers. Cependant, nous pouvons partager vos informations dans les cas suivants :
        </p>
        <ul class="privacy-list">
            <li><strong>Prestataires de services</strong> : Avec des prestataires de services tiers qui nous aident à exploiter notre site web (hébergement, analyse, email), à condition qu'ils acceptent de garder ces informations confidentielles</li>
            <li><strong>Google AdSense</strong> : Comme décrit dans la section 5, Google AdSense peut collecter et utiliser vos informations conformément à leur politique de confidentialité</li>
            <li><strong>Obligations légales</strong> : Si la loi l'exige ou si nous pensons que cela est nécessaire pour protéger nos droits, votre sécurité ou celle d'autrui</li>
            <li><strong>Avec votre consentement</strong> : Dans tout autre cas, uniquement avec votre consentement explicite</li>
        </ul>
    </div>

    <div class="privacy-card">
        <h2 class="privacy-section-title">
            <i class="fas fa-lock"></i>
            7. Sécurité de vos données
        </h2>
        <p class="privacy-text">
            Nous mettons en œuvre des mesures de sécurité appropriées pour protéger vos informations personnelles contre tout accès, modification, divulgation ou destruction non autorisés. Ces mesures incluent :
        </p>
        <ul class="privacy-list">
            <li>Chiffrement SSL/TLS pour les communications sécurisées</li>
            <li>Accès restreint aux données personnelles (accès uniquement pour les personnes autorisées)</li>
            <li>Surveillance régulière de nos systèmes pour détecter les vulnérabilités</li>
            <li>Sauvegardes régulières de nos données</li>
            <li>Mises à jour de sécurité régulières de nos systèmes</li>
        </ul>
        <p class="privacy-text">
            Cependant, aucune méthode de transmission sur Internet ou de stockage électronique n'est 100% sécurisée. Bien que nous nous efforcions d'utiliser des moyens commercialement acceptables pour protéger vos données, nous ne pouvons garantir leur sécurité absolue.
        </p>
    </div>

    <div class="privacy-card">
        <h2 class="privacy-section-title">
            <i class="fas fa-user-shield"></i>
            8. Vos droits (RGPD)
        </h2>
        <p class="privacy-text">
            Conformément au Règlement Général sur la Protection des Données (RGPD) et aux lois applicables sur la protection des données, vous disposez des droits suivants concernant vos données personnelles :
        </p>
        
        <h3 class="privacy-subtitle">8.1 Droit d'accès</h3>
        <p class="privacy-text">
            Vous avez le droit d'obtenir la confirmation que vos données personnelles sont traitées et d'accéder à ces données, ainsi qu'à des informations sur leur traitement.
        </p>
        
        <h3 class="privacy-subtitle">8.2 Droit de rectification</h3>
        <p class="privacy-text">
            Vous avez le droit de demander la correction de vos données personnelles inexactes ou incomplètes. Nous corrigerons vos données dans les plus brefs délais.
        </p>
        
        <h3 class="privacy-subtitle">8.3 Droit à l'effacement ("droit à l'oubli")</h3>
        <p class="privacy-text">
            Vous avez le droit de demander la suppression de vos données personnelles dans certaines circonstances, notamment si elles ne sont plus nécessaires aux fins pour lesquelles elles ont été collectées.
        </p>
        
        <h3 class="privacy-subtitle">8.4 Droit à la limitation du traitement</h3>
        <p class="privacy-text">
            Vous avez le droit de demander la limitation du traitement de vos données personnelles dans certaines circonstances, notamment si vous contestez l'exactitude de vos données.
        </p>
        
        <h3 class="privacy-subtitle">8.5 Droit à la portabilité</h3>
        <p class="privacy-text">
            Vous avez le droit de recevoir vos données personnelles dans un format structuré, couramment utilisé et lisible par machine, et de les transmettre à un autre responsable du traitement.
        </p>
        
        <h3 class="privacy-subtitle">8.6 Droit d'opposition</h3>
        <p class="privacy-text">
            Vous avez le droit de vous opposer au traitement de vos données personnelles pour des raisons tenant à votre situation particulière, notamment pour des motifs liés à votre situation particulière concernant le marketing direct.
        </p>
        
        <h3 class="privacy-subtitle">8.7 Retrait du consentement</h3>
        <p class="privacy-text">
            Lorsque le traitement est basé sur votre consentement, vous avez le droit de retirer ce consentement à tout moment. Le retrait du consentement n'affecte pas la licéité du traitement effectué avant le retrait.
        </p>
        
        <h3 class="privacy-subtitle">8.8 Exercer vos droits</h3>
        <p class="privacy-text">
            Pour exercer l'un de ces droits, veuillez nous contacter à l'adresse : <a href="mailto:NiangProgrammeur@gmail.com" class="privacy-link">NiangProgrammeur@gmail.com</a> ou via notre formulaire de contact. Nous répondrons à votre demande dans un délai d'un mois (peut être étendu à deux mois dans certains cas complexes).
        </p>
        <p class="privacy-text">
            Vous avez également le droit de déposer une plainte auprès de l'autorité de contrôle compétente si vous estimez que le traitement de vos données personnelles constitue une violation du RGPD.
        </p>
    </div>

    <div class="privacy-card">
        <h2 class="privacy-section-title">
            <i class="fas fa-link"></i>
            9. Liens vers d'autres sites
        </h2>
        <p class="privacy-text">
            Notre site <strong>NiangProgrammeur</strong> peut contenir des liens vers d'autres sites web qui ne sont pas exploités par nous. Nous ne sommes pas responsables des pratiques de confidentialité de ces sites tiers. Nous vous encourageons vivement à lire les politiques de confidentialité de chaque site que vous visitez.
        </p>
        <p class="privacy-text">
            Nous n'avons aucun contrôle sur et n'assumons aucune responsabilité quant au contenu, aux politiques de confidentialité ou aux pratiques de tout site ou service tiers.
        </p>
    </div>

    <div class="privacy-card">
        <h2 class="privacy-section-title">
            <i class="fas fa-edit"></i>
            10. Modifications de cette politique
        </h2>
        <p class="privacy-text">
            Nous nous réservons le droit de modifier cette politique de confidentialité à tout moment pour refléter les changements dans nos pratiques ou pour d'autres raisons opérationnelles, légales ou réglementaires. Les modifications seront publiées sur cette page avec une date de mise à jour révisée.
        </p>
        <p class="privacy-text">
            Nous vous encourageons à consulter régulièrement cette page pour être informé de toute modification. Si nous apportons des modifications importantes, nous vous en informerons par e-mail (si vous vous êtes abonné à notre newsletter) ou par un avis sur notre site.
        </p>
        <p class="privacy-text">
            Votre utilisation continue de notre site après la publication de modifications constitue votre acceptation de ces modifications.
        </p>
    </div>

    <div class="privacy-card">
        <h2 class="privacy-section-title">
            <i class="fas fa-envelope"></i>
            11. Nous contacter
        </h2>
        <p class="privacy-text">
            Si vous avez des questions, préoccupations ou demandes concernant cette politique de confidentialité ou le traitement de vos données personnelles, vous pouvez nous contacter :
        </p>
        
        <div class="privacy-contact">
            <div class="privacy-contact-content">
                <div class="privacy-contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <strong>Email :</strong> 
                        <a href="mailto:NiangProgrammeur@gmail.com" class="privacy-link">NiangProgrammeur@gmail.com</a>
                    </div>
                </div>
                <div class="privacy-contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <strong>Téléphone :</strong> 
                        <a href="tel:+221783123657" class="privacy-link">+221 78 312 36 57</a>
                    </div>
                </div>
                <div class="privacy-contact-item">
                    <i class="fab fa-whatsapp"></i>
                    <div>
                        <strong>WhatsApp :</strong> 
                        <a href="https://wa.me/221783123657" target="_blank" class="privacy-link">+221 78 312 36 57</a>
                    </div>
                </div>
                <div class="privacy-contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <strong>Adresse :</strong> Dakar, Sénégal
                    </div>
                </div>
                <div class="privacy-contact-item">
                    <i class="fas fa-globe"></i>
                    <div>
                        <strong>Site web :</strong> 
                        <a href="{{ route('home') }}" class="privacy-link">NiangProgrammeur</a>
                    </div>
                </div>
            </div>
        </div>
        
        <p class="privacy-text" style="margin-top: 30px;">
            Nous nous engageons à répondre à toutes vos demandes dans les plus brefs délais et dans le respect de vos droits en matière de protection des données.
        </p>
    </div>
</section>
@endsection
