<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'department_id',
        'profile_photo_path',
        'email',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    /**
     * Public URL for the profile photo, or null if none uploaded.
     */
    public function profilePhotoUrl(): ?string
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : null;
    }
}