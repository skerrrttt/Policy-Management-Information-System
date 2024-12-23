<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalVersion extends Model
{
    protected $fillable = [
        'proposals_id',
        'file_paths',
        'version',
        'user_id',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposals::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
