@extends('layouts.app')

@section('title', 'FAQ - Questions Fréquentes | NiangProgrammeur')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800;900&display=swap');
    
    body {
        overflow-x: hidden;
    }
    
    .faq-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
        padding: 120px 20px 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
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
    
    .faq-cta-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(6, 182, 212, 0.5);
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
            Nos formations sont conçues pour tous les niveaux, du débutant à l'expert. Pour les formations de base (HTML, CSS), aucun prérequis n'est nécessaire. Pour les formations avancées (PHP, JavaScript, Laravel), une connaissance de base en HTML/CSS est recommandée. Chaque formation indique clairement son niveau de difficulté et les prérequis nécessaires.
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
            Oui, absolument ! Tous nos cours en ligne sont entièrement gratuits et accessibles à tous, sans conditions. Notre mission est de démocratiser l'apprentissage du développement web et de rendre la programmation accessible à tous, notamment en Afrique. Aucun paiement, aucun abonnement, aucun frais caché.
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
            Actuellement, nous ne délivrons pas de certificats officiels. Cependant, vous pouvez créer un portfolio avec les projets réalisés pendant les formations pour démontrer vos compétences à vos futurs employeurs ou clients. Nous travaillons sur un système de badges de compétences qui seront disponibles prochainement.
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
            La durée varie selon la formation et votre rythme d'apprentissage. En moyenne, comptez 2 à 4 semaines pour une formation complète en y consacrant 2 à 3 heures par semaine. Vous pouvez apprendre à votre propre rythme, à tout moment, depuis n'importe où. Il n'y a pas de limite de temps pour terminer une formation.
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
            Oui, nous offrons un support complet ! Vous pouvez nous contacter via WhatsApp (+221 78 312 36 57), par email (NiangProgrammeur@gmail.com), ou via notre formulaire de contact sur le site. Nous nous efforçons de répondre dans les 24-48 heures. Nous organisons également des sessions de questions-réponses en direct sur nos réseaux sociaux.
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
            Oui, nous mettons à jour nos formations régulièrement pour refléter les dernières technologies, frameworks et meilleures pratiques du développement web. Nous suivons de près les évolutions des langages de programmation et des outils modernes pour vous offrir un contenu toujours à jour et pertinent.
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
            Le contenu des formations est destiné à un usage personnel et éducatif. Vous pouvez utiliser les connaissances acquises pour créer vos propres projets commerciaux. Cependant, la reproduction ou la redistribution du contenu des formations à des fins commerciales nécessite une autorisation écrite de notre part.
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
            Pour rester informé, vous pouvez : (1) vous abonner à notre newsletter sur le site, (2) nous suivre sur nos réseaux sociaux (Facebook, LinkedIn, TikTok), (3) activer les notifications sur notre chaîne YouTube, ou (4) nous contacter directement pour être ajouté à notre liste de diffusion.
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
            Nous enseignons un large éventail de technologies web : HTML5, CSS3, JavaScript, PHP, Bootstrap, Git, WordPress, Laravel, et bien plus. Nous couvrons également les concepts d'Intelligence Artificielle appliqués au développement web. Nos formations vont des bases aux concepts avancés.
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
            Absolument ! Nous encourageons le partage de nos formations avec vos amis, collègues et toute personne intéressée par l'apprentissage du développement web. Le partage de connaissances est au cœur de notre mission. N'hésitez pas à partager nos liens sur les réseaux sociaux !
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
            Oui, notre site est entièrement responsive et optimisé pour mobile, tablette et desktop. Vous pouvez accéder à toutes nos formations depuis n'importe quel appareil. Cependant, pour une meilleure expérience d'apprentissage, nous recommandons l'utilisation d'un ordinateur pour le codage pratique.
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
            Oui ! En plus de nos formations gratuites, nous publions régulièrement des offres d'emploi et de stage dans le domaine du développement web sur notre section "Emplois". Nous mettons également en relation les apprenants talentueux avec des entreprises partenaires à la recherche de développeurs compétents.
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
