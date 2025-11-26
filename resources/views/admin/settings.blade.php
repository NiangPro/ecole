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
                    <i class="fab fa-facebook text-blue-500 mr-2"></i>Facebook URL
                </label>
                <input type="url" name="facebook_url" value="{{ old('facebook_url', $settings->facebook_url ?? '') }}" 
                       class="input-admin" placeholder="https://facebook.com/...">
                @error('facebook_url')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fab fa-facebook text-blue-500 mr-2"></i>Facebook App ID
                </label>
                <input type="text" name="facebook_app_id" value="{{ old('facebook_app_id', $settings->facebook_app_id ?? '') }}" 
                       class="input-admin" placeholder="Votre Facebook App ID">
                <p class="text-gray-500 text-sm mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Nécessaire pour le partage via l'API Facebook. 
                    <a href="https://developers.facebook.com/apps/" target="_blank" class="text-cyan-400 hover:underline">
                        Créer une App Facebook
                    </a>
                </p>
                @error('facebook_app_id')
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

        <div class="mb-6">
            <label class="block text-gray-300 mb-2 font-semibold">
                <i class="fab fa-microsoft mr-2 text-blue-400"></i>
                Clé API Bing Webmaster
            </label>
            <input type="text" name="bing_api_key" 
                   value="{{ old('bing_api_key', $settings->bing_api_key ?? '') }}" 
                   class="input-admin" 
                   placeholder="Votre clé API Bing">
            <p class="text-gray-500 text-sm mt-2">
                <i class="fas fa-lightbulb mr-1"></i>
                Obtenez votre clé API depuis <a href="https://www.bing.com/webmasters" target="_blank" class="text-cyan-400 hover:underline">Bing Webmaster Tools</a>
            </p>
            @error('bing_api_key')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>
    </div>
    
    <!-- Configuration Email -->
    <div class="content-section mb-6">
        <h4 class="text-xl font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-envelope text-cyan-400"></i>
            Configuration Email (SMTP)
        </h4>
        
        <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-blue-400 text-xl mt-1"></i>
                <div>
                    <p class="font-semibold text-blue-400 mb-2">Configuration SMTP pour l'envoi d'emails</p>
                    <p class="text-sm text-gray-300">
                        Configurez les paramètres SMTP pour envoyer des emails (newsletter, notifications, etc.). 
                        Les valeurs du fichier .env seront utilisées si ces champs sont vides.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-server mr-2"></i>Type de serveur mail
                </label>
                <select name="mail_mailer" class="input-admin">
                    <option value="">Utiliser .env</option>
                    <option value="smtp" {{ old('mail_mailer', $settings->mail_mailer ?? '') === 'smtp' ? 'selected' : '' }}>SMTP</option>
                    <option value="sendmail" {{ old('mail_mailer', $settings->mail_mailer ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                    <option value="mailgun" {{ old('mail_mailer', $settings->mail_mailer ?? '') === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                    <option value="ses" {{ old('mail_mailer', $settings->mail_mailer ?? '') === 'ses' ? 'selected' : '' }}>Amazon SES</option>
                </select>
                @error('mail_mailer')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-network-wired mr-2"></i>Hôte SMTP
                </label>
                <input type="text" name="mail_host" value="{{ old('mail_host', $settings->mail_host ?? '') }}" 
                       class="input-admin" placeholder="smtp.gmail.com">
                <p class="text-gray-500 text-sm mt-2">
                    <i class="fas fa-lightbulb mr-1"></i>
                    Exemples : smtp.gmail.com, smtp.mailtrap.io, mail.niangprogrammeur.com
                </p>
                @error('mail_host')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-plug mr-2"></i>Port SMTP
                </label>
                <input type="text" name="mail_port" value="{{ old('mail_port', $settings->mail_port ?? '') }}" 
                       class="input-admin" placeholder="587 ou 465">
                <p class="text-gray-500 text-sm mt-2">
                    <i class="fas fa-lightbulb mr-1"></i>
                    Ports courants : 587 (TLS), 465 (SSL), 25, 2525
                </p>
                @error('mail_port')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-lock mr-2"></i>Chiffrement
                </label>
                <select name="mail_encryption" class="input-admin">
                    <option value="">Aucun</option>
                    <option value="tls" {{ old('mail_encryption', $settings->mail_encryption ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                    <option value="ssl" {{ old('mail_encryption', $settings->mail_encryption ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                </select>
                @error('mail_encryption')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-user mr-2"></i>Nom d'utilisateur SMTP
                </label>
                <input type="text" name="mail_username" value="{{ old('mail_username', $settings->mail_username ?? '') }}" 
                       class="input-admin" placeholder="votre-email@example.com">
                @error('mail_username')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-key mr-2"></i>Mot de passe SMTP
                </label>
                <input type="password" name="mail_password" value="{{ old('mail_password', '') }}" 
                       class="input-admin" placeholder="{{ $settings->mail_password ? '•••••••• (Laisser vide pour conserver le mot de passe actuel)' : 'Votre mot de passe SMTP' }}">
                <p class="text-gray-500 text-sm mt-2">
                    <i class="fas fa-shield-alt mr-1"></i>
                    @if($settings->mail_password)
                        Un mot de passe est déjà configuré. Laissez vide pour le conserver.
                    @else
                        Le mot de passe sera stocké de manière sécurisée.
                    @endif
                </p>
                @error('mail_password')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-at mr-2"></i>Adresse email expéditeur
                </label>
                <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $settings->mail_from_address ?? '') }}" 
                       class="input-admin" placeholder="noreply@niangprogrammeur.com">
                <p class="text-gray-500 text-sm mt-2">
                    <i class="fas fa-lightbulb mr-1"></i>
                    Adresse qui apparaîtra comme expéditeur
                </p>
                @error('mail_from_address')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-gray-300 mb-2 font-semibold">
                    <i class="fas fa-signature mr-2"></i>Nom de l'expéditeur
                </label>
                <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $settings->mail_from_name ?? '') }}" 
                       class="input-admin" placeholder="NiangProgrammeur">
                <p class="text-gray-500 text-sm mt-2">
                    <i class="fas fa-lightbulb mr-1"></i>
                    Nom qui apparaîtra comme expéditeur
                </p>
                @error('mail_from_name')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="mt-6 p-4 bg-cyan-500/10 border border-cyan-500/30 rounded-lg">
            <div class="flex items-start gap-3">
                <i class="fas fa-flask text-cyan-400 text-xl mt-1"></i>
                <div>
                    <p class="font-semibold text-cyan-400 mb-2">Tester la configuration</p>
                    <p class="text-sm text-gray-300 mb-3">
                        Après avoir enregistré, vous pouvez tester l'envoi d'email en publiant un article et en l'envoyant par newsletter.
                    </p>
                    <p class="text-xs text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i>
                        Si les champs sont vides, les valeurs du fichier .env seront utilisées.
                    </p>
                </div>
            </div>
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
