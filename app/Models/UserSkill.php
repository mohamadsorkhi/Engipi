<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSkill extends Model
{
    protected $table = 'user_skills';

    protected $fillable = [
        'user_id',
        'skill_id',
        'level',
        'years_of_experience',
        'is_custom',
        'custom_title',
    ];

    public $incrementing = false;

    protected $keyType = 'string';
}