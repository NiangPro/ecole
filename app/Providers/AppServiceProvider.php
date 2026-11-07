<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Chargement direct (indépendant de `composer dump-autoload`, qui n'est pas
        // toujours relancé après un déploiement manuel — voir NumberHelper.php).
        require_once app_path('Helpers/NumberHelper.php');

        // Garde-fou (audit SEC-01) : en production, jamais de page de debug publique
        // (pile d'appels, extraits de code, chemins serveur), même si APP_DEBUG=true
        // traîne dans le .env. Appliqué à l'exécution : survit à config:cache.
        if ($this->app->environment('production') && config('app.debug')) {
            config(['app.debug' => false]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // S'assurer que le répertoire des vues compilées existe (évite HTTP 500 sur file_put_contents)
        $répertoireVuesCompilées = storage_path('framework/views');
        if (! is_dir($répertoireVuesCompilées)) {
            mkdir($répertoireVuesCompilées, 0755, true);
        }

        // Configuration pour éviter l'erreur "La clé est trop longue" avec MySQL/MariaDB
        Schema::defaultStringLength(191);

        // Règle commune des mots de passe (audit SEC-03) : inscription, réinitialisation, profil.
        Password::defaults(fn () => Password::min(8));
        
        // Partager les catégories d'emplois avec la navigation
        view()->composer('partials.navigation', \App\View\Composers\NavigationComposer::class);

        // Partager $siteSettings avec toutes les vues (depuis le cache, 0 requête SQL supplémentaire)
        view()->composer('*', \App\View\Composers\SiteSettingsComposer::class);
        
        // Charger les paramètres email depuis la base de données (avec cache pour améliorer les performances)
        try {
            $settings = \Illuminate\Support\Facades\Cache::remember('site_settings_mail', 3600, function () {
                return \App\Models\SiteSetting::first();
            });
            
            if ($settings) {
                // Configuration mail depuis la base de données
                if ($settings->mail_mailer) {
                    config(['mail.default' => $settings->mail_mailer]);
                }
                if ($settings->mail_host) {
                    config(['mail.mailers.smtp.host' => $settings->mail_host]);
                }
                if ($settings->mail_port) {
                    config(['mail.mailers.smtp.port' => (int)$settings->mail_port]);
                }
                if ($settings->mail_username) {
                    config(['mail.mailers.smtp.username' => $settings->mail_username]);
                }
                if ($settings->mail_password) {
                    config(['mail.mailers.smtp.password' => $settings->mail_password]);
                }
                if ($settings->mail_encryption) {
                    config(['mail.mailers.smtp.encryption' => $settings->mail_encryption]);
                }
                if ($settings->mail_from_address) {
                    config(['mail.from.address' => $settings->mail_from_address]);
                }
                if ($settings->mail_from_name) {
                    config(['mail.from.name' => $settings->mail_from_name]);
                }
            }
        } catch (\Exception $e) {
            // Ignorer les erreurs si la table n'existe pas encore
        }
    }
}
