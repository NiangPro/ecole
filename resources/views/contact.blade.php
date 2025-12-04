@extends('layouts.app')

@section('title', 'Contact - NiangProgrammeur | Prenez Contact')
@section('meta_description', 'Contactez NiangProgrammeur pour vos questions, projets ou collaborations. Formulaire de contact, email et téléphone disponibles. Réponse sous 24h.')
@section('meta_keywords', 'contact NiangProgrammeur, contacter développeur, projet web, collaboration développement, devis site web')

@section('styles')
<style>
    body {
        overflow-x: hidden;
    }
    
    @keyframes gradient-shift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    .gradient-text {
        background: linear-gradient(135deg, #06b6d4, #14b8a6, #06b6d4);
        background-size: 200% 200%;
        animation: gradient-shift 3s ease infinite;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .contact-input {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 10px;
        color: white;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    body:not(.dark-mode) .contact-input {
        background: rgba(255, 255, 255, 0.9) !important;
        color: rgba(30, 41, 59, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
    
    .contact-input:focus {
        outline: none;
        border-color: #06b6d4;
        background: rgba(255, 255, 255, 0.08);
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.3);
    }
    
    body:not(.dark-mode) .contact-input:focus {
        background: rgba(255, 255, 255, 1) !important;
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.2) !important;
    }
    
    .contact-input::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }
    
    body:not(.dark-mode) .contact-input::placeholder {
        color: rgba(30, 41, 59, 0.5) !important;
    }
    
    textarea.contact-input {
        resize: vertical;
        min-height: 120px;
    }
    
    /* Dark Mode Styles for Contact Page */
    body:not(.dark-mode) section.relative.min-h-screen {
        background: linear-gradient(135deg, rgba(248, 250, 252, 0.95) 0%, rgba(241, 245, 249, 0.95) 100%) !important;
    }
    
    body:not(.dark-mode) .text-gray-300 {
        color: rgba(30, 41, 59, 0.8) !important;
    }
    
    body:not(.dark-mode) .text-gray-400 {
        color: rgba(30, 41, 59, 0.6) !important;
    }
    
    body:not(.dark-mode) .bg-gradient-to-br.from-gray-900\/90.to-black\/90 {
        background: linear-gradient(to bottom right, rgba(255, 255, 255, 0.95), rgba(248, 250, 252, 0.95)) !important;
        border-color: rgba(6, 182, 212, 0.2) !important;
    }
    
    body:not(.dark-mode) .bg-green-500\/10 {
        background: rgba(34, 197, 94, 0.1) !important;
    }
    
    body:not(.dark-mode) .border-green-500\/30 {
        border-color: rgba(34, 197, 94, 0.3) !important;
    }
    
    body:not(.dark-mode) .text-green-400 {
        color: rgba(34, 197, 94, 0.8) !important;
    }
    
    /* Force text colors in light mode */
    body:not(.dark-mode) .text-white {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    body:not(.dark-mode) h1,
    body:not(.dark-mode) h2,
    body:not(.dark-mode) h3,
    body:not(.dark-mode) p {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    body:not(.dark-mode) .text-xl {
        color: rgba(30, 41, 59, 0.8) !important;
    }
    
    body:not(.dark-mode) .text-2xl {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    body:not(.dark-mode) .text-3xl {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    body:not(.dark-mode) .text-6xl {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    body:not(.dark-mode) .text-7xl {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    /* Keep gradient text as is */
    body:not(.dark-mode) .gradient-text {
        -webkit-text-fill-color: transparent !important;
    }
    
    /* Icons in cards should remain visible */
    body:not(.dark-mode) .bg-gradient-to-br.from-cyan-500.to-teal-600 .text-white,
    body:not(.dark-mode) .bg-gradient-to-br.from-teal-500.to-cyan-600 .text-white,
    body:not(.dark-mode) .bg-gradient-to-br.from-purple-500.to-pink-600 .text-white {
        color: #fff !important;
    }
    
    /* Responsive Mobile */
    @media (max-width: 768px) {
        section.relative.min-h-screen {
            padding-top: 100px !important;
            padding-bottom: 40px !important;
        }
        
        .container.mx-auto.px-6 {
            padding-left: 16px !important;
            padding-right: 16px !important;
        }
        
        h1.text-6xl {
            font-size: 2.5rem !important;
            line-height: 1.2 !important;
            margin-bottom: 1.5rem !important;
        }
        
        .text-xl {
            font-size: 1rem !important;
            line-height: 1.6 !important;
        }
        
        .max-w-4xl.mx-auto.text-center {
            margin-bottom: 2rem !important;
        }
        
        .max-w-6xl.mx-auto.grid {
            gap: 1.5rem !important;
        }
        
        .bg-gradient-to-br.from-gray-900\/90.to-black\/90 {
            padding: 1.5rem !important;
            border-radius: 1.5rem !important;
        }
        
        h2.text-3xl {
            font-size: 1.5rem !important;
            margin-bottom: 1.5rem !important;
        }
        
        .space-y-5 > * + * {
            margin-top: 1rem !important;
        }
        
        .contact-input {
            padding: 0.75rem 1rem !important;
            font-size: 0.9rem !important;
        }
        
        textarea.contact-input {
            min-height: 100px !important;
        }
        
        .space-y-6 > * + * {
            margin-top: 1.5rem !important;
        }
        
        .flex.items-start.gap-6 {
            flex-direction: column !important;
            gap: 1rem !important;
        }
        
        .w-16.h-16 {
            width: 3.5rem !important;
            height: 3.5rem !important;
        }
        
        .text-2xl {
            font-size: 1.25rem !important;
        }
        
        .text-lg {
            font-size: 1rem !important;
        }
        
        .text-gray-400 {
            font-size: 0.875rem !important;
            line-height: 1.5 !important;
        }
        
        .flex.flex-wrap.gap-4 {
            gap: 0.75rem !important;
        }
        
        .w-14.h-14 {
            width: 3rem !important;
            height: 3rem !important;
        }
        
        .text-2xl {
            font-size: 1.25rem !important;
        }
        
        button.w-full {
            padding: 0.875rem 1rem !important;
            font-size: 0.9rem !important;
        }
    }
    
    @media (max-width: 480px) {
        section.relative.min-h-screen {
            padding-top: 80px !important;
            padding-bottom: 30px !important;
        }
        
        h1.text-6xl {
            font-size: 2rem !important;
        }
        
        .text-xl {
            font-size: 0.9rem !important;
        }
        
        .bg-gradient-to-br.from-gray-900\/90.to-black\/90 {
            padding: 1.25rem !important;
        }
        
        h2.text-3xl {
            font-size: 1.25rem !important;
        }
        
        .w-16.h-16 {
            width: 3rem !important;
            height: 3rem !important;
        }
        
        .text-2xl {
            font-size: 1.125rem !important;
        }
        
        .w-14.h-14 {
            width: 2.75rem !important;
            height: 2.75rem !important;
        }
    }
    
    /* Styles pour intl-tel-input */
    .iti {
        width: 100%;
    }
    
    .iti__flag-container {
        z-index: 10;
    }
    
    .iti__selected-flag {
        padding: 0 12px;
        border-radius: 10px 0 0 10px;
    }
    
    body:not(.dark-mode) .iti__selected-flag {
        background: rgba(255, 255, 255, 0.9) !important;
    }
    
    body.dark-mode .iti__selected-flag {
        background: rgba(15, 23, 42, 0.8) !important;
    }
    
    .iti__country-list {
        z-index: 10000;
        background: rgba(15, 23, 42, 0.98);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 10px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }
    
    body:not(.dark-mode) .iti__country-list {
        background: rgba(255, 255, 255, 0.98) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
    
    .iti__country {
        color: rgba(255, 255, 255, 0.9);
        padding: 8px 12px;
    }
    
    body:not(.dark-mode) .iti__country {
        color: rgba(30, 41, 59, 0.9) !important;
    }
    
    .iti__country:hover,
    .iti__country.iti__highlight {
        background: rgba(6, 182, 212, 0.2);
    }
    
    body:not(.dark-mode) .iti__country:hover,
    body:not(.dark-mode) .iti__country.iti__highlight {
        background: rgba(6, 182, 212, 0.1) !important;
    }
    
    .iti__dial-code {
        color: rgba(255, 255, 255, 0.7);
    }
    
    body:not(.dark-mode) .iti__dial-code {
        color: rgba(30, 41, 59, 0.7) !important;
    }
</style>

<!-- Intl Tel Input CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/css/intlTelInput.css">
@endsection

@section('content')
<!-- Hero Section -->
<section class="relative min-h-screen bg-black pt-32 pb-20 overflow-hidden">
    <!-- Background Effects -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-20 left-10 w-72 h-72 bg-cyan-500 rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-teal-500 rounded-full filter blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <!-- Hero Content -->
        <div class="max-w-4xl mx-auto text-center mb-16">
            <h1 class="text-6xl md:text-7xl font-black mb-6">
                <span class="gradient-text">Prenez Contact</span>
            </h1>
            <p class="text-xl text-gray-400 max-w-2xl mx-auto">
                Une question sur nos formations ? Un projet de développement web à réaliser ? Besoin de conseils pour votre carrière 
                de développeur ? N'hésitez pas à me contacter. Je serais ravi d'échanger avec vous et de vous accompagner dans votre 
                parcours. Que vous soyez un débutant cherchant des conseils, un développeur expérimenté souhaitant collaborer, ou une 
                entreprise à la recherche de solutions web, je suis là pour vous aider.
            </p>
        </div>
        
        <div class="max-w-6xl mx-auto grid lg:grid-cols-2 gap-12">
            <!-- Formulaire de contact -->
            <div class="bg-gradient-to-br from-gray-900/90 to-black/90 backdrop-blur-xl rounded-3xl p-8 md:p-12 border border-cyan-500/30 shadow-2xl">
                <h2 class="text-3xl font-bold mb-6 gradient-text">Envoyez-moi un message</h2>
                
                @if(session('success'))
                    <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg mb-6 flex items-center gap-3">
                        <i class="fas fa-check-circle text-xl"></i>
                        <span class="text-sm">{{ session('success') }}</span>
                    </div>
                @endif
                
                <form action="{{ route('contact.send') }}" method="POST" class="space-y-5" id="contact-form">
                    @csrf
                    
                    <!-- Honeypot field (invisible pour les humains, rempli par les bots) -->
                    <div style="position: absolute; left: -9999px; opacity: 0; pointer-events: none; visibility: hidden;" aria-hidden="true">
                        <label for="website">Website (ne pas remplir)</label>
                        <input type="text" name="website" id="website" autocomplete="off" tabindex="-1">
                    </div>
                    
                    <!-- Temps de remplissage du formulaire (anti-bot) -->
                    <input type="hidden" name="_form_time" id="form-time" value="{{ microtime(true) }}">
                    
                    <div>
                        <label for="name" class="block text-sm font-semibold mb-2 text-gray-300 text-left">
                            <i class="fas fa-user mr-2 text-cyan-400"></i>Nom complet *
                        </label>
                        <input type="text" id="name" name="name" required 
                               class="contact-input" placeholder="Votre nom">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-semibold mb-2 text-gray-300 text-left">
                            <i class="fas fa-envelope mr-2 text-cyan-400"></i>Email *
                        </label>
                        <input type="email" id="email" name="email" required 
                               class="contact-input" placeholder="votre@email.com">
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-semibold mb-2 text-gray-300 text-left">
                            <i class="fas fa-phone mr-2 text-cyan-400"></i>Téléphone
                        </label>
                        <input type="tel" id="phone" name="phone" 
                               class="contact-input" placeholder="">
                        <input type="hidden" id="phone_country" name="phone_country" value="">
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-semibold mb-2 text-gray-300 text-left">
                            <i class="fas fa-tag mr-2 text-cyan-400"></i>Sujet *
                        </label>
                        <input type="text" id="subject" name="subject" required 
                               class="contact-input" placeholder="Objet de votre message">
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-semibold mb-2 text-gray-300 text-left">
                            <i class="fas fa-comment mr-2 text-cyan-400"></i>Message *
                        </label>
                        <textarea id="message" name="message" required 
                                  class="contact-input" placeholder="Votre message..."></textarea>
                    </div>
                    
                    <button type="submit" onclick="event.preventDefault(); if (typeof executeRecaptcha === 'function') { executeRecaptcha('contact-form', function() { document.getElementById('contact-form').submit(); }); } else { document.getElementById('contact-form').submit(); }" class="w-full px-6 py-3 bg-gradient-to-r from-cyan-500 to-teal-600 rounded-xl font-bold hover:shadow-2xl hover:scale-105 transition">
                        <i class="fas fa-paper-plane mr-2"></i>Envoyer le message
                    </button>
                </form>
            </div>
            
            <!-- Informations de contact -->
            <div class="space-y-6">
                <!-- Card Email -->
                <div class="bg-gradient-to-br from-gray-900/90 to-black/90 backdrop-blur-xl rounded-3xl p-8 border border-cyan-500/30 shadow-2xl hover:border-cyan-500/60 transition">
                    <div class="flex items-start gap-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-teal-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-white text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-2">Email</h3>
                            <p class="text-gray-400 mb-3">
                                Envoyez-moi un email pour toute question, demande de devis, ou proposition de collaboration. 
                                Je réponds généralement sous 24 heures, souvent même plus rapidement pour les demandes urgentes. 
                                N'hésitez pas à détailler votre projet ou votre question pour que je puisse vous apporter la meilleure réponse possible.
                            </p>
                            <a href="mailto:{{ $siteSettings->contact_email ?? 'NiangProgrammeur@gmail.com' }}" 
                               class="text-cyan-400 hover:text-cyan-300 font-semibold text-lg flex items-center gap-2">
                                {{ $siteSettings->contact_email ?? 'NiangProgrammeur@gmail.com' }}
                                <i class="fas fa-external-link-alt text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Card Téléphone -->
                <div class="bg-gradient-to-br from-gray-900/90 to-black/90 backdrop-blur-xl rounded-3xl p-8 border border-teal-500/30 shadow-2xl hover:border-teal-500/60 transition">
                    <div class="flex items-start gap-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-white text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-2">Téléphone</h3>
                            <p class="text-gray-400 mb-3">
                                Appelez-moi pour une discussion rapide et directe. Idéal pour les questions urgentes, les clarifications 
                                rapides, ou si vous préférez échanger verbalement. Je suis disponible du lundi au vendredi de 9h à 18h (heure de Dakar). 
                                Pour les appels internationaux, vous pouvez également me joindre via WhatsApp pour une communication plus flexible.
                            </p>
                            <a href="tel:{{ $siteSettings->contact_phone ?? '+221783123657' }}" 
                               class="text-teal-400 hover:text-teal-300 font-semibold text-lg flex items-center gap-2">
                                {{ $siteSettings->contact_phone ?? '+221 78 312 36 57' }}
                                <i class="fas fa-phone-alt text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Card Localisation -->
                <div class="bg-gradient-to-br from-gray-900/90 to-black/90 backdrop-blur-xl rounded-3xl p-8 border border-purple-500/30 shadow-2xl hover:border-purple-500/60 transition">
                    <div class="flex items-start gap-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-2">Localisation</h3>
                            <p class="text-gray-400 mb-3">
                                Basé au Sénégal, à Dakar, je travaille avec des clients et des apprenants du monde entier grâce aux 
                                technologies modernes de communication. Que vous soyez en Afrique, en Europe, en Amérique ou ailleurs, 
                                la distance n'est pas un obstacle pour collaborer ou apprendre ensemble. Je propose également des sessions 
                                de formation en ligne et des consultations à distance pour votre plus grande flexibilité.
                            </p>
                            <p class="text-purple-400 font-semibold text-lg">
                                {{ $siteSettings->contact_address ?? 'Dakar, Sénégal' }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Réseaux sociaux -->
                <div class="bg-gradient-to-br from-gray-900/90 to-black/90 backdrop-blur-xl rounded-3xl p-8 border border-cyan-500/30 shadow-2xl">
                    <h3 class="text-2xl font-bold mb-6 gradient-text">Suivez-moi</h3>
                    <div class="flex flex-wrap gap-4">
                        @php
                            $siteSettings = \Illuminate\Support\Facades\Cache::remember('site_settings', 3600, function () {
                                return \App\Models\SiteSetting::first();
                            });
                        @endphp
                        
                        @if($siteSettings && $siteSettings->facebook_url)
                        <a href="{{ $siteSettings->facebook_url }}" target="_blank" 
                           class="w-14 h-14 bg-blue-500/20 hover:bg-blue-500/30 border border-blue-500/30 rounded-xl flex items-center justify-center text-blue-400 hover:text-blue-300 transition">
                            <i class="fab fa-facebook-f text-2xl"></i>
                        </a>
                        @endif
                        
                        @if($siteSettings && $siteSettings->tiktok_url)
                        <a href="{{ $siteSettings->tiktok_url }}" target="_blank" 
                           class="w-14 h-14 bg-black/40 hover:bg-black/60 border border-white/20 rounded-xl flex items-center justify-center text-white hover:text-white transition">
                            <i class="fab fa-tiktok text-2xl"></i>
                        </a>
                        @endif
                        
                        @if($siteSettings && $siteSettings->linkedin_url)
                        <a href="{{ $siteSettings->linkedin_url }}" target="_blank" 
                           class="w-14 h-14 bg-blue-600/20 hover:bg-blue-600/30 border border-blue-600/30 rounded-xl flex items-center justify-center text-blue-500 hover:text-blue-400 transition">
                            <i class="fab fa-linkedin-in text-2xl"></i>
                        </a>
                        @endif
                        
                        @if($siteSettings && $siteSettings->instagram_url)
                        <a href="{{ $siteSettings->instagram_url }}" target="_blank" 
                           class="w-14 h-14 bg-pink-500/20 hover:bg-pink-500/30 border border-pink-500/30 rounded-xl flex items-center justify-center text-pink-400 hover:text-pink-300 transition">
                            <i class="fab fa-instagram text-2xl"></i>
                        </a>
                        @endif
                        
                        @if($siteSettings && $siteSettings->youtube_url)
                        <a href="{{ $siteSettings->youtube_url }}" target="_blank" 
                           class="w-14 h-14 bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 rounded-xl flex items-center justify-center text-red-400 hover:text-red-300 transition">
                            <i class="fab fa-youtube text-2xl"></i>
                        </a>
                        @endif
                        
                        @if($siteSettings && $siteSettings->github_url)
                        <a href="{{ $siteSettings->github_url }}" target="_blank" 
                           class="w-14 h-14 bg-gray-500/20 hover:bg-gray-500/30 border border-gray-500/30 rounded-xl flex items-center justify-center text-gray-400 hover:text-gray-300 transition">
                            <i class="fab fa-github text-2xl"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Intl Tel Input JS -->
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/js/intlTelInput.min.js"></script>
<script>
// Initialiser intl-tel-input pour le champ téléphone
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    if (phoneInput && window.intlTelInput) {
        const iti = window.intlTelInput(phoneInput, {
            initialCountry: "sn",
            preferredCountries: ['sn', 'fr', 'us', 'gb', 'de', 'es', 'it', 'ma', 'ci', 'cm', 'bf', 'ml', 'ne', 'td', 'mr'],
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/js/utils.js",
            separateDialCode: true,
            nationalMode: false,
            autoPlaceholder: "aggressive",
            formatOnDisplay: true,
        });
        
        // Mettre à jour le champ hidden avec le pays sélectionné
        phoneInput.addEventListener('countrychange', function() {
            const countryField = document.getElementById('phone_country');
            if (countryField) {
                countryField.value = iti.getSelectedCountryData().iso2;
            }
        });
        
        // Mettre à jour le champ hidden au chargement
        const countryField = document.getElementById('phone_country');
        if (countryField) {
            countryField.value = iti.getSelectedCountryData().iso2;
        }
        
        // Valider le numéro avant la soumission
        const contactForm = document.getElementById('contact-form');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                if (phoneInput.value.trim()) {
                    if (!iti.isValidNumber()) {
                        e.preventDefault();
                        alert('Numéro de téléphone invalide. Veuillez vérifier le format.');
                        phoneInput.focus();
                        return false;
                    }
                    // Mettre à jour le champ avec le numéro formaté international
                    phoneInput.value = iti.getNumber();
                }
            });
        }
    }
});
</script>
@endsection
