<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordSetupNotification extends Notification
{
    use Queueable;

    public $token;
    public $isNewUser;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token, bool $isNewUser = false)
    {
        $this->token = $token;
        $this->isNewUser = $isNewUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Create signed URL expiring in 60 minutes!
        $url = URL::temporarySignedRoute(
            'portal.verify-email',
            now()->addMinutes(60),
            ['email' => $notifiable->email, 'token' => $this->token]
        );

        $subject = $this->isNewUser ? 'Verify Your Email & Set Up Password - SmartBlood PH' : 'Verify Your Email & Reset Password - SmartBlood PH';
        $line1 = $this->isNewUser ? 'Welcome to SmartBlood PH! Please verify that this is your email address to set up your password.' : 'You are receiving this email because we received a password reset request. Please verify your email address first.';
        
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject($subject)
            ->line($line1)
            ->action('Yes, it\'s me', $url)
            ->line('This link will expire in 60 minutes.')
            ->line('If you did not request this, no further action is required.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'token' => $this->token,
            'is_new_user' => $this->isNewUser,
        ];
    }
}
