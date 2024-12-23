<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversityMeetingAgenda extends Model
{
    protected $fillable = [
        'lma_id',
        'lmap_id',
        'ucm_id',
        'proposals_id',
        'requested_action_id',
        'proposals_status_id',
    ];

    public function localMeetingAgenda()
    {
        return $this->belongsTo(LocalMeetingAgenda::class, 'lma_id');
    }

    public function proposal()
    {
        return $this->belongsTo(Proposals::class, 'proposals_id');
    }

    public function borMeetingAgendas()
    {
        return $this->hasMany(BORMeetingAgenda::class, 'uni_agenda_id');
    }
}
