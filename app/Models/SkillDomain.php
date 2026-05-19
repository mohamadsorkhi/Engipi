<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillDomain extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function subdomains()
    {
        return $this->hasMany(
            Subdomain::class,
            'skill_domain_id'
        );
    }

    public function processes()
    {
        return $this->hasMany(Process::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}