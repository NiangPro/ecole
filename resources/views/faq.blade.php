@extends('layouts.app')

@section('title', 'FAQ - Questions Fréquentes | NiangProgrammeur')
@section('meta_description', 'Toutes les réponses sur les formations gratuites, documents, épreuves, cours payants, certificats, badges, forum et emplois de NiangProgrammeur.')

@section('styles')
<style>
    body { overflow-x: hidden; }

    body:not(.dark-mode) { background: #ffffff !important; }
    body.dark-mode { background: #0a0a0f !important; }

    /* ---------- Hero ---------- */
    .faq-hero {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
        padding: 110px 20px 60px;
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
        animation: faqRotate 20s linear infinite;
    }

    @keyframes faqRotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    @keyframes faqFloat { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
    @keyframes faqShimmer { to { background-position: 200% center; } }

    .faq-hero-content { position: relative; z-index: 1; max-width: 720px; margin: 0 auto; }

    .faq-icon-wrapper { display: inline-block; margin-bottom: 25px; animation: faqFloat 3s ease-in-out infinite; }

    .faq-icon {
        width: 90px; height: 90px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 2.6rem; color: #000;
        box-shadow: 0 10px 40px rgba(6, 182, 212, 0.3);
    }

    .faq-hero h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 3.2rem;
        font-weight: 900;
        background: linear-gradient(135deg, #06b6d4 0%, #14b8a6 50%, #06b6d4 100%);
        background-size: 200% auto;
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        animation: faqShimmer 3s linear infinite;
        margin-bottom: 16px;
    }

    .faq-hero p {
        font-size: 1.15rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 400;
        margin-bottom: 35px;
    }

    body:not(.dark-mode) .faq-hero p { color: rgba(30, 41, 59, 0.7) !important; }

    /* ---------- Search ---------- */
    .faq-search-wrap { position: relative; max-width: 520px; margin: 0 auto; }

    .faq-search-wrap i.fa-search {
        position: absolute; left: 20px; top: 50%; transform: translateY(-50%);
        color: rgba(6, 182, 212, 0.7); font-size: 1rem;
    }

    #faqSearchInput {
        width: 100%;
        padding: 15px 20px 15px 50px;
        border-radius: 16px;
        border: 2px solid rgba(6, 182, 212, 0.3);
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(20px);
        color: #fff;
        font-size: 1rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.25s ease;
    }

    #faqSearchInput:focus { outline: none; border-color: #06b6d4; box-shadow: 0 0 0 5px rgba(6, 182, 212, 0.15); }
    #faqSearchInput::placeholder { color: rgba(255, 255, 255, 0.4); }

    body:not(.dark-mode) #faqSearchInput {
        background: rgba(255, 255, 255, 0.95) !important;
        color: #1e293b !important;
        border-color: rgba(6, 182, 212, 0.35) !important;
    }

    body:not(.dark-mode) #faqSearchInput::placeholder { color: rgba(30, 41, 59, 0.4) !important; }

    /* ---------- Category pills ---------- */
    .faq-categories {
        display: flex; flex-wrap: wrap; justify-content: center; gap: 10px;
        max-width: 1000px; margin: 35px auto 0; padding: 0 20px;
    }

    .faq-cat-pill {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px;
        border-radius: 999px;
        font-size: 0.85rem; font-weight: 600;
        background: rgba(255, 255, 255, 0.05);
        border: 1.5px solid rgba(6, 182, 212, 0.25);
        color: rgba(255, 255, 255, 0.7);
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }

    .faq-cat-pill:hover { border-color: rgba(6, 182, 212, 0.6); color: #06b6d4; }

    .faq-cat-pill.is-active {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border-color: transparent;
        color: #000;
    }

    body:not(.dark-mode) .faq-cat-pill {
        background: rgba(255, 255, 255, 0.7) !important;
        color: rgba(30, 41, 59, 0.7) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }

    body:not(.dark-mode) .faq-cat-pill.is-active {
        background: linear-gradient(135deg, #06b6d4, #14b8a6) !important;
        color: #000 !important;
    }

    /* ---------- Section ---------- */
    .faq-section { padding: 60px 20px 80px; max-width: 900px; margin: 0 auto; }

    .faq-results-count {
        text-align: center;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.4);
        margin-bottom: 25px;
    }

    body:not(.dark-mode) .faq-results-count { color: rgba(30, 41, 59, 0.5) !important; }

    .faq-no-results {
        display: none;
        text-align: center;
        padding: 50px 20px;
        color: rgba(255, 255, 255, 0.45);
        font-size: 1rem;
    }

    body:not(.dark-mode) .faq-no-results { color: rgba(30, 41, 59, 0.55) !important; }

    /* ---------- Item (accordion) ---------- */
    .faq-item {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 18px;
        margin-bottom: 16px;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    body:not(.dark-mode) .faq-item { background: rgba(255, 255, 255, 0.9) !important; border-color: rgba(6, 182, 212, 0.25) !important; }

    .faq-item:hover { border-color: rgba(6, 182, 212, 0.45); }
    .faq-item.is-open { border-color: rgba(6, 182, 212, 0.6); box-shadow: 0 10px 30px rgba(6, 182, 212, 0.12); }

    .faq-question {
        display: flex; align-items: center; gap: 15px;
        padding: 22px 26px;
        cursor: pointer;
    }

    .faq-question-icon {
        width: 38px; height: 38px; flex-shrink: 0;
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.2));
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; color: #06b6d4;
        border: 1px solid rgba(6, 182, 212, 0.3);
    }

    body:not(.dark-mode) .faq-question-icon {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(20, 184, 166, 0.15)) !important;
    }

    .faq-question h3 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.15rem; font-weight: 700;
        color: #06b6d4;
        margin: 0; flex: 1;
    }

    body:not(.dark-mode) .faq-question h3 { color: rgba(30, 41, 59, 0.95) !important; }

    .faq-chevron { color: rgba(6, 182, 212, 0.6); transition: transform 0.3s ease; flex-shrink: 0; }
    .faq-item.is-open .faq-chevron { transform: rotate(180deg); }

    .faq-answer-wrap { max-height: 0; overflow: hidden; transition: max-height 0.35s ease; }
    .faq-item.is-open .faq-answer-wrap { max-height: 600px; }

    .faq-answer {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.75;
        font-size: 0.97rem;
        padding: 0 26px 24px 79px;
    }

    body:not(.dark-mode) .faq-answer { color: rgba(30, 41, 59, 0.8) !important; }

    .faq-answer a { color: #06b6d4; font-weight: 600; }

    /* ---------- CTA ---------- */
    .faq-cta {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(20, 184, 166, 0.1));
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 24px;
        padding: 50px;
        text-align: center;
        margin-top: 50px;
        position: relative;
        overflow: hidden;
    }

    body:not(.dark-mode) .faq-cta {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.05), rgba(20, 184, 166, 0.05)) !important;
        border-color: rgba(6, 182, 212, 0.25) !important;
    }

    .faq-cta::before {
        content: ''; position: absolute; top: -50%; right: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        animation: faqRotate 15s linear infinite;
    }

    .faq-cta-content { position: relative; z-index: 1; }

    .faq-cta h3 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.9rem; font-weight: 700;
        color: #06b6d4;
        margin-bottom: 12px;
    }

    body:not(.dark-mode) .faq-cta h3 { color: rgba(30, 41, 59, 0.95) !important; }

    .faq-cta p { color: rgba(255, 255, 255, 0.7); font-size: 1.05rem; margin-bottom: 28px; }
    body:not(.dark-mode) .faq-cta p { color: rgba(30, 41, 59, 0.7) !important; }

    .faq-cta-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }

    .faq-cta-button {
        display: inline-flex; align-items: center; gap: 10px;
        padding: 15px 30px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #000; font-weight: 700; font-size: 1.05rem;
        border-radius: 12px; text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(6, 182, 212, 0.3);
    }

    body:not(.dark-mode) .faq-cta-button { color: #ffffff !important; box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4) !important; }

    .faq-cta-button:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(6, 182, 212, 0.5); }
    body:not(.dark-mode) .faq-cta-button:hover { box-shadow: 0 8px 30px rgba(6, 182, 212, 0.6) !important; background: linear-gradient(135deg, #0891b2, #0d9488) !important; }

    .faq-cta-button.is-secondary {
        background: rgba(255, 255, 255, 0.06);
        border: 1.5px solid rgba(6, 182, 212, 0.4);
        color: #06b6d4;
        box-shadow: none;
    }

    body:not(.dark-mode) .faq-cta-button.is-secondary {
        background: rgba(255, 255, 255, 0.8) !important;
        color: #06b6d4 !important;
        box-shadow: none !important;
    }

    body:not(.dark-mode) h1, body:not(.dark-mode) h2, body:not(.dark-mode) h3, body:not(.dark-mode) p {
        color: rgba(30, 41, 59, 0.9) !important;
    }

    @media (max-width: 768px) {
        .faq-hero { padding: 90px 20px 45px; }
        .faq-hero h1 { font-size: 2.3rem; }
        .faq-hero p { font-size: 1rem; }
        .faq-question { padding: 18px 18px; }
        .faq-question h3 { font-size: 1.02rem; }
        .faq-answer { padding: 0 18px 20px 63px; }
        .faq-cta { padding: 32px 20px; }
        .faq-cta h3 { font-size: 1.5rem; }
    }
</style>
@endsection

@section('content')
<!-- Hero -->
<section class="faq-hero">
    <div class="faq-hero-content">
        <div class="faq-icon-wrapper">
            <div class="faq-icon"><i class="fas fa-question-circle"></i></div>
        </div>
        <h1>Foire Aux Questions</h1>
        <p>Formations, documents, épreuves, cours payants, certificats, forum, emplois… trouvez rapidement votre réponse.</p>

        <div class="faq-search-wrap">
            <i class="fas fa-search"></i>
            <input type="text" id="faqSearchInput" placeholder="Rechercher une question (ex : certificat, Google, épreuve...)">
        </div>
    </div>
</section>

<!-- Category pills -->
<div class="faq-categories" id="faqCategories">
    <span class="faq-cat-pill is-active" data-cat="all">Toutes</span>
    <span class="faq-cat-pill" data-cat="formations"><i class="fas fa-graduation-cap"></i> Formations</span>
    <span class="faq-cat-pill" data-cat="documents"><i class="fas fa-file-alt"></i> Documents & Épreuves</span>
    <span class="faq-cat-pill" data-cat="payant"><i class="fas fa-crown"></i> Cours payants & Dons</span>
    <span class="faq-cat-pill" data-cat="certificats"><i class="fas fa-award"></i> Certificats & Badges</span>
    <span class="faq-cat-pill" data-cat="compte"><i class="fas fa-user"></i> Compte & Connexion</span>
    <span class="faq-cat-pill" data-cat="communaute"><i class="fas fa-users"></i> Communauté & Emplois</span>
    <span class="faq-cat-pill" data-cat="support"><i class="fas fa-headset"></i> Support</span>
</div>

<!-- FAQ Content -->
<section class="faq-section">
    <p class="faq-results-count" id="faqResultsCount"></p>

    <div id="faqList">

        {{-- ===== FORMATIONS ===== --}}
        <div class="faq-item" data-cat="formations" data-search="prérequis formations débutant niveau">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-graduation-cap"></i></div>
                <h3>Quels sont les prérequis pour suivre vos formations ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    Aucun prérequis pour les formations de base (HTML5, CSS3) : vous pouvez commencer même sans avoir jamais codé. Pour les formations avancées (JavaScript, PHP, Laravel, Data Science...), une base en HTML/CSS est recommandée. Chaque formation indique clairement son niveau. Notre conseil pour débuter : HTML5 → CSS3 → JavaScript → PHP.
                </div>
            </div>
        </div>

        <div class="faq-item" data-cat="formations" data-search="gratuit prix formations gratuites">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-gift"></i></div>
                <h3>Les formations sont-elles vraiment gratuites ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    Oui, tout notre catalogue de formations (plus de 20 technologies), les exercices pratiques et les quiz sont 100% gratuits et accessibles sans inscription payante. Nous proposons en complément des <strong>cours payants avancés</strong> et un <strong>abonnement Premium</strong> pour ceux qui veulent aller plus loin — voir la catégorie « Cours payants & Dons ».
                </div>
            </div>
        </div>

        <div class="faq-item" data-cat="formations" data-search="technologies langages java python c++ typescript go rust exercices quiz">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-laptop-code"></i></div>
                <h3>Quelles technologies puis-je apprendre sur NiangProgrammeur ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    Plus de 20 technologies : HTML5, CSS3, JavaScript, TypeScript, PHP, Python, Java, C, C++, C#, SQL, Git, Bootstrap, WordPress, Go, Rust, Ruby, Swift, Dart, Perl, ainsi que des parcours Cybersécurité, Data Science, Big Data et Intelligence Artificielle. Chaque formation est accompagnée d'<a href="{{ route('exercices') }}">exercices pratiques interactifs</a> et de <a href="{{ route('quiz') }}">quiz</a> pour valider vos acquis.
                </div>
            </div>
        </div>

        <div class="faq-item" data-cat="formations" data-search="durée temps rythme apprentissage">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-clock"></i></div>
                <h3>Combien de temps faut-il pour terminer une formation ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    En moyenne 1 à 2 semaines pour une formation de base, 4 à 8 semaines pour une formation avancée, à raison de 2-3h par semaine. Vous apprenez à votre rythme, sans limite de temps, et pouvez y revenir à volonté.
                </div>
            </div>
        </div>

        {{-- ===== DOCUMENTS & ÉPREUVES ===== --}}
        <div class="faq-item" data-cat="documents" data-search="documents téléchargeables pdf fiches cours prix panier paiement wave orange money">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-file-alt"></i></div>
                <h3>Qu'est-ce que la section « Documents » et sont-ils gratuits ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    Notre <a href="{{ route('documents.index') }}">catalogue de documents</a> propose des fiches de cours, supports PDF et papiers administratifs. Certains sont gratuits, d'autres payants (avec panier et paiement sécurisé via Wave, Orange Money ou carte bancaire selon disponibilité). Après achat, le document est disponible immédiatement en téléchargement depuis votre espace.
                </div>
            </div>
        </div>

        <div class="faq-item" data-cat="documents" data-search="packs bundles réduction prix réduit">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-box-open"></i></div>
                <h3>Comment fonctionnent les packs (bundles) de documents ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    Un <a href="{{ route('bundles.index') }}">pack</a> regroupe plusieurs documents liés à un même thème à un prix réduit par rapport à un achat séparé. C'est la manière la plus économique d'obtenir un ensemble complet de ressources sur un sujet.
                </div>
            </div>
        </div>

        <div class="faq-item" data-cat="documents" data-search="épreuves corrigés examens cfee bac bfem papiers administratifs">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-file-signature"></i></div>
                <h3>Proposez-vous des épreuves et corrigés d'examens ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    Oui, la section <a href="{{ route('epreuves.index') }}">Épreuves</a> regroupe des sujets d'examens classés par niveau et par matière, avec leurs corrigés (certains gratuits, d'autres payants). Nous proposons également des <a href="{{ route('admin-docs.index') }}">papiers administratifs</a> (formulaires, modèles de documents officiels) prêts à télécharger.
                </div>
            </div>
        </div>

        {{-- ===== COURS PAYANTS & DONS ===== --}}
        <div class="faq-item" data-cat="payant" data-search="cours payants abonnement premium différence gratuit">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-crown"></i></div>
                <h3>Quelle est la différence entre formations gratuites, cours payants et abonnement Premium ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    Les <strong>formations gratuites</strong> couvrent l'essentiel de chaque technologie et restent accessibles à tous. Les <strong>cours payants</strong> approfondissent des sujets spécifiques avec un contenu premium et donnent accès à un <strong>certificat</strong> à la complétion. L'<strong>abonnement Premium</strong> donne un accès élargi à l'ensemble des cours payants pour un tarif mensuel/annuel. Retrouvez le détail sur la page <a href="{{ route('monetization.courses') }}">Cours payants</a>.
                </div>
            </div>
        </div>

        <div class="faq-item" data-cat="payant" data-search="don faire un don affiliation programme affilié">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-hand-holding-heart"></i></div>
                <h3>Puis-je faire un don ou devenir affilié ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    Oui. Vous pouvez soutenir la plateforme via la page <a href="{{ route('monetization.donations') }}">Dons</a>, ce qui nous aide à maintenir la gratuité des formations. Vous pouvez aussi rejoindre notre <a href="{{ route('monetization.affiliates') }}">programme d'affiliation</a> et toucher une commission en recommandant nos cours payants.
                </div>
            </div>
        </div>

        {{-- ===== CERTIFICATS & BADGES ===== --}}
        <div class="faq-item" data-cat="certificats" data-search="certificat badge récompense profil complet">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-award"></i></div>
                <h3>Puis-je obtenir un certificat ou un badge ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    Oui ! En complétant un <a href="{{ route('monetization.courses') }}">cours payant</a>, vous débloquez un <a href="{{ route('dashboard.certificates') }}">certificat</a> téléchargeable (votre profil doit être complété au préalable). Vous gagnez aussi des <a href="{{ route('dashboard.badges') }}">badges</a> en progressant sur la plateforme (exercices, quiz, assiduité...), à afficher fièrement sur votre profil ou LinkedIn.
                </div>
            </div>
        </div>

        {{-- ===== COMPTE & CONNEXION ===== --}}
        <div class="faq-item" data-cat="compte" data-search="compte inscription connexion google github facebook oauth">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-user-plus"></i></div>
                <h3>Comment créer un compte ? Puis-je me connecter avec Google ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    L'inscription est gratuite et se fait en quelques secondes via email/mot de passe, ou en un clic avec votre compte <strong>Google</strong>, <strong>GitHub</strong> ou <strong>Facebook</strong> — aucun formulaire à remplir dans ce cas, votre profil est créé automatiquement.
                </div>
            </div>
        </div>

        <div class="faq-item" data-cat="compte" data-search="mot de passe oublié réinitialiser">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-key"></i></div>
                <h3>J'ai oublié mon mot de passe, que faire ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    Cliquez sur « Mot de passe oublié » depuis la page de connexion, saisissez votre email et suivez le lien de réinitialisation reçu. Si vous vous êtes inscrit via Google/GitHub/Facebook, connectez-vous simplement avec ce même bouton, aucun mot de passe n'est nécessaire.
                </div>
            </div>
        </div>

        {{-- ===== COMMUNAUTÉ & EMPLOIS ===== --}}
        <div class="faq-item" data-cat="communaute" data-search="forum communauté discussion entraide">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-comments"></i></div>
                <h3>À quoi sert le forum NiangProgrammeur ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    Le <a href="{{ route('forum.index') }}">forum</a> est notre espace d'entraide communautaire : posez vos questions techniques, partagez vos projets, aidez d'autres apprenants et échangez avec la communauté de développeurs NiangProgrammeur, classé par catégories.
                </div>
            </div>
        </div>

        <div class="faq-item" data-cat="communaute" data-search="emploi stage offre opportunité candidature">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-briefcase"></i></div>
                <h3>Proposez-vous des offres d'emploi ou de stage ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    Oui, la section <a href="{{ route('emplois') }}">Emplois</a> publie régulièrement des offres d'emploi, de stage, des bourses et des concours dans le développement web. Un portfolio solide construit avec vos projets de formation est votre meilleur atout pour candidater.
                </div>
            </div>
        </div>

        <div class="faq-item" data-cat="communaute" data-search="partager amis réseaux sociaux">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-share-alt"></i></div>
                <h3>Puis-je partager les formations avec mes amis ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    Absolument, nous vous y encourageons ! Plus la connaissance circule, plus la communauté de développeurs grandit — n'hésitez pas à partager nos liens sur les réseaux sociaux ou dans vos groupes.
                </div>
            </div>
        </div>

        {{-- ===== SUPPORT ===== --}}
        <div class="faq-item" data-cat="support" data-search="contact aide support whatsapp email">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-headset"></i></div>
                <h3>Proposez-vous un support ou de l'aide ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    Oui ! WhatsApp (+221 78 312 36 57) pour une réponse rapide, email (NiangProgrammeur@gmail.com) pour les questions détaillées, ou notre <a href="{{ route('contact') }}">formulaire de contact</a>. Réponse sous 24-48h en général. Le <a href="{{ route('forum.index') }}">forum</a> reste aussi une excellente option pour l'entraide entre apprenants.
                </div>
            </div>
        </div>

        <div class="faq-item" data-cat="support" data-search="newsletter nouveautés informé réseaux sociaux">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-bell"></i></div>
                <h3>Comment rester informé des nouveautés ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    Abonnez-vous à notre newsletter, suivez-nous sur Facebook, LinkedIn et TikTok, ou activez les notifications sur notre chaîne YouTube. Nous publions régulièrement de nouvelles formations, documents et offres d'emploi.
                </div>
            </div>
        </div>

        <div class="faq-item" data-cat="support" data-search="mobile smartphone tablette responsive">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-mobile-alt"></i></div>
                <h3>Puis-je accéder au site depuis mon mobile ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    Oui, le site est entièrement responsive (mobile, tablette, ordinateur). Pour coder confortablement, un ordinateur reste préférable ; le mobile est parfait pour suivre les cours théoriques en déplacement.
                </div>
            </div>
        </div>

        <div class="faq-item" data-cat="support" data-search="mise à jour contenu actualisé usage commercial">
            <div class="faq-question">
                <div class="faq-question-icon"><i class="fas fa-sync-alt"></i></div>
                <h3>Les contenus sont-ils régulièrement mis à jour ?</h3>
                <i class="fas fa-chevron-down faq-chevron"></i>
            </div>
            <div class="faq-answer-wrap">
                <div class="faq-answer">
                    Oui, nous révisons nos formations régulièrement pour suivre les dernières versions des langages et frameworks. Les projets que vous créez avec vos connaissances vous appartiennent entièrement ; seule la redistribution commerciale du contenu des formations lui-même nécessite notre accord écrit.
                </div>
            </div>
        </div>

    </div>

    <p class="faq-no-results" id="faqNoResults">
        <i class="fas fa-search" style="display:block; font-size:2rem; margin-bottom:12px; opacity:0.5;"></i>
        Aucune question ne correspond à votre recherche. <a href="{{ route('contact') }}" style="color:#06b6d4;">Contactez-nous directement</a>.
    </p>

    <!-- CTA -->
    <div class="faq-cta">
        <div class="faq-cta-content">
            <h3>Vous avez d'autres questions ?</h3>
            <p>Notre équipe et la communauté sont là pour vous aider.</p>
            <div class="faq-cta-actions">
                <a href="{{ route('contact') }}" class="faq-cta-button">
                    <i class="fas fa-envelope"></i> Nous contacter
                </a>
                <a href="{{ route('forum.index') }}" class="faq-cta-button is-secondary">
                    <i class="fas fa-comments"></i> Rejoindre le forum
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    const items = Array.from(document.querySelectorAll('.faq-item'));
    const pills = Array.from(document.querySelectorAll('.faq-cat-pill'));
    const searchInput = document.getElementById('faqSearchInput');
    const resultsCount = document.getElementById('faqResultsCount');
    const noResults = document.getElementById('faqNoResults');

    let activeCategory = 'all';

    // Accordéon : un seul item ouvert à la fois
    items.forEach(item => {
        item.querySelector('.faq-question').addEventListener('click', () => {
            const wasOpen = item.classList.contains('is-open');
            items.forEach(i => i.classList.remove('is-open'));
            if (!wasOpen) item.classList.add('is-open');
        });
    });

    pills.forEach(pill => {
        pill.addEventListener('click', () => {
            pills.forEach(p => p.classList.remove('is-active'));
            pill.classList.add('is-active');
            activeCategory = pill.dataset.cat;
            applyFilters();
        });
    });

    searchInput.addEventListener('input', applyFilters);

    function applyFilters() {
        const query = searchInput.value.trim().toLowerCase();
        let visibleCount = 0;

        items.forEach(item => {
            const matchesCategory = activeCategory === 'all' || item.dataset.cat === activeCategory;
            const haystack = (item.querySelector('h3').textContent + ' ' + (item.dataset.search || '')).toLowerCase();
            const matchesSearch = !query || haystack.includes(query);
            const visible = matchesCategory && matchesSearch;

            item.style.display = visible ? '' : 'none';
            if (visible) visibleCount++;
        });

        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        resultsCount.textContent = query ? `${visibleCount} question${visibleCount > 1 ? 's' : ''} trouvée${visibleCount > 1 ? 's' : ''}` : '';
    }
})();
</script>
@endpush
