@extends('layouts.app')

@section('title', 'Mentions Légales | NiangProgrammeur')

@section('styles')
<style>
    body {
        overflow-x: hidden;
    }
</style>
@endsection

@section('content')
@php
    $siteSettings = \App\Models\SiteSetting::first();
@endphp

<section class="py-20 relative overflow-hidden pt-24">
    <div class="container mx-auto px-6">
        <h1 class="text-5xl font-bold text-center mb-8 bg-gradient-to-r from-cyan-400 to-teal-500 bg-clip-text text-transparent">
            Mentions Légales
        </h1>
        <div class="max-w-4xl mx-auto bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-8 border border-cyan-500/20">
            <p class="text-gray-400 text-sm mb-8">Dernière mise à jour : {{ date('d/m/Y') }}</p>
            
            <h2 class="text-2xl font-bold text-cyan-400 mb-4">1. Informations légales</h2>
            <h3 class="text-xl font-semibold text-gray-200 mb-3">1.1 Éditeur du site</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                Le site <strong>NiangProgrammeur</strong> (ci-après "le Site") est édité par :
            </p>
            <ul class="list-none text-gray-300 mb-6 ml-4">
                <li class="mb-2"><strong>Nom :</strong> Bassirou Niang ({{ $siteSettings->site_name ?? 'NiangProgrammeur' }})</li>
                <li class="mb-2"><strong>Statut :</strong> Auto-entrepreneur / Développeur indépendant</li>
                <li class="mb-2"><strong>Adresse :</strong> {{ $siteSettings->contact_address ?? 'Dakar, Sénégal' }}</li>
                <li class="mb-2"><strong>Email :</strong> <a href="mailto:{{ $siteSettings->contact_email ?? 'NiangProgrammeur@gmail.com' }}" class="text-cyan-400 hover:underline">{{ $siteSettings->contact_email ?? 'NiangProgrammeur@gmail.com' }}</a></li>
                <li class="mb-2"><strong>Téléphone :</strong> <a href="tel:{{ str_replace(' ', '', $siteSettings->contact_phone ?? '+221783123657') }}" class="text-cyan-400 hover:underline">{{ $siteSettings->contact_phone ?? '+221 78 312 36 57' }}</a></li>
            </ul>

            <h3 class="text-xl font-semibold text-gray-200 mb-3">1.2 Directeur de publication</h3>
            <p class="text-gray-300 leading-relaxed mb-6">
                Le directeur de la publication du Site est <strong>Bassirou Niang</strong>.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">2. Hébergement</h2>
            <h3 class="text-xl font-semibold text-gray-200 mb-3">2.1 Hébergeur du site</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                Le Site est hébergé par :
            </p>
            <ul class="list-none text-gray-300 mb-6 ml-4">
                <li class="mb-2"><strong>Nom :</strong> LWS (Ligne Web Services)</li>
                <li class="mb-2"><strong>Raison sociale :</strong> LWS SARL</li>
                <li class="mb-2"><strong>Adresse :</strong> 4 Rue Galvani, 75017 Paris, France</li>
                <li class="mb-2"><strong>Téléphone :</strong> +33 (0)1 77 62 30 03</li>
                <li class="mb-2"><strong>Site web :</strong> <a href="https://www.lws.fr" target="_blank" class="text-cyan-400 hover:underline">www.lws.fr</a></li>
            </ul>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">3. Propriété intellectuelle</h2>
            <h3 class="text-xl font-semibold text-gray-200 mb-3">3.1 Droits d'auteur</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                L'ensemble du contenu présent sur le Site, incluant mais ne se limitant pas aux textes, images, graphismes, logos, icônes, vidéos, sons, logiciels et bases de données, est la propriété exclusive de NiangProgrammeur ou de ses partenaires et est protégé par les lois françaises et internationales relatives à la propriété intellectuelle.
            </p>

            <h3 class="text-xl font-semibold text-gray-200 mb-3">3.2 Utilisation du contenu</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                Toute reproduction, représentation, modification, publication, adaptation de tout ou partie des éléments du Site, quel que soit le moyen ou le procédé utilisé, est interdite, sauf autorisation écrite préalable de NiangProgrammeur.
            </p>

            <h3 class="text-xl font-semibold text-gray-200 mb-3">3.3 Marques et logos</h3>
            <p class="text-gray-300 leading-relaxed mb-6">
                Les marques, logos, signes et tout autre contenu du Site font l'objet d'une protection par le Code de la propriété intellectuelle. Toute reproduction totale ou partielle de ces marques ou de ces logos sans l'autorisation expresse de NiangProgrammeur est interdite.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">4. Responsabilité</h2>
            <h3 class="text-xl font-semibold text-gray-200 mb-3">4.1 Contenu du site</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                NiangProgrammeur s'efforce de fournir sur le Site des informations aussi précises que possible. Toutefois, il ne pourra être tenu responsable des omissions, des inexactitudes et des carences dans la mise à jour, qu'elles soient de son fait ou du fait des tiers partenaires qui lui fournissent ces informations.
            </p>

            <h3 class="text-xl font-semibold text-gray-200 mb-3">4.2 Disponibilité du site</h3>
            <p class="text-gray-300 leading-relaxed mb-4">
                NiangProgrammeur ne peut être tenu responsable en cas d'interruption du Site, de survenance de bugs ou de tout dommage résultant d'une intrusion frauduleuse d'un tiers ayant entraîné une modification des informations mises à disposition sur le Site.
            </p>

            <h3 class="text-xl font-semibold text-gray-200 mb-3">4.3 Liens hypertextes</h3>
            <p class="text-gray-300 leading-relaxed mb-6">
                Le Site peut contenir des liens hypertextes vers d'autres sites. NiangProgrammeur n'exerce aucun contrôle sur ces sites et décline toute responsabilité quant à leur contenu.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">5. Protection des données personnelles</h2>
            <p class="text-gray-300 leading-relaxed mb-4">
                Le traitement des données personnelles collectées sur le Site est régi par notre <a href="{{ route('privacy-policy') }}" class="text-cyan-400 hover:underline">Politique de Confidentialité</a>.
            </p>
            <p class="text-gray-300 leading-relaxed mb-6">
                Conformément à la loi "Informatique et Libertés" du 6 janvier 1978 modifiée et au Règlement Général sur la Protection des Données (RGPD), vous disposez d'un droit d'accès, de rectification, de suppression et d'opposition aux données personnelles vous concernant.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">6. Cookies</h2>
            <p class="text-gray-300 leading-relaxed mb-6">
                Le Site utilise des cookies pour améliorer l'expérience utilisateur et pour des fins statistiques. Pour plus d'informations sur l'utilisation des cookies, veuillez consulter notre <a href="{{ route('privacy-policy') }}" class="text-cyan-400 hover:underline">Politique de Confidentialité</a>.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">7. Droit applicable et juridiction</h2>
            <p class="text-gray-300 leading-relaxed mb-6">
                Les présentes mentions légales sont régies par le droit sénégalais. En cas de litige et à défaut d'accord amiable, le litige sera porté devant les tribunaux sénégalais conformément aux règles de compétence en vigueur.
            </p>

            <h2 class="text-2xl font-bold text-cyan-400 mb-4">8. Contact</h2>
            <p class="text-gray-300 leading-relaxed mb-4">
                Pour toute question concernant ces mentions légales ou pour exercer vos droits, vous pouvez nous contacter :
            </p>
            <ul class="list-none text-gray-300 mb-6">
                <li class="mb-2"><strong>Par email :</strong> <a href="mailto:{{ $siteSettings->contact_email ?? 'NiangProgrammeur@gmail.com' }}" class="text-cyan-400 hover:underline">{{ $siteSettings->contact_email ?? 'NiangProgrammeur@gmail.com' }}</a></li>
                <li class="mb-2"><strong>Par téléphone :</strong> <a href="tel:{{ str_replace(' ', '', $siteSettings->contact_phone ?? '+221783123657') }}" class="text-cyan-400 hover:underline">{{ $siteSettings->contact_phone ?? '+221 78 312 36 57' }}</a></li>
                <li class="mb-2"><strong>Par courrier :</strong> {{ $siteSettings->contact_address ?? 'Dakar, Sénégal' }}</li>
            </ul>
        </div>
    </div>
</section>
@endsection
