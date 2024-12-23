<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouncilType extends Model
{
    protected $table = 'council_type';

    protected $fillable = [
        'type_description',
    ];

    /**
     * Get the local council meetings of this council type.
     */
    public function localCouncilMeetings()
    {
        return $this->hasMany(LocalCouncilMeeting::class, 'council_type_id');
    }
}
