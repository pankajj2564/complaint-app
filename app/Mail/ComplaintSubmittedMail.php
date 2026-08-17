<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class ComplaintSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $complaint;
    public $user;

    public function __construct($complaint, $user)
    {
        $this->complaint = $complaint;
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Complaint Registered - Ticket #' . $this->complaint->ticket_number,
            from: new Address(config('mail.from.address'), 'Complaint Portal - CGC University Mohali'),
            // Jab support team reply kare, toh seedha student ki email par jaye
            replyTo: [
                new Address($this->user->email, $this->user->name)
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.complaint_submitted',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}