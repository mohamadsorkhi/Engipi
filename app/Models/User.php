<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Morilog\Jalali\Jalalian;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'mobile',
        'email',
        'password',
        'avatar',
        'role',
        'active',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'boolean',
        'is_admin' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get created_at in Jalali format.
     *
     * @param  string  $value
     * @return \Morilog\Jalali\Jalalian
     */
    public function getCreatedAtAttribute($value)
    {
        return Jalalian::fromDateTime($value);
    }

    /**
     * Get updated_at in Jalali format.
     *
     * @param  string  $value
     * @return \Morilog\Jalali\Jalalian
     */
    public function getUpdatedAtAttribute($value)
    {
        return Jalalian::fromDateTime($value);
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getNameAttribute()
    {
        return $this->getFullNameAttribute();
    }

    /**
     * Get the projects for the user (as an employer).
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'employer_id');
    }

    /**
     * Get the requests sent by the user (as a worker).
     */
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    /**
     * The skills that the user possesses.
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->withPivot(['level', 'years_of_experience'])
            ->withTimestamps();
    }

    public function profiles()
    {
        return $this->hasMany(UserProfile::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketMessages()
    {
        return $this->hasMany(TicketMessage::class, 'user_id');
    }

    public function ticketMessagesAsAdmin()
    {
        return $this->hasMany(TicketMessage::class, 'admin_id');
    }

    public function getDisplayRoleAttribute()
    {
        return $this->role == 'employer' ? 'کارفرما' : 'متخصص';
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
