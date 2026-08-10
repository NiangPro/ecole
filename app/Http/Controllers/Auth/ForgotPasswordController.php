<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    private const OTP_TTL_MINUTES = 10;

    public function showRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Message générique dans tous les cas pour ne pas révéler si l'e-mail existe.
        $genericMessage = "Si un compte existe avec cette adresse, un code de vérification vient d'être envoyé.";

        if ($user) {
            $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                ['token' => Hash::make($otp), 'created_at' => now()]
            );

            try {
                Mail::to($user->email)->send(new OtpMail($otp, $user->name));
            } catch (\Throwable $e) {
                Log::error('ForgotPasswordController::sendOtp — échec envoi e-mail OTP', ['error' => $e->getMessage()]);
                return back()->withErrors(['email' => "L'envoi de l'e-mail a échoué. Réessayez plus tard."])->withInput();
            }
        }

        return redirect()->route('password.otp.verify.form', ['email' => $request->email])
            ->with('success', $genericMessage);
    }

    public function showVerifyForm(Request $request)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-otp', ['email' => $email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => 'required|min:8|confirmed',
        ]);

        $reset = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$reset || !Hash::check($request->otp, $reset->token)) {
            return back()->withErrors(['otp' => "Code de vérification invalide."])->withInput();
        }

        if (now()->diffInMinutes($reset->created_at) > self::OTP_TTL_MINUTES) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['otp' => "Ce code a expiré. Demandez-en un nouveau."])->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->route('password.request')->withErrors(['email' => "Compte introuvable."]);
        }

        $user->password = $request->password;
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Votre mot de passe a été réinitialisé avec succès. Connectez-vous.');
    }
}
