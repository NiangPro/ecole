@extends('layouts.app')

@section('title', 'Politique de Confidentialité | DevFormation')

@section('styles')
<style>
    body {
        overflow-x: hidden;
    }
</style>
@endsection

@section('content')
<section class="py-20 relative overflow-hidden pt-24">
    <div class="container mx-auto px-6">
        <h1 class="text-5xl font-bold text-center mb-8 bg-gradient-to-r from-cyan-400 to-teal-500 bg-clip-text text-transparent">
            Politique de Confidentialité
        </h1>
        <div class="max-w-4xl mx-auto bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-8 border border-cyan-500/20">
            <p class="text-gray-400 text-sm mb-8">Dernière mise à jour : {{ date('d/m/Y') }}</p>
            
            <h2 class="text-2xl font-bold text-cyan-400 mb-4">1. Introduction</h2>
            <p class="text-gray-300 leading-relaxed mb-6">
                Chez DevFormation (ci-après "nous", "notre" ou "nos"), nous respectons votre vie privée et nous nous engageons à protéger vos données personnelles. Cette politique de confidentialité explique comment nous collectons, utilisons, partageons et protégeons vos informations personnelles lorsque vous utilisez notre site web.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">2. Informations que nous collectons</h2>
            <h3 class="text-xl font-semibold text-gray-200 mb-3">2.1 Informations fournies volontairement</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                Lorsque vous nous contactez via notre formulaire de contact, nous pouvons collecter :
            </p>
            <ul class="list-disc list-inside text-gray-300 mb-6 ml-4">
                <li>Votre nom</li>
                <li>Votre adresse e-mail</li>
                <li>Votre message ou demande</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-200 mb-3">2.2 Informations collectées automatiquement</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                Lorsque vous visitez notre site, nous collectons automatiquement certaines informations, notamment :
            </p>
            <ul class="list-disc list-inside text-gray-300 mb-6 ml-4">
                <li>Votre adresse IP</li>
                <li>Le type de navigateur et le système d'exploitation</li>
                <li>Les pages visitées et le temps passé sur chaque page</li>
                <li>La date et l'heure de votre visite</li>
                <li>Les sites web référents</li>
            </ul>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">3. Utilisation de vos informations</h2>
            <p class="text-gray-300 leading-relaxed mb-4">Nous utilisons les informations collectées pour :</p>
            <ul class="list-disc list-inside text-gray-300 mb-6 ml-4">
                <li>Répondre à vos demandes et questions</li>
                <li>Améliorer notre site web et nos services</li>
                <li>Analyser l'utilisation de notre site</li>
                <li>Vous envoyer des communications marketing (avec votre consentement)</li>
                <li>Respecter nos obligations légales</li>
            </ul>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">4. Cookies et technologies similaires</h2>
            <p class="text-gray-300 leading-relaxed mb-6">
                Nous utilisons des cookies et des technologies similaires pour améliorer votre expérience sur notre site. Les cookies sont de petits fichiers texte stockés sur votre appareil. Vous pouvez configurer votre navigateur pour refuser les cookies, mais cela peut affecter certaines fonctionnalités du site.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">5. Google AdSense et Publicités</h2>
            <h3 class="text-xl font-semibold text-gray-200 mb-3">5.1 Utilisation de Google AdSense</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                Notre site utilise Google AdSense, un service de publicité fourni par Google LLC. Google AdSense utilise des cookies et des technologies similaires pour diffuser des annonces personnalisées basées sur vos visites précédentes sur notre site ou d'autres sites web.
            </p>
            
            <h3 class="text-xl font-semibold text-gray-200 mb-3">5.2 Données collectées par Google AdSense</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                Google AdSense peut collecter et utiliser les informations suivantes :
            </p>
            <ul class="list-disc list-inside text-gray-300 mb-4 ml-4">
                <li>Cookies et identifiants d'appareil</li>
                <li>Adresse IP</li>
                <li>Type de navigateur et système d'exploitation</li>
                <li>Pages visitées et temps passé sur le site</li>
                <li>Interactions avec les annonces</li>
            </ul>
            
            <h3 class="text-xl font-semibold text-gray-200 mb-3">5.3 Publicité personnalisée</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                Google utilise ces informations pour :
            </p>
            <ul class="list-disc list-inside text-gray-300 mb-4 ml-4">
                <li>Diffuser des annonces pertinentes</li>
                <li>Mesurer l'efficacité des annonces</li>
                <li>Créer des rapports sur les performances publicitaires</li>
            </ul>
            
            <h3 class="text-xl font-semibold text-gray-200 mb-3">5.4 Gestion de vos préférences publicitaires</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                Vous pouvez contrôler les annonces que vous voyez :
            </p>
            <ul class="list-disc list-inside text-gray-300 mb-4 ml-4">
                <li>Visitez les <a href="https://www.google.com/settings/ads" target="_blank" class="text-cyan-400 hover:underline">Paramètres des annonces Google</a> pour désactiver la personnalisation</li>
                <li>Utilisez le <a href="https://optout.aboutads.info/" target="_blank" class="text-cyan-400 hover:underline">NAI Consumer Opt-Out</a></li>
                <li>Configurez votre navigateur pour bloquer les cookies tiers</li>
            </ul>
            
            <h3 class="text-xl font-semibold text-gray-200 mb-3">5.5 Politique de confidentialité de Google</h3>
            <p class="text-gray-300 leading-relaxed mb-6">
                Pour plus d'informations sur la manière dont Google collecte et utilise vos données, consultez la <a href="https://policies.google.com/privacy" target="_blank" class="text-cyan-400 hover:underline">Politique de confidentialité de Google</a> et les <a href="https://policies.google.com/technologies/partner-sites" target="_blank" class="text-cyan-400 hover:underline">Règles de confidentialité des partenaires Google</a>.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">6. Partage de vos informations</h2>
            <p class="text-gray-300 leading-relaxed mb-6">
                Nous ne vendons, n'échangeons ni ne louons vos informations personnelles à des tiers. Nous pouvons partager vos informations avec des prestataires de services tiers qui nous aident à exploiter notre site web, à condition qu'ils acceptent de garder ces informations confidentielles.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">7. Sécurité de vos données</h2>
            <p class="text-gray-300 leading-relaxed mb-6">
                Nous mettons en œuvre des mesures de sécurité appropriées pour protéger vos informations personnelles contre tout accès, modification, divulgation ou destruction non autorisés.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">8. Vos droits (RGPD)</h2>
            <p class="text-gray-300 leading-relaxed mb-4">
                Conformément au Règlement Général sur la Protection des Données (RGPD), vous disposez des droits suivants :
            </p>
            
            <h3 class="text-xl font-semibold text-gray-200 mb-3">8.1 Droit d'accès</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                Vous avez le droit d'obtenir la confirmation que vos données personnelles sont traitées et d'accéder à ces données.
            </p>
            
            <h3 class="text-xl font-semibold text-gray-200 mb-3">8.2 Droit de rectification</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                Vous avez le droit de demander la correction de vos données personnelles inexactes ou incomplètes.
            </p>
            
            <h3 class="text-xl font-semibold text-gray-200 mb-3">8.3 Droit à l'effacement ("droit à l'oubli")</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                Vous avez le droit de demander la suppression de vos données personnelles dans certaines circonstances.
            </p>
            
            <h3 class="text-xl font-semibold text-gray-200 mb-3">8.4 Droit à la limitation du traitement</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                Vous avez le droit de demander la limitation du traitement de vos données personnelles.
            </p>
            
            <h3 class="text-xl font-semibold text-gray-200 mb-3">8.5 Droit à la portabilité</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                Vous avez le droit de recevoir vos données personnelles dans un format structuré et de les transmettre à un autre responsable du traitement.
            </p>
            
            <h3 class="text-xl font-semibold text-gray-200 mb-3">8.6 Droit d'opposition</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                Vous avez le droit de vous opposer au traitement de vos données personnelles pour des raisons tenant à votre situation particulière.
            </p>
            
            <h3 class="text-xl font-semibold text-gray-200 mb-3">8.7 Retrait du consentement</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                Lorsque le traitement est basé sur votre consentement, vous avez le droit de retirer ce consentement à tout moment.
            </p>
            
            <h3 class="text-xl font-semibold text-gray-200 mb-3">8.8 Exercer vos droits</h3>
            <p class="text-gray-300 leading-relaxed mb-6">
                Pour exercer l'un de ces droits, veuillez nous contacter à l'adresse : <a href="mailto:NiangProgrammeur@gmail.com" class="text-cyan-400 hover:underline">NiangProgrammeur@gmail.com</a>. Nous répondrons à votre demande dans un délai d'un mois.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">9. Liens vers d'autres sites</h2>
            <p class="text-gray-300 leading-relaxed mb-6">
                Notre site peut contenir des liens vers d'autres sites web. Nous ne sommes pas responsables des pratiques de confidentialité de ces sites. Nous vous encourageons à lire les politiques de confidentialité de chaque site que vous visitez.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">10. Modifications de cette politique</h2>
            <p class="text-gray-300 leading-relaxed mb-6">
                Nous nous réservons le droit de modifier cette politique de confidentialité à tout moment. Les modifications seront publiées sur cette page avec une date de mise à jour révisée.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">11. Nous contacter</h2>
            <p class="text-gray-300 leading-relaxed mb-4">
                Si vous avez des questions concernant cette politique de confidentialité, vous pouvez nous contacter :
            </p>
            <ul class="list-none text-gray-300 mb-6">
                <li class="mb-2"><strong>Email :</strong> NiangProgrammeur@gmail.com</li>
                <li class="mb-2"><strong>Téléphone :</strong> +221 78 312 36 57</li>
                <li class="mb-2"><strong>Adresse :</strong> Dakar, Sénégal</li>
            </ul>
        </div>
    </div>
</section>
@endsection
