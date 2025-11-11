@extends('layouts.app')

@section('title', 'Conditions d\'Utilisation | NiangProgrammeur')

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
            Conditions d'Utilisation
        </h1>
        <div class="max-w-4xl mx-auto bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-8 border border-cyan-500/20">
            <p class="text-gray-400 text-sm mb-8">Dernière mise à jour : {{ date('d/m/Y') }}</p>
            
            <h2 class="text-2xl font-bold text-cyan-400 mb-4">1. Acceptation des conditions</h2>
            <p class="text-gray-300 leading-relaxed mb-6">
                En accédant et en utilisant le site web NiangProgrammeur (ci-après "le Site"), vous acceptez d'être lié par les présentes conditions d'utilisation, toutes les lois et réglementations applicables. Si vous n'acceptez pas l'une de ces conditions, vous n'êtes pas autorisé à utiliser ou à accéder à ce Site.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">2. Description du service</h2>
            <p class="text-gray-300 leading-relaxed mb-6">
                NiangProgrammeur est une plateforme éducative qui propose des formations gratuites en développement web, incluant HTML5, CSS3, JavaScript, PHP, Bootstrap, Git, WordPress et Intelligence Artificielle. Nous fournissons du contenu éducatif, des tutoriels et des ressources pour aider les apprenants à développer leurs compétences en programmation.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">3. Utilisation du site</h2>
            <h3 class="text-xl font-semibold text-gray-200 mb-3">3.1 Utilisation autorisée</h3>
            <p class="text-gray-300 leading-relaxed mb-4">Vous pouvez utiliser notre Site pour :</p>
            <ul class="list-disc list-inside text-gray-300 mb-4 ml-4">
                <li>Accéder aux formations et tutoriels gratuits</li>
                <li>Lire et apprendre du contenu éducatif</li>
                <li>Nous contacter pour des questions ou demandes</li>
                <li>Partager le contenu sur les réseaux sociaux (avec attribution)</li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-200 mb-3">3.2 Utilisation interdite</h3>
            <p class="text-gray-300 leading-relaxed mb-4">Vous vous engagez à ne pas :</p>
            <ul class="list-disc list-inside text-gray-300 mb-6 ml-4">
                <li>Utiliser le Site à des fins illégales ou non autorisées</li>
                <li>Copier, reproduire ou distribuer le contenu sans autorisation</li>
                <li>Tenter d'accéder à des zones non autorisées du Site</li>
                <li>Transmettre des virus, malwares ou tout code malveillant</li>
                <li>Harceler, menacer ou nuire à d'autres utilisateurs</li>
                <li>Utiliser des robots, scrapers ou autres moyens automatisés</li>
                <li>Se faire passer pour une autre personne ou entité</li>
            </ul>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">4. Propriété intellectuelle</h2>
            <h3 class="text-xl font-semibold text-gray-200 mb-3">4.1 Droits d'auteur</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                Tout le contenu présent sur le Site, y compris mais sans s'y limiter, les textes, graphiques, logos, images, clips audio, téléchargements numériques et compilations de données, est la propriété de NiangProgrammeur ou de ses fournisseurs de contenu et est protégé par les lois internationales sur le droit d'auteur.
            </p>

            <h3 class="text-xl font-semibold text-gray-200 mb-3">4.2 Licence d'utilisation</h3>
            <p class="text-gray-300 leading-relaxed mb-6">
                Nous vous accordons une licence limitée, non exclusive, non transférable et révocable pour accéder et utiliser le Site à des fins personnelles et éducatives uniquement. Cette licence ne vous donne pas le droit de revendre ou d'utiliser commercialement le contenu du Site.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">5. Contenu utilisateur</h2>
            <p class="text-gray-300 leading-relaxed mb-4">
                Si vous nous soumettez du contenu (commentaires, suggestions, messages via le formulaire de contact), vous nous accordez une licence mondiale, non exclusive, libre de redevances pour utiliser, reproduire et afficher ce contenu.
            </p>
            <p class="text-gray-300 leading-relaxed mb-6">
                Vous garantissez que tout contenu que vous soumettez ne viole aucun droit de tiers et ne contient aucun contenu illégal, diffamatoire ou inapproprié.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">6. Limitation de responsabilité</h2>
            <h3 class="text-xl font-semibold text-gray-200 mb-3">6.1 Contenu "tel quel"</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                Le Site et son contenu sont fournis "tels quels" sans garantie d'aucune sorte, expresse ou implicite. Nous ne garantissons pas que le Site sera ininterrompu, sécurisé ou exempt d'erreurs.
            </p>

            <h3 class="text-xl font-semibold text-gray-200 mb-3">6.2 Exclusion de responsabilité</h3>
            <p class="text-gray-300 leading-relaxed mb-6">
                En aucun cas NiangProgrammeur ne sera responsable des dommages directs, indirects, accessoires, spéciaux ou consécutifs résultant de l'utilisation ou de l'impossibilité d'utiliser le Site, même si nous avons été informés de la possibilité de tels dommages.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">7. Liens externes</h2>
            <p class="text-gray-300 leading-relaxed mb-6">
                Le Site peut contenir des liens vers des sites web tiers. Ces liens sont fournis uniquement pour votre commodité. Nous n'avons aucun contrôle sur ces sites et n'assumons aucune responsabilité quant à leur contenu ou leurs pratiques de confidentialité.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">8. Publicité</h2>
            <p class="text-gray-300 leading-relaxed mb-6">
                Le Site utilise Google AdSense pour afficher des publicités. En utilisant le Site, vous acceptez que des publicités tierces puissent être affichées. Ces publicités peuvent utiliser des cookies et d'autres technologies de suivi. Consultez notre <a href="{{ route('privacy-policy') }}" class="text-cyan-400 hover:underline">Politique de Confidentialité</a> pour plus d'informations.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">9. Modifications du service</h2>
            <p class="text-gray-300 leading-relaxed mb-6">
                Nous nous réservons le droit de modifier, suspendre ou interrompre tout ou partie du Site à tout moment, avec ou sans préavis. Nous ne serons pas responsables envers vous ou envers des tiers pour toute modification, suspension ou interruption du Site.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">10. Modifications des conditions</h2>
            <p class="text-gray-300 leading-relaxed mb-6">
                Nous nous réservons le droit de modifier ces conditions d'utilisation à tout moment. Les modifications entreront en vigueur dès leur publication sur le Site. Votre utilisation continue du Site après la publication des modifications constitue votre acceptation de ces modifications.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">11. Résiliation</h2>
            <p class="text-gray-300 leading-relaxed mb-6">
                Nous nous réservons le droit de résilier ou de suspendre votre accès au Site immédiatement, sans préavis ni responsabilité, pour quelque raison que ce soit, y compris, sans limitation, si vous violez les présentes conditions d'utilisation.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">12. Droit applicable</h2>
            <p class="text-gray-300 leading-relaxed mb-6">
                Les présentes conditions d'utilisation sont régies et interprétées conformément aux lois du Sénégal. Tout litige découlant de ces conditions sera soumis à la juridiction exclusive des tribunaux sénégalais.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">13. Divisibilité</h2>
            <p class="text-gray-300 leading-relaxed mb-6">
                Si une disposition des présentes conditions est jugée invalide ou inapplicable, cette disposition sera limitée ou éliminée dans la mesure minimale nécessaire, et les autres dispositions resteront pleinement en vigueur.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">14. Contact</h2>
            <p class="text-gray-300 leading-relaxed mb-4">
                Pour toute question concernant ces conditions d'utilisation, veuillez nous contacter :
            </p>
            <ul class="list-none text-gray-300 mb-6">
                <li class="mb-2"><strong>Email :</strong> <a href="mailto:NiangProgrammeur@gmail.com" class="text-cyan-400 hover:underline">NiangProgrammeur@gmail.com</a></li>
                <li class="mb-2"><strong>Téléphone :</strong> <a href="tel:+221783123657" class="text-cyan-400 hover:underline">+221 78 312 36 57</a></li>
                <li class="mb-2"><strong>Adresse :</strong> Dakar, Sénégal</li>
            </ul>
        </div>
    </div>
</section>
@endsection
