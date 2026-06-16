<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Subdomain extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'skill_domain_id',
    ];

    public function domain()
    {
        return $this->belongsTo(SkillDomain::class, 'skill_domain_id');
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }
}