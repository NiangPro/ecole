<?php

namespace App\Http\Controllers;

use App\Models\CorrigePurchase;
use App\Models\Epreuve;
use App\Models\Payment;
use App\Models\WhatsAppSettings;
use App\Services\WavePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class CorrigeController extends Controller
{
    /**
     * Lance l'achat d'un corrigé (Wave) : identité par e-mail OU téléphone.
     */
    public function checkout(Request $request, int $id)
    {
        $epreuve = Epreuve::published()->findOrFail($id);
        abort_unless($epreuve->hasCorrige(), 404);

        // E-mail OU téléphone obligatoire (au moins un des deux)
        $request->validate([
            'payment_method' => 'nullable|in:wave,orange_money',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255|required_without:customer_phone',
            'customer_phone' => 'nullable|string|max:20|required_without:customer_email',
            'country_code' => 'nullable|string|max:5',
        ], [
            'customer_email.required_without' => 'Indiquez un e-mail ou un numéro de téléphone.',
            'customer_phone.required_without' => 'Indiquez un numéro de téléphone ou un e-mail.',
        ]);

        $email = $request->filled('customer_email') ? trim($request->customer_email) : null;
        $phone = $request->filled('customer_phone') ? trim($request->customer_phone) : null;

        // Déjà acheté ? → renvoyer directement vers le lien de téléchargement
        $existing = CorrigePurchase::findCompletedFor($epreuve->id, $email, $phone);
        if ($existing && $existing->download_token) {
            return redirect()->route('epreuves.corrige.download', ['token' => $existing->download_token])
                ->with('success', 'Vous avez déjà acheté ce corrigé.');
        }

        $price = $epreuve->getCorrigePrice();
        $paymentMethod = $request->input('payment_method', 'wave');

        // WhatsApp activé pour cet achat si un téléphone est fourni et l'option globale active
        $whatsappEnabled = false;
        if ($phone) {
            $waSettings = WhatsAppSettings::getSettings();
            $whatsappEnabled = (bool) ($waSettings->enabled && ($waSettings->send_on_purchase ?? true));
        }

        $purchase = CorrigePurchase::create([
            'epreuve_id' => $epreuve->id,
            'user_id' => Auth::id(),
            'customer_email' => $email,
            'customer_name' => $request->customer_name,
            'customer_phone' => $phone,
            'country_code' => $phone ? ($request->country_code ?: '+221') : null,
            'whatsapp_enabled' => $whatsappEnabled,
            'amount_paid' => $price,
            'currency' => 'XOF',
            'status' => 'pending',
        ]);

        \App\Models\Notification::notifyAdmins(
            'corrige_purchase',
            'Nouvel achat de corrigé',
            ($request->customer_name ?: $email ?: $phone) . ' souhaite acheter le corrigé : ' . Str::limit($epreuve->title, 60),
            route('admin.epreuves.purchases'),
            'fa-file-invoice-dollar',
            '#f59e0b'
        );

        $payment = Payment::create([
            'user_id' => Auth::id(),
            'paymentable_type' => CorrigePurchase::class,
            'paymentable_id' => $purchase->id,
            'amount' => $price,
            'currency' => 'XOF',
            'status' => 'pending',
            'payment_method' => $paymentMethod,
            'payment_gateway' => $paymentMethod,
            'transaction_id' => 'COR-' . Str::upper(Str::random(12)),
            'payment_reference' => 'REF-' . Str::upper(Str::random(10)),
            'payment_details' => array_filter([
                'customer_email' => $email,
                'customer_name' => $request->customer_name,
                'customer_phone' => $phone,
            ]),
        ]);

        $purchase->update(['payment_id' => $payment->id]);

        if ($paymentMethod === 'orange_money') {
            return redirect()->route('payment.confirm', $payment->id);
        }

        // Générer le lien de paiement Wave
        $waveLink = WavePaymentService::generatePaymentLink(
            (float) $price,
            $payment->payment_reference,
            'Corrigé : ' . Str::limit($epreuve->title, 60)
        );
        $payment->update([
            'payment_details' => array_merge($payment->payment_details ?? [], ['wave_link' => $waveLink]),
        ]);

        return redirect()->route('payment.wave', $payment->id)
            ->with('wave_link', $waveLink);
    }

    /**
     * Page de succès après paiement : affiche le lien de téléchargement.
     */
    public function success(int $paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $purchase = $payment->paymentable;

        abort_unless($purchase instanceof CorrigePurchase, 404);

        return view('epreuves.corrige-success', compact('payment', 'purchase'));
    }

    /**
     * Téléchargement du corrigé via token d'achat valide.
     */
    public function download(string $token)
    {
        $purchase = CorrigePurchase::where('download_token', $token)->firstOrFail();

        abort_unless($purchase->isTokenValid($token), 403, 'Lien de téléchargement invalide ou expiré.');

        $epreuve = $purchase->epreuve;
        $corrigePath = $epreuve?->corrige_file_path;
        abort_unless($corrigePath && Storage::disk('public')->exists($corrigePath), 404);

        $purchase->increment('download_count');

        $slug = Str::slug($epreuve->title);

        // Le sujet de l'épreuve est livré avec le corrigé quand le fichier existe (l'acheteur
        // n'a alors qu'un seul lien à récupérer au lieu de devoir chercher l'épreuve à part).
        $epreuvePath = $epreuve->file_path;
        if ($epreuvePath && Storage::disk('public')->exists($epreuvePath)) {
            $tmpFile = tempnam(sys_get_temp_dir(), 'corrige_pack_');
            $zip = new ZipArchive();
            $zip->open($tmpFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $zip->addFile(Storage::disk('public')->path($epreuvePath), $slug . '-epreuve.pdf');
            $zip->addFile(Storage::disk('public')->path($corrigePath), $slug . '-corrige.pdf');
            $zip->close();

            return response()
                ->download($tmpFile, $slug . '-epreuve-et-corrige.zip', ['Content-Type' => 'application/zip'])
                ->deleteFileAfterSend(true);
        }

        return Storage::disk('public')->download($corrigePath, $slug . '-corrige.pdf');
    }
}
