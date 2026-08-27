<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subdomain extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'skill_domain_id',
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(
            SkillDomain::class,
            'skill_domain_id',
        );
    }

    /**
     * Legacy singular Skill/Subdomain relationship.
     *
     * Keep this relationship during the migration period because existing
     * write and cleanup paths still depend on skills.subdomain_id.
     */
    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }

    /**
     * Canonical Skill/Subdomain relationship.
     */
    public function canonicalSkills(): BelongsToMany
    {
        return $this->belongsToMany(
            Skill::class,
            'skill_subdomain',
            'subdomain_id',
            'skill_id',
        );
    }

    public function skillSuggestions(): HasMany
    {
        return $this->hasMany(SkillSuggestion::class);
    }
}
