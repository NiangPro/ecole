<?php

namespace App\Http\Controllers;

use App\Models\BundlePurchase;
use App\Models\Document;
use App\Models\DocumentBundle;
use App\Models\Epreuve;
use App\Models\Payment;
use App\Services\WavePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class BundlePaymentController extends Controller
{
    /**
     * Lance l'achat d'un pack (Wave) : identité par e-mail OU téléphone.
     */
    public function checkout(Request $request, string $slug)
    {
        $bundle = DocumentBundle::where('slug', $slug)->active()->with('items')->firstOrFail();

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

        // Déjà acheté ? (uniquement pour le formulaire classique, PAS le modal Wave AJAX)
        // Le modal doit toujours enregistrer un paiement en attente et afficher le QR Wave.
        if (!$request->ajax()) {
            $existing = BundlePurchase::findCompletedFor($bundle->id, $email, $phone);
            if ($existing) {
                // Régénérer un token si absent ou expiré, sinon le lien renverrait un 403
                if (!$existing->download_token || !$existing->isTokenValid($existing->download_token)) {
                    $existing->generateDownloadToken();
                    $existing->refresh();
                }
                $downloadUrl = route('bundles.payment.download', ['token' => $existing->download_token]);
                return redirect($downloadUrl)->with('success', 'Vous avez déjà acheté ce pack.');
            }
        }

        $price = (float) $bundle->current_price;
        $paymentMethod = $request->input('payment_method', 'wave');

        $purchase = BundlePurchase::create([
            'bundle_id' => $bundle->id,
            'user_id' => Auth::id(),
            'customer_email' => $email,
            'customer_name' => $request->customer_name,
            'customer_phone' => $phone,
            'country_code' => $phone ? ($request->country_code ?: '+221') : null,
            'amount_paid' => $price,
            'currency' => 'XOF',
            'status' => 'pending',
        ]);

        \App\Models\Notification::notifyAdmins(
            'bundle_purchase',
            'Nouvel achat de pack',
            ($request->customer_name ?: $email ?: $phone) . ' souhaite acheter le pack : ' . Str::limit($bundle->name, 60),
            route('admin.bundles.purchases.index'),
            'fa-box-open',
            '#f59e0b'
        );

        $payment = Payment::create([
            'user_id' => Auth::id(),
            'paymentable_type' => BundlePurchase::class,
            'paymentable_id' => $purchase->id,
            'amount' => $price,
            'currency' => 'XOF',
            'status' => 'pending',
            'payment_method' => $paymentMethod,
            'payment_gateway' => $paymentMethod,
            'transaction_id' => 'BUN-' . Str::upper(Str::random(12)),
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

        $waveLink = WavePaymentService::generatePaymentLink(
            $price,
            $payment->payment_reference,
            'Pack : ' . Str::limit($bundle->name, 60)
        );
        $payment->update([
            'payment_details' => array_merge($payment->payment_details ?? [], ['wave_link' => $waveLink]),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'wave_link' => $waveLink,
                'payment_id' => $payment->id,
                'amount' => $price,
                'contact_phone' => \App\Models\SiteSetting::get('contact_phone', '+221783123657'),
            ]);
        }

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

        abort_unless($purchase instanceof BundlePurchase, 404);

        return view('documents.bundles.purchase-success', compact('payment', 'purchase'));
    }

    /**
     * Téléchargement du pack (zip de tous les documents/épreuves) via token d'achat valide.
     */
    public function download(string $token)
    {
        $purchase = BundlePurchase::where('download_token', $token)->firstOrFail();

        abort_unless($purchase->isTokenValid($token), 403, 'Lien de téléchargement invalide ou expiré.');

        $bundle = $purchase->bundle()->with('items')->first();
        abort_unless($bundle, 404);

        $items = $bundle->items()->with('itemable')->get();

        $tmpFile = tempnam(sys_get_temp_dir(), 'bundle_pack_');
        $zip = new ZipArchive();
        $zip->open($tmpFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $addedCount = 0;
        foreach ($items as $item) {
            $itemable = $item->itemable;
            if (!$itemable) {
                continue;
            }

            if ($itemable instanceof Document) {
                $path = $itemable->file_path;
                if ($path && Storage::disk('local')->exists($path)) {
                    $zip->addFile(Storage::disk('local')->path($path), Str::slug($itemable->title) . '.pdf');
                    $addedCount++;
                }
            } elseif ($itemable instanceof Epreuve) {
                $path = $itemable->file_path;
                if ($path && Storage::disk('public')->exists($path)) {
                    $zip->addFile(Storage::disk('public')->path($path), Str::slug($itemable->title) . '-epreuve.pdf');
                    $addedCount++;
                }

                // Le corrigé est livré avec l'épreuve quand il existe (même logique que
                // CorrigeController::download pour un achat individuel de corrigé).
                $corrigePath = $itemable->corrige_file_path;
                if ($corrigePath && Storage::disk('public')->exists($corrigePath)) {
                    $zip->addFile(Storage::disk('public')->path($corrigePath), Str::slug($itemable->title) . '-corrige.pdf');
                    $addedCount++;
                }
            }
        }
        $zip->close();

        abort_if($addedCount === 0, 404, 'Aucun fichier disponible dans ce pack.');

        $purchase->increment('download_count');

        $slug = Str::slug($bundle->name);

        return response()
            ->download($tmpFile, $slug . '-pack.zip', ['Content-Type' => 'application/zip'])
            ->deleteFileAfterSend(true);
    }
}
