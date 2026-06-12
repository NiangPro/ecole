<?php

namespace App\Mail;

use App\Models\CorrigePurchase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CorrigePurchaseConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // Relances en cas d'échec SMTP transitoire
    public $tries = 3;
    public $backoff = 30;

    public $purchase;
    public $downloadUrl;

    public function __construct(CorrigePurchase $purchase)
    {
        $this->purchase = $purchase;
        $this->downloadUrl = route('epreuves.corrige.download', ['token' => $purchase->download_token]);
    }

    public function build()
    {
        return $this->subject('Votre corrigé - ' . $this->purchase->epreuve->title)
            ->view('emails.corrige-purchase-confirmation')
            ->with([
                'purchase' => $this->purchase,
                'downloadUrl' => $this->downloadUrl,
            ]);
    }
}
