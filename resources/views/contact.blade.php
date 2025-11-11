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
    
    .contact-input:focus {
        outline: none;
        border-color: #06b6d4;
        background: rgba(255, 255, 255, 0.08);
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.3);
    }
    
    .contact-input::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }
    
    textarea.contact-input {
        resize: vertical;
        min-height: 120px;
    }
</style>
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
                Une question ? Un projet ? N'hésitez pas à me contacter. Je serais ravi d'échanger avec vous !
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
                
                <form action="{{ route('contact.send') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label for="name" class="block text-sm font-semibold mb-2 text-gray-300">
                            <i class="fas fa-user mr-2 text-cyan-400"></i>Nom complet *
                        </label>
                        <input type="text" id="name" name="name" required 
                               class="contact-input" placeholder="Votre nom">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-semibold mb-2 text-gray-300">
                            <i class="fas fa-envelope mr-2 text-cyan-400"></i>Email *
                        </label>
                        <input type="email" id="email" name="email" required 
                               class="contact-input" placeholder="votre@email.com">
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-semibold mb-2 text-gray-300">
                            <i class="fas fa-phone mr-2 text-cyan-400"></i>Téléphone
                        </label>
                        <input type="tel" id="phone" name="phone" 
                               class="contact-input" placeholder="+221 77 123 45 67">
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-semibold mb-2 text-gray-300">
                            <i class="fas fa-tag mr-2 text-cyan-400"></i>Sujet *
                        </label>
                        <input type="text" id="subject" name="subject" required 
                               class="contact-input" placeholder="Objet de votre message">
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-semibold mb-2 text-gray-300">
                            <i class="fas fa-comment mr-2 text-cyan-400"></i>Message *
                        </label>
                        <textarea id="message" name="message" required 
                                  class="contact-input" placeholder="Votre message..."></textarea>
                    </div>
                    
                    <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-cyan-500 to-teal-600 rounded-xl font-bold hover:shadow-2xl hover:scale-105 transition">
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
                            <p class="text-gray-400 mb-3">Envoyez-moi un email, je réponds généralement sous 24h</p>
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
                            <p class="text-gray-400 mb-3">Appelez-moi pour une discussion rapide</p>
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
                            <p class="text-gray-400 mb-3">Basé au Sénégal</p>
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
                            $siteSettings = \App\Models\SiteSetting::first();
                        @endphp
                        
                        @if($siteSettings && $siteSettings->facebook_url)
                        <a href="{{ $siteSettings->facebook_url }}" target="_blank" 
                           class="w-14 h-14 bg-blue-500/20 hover:bg-blue-500/30 border border-blue-500/30 rounded-xl flex items-center justify-center text-blue-400 hover:text-blue-300 transition">
                            <i class="fab fa-facebook-f text-2xl"></i>
                        </a>
                        @endif
                        
                        @if($siteSettings && $siteSettings->twitter_url)
                        <a href="{{ $siteSettings->twitter_url }}" target="_blank" 
                           class="w-14 h-14 bg-sky-500/20 hover:bg-sky-500/30 border border-sky-500/30 rounded-xl flex items-center justify-center text-sky-400 hover:text-sky-300 transition">
                            <i class="fab fa-twitter text-2xl"></i>
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
@endsection
