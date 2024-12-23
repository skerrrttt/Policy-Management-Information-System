<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingNotification extends Notification
{
    use Queueable;

    protected $meetingDetails;

    public function __construct($meetingDetails)
    {
        $this->meetingDetails = $meetingDetails;
    }

    

    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Submission Scheduled')
            ->greeting('Hello ' . $notifiable->first_name . ',')
            ->line('A new submission has been scheduled.')
            ->line('Details:')
            ->line('Submission Start: ' . $this->meetingDetails['submission_start'])
            ->line('Submission End: ' . $this->meetingDetails['submission_end'])
            ->action('Click Here', url('/'))
            ->line('Kindly login to your account to check it. Thank you for your attention!');
    }

    
}
