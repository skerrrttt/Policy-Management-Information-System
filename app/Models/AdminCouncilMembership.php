<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminCouncilMembership extends Model
{
    protected $table = 'admin_council_membership';
    protected $primaryKey = 'users_id';
    public $incrementing = false;
    protected $fillable = ['users_id', 'office_name'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}
