@extends('layouts.app')

@section('title', 'FAQ - Questions Fréquentes | DevFormation')

@section('content')
<!-- Hero Section -->
<section class="py-20 relative overflow-hidden pt-24">
    <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/10 to-teal-500/10"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-block mb-6">
                <div class="bg-gradient-to-r from-cyan-500 to-teal-500 p-4 rounded-2xl">
                    <i class="fas fa-question-circle text-5xl"></i>
                </div>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-cyan-400 via-teal-400 to-cyan-500 bg-clip-text text-transparent">
                Foire Aux Questions
            </h1>
            <p class="text-xl text-gray-300">Trouvez rapidement les réponses à vos questions</p>
        </div>
    </div>
</section>

<!-- FAQ Content -->
<section class="py-16">
    <div class="container mx-auto px-6 max-w-4xl">
        <!-- Question 1 -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 mb-6 border border-cyan-500/20 hover:border-cyan-500/40 transition">
            <h3 class="text-2xl font-bold text-cyan-400 mb-3 flex items-center">
                <i class="fas fa-chevron-right mr-3"></i>
                Quels sont les prérequis pour suivre vos formations ?
            </h3>
            <p class="text-gray-300 leading-relaxed">
                Nos formations sont conçues pour tous les niveaux. Pour les débutants, aucun prérequis n'est nécessaire. Pour les formations avancées, une connaissance de base en HTML/CSS est recommandée. Chaque formation indique clairement son niveau de difficulté.
            </p>
        </div>

        <!-- Question 2 -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 mb-6 border border-cyan-500/20 hover:border-cyan-500/40 transition">
            <h3 class="text-2xl font-bold text-cyan-400 mb-3 flex items-center">
                <i class="fas fa-chevron-right mr-3"></i>
                Les formations sont-elles gratuites ?
            </h3>
            <p class="text-gray-300 leading-relaxed">
                Oui, tous nos cours en ligne sont entièrement gratuits et accessibles à tous. Notre mission est de démocratiser l'apprentissage du développement web.
            </p>
        </div>

        <!-- Question 3 -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 mb-6 border border-cyan-500/20 hover:border-cyan-500/40 transition">
            <h3 class="text-2xl font-bold text-cyan-400 mb-3 flex items-center">
                <i class="fas fa-chevron-right mr-3"></i>
                Puis-je obtenir un certificat à la fin de la formation ?
            </h3>
            <p class="text-gray-300 leading-relaxed">
                Actuellement, nous ne délivrons pas de certificats officiels. Cependant, vous pouvez créer un portfolio avec les projets réalisés pendant les formations pour démontrer vos compétences.
            </p>
        </div>

        <!-- Question 4 -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 mb-6 border border-cyan-500/20 hover:border-cyan-500/40 transition">
            <h3 class="text-2xl font-bold text-cyan-400 mb-3 flex items-center">
                <i class="fas fa-chevron-right mr-3"></i>
                Combien de temps faut-il pour terminer une formation ?
            </h3>
            <p class="text-gray-300 leading-relaxed">
                La durée varie selon la formation et votre rythme d'apprentissage. En moyenne, comptez 2 à 4 semaines pour une formation complète en y consacrant quelques heures par semaine. Vous pouvez apprendre à votre propre rythme.
            </p>
        </div>

        <!-- Question 5 -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 mb-6 border border-cyan-500/20 hover:border-cyan-500/40 transition">
            <h3 class="text-2xl font-bold text-cyan-400 mb-3 flex items-center">
                <i class="fas fa-chevron-right mr-3"></i>
                Proposez-vous un support ou de l'aide ?
            </h3>
            <p class="text-gray-300 leading-relaxed">
                Vous pouvez nous contacter via WhatsApp ou par téléphone pour toute question. Nous nous efforçons de répondre dans les 24-48 heures.
            </p>
        </div>

        <!-- Question 6 -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 mb-6 border border-cyan-500/20 hover:border-cyan-500/40 transition">
            <h3 class="text-2xl font-bold text-cyan-400 mb-3 flex items-center">
                <i class="fas fa-chevron-right mr-3"></i>
                Les contenus sont-ils régulièrement mis à jour ?
            </h3>
            <p class="text-gray-300 leading-relaxed">
                Oui, nous mettons à jour nos formations régulièrement pour refléter les dernières technologies et meilleures pratiques du développement web.
            </p>
        </div>

        <!-- Question 7 -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 mb-6 border border-cyan-500/20 hover:border-cyan-500/40 transition">
            <h3 class="text-2xl font-bold text-cyan-400 mb-3 flex items-center">
                <i class="fas fa-chevron-right mr-3"></i>
                Puis-je utiliser le contenu pour un usage commercial ?
            </h3>
            <p class="text-gray-300 leading-relaxed">
                Le contenu des formations est destiné à un usage personnel et éducatif. Pour toute utilisation commerciale, veuillez nous contacter pour obtenir une autorisation.
            </p>
        </div>

        <!-- Question 8 -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-6 mb-6 border border-cyan-500/20 hover:border-cyan-500/40 transition">
            <h3 class="text-2xl font-bold text-cyan-400 mb-3 flex items-center">
                <i class="fas fa-chevron-right mr-3"></i>
                Comment puis-je rester informé des nouvelles formations ?
            </h3>
            <p class="text-gray-300 leading-relaxed">
                Suivez-nous sur nos réseaux sociaux ou contactez-nous pour être informé des nouvelles formations et mises à jour.
            </p>
        </div>

        <!-- CTA -->
        <div class="mt-12 text-center bg-gradient-to-r from-cyan-500/10 to-teal-500/10 rounded-2xl p-8 border border-cyan-500/30">
            <h3 class="text-2xl font-bold mb-4">Vous avez d'autres questions ?</h3>
            <p class="text-gray-300 mb-6">N'hésitez pas à nous contacter, nous serons ravis de vous aider !</p>
            <a href="{{ route('home') }}#contact" class="inline-block px-8 py-3 bg-gradient-to-r from-cyan-500 to-teal-600 text-white font-medium rounded-xl hover:shadow-lg hover:shadow-cyan-500/50 transform hover:scale-105 transition duration-300">
                <i class="fas fa-envelope mr-2"></i>
                Nous contacter
            </a>
        </div>
    </div>
</section>
@endsection
