<?php

namespace App\Mail;

use App\Models\BundlePurchase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BundlePurchaseConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 30;

    public $purchase;
    public $downloadUrl;

    public function __construct(BundlePurchase $purchase)
    {
        $this->purchase = $purchase;
        $this->downloadUrl = route('bundles.payment.download', ['token' => $purchase->download_token]);
    }

    public function build()
    {
        return $this->subject('Votre pack - ' . $this->purchase->bundle->name)
            ->view('emails.bundle-purchase-confirmation')
            ->with([
                'purchase' => $this->purchase,
                'downloadUrl' => $this->downloadUrl,
            ]);
    }
}
