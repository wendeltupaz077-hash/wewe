<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $emailOrPhone;
    public $loginLink;
    public $logoUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(string $userName, string $emailOrPhone, string $loginLink = null, string $logoUrl = null)
    {
        $this->userName = $userName;
        $this->emailOrPhone = $emailOrPhone;
        $this->loginLink = $loginLink ?? url('/portal/login');
        $this->logoUrl = $logoUrl ?? config('app.logo_url', asset('images/logo.png'));
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Welcome to SmartBlood')
                    ->view('emails.registration')
                    ->with([
                        'userName' => $this->userName,
                        'emailOrPhone' => $this->emailOrPhone,
                        'loginLink' => $this->loginLink,
                        'logoUrl' => $this->logoUrl,
                    ]);
    }
}
