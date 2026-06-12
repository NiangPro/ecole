<?php

namespace App\Mail;

use App\Models\DocumentPurchase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentPurchaseConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // Relances en cas d'échec SMTP transitoire
    public $tries = 3;
    public $backoff = 30;

    public $purchase;
    public $downloadUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(DocumentPurchase $purchase)
    {
        $this->purchase = $purchase;
        $email = $purchase->customer_email ?? ($purchase->user ? $purchase->user->email : '');
        $this->downloadUrl = route('documents.download.token', ['token' => $purchase->download_token]) . '?email=' . urlencode($email);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = 'Confirmation d\'achat - ' . $this->purchase->document->title;
        
        return $this->subject($subject)
                    ->view('emails.document-purchase-confirmation')
                    ->with([
                        'purchase' => $this->purchase,
                        'downloadUrl' => $this->downloadUrl,
                    ]);
    }
}
