<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorMeeting extends Model
{
    protected $table = 'bor_meeting'; // Explicit table name

    protected $fillable = [
        'meeting_description',
        'meeting_date',
        'quarter',
        'meeting_modality_id',
        'meeting_venue_id',
    ];

    /**
     * Get the BOR meeting agendas associated with this BOR meeting.
     */
    public function borMeetingAgendas()
    {
        return $this->hasMany(BorMeetingAgenda::class, 'bcm_id');
    }

    public function meetingModality()
    {
        return $this->belongsTo(MeetingModality::class, 'meeting_modality_id');
    }

    /**
     * Get the meeting venue associated with this BOR meeting.
     */
    public function meetingVenue()
    {
        return $this->belongsTo(MeetingVenue::class, 'meeting_venue_id');
    }
}
