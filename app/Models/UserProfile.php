<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'type',
        'headline',
        'bio',
        'company_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processes()
    {
        return $this->belongsToMany(Process::class, 'profile_processes', 'profile_id', 'process_id')
            ->withPivot(['level'])
            ->withTimestamps();
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'employer_profile_id');
    }

    public function domains()
    {
        return $this->belongsToMany(SkillDomain::class, 'user_profile_domains', 'profile_id', 'skill_domain_id')
            ->withTimestamps();
    }
}
