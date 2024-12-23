<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'campus',
        'image',
        'google_id',
        'verified_email',        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    

    public function campus()
    {
        return $this->belongsTo(Campuses::class, 'campus_id', 'campus_id');
    }
      /**
     * Relationship with Academic Council Membership
     */
    public function academicCouncilMembership()
    {
        return $this->hasOne(AcademicCouncilMembership::class, 'users_id', 'id');
    }


   
    /**
     * Relationship with Admin Council Membership
     */
    public function adminCouncilMembership()
    {
        return $this->hasOne(AdminCouncilMembership::class, 'users_id', 'id');
    }

    /**
     * Relationship with Board Secretary
     */
    public function boardSecretary()
    {
        return $this->hasOne(BoardSecretary::class, 'users_id', 'id');
    }

    /**
     * Relationship with Local Secretary
     */
    public function localSecretary()
    {
        return $this->hasOne(LocalSecretary::class, 'users_id', 'id');
    }

    /**
     * Relationship with University Secretary
     */
    public function universitySecretary()
    {
        return $this->hasOne(UniversitySecretary::class, 'users_id', 'id');
    }

    public function proposals()
    {
        return $this->hasMany(Proposals::class);
    }

    public function proposalVersions()
    {
        return $this->hasMany(ProposalVersion::class);
    }

    public function hasRole(string $role): bool
    {
        return match ($role) {
            'academic_council_membership' => $this->academicCouncilMembership()->exists(),
            'admin_council_membership' => $this->adminCouncilMembership()->exists(),
            'local_secretary' => $this->localSecretary()->exists(),
            'board_sec' => $this->boardSecretary()->exists(),
            'university_secretary' => $this->universitySecretary()->exists(),
            default => false,
        };
    }
}
