<?php

namespace App\Services\Taxonomy;

use App\Models\Skill;
use Illuminate\Support\Facades\DB;

final class SkillSubdomainAuthority
{
    /**
     * Create a skill while writing both the legacy primary relation
     * and the canonical pivot relation.
     */
    public function create(array $attributes): Skill
    {
        return DB::transaction(function () use ($attributes): Skill {
            $skill = Skill::query()->create($attributes);

            $this->attach($skill, $attributes['subdomain_id']);

            return $skill->fresh();
        });
    }

    /**
     * Update the legacy primary relation and ensure that the same
     * relation exists in the canonical pivot without removing any
     * additional canonical subdomain relations.
     */
    public function updatePrimary(
        Skill $skill,
        array $attributes,
    ): Skill {
        return DB::transaction(function () use ($skill, $attributes): Skill {
            $skill->update($attributes);

            $this->attach($skill, $attributes['subdomain_id']);

            return $skill->fresh();
        });
    }

    /**
     * Add a canonical Skill/Subdomain relation idempotently.
     */
    public function attach(Skill $skill, string $subdomainId): void
    {
        $skill->subdomains()->syncWithoutDetaching([$subdomainId]);
    }
}
