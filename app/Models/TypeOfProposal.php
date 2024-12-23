<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfProposal extends Model
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_type_of_proposals', 'type_of_proposal_id', 'role_id');
    }
}
