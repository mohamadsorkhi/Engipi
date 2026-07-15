<?php

namespace Tests\Feature\Authorization;

use App\Models\Message;
use App\Models\Project;
use App\Models\Request as CollaborationRequest;
use App\Models\Skill;
use App\Models\SkillDomain;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

abstract class AuthorizationTestCase extends TestCase
{
    use RefreshDatabase;

    protected function createUser(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }

    protected function createProfile(User $user, string $type): UserProfile
    {
        return UserProfile::query()->create([
            'user_id' => $user->id,
            'type' => $type,
        ]);
    }

    protected function createProject(User $employer, array $attributes = []): Project
    {
        $profile = $employer->profiles()->where('type', 'employer')->first()
            ?? $this->createProfile($employer, 'employer');

        return Project::query()->create(array_merge([
            'employer_id' => $employer->id,
            'employer_profile_id' => $profile->id,
            'short_id' => Str::upper(Str::random(12)),
            'title' => 'Characterization project',
            'description' => 'Synthetic project used only by the isolated test suite.',
            'work_type' => 'remote',
        ], $attributes));
    }

    protected function matchProjectToSpecialist(Project $project, User $specialist): Skill
    {
        $domain = SkillDomain::query()->create([
            'name' => 'Domain '.Str::random(10),
        ]);

        $skill = Skill::query()->create([
            'name' => 'Skill '.Str::random(10),
            'skill_domain_id' => $domain->id,
        ]);

        $specialist->skills()->attach($skill->id, [
            'level' => 'intermediate',
            'years_of_experience' => 2,
        ]);
        $project->skills()->attach($skill->id, [
            'level' => 'intermediate',
            'years_of_experience' => 1,
        ]);

        return $skill;
    }

    protected function createCollaborationRequest(
        Project $project,
        User $specialist,
        string $status = 'pending'
    ): CollaborationRequest {
        return CollaborationRequest::query()->create([
            'project_id' => $project->id,
            'user_id' => $specialist->id,
            'message' => 'Characterization request',
            'status' => $status,
        ]);
    }

    protected function createMessage(
        User $sender,
        User $receiver,
        ?Project $project = null,
        array $attributes = []
    ): Message {
        return Message::query()->create(array_merge([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'project_id' => $project?->id,
            'body' => 'Characterization message',
        ], $attributes));
    }
}
