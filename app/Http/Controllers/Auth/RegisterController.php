<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
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
    
    public function showRegistrationForm()
    {
        // Définir la locale AVANT tout traitement
        $this->ensureLocale();
        
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Définir la locale AVANT tout traitement
        $this->ensureLocale();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        // Utiliser phone_full si disponible, sinon phone
        $phone = $request->input('phone_full') ?: $request->phone;
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $phone,
            'role' => 'user',
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->intended('/')->with('success', trans('app.auth.register.success'));
    }
}
