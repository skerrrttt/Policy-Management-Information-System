<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversityCouncilMeeting extends Model
{
    protected $table = 'university_council_meeting'; // Explicit table name

    protected $fillable = [
        'meeting_description',
        'meeting_date',
        'quarter',
        'council_type_id',
        'meeting_modality_id',
        'meeting_venue_id',
    ];

     /**
     * Get the council type associated with this meeting.
     */
    public function councilType()
    {
        return $this->belongsTo(CouncilType::class, 'council_type_id');
    }

    public function meetingModality()
    {
        return $this->belongsTo(MeetingModality::class, 'meeting_modality_id');
    }

    /**
     * Get the meeting venue associated with this university council meeting.
     */
    public function meetingVenue()
    {
        return $this->belongsTo(MeetingVenue::class, 'meeting_venue_id');
    }

    /**
     * Get the university meeting agendas associated with this council meeting.
     */
    public function universityMeetingAgendas()
    {
        return $this->hasMany(UniversityMeetingAgenda::class, 'ucm_id');
    }
}
