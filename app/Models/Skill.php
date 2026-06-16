<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Skill extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'subdomain_id',
    ];

    public function subdomain()
    {
        return $this->belongsTo(Subdomain::class);
    }

    public function subdomains()
    {
        return $this->belongsToMany(
            Subdomain::class,
            'skill_subdomain',
            'skill_id',
            'subdomain_id'
        );
    }
}