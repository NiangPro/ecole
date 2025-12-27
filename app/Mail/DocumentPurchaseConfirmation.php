<?php

namespace App\Mail;

use App\Models\DocumentPurchase;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentPurchaseConfirmation extends Mailable
{
    use Queueable, SerializesModels;

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
