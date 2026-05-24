<?php

namespace App\Actions\Admin;

use App\Models\Skill;

class UpdateSkillAction
{
    public function execute(Skill $skill, array $data): Skill
    {
        $skill->update([
            'name'         => $data['name'],
            'subdomain_id' => $data['subdomain_id'],
        ]);

        return $skill->fresh();
    }
}
