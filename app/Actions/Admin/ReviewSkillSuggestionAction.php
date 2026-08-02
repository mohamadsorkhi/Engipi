<?php

namespace App\Actions\Admin;

use App\Models\Skill;
use App\Models\SkillSuggestion;
use App\Models\Process;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReviewSkillSuggestionAction
{
    public function approve(SkillSuggestion $suggestion, User $admin): Skill
    {
        return DB::transaction(function () use ($suggestion, $admin): Skill {
            $suggestion = SkillSuggestion::query()->lockForUpdate()->findOrFail($suggestion->id);
            $this->ensurePending($suggestion);

            $skill = Skill::query()->where('skill_type', $suggestion->skill_type)->get()->first(
                fn (Skill $skill): bool => SkillSuggestion::normalizeName($skill->name) === $suggestion->normalized_name
            );

            if (! $skill) {
                $process = null;
                if ($suggestion->skill_type === SkillSuggestion::TYPE_PROCESSING) {
                    $process = Process::query()->firstOrCreate([
                        'skill_domain_id' => $suggestion->subdomain()->value('skill_domain_id'),
                        'name' => $suggestion->skill_name,
                    ]);
                }

                $skill = Skill::query()->create([
                    'name' => $suggestion->skill_name,
                    'subdomain_id' => $suggestion->subdomain_id,
                    'skill_type' => $suggestion->skill_type,
                    'process_id' => $process?->id,
                ]);
            }

            $suggestion->user->skills()->syncWithoutDetaching([
                $skill->id => [
                    'level' => null,
                    'years_of_experience' => null,
                ],
            ]);

            $suggestion->update([
                'status' => SkillSuggestion::STATUS_APPROVED,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
                'pending_name' => null,
            ]);

            return $skill;
        });
    }

    public function reject(SkillSuggestion $suggestion, User $admin): void
    {
        DB::transaction(function () use ($suggestion, $admin): void {
            $suggestion = SkillSuggestion::query()->lockForUpdate()->findOrFail($suggestion->id);
            $this->ensurePending($suggestion);
            $suggestion->update([
                'status' => SkillSuggestion::STATUS_REJECTED,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
                'pending_name' => null,
            ]);
        });
    }

    private function ensurePending(SkillSuggestion $suggestion): void
    {
        if ($suggestion->status !== SkillSuggestion::STATUS_PENDING) {
            throw ValidationException::withMessages([
                'suggestion' => 'این پیشنهاد قبلاً بررسی شده است.',
            ]);
        }
    }
}
