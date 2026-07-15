<?php

namespace Tests\Feature\Authorization;

class MatchedProjectAuthorizationCharacterizationTest extends AuthorizationTestCase
{
    public function test_guest_is_redirected_from_matched_project_index(): void
    {
        $this->get(route('user.matched-projects.index'))
            ->assertRedirect(route('login'));
    }

    public function test_specialist_can_view_a_matched_project_and_view_count_increments(): void
    {
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');
        $employer = $this->createUser(['role' => 'employer']);
        $project = $this->createProject($employer);
        $this->matchProjectToSpecialist($project, $specialist);

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->get(route('user.matched-projects.show', $project))
            ->assertOk()
            ->assertViewIs('user.matched-projects.show')
            ->assertViewHas('project', fn ($viewProject) => $viewProject->is($project));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'view_count' => 1,
        ]);
    }

    public function test_unmatched_project_returns_not_found_without_incrementing_view_count(): void
    {
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');
        $employer = $this->createUser(['role' => 'employer']);
        $project = $this->createProject($employer);

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->get(route('user.matched-projects.show', $project))
            ->assertNotFound();

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'view_count' => 0,
        ]);
    }

    public function test_specialist_cannot_view_own_project_even_when_skills_match(): void
    {
        $specialist = $this->createUser(['role' => 'employer']);
        $this->createProfile($specialist, 'specialist');
        $project = $this->createProject($specialist);
        $this->matchProjectToSpecialist($project, $specialist);

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->get(route('user.matched-projects.show', $project))
            ->assertNotFound();

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'view_count' => 0,
        ]);
    }

    public function test_rejected_project_returns_not_found_without_incrementing_view_count(): void
    {
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');
        $employer = $this->createUser(['role' => 'employer']);
        $project = $this->createProject($employer);
        $this->matchProjectToSpecialist($project, $specialist);
        $this->createCollaborationRequest($project, $specialist, 'rejected');

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->get(route('user.matched-projects.show', $project))
            ->assertNotFound();

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'view_count' => 0,
        ]);
    }

    public function test_employer_active_role_is_redirected_from_matched_project_detail(): void
    {
        $employer = $this->createUser(['role' => 'employer']);
        $this->createProfile($employer, 'employer');
        $projectOwner = $this->createUser(['role' => 'employer']);
        $project = $this->createProject($projectOwner);

        $this->actingAs($employer)
            ->withSession(['active_role' => 'employer'])
            ->get(route('user.matched-projects.show', $project))
            ->assertRedirect(route('profile.select'));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'view_count' => 0,
        ]);
    }
}
