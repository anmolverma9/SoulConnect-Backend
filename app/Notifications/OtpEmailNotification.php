<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $otp;
    public int $expiryMinutes;

    public function __construct(string $otp, int $expiryMinutes = 5)
    {
        $this->otp = $otp;
        $this->expiryMinutes = $expiryMinutes;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Login Verification Code - '.config('app.name'))
            ->greeting('Hello!')
            ->line('Use the following 6-digit one-time code to sign in to your account:')
            ->line("**{$this->otp}**")
            ->line("This verification code is valid for {$this->expiryMinutes} minutes.")
            ->line('If you did not request this login code, you can safely ignore this email.');
    }
}
