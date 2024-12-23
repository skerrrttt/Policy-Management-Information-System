<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingVenue extends Model
{
    protected $table = 'meeting_venue';

    protected $fillable = ['venue_description'];


    /**
     * Get the local council meetings associated with this meeting venue.
     */
    public function localCouncilMeetings()
    {
        return $this->hasMany(LocalCouncilMeeting::class, 'meeting_venue_id');
    }

    /**
     * Get the university council meetings associated with this meeting venue.
     */
    public function universityCouncilMeetings()
    {
        return $this->hasMany(UniversityCouncilMeeting::class, 'meeting_venue_id');
    }

    /**
     * Get the BOR meetings associated with this meeting venue.
     */
    public function borMeetings()
    {
        return $this->hasMany(BorMeeting::class, 'meeting_venue_id');
    }
}
