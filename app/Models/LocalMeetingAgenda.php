<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalMeetingAgenda extends Model
{

    protected $table = 'local_meeting_agenda';

    protected $fillable = [
        'proposals_id',
        'local_council_meeting_id',
        'proposals_status_id',
        'requested_action_id',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposals::class, 'proposals_id');
    }

    public function universityMeetingAgendas()
    {
        return $this->hasMany(UniversityMeetingAgenda::class, 'lma_id');
    }
}
