<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScheduleEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $scheduleDetails;

    public function __construct($scheduleDetails)
    {
        $this->scheduleDetails = $scheduleDetails;
    }

    public function build()
    {
        return $this->subject('New Proposal Submission Schedule')
                    ->view('emails.schedule-notification')
                    ->with('scheduleDetails', $this->scheduleDetails);
    }
}
