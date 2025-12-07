<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

trait LocaleTrait
{
    /**
     * Détermine la locale à utiliser (français ou anglais)
     * 
     * @return string
     */
    protected function ensureLocale(): string
    {
        // Récupérer la langue depuis la session ou le paramètre de requête
        $locale = Session::get('language', 'fr');
        
        // Vérifier si un paramètre de langue est passé dans l'URL
        if (request()->has('lang')) {
            $locale = request()->get('lang');
            Session::put('language', $locale);
        }
        
        // Valider que la locale est supportée
        if (!in_array($locale, ['fr', 'en'])) {
            $locale = 'fr';
        }
        
        // Définir la locale
        App::setLocale($locale);
        \Illuminate\Support\Facades\Lang::setLocale($locale);
        config(['app.locale' => $locale]);
        config(['app.fallback_locale' => 'fr']);
        
        return $locale;
    }
}

