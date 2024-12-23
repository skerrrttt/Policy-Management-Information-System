<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocalCouncilMeeting extends Model
{
    use SoftDeletes;

    protected $table = 'local_council_meeting'; // Explicit table name since it doesn't follow Laravel's naming convention.

    protected $fillable = [
        'submission_start',
        'submission_end',
        'meeting_description',
        'meeting_date',
        'quarter',
        'council_type_id',
        'meeting_modality_id',
        'meeting_venue_id',
    ];

    protected $casts = [
        'quarter' => 'integer', // Ensure quarter is always handled as an integer.
    ];



    public function councilType()
    {
        return $this->belongsTo(CouncilType::class, 'council_type_id');
    }

    public function localMeetingAgendas()
    {
        return $this->hasMany(LocalMeetingAgenda::class, 'local_council_meeting_id');
    }

    public function modality()
{
    return $this->belongsTo(MeetingModality::class, 'meeting_modality_id');
}

    /**
     * Get the meeting venue associated with this local council meeting.
     */
    public function venue()
{
    return $this->belongsTo(MeetingVenue::class, 'meeting_venue_id');
}


}
