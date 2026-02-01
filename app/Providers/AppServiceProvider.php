<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
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
        
        // Partager les catégories d'emplois avec la navigation
        view()->composer('partials.navigation', \App\View\Composers\NavigationComposer::class);
        
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
