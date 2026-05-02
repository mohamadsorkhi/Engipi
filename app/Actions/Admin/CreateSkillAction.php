<?php

namespace App\Actions\Admin;

use App\Models\Skill;

class CreateSkillAction
{
    public function execute(array $data): Skill
    {
        return Skill::create([
            'name' => $data['name'],
            'process_id' => $data['process_id'] ?? null,
        ]);
    }
}
