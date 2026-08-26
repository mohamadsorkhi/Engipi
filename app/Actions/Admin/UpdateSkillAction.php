<?php

namespace App\Actions\Admin;

use App\Models\Skill;
use App\Services\Taxonomy\SkillSubdomainAuthority;

final class UpdateSkillAction
{
    public function __construct(
        private readonly SkillSubdomainAuthority $authority,
    ) {}

    public function execute(Skill $skill, array $data): Skill
    {
        return $this->authority->updatePrimary($skill, [
            'name' => $data['name'],
            'subdomain_id' => $data['subdomain_id'],
        ]);
    }
}
