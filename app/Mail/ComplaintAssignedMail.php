<?php

namespace App\Mail;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ComplaintAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $complaint;
    public $recipientType; // 'user' ya 'employee' differentiate karne ke liye

    public function __construct(Complaint $complaint, $recipientType = 'user')
    {
        $this->complaint = $complaint;
        $this->recipientType = $recipientType;
    }

    public function build()
    {
        $subject = $this->recipientType == 'employee' 
            ? 'New Complaint Assigned to You - #' . $this->complaint->ticket_number 
            : 'Your Complaint Has Been Assigned - #' . $this->complaint->ticket_number;

        return $this->subject($subject)
                    ->view('emails.complaint_assigned');
    }
}
