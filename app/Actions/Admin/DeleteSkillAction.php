<?php

namespace App\Actions\Admin;

use App\Models\Skill;
use Illuminate\Support\Facades\DB;

class DeleteSkillAction
{
    public function execute(Skill $skill): bool
    {
        return DB::transaction(function () use ($skill) {
            $skill->users()->detach();
            $skill->projects()->detach();
            return $skill->delete();
        });
    }
}
