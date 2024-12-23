<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingModality extends Model
{
    protected $table = 'meeting_modality';

    protected $fillable = ['modality_description'];

    public $timestamps = false;

    /**
     * Get the local council meetings associated with this meeting modality.
     */
    public function localCouncilMeetings()
    {
        return $this->hasMany(LocalCouncilMeeting::class, 'meeting_modality_id');
    }

    /**
     * Get the university council meetings associated with this meeting modality.
     */
    public function universityCouncilMeetings()
    {
        return $this->hasMany(UniversityCouncilMeeting::class, 'meeting_modality_id');
    }

    /**
     * Get the BOR meetings associated with this meeting modality.
     */
    public function borMeetings()
    {
        return $this->hasMany(BorMeeting::class, 'meeting_modality_id');
    }

    
}
