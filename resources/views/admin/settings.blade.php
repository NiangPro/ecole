@extends('admin.layout')

@section('content')
<h3 class="text-3xl font-bold mb-8">Paramètres du site</h3>

@if(session('success'))
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
        <i class="fas fa-check-circle text-xl"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    
    <!-- Informations générales -->
    <div class="content-section mb-6">
        <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-info-circle text-cyan-400"></i>
            Informations générales
        </h4>
        
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-globe mr-2"></i>Nom du site *
                </label>
                <input type="text" name="site_name" value="{{ old('site_name', $settings->site_name ?? 'NiangProgrammeur') }}" 
                       class="input-admin" required>
                @error('site_name')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-envelope mr-2"></i>Email de contact
                </label>
                <input type="email" name="contact_email" value="{{ old('contact_email', $settings->contact_email ?? 'NiangProgrammeur@gmail.com') }}" 
                       class="input-admin">
                @error('contact_email')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mt-6">
            <label class="block text-gray-300 mb-2 font-semibold">
                <i class="fas fa-align-left mr-2"></i>Description du site
            </label>
            <textarea name="site_description" class="input-admin" rows="3">{{ old('site_description', $settings->site_description ?? 'Plateforme de formation gratuite en développement web') }}</textarea>
            @error('site_description')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>
    </div>
    
    <!-- Coordonnées -->
    <div class="content-section mb-6">
        <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-address-book text-purple-400"></i>
            Coordonnées
        </h4>
        
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-phone mr-2"></i>Téléphone
                </label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings->contact_phone ?? '+221 78 312 36 57') }}" 
                       class="input-admin" placeholder="+221 77 123 45 67">
                @error('contact_phone')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-map-marker-alt mr-2"></i>Adresse
                </label>
                <input type="text" name="contact_address" value="{{ old('contact_address', $settings->contact_address ?? 'Dakar, Sénégal') }}" 
                       class="input-admin" placeholder="Ville, Pays">
                @error('contact_address')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Réseaux sociaux -->
    <div class="content-section mb-6">
        <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-share-alt text-green-400"></i>
            Réseaux sociaux
        </h4>
        
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fab fa-facebook text-blue-500 mr-2"></i>Facebook
                </label>
                <input type="url" name="facebook_url" value="{{ old('facebook_url', $settings->facebook_url ?? '') }}" 
                       class="input-admin" placeholder="https://facebook.com/...">
                @error('facebook_url')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fab fa-tiktok mr-2" style="color: #000; background: #fff; padding: 2px 4px; border-radius: 4px;"></i>TikTok
                </label>
                <input type="url" name="tiktok_url" value="{{ old('tiktok_url', $settings->tiktok_url ?? '') }}" 
                       class="input-admin" placeholder="https://tiktok.com/@...">
                @error('tiktok_url')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fab fa-linkedin text-blue-600 mr-2"></i>LinkedIn
                </label>
                <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $settings->linkedin_url ?? '') }}" 
                       class="input-admin" placeholder="https://linkedin.com/in/...">
                @error('linkedin_url')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fab fa-instagram text-pink-500 mr-2"></i>Instagram
                </label>
                <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings->instagram_url ?? '') }}" 
                       class="input-admin" placeholder="https://instagram.com/...">
                @error('instagram_url')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fab fa-youtube text-red-500 mr-2"></i>YouTube
                </label>
                <input type="url" name="youtube_url" value="{{ old('youtube_url', $settings->youtube_url ?? '') }}" 
                       class="input-admin" placeholder="https://youtube.com/@...">
                @error('youtube_url')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fab fa-github text-gray-400 mr-2"></i>GitHub
                </label>
                <input type="url" name="github_url" value="{{ old('github_url', $settings->github_url ?? '') }}" 
                       class="input-admin" placeholder="https://github.com/...">
                @error('github_url')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Section Google Analytics -->
    <div class="content-section mt-6">
        <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-chart-line text-green-400"></i>
            Google Analytics
        </h4>
        
        <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-yellow-400 text-xl mt-1"></i>
                <div>
                    <p class="font-semibold text-yellow-400 mb-2">Comment obtenir votre ID Google Analytics ?</p>
                    <ol class="text-sm text-gray-300 space-y-1 ml-4 list-decimal">
                        <li>Créez un compte sur <a href="https://analytics.google.com/" target="_blank" class="text-cyan-400 hover:underline">analytics.google.com</a></li>
                        <li>Créez une propriété GA4</li>
                        <li>Copiez l'ID de mesure (format: G-XXXXXXXXXX)</li>
                        <li>Collez-le dans le champ ci-dessous</li>
                    </ol>
                </div>
            </div>
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-300 mb-2 font-semibold">
                <i class="fas fa-chart-bar mr-2 text-green-400"></i>
                ID Google Analytics (GA4)
            </label>
            <input type="text" name="google_analytics_id" 
                   value="{{ old('google_analytics_id', $settings->google_analytics_id ?? '') }}" 
                   class="input-admin" 
                   placeholder="G-XXXXXXXXXX">
            <p class="text-gray-500 text-sm mt-2">
                <i class="fas fa-lightbulb mr-1"></i>
                Format attendu : G-XXXXXXXXXX (commence toujours par "G-")
            </p>
            @error('google_analytics_id')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>
    </div>
    
    <div class="flex gap-4 mt-6">
        <button type="submit" class="btn-primary">
            <i class="fas fa-save mr-2"></i>Enregistrer les modifications
        </button>
        <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 rounded-lg font-semibold transition">
            <i class="fas fa-times mr-2"></i>Annuler
        </a>
    </div>
</form>
@endsection
