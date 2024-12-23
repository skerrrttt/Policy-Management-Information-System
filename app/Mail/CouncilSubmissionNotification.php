<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CouncilSubmissionNotification extends Mailable
{
    use SerializesModels;

    public $submissionStart;
    public $submissionEnd;

    public function __construct($submissionStart, $submissionEnd)
    {
        $this->submissionStart = $submissionStart;
        $this->submissionEnd = $submissionEnd;
    }

    public function build()
    {
        return $this->subject('Proposal Submission Dates Updated')
                    ->view('emails.schedule-notification')
                    ->with([
                        'submissionStart' => $this->submissionStart,
                        'submissionEnd' => $this->submissionEnd,
                    ]);
    }
}
