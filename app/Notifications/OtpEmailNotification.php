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
            ->subject("Your Soul Connect Verification Code: {$this->otp}")
            ->view('emails.otp', [
                'otp' => $this->otp,
                'expiryMinutes' => $this->expiryMinutes,
            ]);
    }
}
