<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $resetLink;
    public $logoUrl;

    public function __construct(string $userName, string $resetLink, string $logoUrl = null)
    {
        $this->userName = $userName;
        $this->resetLink = $resetLink;
        $this->logoUrl = $logoUrl ?? config('app.logo_url', asset('images/logo.png'));
    }

    public function build()
    {
        return $this->subject('SmartBlood Password Reset')
                    ->view('emails.password_reset')
                    ->with([
                        'userName' => $this->userName,
                        'resetLink' => $this->resetLink,
                        'logoUrl' => $this->logoUrl,
                    ]);
    }
}
