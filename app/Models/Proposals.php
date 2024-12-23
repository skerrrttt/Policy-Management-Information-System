<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposals extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'type',
        'subtype',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function versions()
    {
        return $this->hasMany(ProposalVersion::class, 'proposals_id');
    }

    public function localMeetingAgendas()
    {
        return $this->hasMany(LocalMeetingAgenda::class, 'proposals_id');
    }

    public function universityMeetingAgendas()
    {
        return $this->hasMany(UniversityMeetingAgenda::class, 'proposals_id');
    }

    public function borMeetingAgendas()
    {
        return $this->hasMany(BORMeetingAgenda::class, 'proposals_id');
    }
}
