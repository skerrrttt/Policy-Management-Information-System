<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BORMeetingAgenda extends Model
{
    protected $fillable = [
        'uni_agenda_id',
        'loc_agenda_id',
        'proposals_id',
        'ucm_id',
        'bcm_id',
        'proposals_status_id',
        'requested_action_id',
    ];

    public function universityMeetingAgenda()
    {
        return $this->belongsTo(UniversityMeetingAgenda::class, 'uni_agenda_id');
    }

    public function proposal()
    {
        return $this->belongsTo(Proposals::class, 'proposals_id');
    }
}
