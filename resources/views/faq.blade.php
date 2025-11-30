@extends('layouts.app')

@section('title', 'FAQ - Questions Fréquentes | NiangProgrammeur')

@section('styles')
<style>
    /* Fonts chargées via preload dans layouts.app - pas de @import bloquant */
    
    body {
        overflow-x: hidden;
    }
    
    /* Body background for FAQ page */
    body:not(.dark-mode) {
        background: #ffffff !important;
    }
    
    body.dark-mode {
        background: #0a0a0f !important;
    }
    
    .faq-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
        padding: 120px 20px 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    body:not(.dark-mode) .faq-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.05) 0%, rgba(20, 184, 166, 0.05) 100%) !important;
    }
    
    .faq-hero::before {
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
    
    .faq-hero-content {
        position: relative;
        z-index: 1;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .faq-icon-wrapper {
        display: inline-block;
        margin-bottom: 30px;
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .faq-icon {
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
    
    .faq-hero h1 {
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
    
    .faq-hero p {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 400;
    }
    
    body:not(.dark-mode) .faq-hero p {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    .faq-section {
        padding: 80px 20px;
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .faq-item {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 25px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    
    body:not(.dark-mode) .faq-item {
        background: rgba(255, 255, 255, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
    }
    
    .faq-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.6s;
    }
    
    .faq-item:hover::before {
        left: 100%;
    }
    
    .faq-item:hover {
        border-color: rgba(6, 182, 212, 0.5);
        box-shadow: 0 15px 40px rgba(6, 182, 212, 0.2);
        transform: translateY(-5px);
    }
    
    .faq-question {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .faq-question-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #06b6d4;
        border: 1px solid rgba(6, 182, 212, 0.3);
        flex-shrink: 0;
    }
    
    .faq-question h3 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #06b6d4;
        margin: 0;
        flex: 1;
    }
    
    .faq-answer {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.8;
        font-size: 1rem;
        padding-left: 55px;
        transition: all 0.3s ease;
    }
    
    body:not(.dark-mode) .faq-answer {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    .faq-cta {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 24px;
        padding: 50px;
        text-align: center;
        margin-top: 60px;
        position: relative;
        overflow: hidden;
    }
    
    body:not(.dark-mode) .faq-cta {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.05), rgba(20, 184, 166, 0.05)) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
    }
    
    .faq-cta::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        animation: rotate 15s linear infinite;
    }
    
    .faq-cta-content {
        position: relative;
        z-index: 1;
    }
    
    .faq-cta h3 {
        font-family: 'Poppins', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: #06b6d4;
        margin-bottom: 15px;
    }
    
    .faq-cta p {
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.1rem;
        margin-bottom: 30px;
    }
    
    body:not(.dark-mode) .faq-cta p {
        color: rgba(30, 41, 59, 0.7) !important;
    }
    
    /* Force text colors in light mode */
    body:not(.dark-mode) .faq-question h3 {
        color: rgba(30, 41, 59, 0.95) !important;
    }
    
    body:not(.dark-mode) h1,
    body:not(.dark-mode) h2,
    body:not(.dark-mode) h3,
    body:not(.dark-mode) p {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    body:not(.dark-mode) .faq-cta h3 {
        color: rgba(30, 41, 59, 0.95) !important;
    }
    
    body:not(.dark-mode) .faq-answer {
        color: rgba(30, 41, 59, 0.85) !important;
    }
    
    body:not(.dark-mode) .faq-question-icon {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(20, 184, 166, 0.15)) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
    
    .faq-cta-button {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 16px 32px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #000;
        font-weight: 700;
        font-size: 1.1rem;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(6, 182, 212, 0.3);
    }
    
    body:not(.dark-mode) .faq-cta-button {
        background: linear-gradient(135deg, #06b6d4, #14b8a6) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4) !important;
    }
    
    .faq-cta-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(6, 182, 212, 0.5);
    }
    
    body:not(.dark-mode) .faq-cta-button:hover {
        box-shadow: 0 8px 30px rgba(6, 182, 212, 0.6) !important;
        background: linear-gradient(135deg, #0891b2, #0d9488) !important;
    }
    
    @media (max-width: 768px) {
        .faq-hero {
            padding: 100px 20px 60px;
        }
        
        .faq-hero h1 {
            font-size: 2.5rem;
        }
        
        .faq-hero p {
            font-size: 1rem;
        }
        
        .faq-item {
            padding: 20px;
        }
        
        .faq-question h3 {
            font-size: 1.2rem;
        }
        
        .faq-answer {
            padding-left: 0;
            margin-top: 15px;
        }
        
        .faq-cta {
            padding: 30px 20px;
        }
        
        .faq-cta h3 {
            font-size: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="faq-hero">
    <div class="faq-hero-content">
        <div class="faq-icon-wrapper">
            <div class="faq-icon">
                <i class="fas fa-question-circle"></i>
            </div>
        </div>
        <h1>Foire Aux Questions</h1>
        <p>Trouvez rapidement les réponses à vos questions sur NiangProgrammeur</p>
    </div>
</section>

<!-- FAQ Content -->
<section class="faq-section">
    <!-- Question 1 -->
    <div class="faq-item">
        <div class="faq-question">
            <div class="faq-question-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h3>Quels sont les prérequis pour suivre vos formations sur NiangProgrammeur ?</h3>
        </div>
        <div class="faq-answer">
            Nos formations sont conçues pour tous les niveaux, du débutant à l'expert. Pour les formations de base (HTML, CSS), aucun prérequis n'est nécessaire - vous pouvez commencer dès aujourd'hui même si vous n'avez jamais écrit une ligne de code. Pour les formations avancées (PHP, JavaScript, Laravel), une connaissance de base en HTML/CSS est recommandée pour une meilleure compréhension. Chaque formation indique clairement son niveau de difficulté et les prérequis nécessaires dans la description détaillée. Nous proposons également des parcours d'apprentissage progressifs qui vous guident étape par étape, de la découverte des bases jusqu'à la maîtrise des concepts avancés. Si vous êtes débutant, nous vous recommandons de commencer par HTML5, puis CSS3, avant de passer à JavaScript et PHP.
        </div>
    </div>

    <!-- Question 2 -->
    <div class="faq-item">
        <div class="faq-question">
            <div class="faq-question-icon">
                <i class="fas fa-gift"></i>
            </div>
            <h3>Les formations sont-elles vraiment gratuites sur NiangProgrammeur ?</h3>
        </div>
        <div class="faq-answer">
            Oui, absolument ! Tous nos cours en ligne sont entièrement gratuits et accessibles à tous, sans conditions. Notre mission est de démocratiser l'apprentissage du développement web et de rendre la programmation accessible à tous, notamment en Afrique. Aucun paiement, aucun abonnement, aucun frais caché. Nous croyons fermement que l'éducation devrait être accessible à tous, indépendamment de la situation financière. C'est pourquoi nous avons créé cette plateforme entièrement gratuite, financée par des partenariats et des dons, pour permettre à chacun d'apprendre les compétences essentielles du développement web moderne. Vous avez accès à tous nos contenus : formations complètes, exercices pratiques, quiz interactifs, et même des opportunités d'emploi, le tout sans débourser un seul franc.
        </div>
    </div>

    <!-- Question 3 -->
    <div class="faq-item">
        <div class="faq-question">
            <div class="faq-question-icon">
                <i class="fas fa-certificate"></i>
            </div>
            <h3>Puis-je obtenir un certificat à la fin de la formation sur NiangProgrammeur ?</h3>
        </div>
        <div class="faq-answer">
            Actuellement, nous ne délivrons pas de certificats officiels. Cependant, vous pouvez créer un portfolio avec les projets réalisés pendant les formations pour démontrer vos compétences à vos futurs employeurs ou clients. Un portfolio bien construit avec des projets concrets est souvent plus valorisant qu'un simple certificat, car il montre vos compétences pratiques et votre capacité à résoudre des problèmes réels. Nous travaillons activement sur un système de badges de compétences numériques qui seront disponibles prochainement. Ces badges pourront être partagés sur LinkedIn et autres réseaux professionnels pour attester de vos compétences acquises. En attendant, nous vous encourageons à documenter vos projets, à contribuer à des projets open source, et à partager votre parcours d'apprentissage sur les réseaux sociaux pour construire votre réputation en tant que développeur.
        </div>
    </div>

    <!-- Question 4 -->
    <div class="faq-item">
        <div class="faq-question">
            <div class="faq-question-icon">
                <i class="fas fa-clock"></i>
            </div>
            <h3>Combien de temps faut-il pour terminer une formation sur NiangProgrammeur ?</h3>
        </div>
        <div class="faq-answer">
            La durée varie selon la formation et votre rythme d'apprentissage. En moyenne, comptez 2 à 4 semaines pour une formation complète en y consacrant 2 à 3 heures par semaine. Les formations de base (HTML5, CSS3) peuvent être complétées en 1 à 2 semaines, tandis que les formations avancées (JavaScript, PHP, Laravel) peuvent nécessiter 4 à 8 semaines selon votre niveau initial. Vous pouvez apprendre à votre propre rythme, à tout moment, depuis n'importe où. Il n'y a pas de limite de temps pour terminer une formation - vous pouvez y revenir autant de fois que nécessaire. Nous recommandons de suivre les formations de manière régulière (même 30 minutes par jour) plutôt que de tout faire en une seule session, car cela améliore la rétention des connaissances. Chaque formation comprend des exercices pratiques et des quiz pour renforcer votre apprentissage.
        </div>
    </div>

    <!-- Question 5 -->
    <div class="faq-item">
        <div class="faq-question">
            <div class="faq-question-icon">
                <i class="fas fa-headset"></i>
            </div>
            <h3>Proposez-vous un support ou de l'aide sur NiangProgrammeur ?</h3>
        </div>
        <div class="faq-answer">
            Oui, nous offrons un support complet et personnalisé ! Vous pouvez nous contacter via WhatsApp (+221 78 312 36 57) pour une assistance rapide, par email (NiangProgrammeur@gmail.com) pour des questions détaillées, ou via notre formulaire de contact sur le site pour toute demande générale. Nous nous efforçons de répondre dans les 24-48 heures, souvent même plus rapidement pour les questions urgentes. Nous organisons également des sessions de questions-réponses en direct sur nos réseaux sociaux (Facebook, LinkedIn, TikTok) où vous pouvez poser vos questions et obtenir des réponses en temps réel. Pour les problèmes techniques ou les questions sur le code, nous avons également une communauté active d'apprenants qui s'entraident mutuellement. N'hésitez pas à nous contacter, nous sommes là pour vous accompagner dans votre parcours d'apprentissage !
        </div>
    </div>

    <!-- Question 6 -->
    <div class="faq-item">
        <div class="faq-question">
            <div class="faq-question-icon">
                <i class="fas fa-sync-alt"></i>
            </div>
            <h3>Les contenus sont-ils régulièrement mis à jour sur NiangProgrammeur ?</h3>
        </div>
        <div class="faq-answer">
            Oui, nous mettons à jour nos formations régulièrement pour refléter les dernières technologies, frameworks et meilleures pratiques du développement web. Le monde du développement web évolue rapidement, et nous nous engageons à maintenir nos contenus à jour avec les dernières versions des langages (PHP 8.x, JavaScript ES6+, HTML5, CSS3), les nouveaux frameworks populaires (Laravel, React, Vue.js), et les meilleures pratiques de l'industrie. Nous suivons de près les évolutions des langages de programmation et des outils modernes pour vous offrir un contenu toujours à jour et pertinent. Chaque trimestre, nous révisons nos formations pour intégrer les nouvelles fonctionnalités, corriger les informations obsolètes, et ajouter des exemples pratiques basés sur les projets réels. Nous surveillons également les tendances du marché pour vous préparer aux compétences les plus demandées par les employeurs.
        </div>
    </div>

    <!-- Question 7 -->
    <div class="faq-item">
        <div class="faq-question">
            <div class="faq-question-icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <h3>Puis-je utiliser le contenu de NiangProgrammeur pour un usage commercial ?</h3>
        </div>
        <div class="faq-answer">
            Le contenu des formations est destiné à un usage personnel et éducatif. Vous pouvez utiliser les connaissances acquises pour créer vos propres projets commerciaux, développer des sites web pour vos clients, ou même créer votre propre entreprise de développement. C'est exactement l'objectif de nos formations : vous donner les compétences nécessaires pour réussir professionnellement. Cependant, la reproduction ou la redistribution du contenu des formations (textes, vidéos, exercices) à des fins commerciales nécessite une autorisation écrite de notre part. Si vous souhaitez utiliser nos contenus pour créer votre propre plateforme de formation, nous serions ravis de discuter d'un partenariat. Les projets que vous créez en utilisant les connaissances acquises vous appartiennent entièrement - nous encourageons même nos apprenants à partager leurs réalisations avec la communauté !
        </div>
    </div>

    <!-- Question 8 -->
    <div class="faq-item">
        <div class="faq-question">
            <div class="faq-question-icon">
                <i class="fas fa-bell"></i>
            </div>
            <h3>Comment puis-je rester informé des nouvelles formations sur NiangProgrammeur ?</h3>
        </div>
        <div class="faq-answer">
            Pour rester informé de toutes nos nouveautés, formations et opportunités, nous vous proposons plusieurs moyens : (1) vous abonner à notre newsletter sur le site - vous recevrez un email hebdomadaire avec les dernières formations, articles et offres d'emploi, (2) nous suivre sur nos réseaux sociaux (Facebook, LinkedIn, TikTok) où nous partageons quotidiennement des astuces, des tutoriels courts, et des actualités du développement web, (3) activer les notifications sur notre chaîne YouTube pour être alerté dès qu'une nouvelle vidéo est publiée, ou (4) nous contacter directement pour être ajouté à notre liste de diffusion prioritaire. Nous publions généralement 2 à 3 nouvelles formations par mois, ainsi que des articles réguliers sur les tendances du développement web, les opportunités d'emploi, et les conseils de carrière. En vous abonnant, vous ne manquerez aucune opportunité d'apprentissage ou professionnelle !
        </div>
    </div>

    <!-- Question 9 -->
    <div class="faq-item">
        <div class="faq-question">
            <div class="faq-question-icon">
                <i class="fas fa-laptop-code"></i>
            </div>
            <h3>Quelles technologies sont enseignées sur NiangProgrammeur ?</h3>
        </div>
        <div class="faq-answer">
            Nous enseignons un large éventail de technologies web modernes et essentielles pour devenir un développeur web complet. Nos formations couvrent : HTML5 (structure et sémantique), CSS3 (styles, animations, responsive design), JavaScript (ES6+, DOM, async/await, modules), PHP (syntaxe, OOP, frameworks), Bootstrap (grilles, composants, responsive), Git (versioning, branches, collaboration), WordPress (thèmes, plugins, personnalisation), Laravel (framework PHP moderne), Python (programmation et applications web), et bien plus encore. Nous couvrons également les concepts d'Intelligence Artificielle appliqués au développement web, ainsi que les outils modernes comme les API REST, les bases de données (MySQL, PostgreSQL), et les pratiques DevOps. Nos formations vont des bases absolues (pour les débutants complets) aux concepts avancés (pour les développeurs expérimentés souhaitant se perfectionner). Chaque technologie est enseignée avec des exemples pratiques, des projets réels, et des exercices interactifs pour une meilleure compréhension.
        </div>
    </div>

    <!-- Question 10 -->
    <div class="faq-item">
        <div class="faq-question">
            <div class="faq-question-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3>Puis-je partager les formations de NiangProgrammeur avec mes amis ?</h3>
        </div>
        <div class="faq-answer">
            Absolument ! Nous encourageons vivement le partage de nos formations avec vos amis, collègues, famille et toute personne intéressée par l'apprentissage du développement web. Le partage de connaissances est au cœur de notre mission - plus il y a de personnes qui apprennent, plus la communauté de développeurs grandit et s'enrichit. N'hésitez pas à partager nos liens sur les réseaux sociaux, dans vos groupes WhatsApp, ou par email. Vous pouvez également recommander nos formations à vos professeurs, vos mentors, ou dans votre entreprise si vous souhaitez que vos collègues se forment également. Chaque partage nous aide à toucher plus de personnes et à démocratiser l'accès à l'éducation en développement web, notamment en Afrique où les ressources éducatives de qualité sont parfois limitées. Ensemble, nous construisons une communauté forte de développeurs talentueux !
        </div>
    </div>

    <!-- Question 11 -->
    <div class="faq-item">
        <div class="faq-question">
            <div class="faq-question-icon">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <h3>Puis-je accéder aux formations de NiangProgrammeur sur mobile ?</h3>
        </div>
        <div class="faq-answer">
            Oui, notre site est entièrement responsive et optimisé pour mobile, tablette et desktop. Vous pouvez accéder à toutes nos formations depuis n'importe quel appareil - smartphone Android ou iOS, tablette, ou ordinateur. L'interface s'adapte automatiquement à la taille de votre écran pour une expérience optimale. Vous pouvez même télécharger nos pages pour une consultation hors ligne. Cependant, pour une meilleure expérience d'apprentissage, nous recommandons fortement l'utilisation d'un ordinateur (laptop ou desktop) pour le codage pratique, car cela vous permet d'avoir un écran plus grand, un clavier complet pour écrire du code efficacement, et la possibilité d'utiliser plusieurs fenêtres simultanément (éditeur de code, navigateur, documentation). Pour la consultation des cours et la lecture des contenus théoriques, le mobile est parfaitement adapté et vous permet d'apprendre pendant vos trajets ou vos moments libres.
        </div>
    </div>

    <!-- Question 12 -->
    <div class="faq-item">
        <div class="faq-question">
            <div class="faq-question-icon">
                <i class="fas fa-gift"></i>
            </div>
            <h3>NiangProgrammeur propose-t-il des opportunités d'emploi ou de stage ?</h3>
        </div>
        <div class="faq-answer">
            Oui, absolument ! En plus de nos formations gratuites, nous publions régulièrement des offres d'emploi et de stage dans le domaine du développement web sur notre section "Emplois". Ces offres proviennent d'entreprises locales et internationales à la recherche de développeurs talentueux. Nous mettons également en relation les apprenants talentueux avec des entreprises partenaires à la recherche de développeurs compétents. Si vous avez complété plusieurs formations avec succès et que vous avez construit un portfolio solide, n'hésitez pas à nous contacter - nous pouvons vous recommander auprès de nos partenaires. Nous organisons également des événements de networking, des webinaires avec des recruteurs, et des sessions de coaching pour vous aider à préparer vos entretiens d'embauche. Notre objectif est non seulement de vous former, mais aussi de vous aider à décrocher votre premier emploi ou à faire évoluer votre carrière dans le développement web.
        </div>
    </div>

    <!-- CTA -->
    <div class="faq-cta">
        <div class="faq-cta-content">
            <h3>Vous avez d'autres questions ?</h3>
            <p>Notre équipe est là pour vous aider ! Contactez-nous et nous vous répondrons dans les plus brefs délais.</p>
            <a href="{{ route('contact') }}" class="faq-cta-button">
                <i class="fas fa-envelope"></i>
                Nous contacter
            </a>
        </div>
    </div>
</section>
@endsection
