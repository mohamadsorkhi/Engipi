<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'skill_domain_id',
        'name',
    ];

    public function domain()
    {
        return $this->belongsTo(SkillDomain::class, 'skill_domain_id');
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function profiles()
    {
        return $this->belongsToMany(UserProfile::class, 'profile_processes', 'process_id', 'profile_id')
            ->withPivot(['level'])
            ->withTimestamps();
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_processes', 'process_id', 'project_id')
            ->withPivot(['desired_levels'])
            ->withTimestamps();
    }
}
