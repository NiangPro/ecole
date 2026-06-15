<?php

namespace App\Http\Controllers;

use App\Models\Epreuve;
use App\Models\EpreuvePurchase;
use App\Models\Payment;
use App\Services\WavePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EpreuvePaymentController extends Controller
{
    /**
     * Lance l'achat d'une épreuve payante (Wave).
     */
    public function checkout(Request $request, int $id)
    {
        $epreuve = Epreuve::published()->findOrFail($id);
        abort_if($epreuve->isFree(), 404);

        $request->validate([
            'customer_name'  => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255|required_without:customer_phone',
            'customer_phone' => 'nullable|string|max:20|required_without:customer_email',
            'country_code'   => 'nullable|string|max:5',
        ], [
            'customer_email.required_without' => 'Indiquez un e-mail ou un numéro de téléphone.',
            'customer_phone.required_without' => 'Indiquez un numéro de téléphone ou un e-mail.',
        ]);

        $email = $request->filled('customer_email') ? trim($request->customer_email) : null;
        $phone = $request->filled('customer_phone') ? trim($request->customer_phone) : null;

        // Déjà acheté ? → lien direct
        $existing = EpreuvePurchase::findCompletedFor($epreuve->id, $email, $phone);
        if ($existing && $existing->download_token) {
            return redirect()->route('epreuves.pay.download', ['token' => $existing->download_token])
                ->with('success', 'Vous avez déjà acheté cette épreuve.');
        }

        $price = (float) $epreuve->price;

        $purchase = EpreuvePurchase::create([
            'epreuve_id'     => $epreuve->id,
            'user_id'        => Auth::id(),
            'customer_email' => $email,
            'customer_name'  => $request->customer_name,
            'customer_phone' => $phone,
            'country_code'   => $phone ? ($request->country_code ?: '+221') : null,
            'amount_paid'    => $price,
            'currency'       => 'XOF',
            'status'         => 'pending',
        ]);

        $payment = Payment::create([
            'user_id'            => Auth::id(),
            'paymentable_type'   => EpreuvePurchase::class,
            'paymentable_id'     => $purchase->id,
            'amount'             => $price,
            'currency'           => 'XOF',
            'status'             => 'pending',
            'payment_method'     => 'wave',
            'payment_gateway'    => 'wave',
            'transaction_id'     => 'EPR-' . Str::upper(Str::random(12)),
            'payment_reference'  => 'REF-' . Str::upper(Str::random(10)),
            'payment_details'    => array_filter([
                'customer_email' => $email,
                'customer_name'  => $request->customer_name,
                'customer_phone' => $phone,
            ]),
        ]);

        $purchase->update(['payment_id' => $payment->id]);

        $waveLink = WavePaymentService::generatePaymentLink(
            $price,
            $payment->payment_reference,
            'Épreuve : ' . Str::limit($epreuve->title, 60)
        );
        $payment->update([
            'payment_details' => array_merge($payment->payment_details ?? [], ['wave_link' => $waveLink]),
        ]);

        return redirect()->route('payment.wave', $payment->id)
            ->with('wave_link', $waveLink);
    }

    /**
     * Page de succès après paiement.
     */
    public function success(int $paymentId)
    {
        $payment  = Payment::findOrFail($paymentId);
        $purchase = $payment->paymentable;

        abort_unless($purchase instanceof EpreuvePurchase, 404);

        return view('epreuves.epreuve-success', compact('payment', 'purchase'));
    }

    /**
     * Téléchargement via token valide.
     */
    public function download(string $token)
    {
        $purchase = EpreuvePurchase::where('download_token', $token)->firstOrFail();

        abort_unless($purchase->isTokenValid($token), 403, 'Lien de téléchargement invalide ou expiré.');

        $epreuve = $purchase->epreuve;
        $path    = $epreuve?->file_path;
        abort_unless($path && Storage::disk('public')->exists($path), 404);

        $purchase->increment('download_count');
        Epreuve::where('id', $epreuve->id)->increment('downloads_count');

        return Storage::disk('public')->download($path, Str::slug($epreuve->title) . '.pdf');
    }
}
