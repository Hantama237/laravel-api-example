<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            // The original URL looks like:
            // http://localhost/api/verify-email/{id}/{hash}?expires=...&signature=...

            // Parse the original URL to extract its components
            $path = parse_url($url, PHP_URL_PATH);
            $query = parse_url($url, PHP_URL_QUERY);

            // Explode the path to get the id and hash
            $pathSegments = explode('/', trim($path, '/'));
            $id = $pathSegments[count($pathSegments) - 2];
            $hash = $pathSegments[count($pathSegments) - 1];

            // Determine user type based on roles
            $userType = $notifiable->hasRole('student') ? 'B2C' : 'B2B';

            // Build the frontend URL with all the necessary query parameters
            $spaUrl = rtrim(config('app.frontend_url'), '/') .
                      config('app.frontend_verification_path') .
                      '?id=' . urlencode($id) .
                      '&hash=' . urlencode($hash) .
                      '&type=' . urlencode($userType) .
                      '&' . $query;

            return (new MailMessage)
                ->subject('Verify Email Address')
                ->line('Click the button below to verify your email address.')
                ->action('Verify Email Address', $spaUrl);
        });

        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            // Build the frontend URL with the reset token
            $frontendUrl = rtrim(config('app.frontend_url'), '/') .
                          config('app.frontend_reset_password_path') .
                          '?token=' . urlencode($token) .
                          '&email=' . urlencode($notifiable->getEmailForPasswordReset()) . 
                          '&type=B2B';

            return (new MailMessage)
                ->subject('Reset Password Notification')
                ->line('You are receiving this email because we received a password reset request for your account.')
                ->action('Reset Password', $frontendUrl)
                ->line('This password reset link will expire in ' . config('auth.passwords.users.expire') . ' minutes.')
                ->line('If you did not request a password reset, no further action is required.');
        });
    }
}
