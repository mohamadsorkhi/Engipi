<?php

namespace App\Actions\Admin;

use App\Models\Skill;
use App\Services\Taxonomy\SkillSubdomainAuthority;

final class CreateSkillAction
{
    public function __construct(
        private readonly SkillSubdomainAuthority $authority,
    ) {}

    public function execute(array $data): Skill
    {
        return $this->authority->create([
            'name' => $data['name'],
            'subdomain_id' => $data['subdomain_id'],
        ]);
    }
}
