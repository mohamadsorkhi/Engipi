<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class Skill extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['name', 'process_id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
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
     * The users that have this skill.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_skills');
    }

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    /**
     * The projects that require this skill.
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_skills');
    }
}
