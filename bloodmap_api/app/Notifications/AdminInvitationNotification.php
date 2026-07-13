<?php

namespace App\Notifications;

use App\Models\AdminInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class AdminInvitationNotification extends Notification
{
    use Queueable;

    public function __construct(public AdminInvitation $invitation)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = URL::temporarySignedRoute(
            'portal.invite.accept',
            now()->addHours(48),
            ['token' => $this->invitation->token]
        );

        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('SmartBlood PH Admin Invitation')
            ->greeting('Hello,')
            ->line('You have been invited to join SmartBlood PH as a '.$this->invitation->role.'.')
            ->action('Accept Invitation', $url)
            ->line('This invitation will expire in 48 hours.')
            ->line('If you did not request this invitation, you can ignore this email.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'invitation_id' => $this->invitation->id,
            'role' => $this->invitation->role,
        ];
    }
}
