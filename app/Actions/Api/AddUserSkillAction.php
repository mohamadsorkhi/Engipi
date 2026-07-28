<?php

namespace App\Actions\Api;

use App\Models\User;
use App\Models\UserSkill;
use Illuminate\Support\Facades\DB;

class AddUserSkillAction
{
    public function execute(User $user, string $skillId): bool
    {
        return DB::transaction(function () use ($user, $skillId): bool {
            User::query()
                ->whereKey($user->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $exists = UserSkill::query()
                ->where('user_id', $user->getKey())
                ->where('skill_id', $skillId)
                ->exists();

            if ($exists) {
                return false;
            }

            UserSkill::query()->create([
                'user_id' => $user->getKey(),
                'skill_id' => $skillId,
            ]);

            return true;
        });
    }
}
