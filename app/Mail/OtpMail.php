<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $userName;

    public function __construct(string $otp, string $userName)
    {
        $this->otp = $otp;
        $this->userName = $userName;
    }

    public function build()
    {
        return $this->subject('Votre code de vérification - NiangProgrammeur')
            ->view('emails.otp')
            ->with([
                'otp' => $this->otp,
                'userName' => $this->userName,
            ]);
    }
}
