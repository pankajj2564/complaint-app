<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    // Constructor me OTP receive karein
    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'University Portal - Login OTP Verification',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp', // Ye blade view hum agle step me banayenge
        );
    }

    public function attachments(): array
    {
        return [];
    }
}