<?php

namespace Tests\Feature\Authorization;

use Illuminate\Support\Str;

class CollaborationRequestAuthorizationCharacterizationTest extends AuthorizationTestCase
{
    public function test_guest_cannot_submit_collaboration_request(): void
    {
        $employer = $this->createUser(['role' => 'employer']);
        $project = $this->createProject($employer);

        $this->postJson(route('user.requests.store'), [
            'project_id' => $project->id,
        ])->assertUnauthorized();

        $this->assertDatabaseCount('requests', 0);
    }

    public function test_specialist_can_submit_request_for_matched_project_with_current_json_contract(): void
    {
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');
        $employer = $this->createUser(['role' => 'employer']);
        $project = $this->createProject($employer);
        $this->matchProjectToSpecialist($project, $specialist);

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->postJson(route('user.requests.store'), [
                'project_id' => $project->id,
                'message' => 'Please consider this request.',
            ])
            ->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('requests', [
            'project_id' => $project->id,
            'user_id' => $specialist->id,
            'status' => 'pending',
        ]);
    }

    public function test_unmatched_project_request_is_rejected_without_creating_request(): void
    {
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');
        $employer = $this->createUser(['role' => 'employer']);
        $project = $this->createProject($employer);

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->postJson(route('user.requests.store'), [
                'project_id' => $project->id,
            ])
            ->assertUnprocessable()
            ->assertJsonPath('status', 'error');

        $this->assertDatabaseCount('requests', 0);
    }

    public function test_own_project_request_is_rejected_even_when_skills_match(): void
    {
        $specialist = $this->createUser(['role' => 'employer']);
        $this->createProfile($specialist, 'specialist');
        $project = $this->createProject($specialist);
        $this->matchProjectToSpecialist($project, $specialist);

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->postJson(route('user.requests.store'), [
                'project_id' => $project->id,
            ])
            ->assertUnprocessable()
            ->assertJsonPath('status', 'error');

        $this->assertDatabaseCount('requests', 0);
    }

    public function test_rejected_project_request_is_rejected_before_duplicate_handling(): void
    {
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');
        $employer = $this->createUser(['role' => 'employer']);
        $project = $this->createProject($employer);
        $this->matchProjectToSpecialist($project, $specialist);
        $this->createCollaborationRequest($project, $specialist, 'rejected');

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->postJson(route('user.requests.store'), [
                'project_id' => $project->id,
            ])
            ->assertUnprocessable()
            ->assertJsonPath('status', 'error');

        $this->assertDatabaseCount('requests', 1);
    }

    public function test_malformed_project_id_is_rejected_by_validation(): void
    {
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->postJson(route('user.requests.store'), [
                'project_id' => 'not-a-uuid',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('project_id');

        $this->assertDatabaseCount('requests', 0);
    }

    public function test_nonexistent_project_id_is_rejected_by_validation(): void
    {
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->postJson(route('user.requests.store'), [
                'project_id' => (string) Str::uuid(),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('project_id');

        $this->assertDatabaseCount('requests', 0);
    }

    public function test_duplicate_request_is_rejected_with_current_response_contract(): void
    {
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');
        $employer = $this->createUser(['role' => 'employer']);
        $project = $this->createProject($employer);
        $this->matchProjectToSpecialist($project, $specialist);
        $this->createCollaborationRequest($project, $specialist);

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->postJson(route('user.requests.store'), [
                'project_id' => $project->id,
            ])
            ->assertUnprocessable()
            ->assertJsonPath('status', 'error');

        $this->assertDatabaseCount('requests', 1);
    }
}
