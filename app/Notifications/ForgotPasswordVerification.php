<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class ForgotPasswordVerification extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        // Create verification URL that goes to frontend
        $verificationUrl = rtrim(config('app.frontend_url'), '/') .
                          config('app.frontend_verification_path') .
                          '?token=' . urlencode($this->token) .
                          '&email=' . urlencode($notifiable->email) .
                          '&type=B2C';

        return (new MailMessage)
            ->subject('Verify Your Email for PIN Reset')
            ->line('You requested to reset your PIN for your account.')
            ->line('Please click the button below to verify your email address and generate a new PIN.')
            ->action('Verify Email & Generate PIN', $verificationUrl)
            ->line('This verification link will expire in 60 minutes.')
            ->line('If you did not request a PIN reset, no further action is required.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
