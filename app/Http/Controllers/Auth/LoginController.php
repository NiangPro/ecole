<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Détermine la locale à utiliser (français ou anglais)
     */
    private function ensureLocale()
    {
        // Vérifier d'abord si un paramètre de langue est passé dans l'URL (priorité)
        if (request()->has('lang')) {
            $locale = request()->get('lang');
            if (in_array($locale, ['fr', 'en'])) {
                Session::put('language', $locale);
                Session::save(); // Forcer la sauvegarde de la session
            }
        } else {
            // Sinon, récupérer la langue depuis la session
            $locale = Session::get('language', 'fr');
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
    
    public function showLoginForm()
    {
        // Définir la locale AVANT tout traitement
        $this->ensureLocale();
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Définir la locale AVANT tout traitement
        $this->ensureLocale();
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard.overview'))->with('success', trans('app.auth.login.success'));
        }

        return back()->withErrors([
            'email' => trans('app.auth.login.error'),
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Déconnexion réussie.');
    }
}
