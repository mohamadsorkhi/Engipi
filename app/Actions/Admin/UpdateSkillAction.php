<?php

namespace App\Actions\Admin;

use App\Models\Skill;

class UpdateSkillAction
{
    public function execute(Skill $skill, array $data): Skill
    {
        $skill->update([
            'name' => $data['name'],
            'process_id' => $data['process_id'] ?? $skill->process_id,
        ]);

        return $skill->fresh();
    }
}
