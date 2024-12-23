<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversitySecretary extends Model
{
    protected $table = 'university_secretary';
    protected $primaryKey = 'users_id';
    public $incrementing = false;
    protected $fillable = ['users_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
