<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            Log::error('SocialAuthController::handleGoogleCallback — échec Socialite', ['error' => $e->getMessage()]);
            return redirect()->route('login')->withErrors([
                'email' => "La connexion avec Google a échoué. Réessayez.",
            ]);
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $user->avatar ?: $googleUser->getAvatar(),
                ]);
            }
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Utilisateur Google',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => null,
                'role' => 'user',
                'is_active' => true,
            ]);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->intended(route('dashboard.overview'))->with('success', trans('app.auth.login.success'));
    }

    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();
        } catch (\Throwable $e) {
            Log::error('SocialAuthController::handleGithubCallback — échec Socialite', ['error' => $e->getMessage()]);
            return redirect()->route('login')->withErrors([
                'email' => "La connexion avec GitHub a échoué. Réessayez.",
            ]);
        }

        if (!$githubUser->getEmail()) {
            return redirect()->route('login')->withErrors([
                'email' => "Votre compte GitHub ne partage pas d'adresse e-mail publique. Rendez votre e-mail public dans les paramètres GitHub puis réessayez.",
            ]);
        }

        $user = User::where('github_id', $githubUser->getId())
            ->orWhere('email', $githubUser->getEmail())
            ->first();

        if ($user) {
            if (!$user->github_id) {
                $user->update([
                    'github_id' => $githubUser->getId(),
                    'avatar' => $user->avatar ?: $githubUser->getAvatar(),
                ]);
            }
        } else {
            $user = User::create([
                'name' => $githubUser->getName() ?: $githubUser->getNickname() ?: 'Utilisateur GitHub',
                'email' => $githubUser->getEmail(),
                'github_id' => $githubUser->getId(),
                'avatar' => $githubUser->getAvatar(),
                'password' => null,
                'role' => 'user',
                'is_active' => true,
            ]);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->intended(route('dashboard.overview'))->with('success', trans('app.auth.login.success'));
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
        } catch (\Throwable $e) {
            Log::error('SocialAuthController::handleFacebookCallback — échec Socialite', ['error' => $e->getMessage()]);
            return redirect()->route('login')->withErrors([
                'email' => "La connexion avec Facebook a échoué. Réessayez.",
            ]);
        }

        if (!$facebookUser->getEmail()) {
            return redirect()->route('login')->withErrors([
                'email' => "Votre compte Facebook ne partage pas d'adresse e-mail. Ajoutez une adresse e-mail vérifiée à votre compte Facebook puis réessayez.",
            ]);
        }

        $user = User::where('facebook_id', $facebookUser->getId())
            ->orWhere('email', $facebookUser->getEmail())
            ->first();

        if ($user) {
            if (!$user->facebook_id) {
                $user->update([
                    'facebook_id' => $facebookUser->getId(),
                    'avatar' => $user->avatar ?: $facebookUser->getAvatar(),
                ]);
            }
        } else {
            $user = User::create([
                'name' => $facebookUser->getName() ?: $facebookUser->getNickname() ?: 'Utilisateur Facebook',
                'email' => $facebookUser->getEmail(),
                'facebook_id' => $facebookUser->getId(),
                'avatar' => $facebookUser->getAvatar(),
                'password' => null,
                'role' => 'user',
                'is_active' => true,
            ]);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->intended(route('dashboard.overview'))->with('success', trans('app.auth.login.success'));
    }
}
