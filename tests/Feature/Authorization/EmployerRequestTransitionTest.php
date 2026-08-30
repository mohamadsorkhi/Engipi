<?php

namespace Tests\Feature\Authorization;

use App\Models\Request;
use App\Models\User;

class EmployerRequestTransitionTest extends AuthorizationTestCase
{
    public function test_project_owner_can_accept_pending_request(): void
    {
        [$employer, $request] = $this->createEmployerRequest('pending');

        $this->actingAs($employer)
            ->withSession(['active_role' => 'employer'])
            ->postJson(route('user.requests.accept', $request))
            ->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('requests', [
            'id' => $request->id,
            'status' => 'accepted',
        ]);
    }

    public function test_project_owner_can_reject_pending_request(): void
    {
        [$employer, $request] = $this->createEmployerRequest('pending');

        $this->actingAs($employer)
            ->withSession(['active_role' => 'employer'])
            ->postJson(route('user.requests.reject', $request))
            ->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('requests', [
            'id' => $request->id,
            'status' => 'rejected',
        ]);
    }

    public function test_project_owner_can_revert_accepted_request_to_pending(): void
    {
        [$employer, $request] = $this->createEmployerRequest('accepted');

        $this->actingAs($employer)
            ->withSession(['active_role' => 'employer'])
            ->postJson(route('user.requests.revert', $request))
            ->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('requests', [
            'id' => $request->id,
            'status' => 'pending',
        ]);
    }

    public function test_project_owner_can_revert_rejected_request_to_pending(): void
    {
        [$employer, $request] = $this->createEmployerRequest('rejected');

        $this->actingAs($employer)
            ->withSession(['active_role' => 'employer'])
            ->postJson(route('user.requests.revert', $request))
            ->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('requests', [
            'id' => $request->id,
            'status' => 'pending',
        ]);
    }

    public function test_already_accepted_request_cannot_be_accepted_again(): void
    {
        [$employer, $request] = $this->createEmployerRequest('accepted');

        $this->actingAs($employer)
            ->withSession(['active_role' => 'employer'])
            ->postJson(route('user.requests.accept', $request))
            ->assertUnprocessable();

        $this->assertDatabaseHas('requests', [
            'id' => $request->id,
            'status' => 'accepted',
        ]);
    }

    public function test_already_rejected_request_cannot_be_rejected_again(): void
    {
        [$employer, $request] = $this->createEmployerRequest('rejected');

        $this->actingAs($employer)
            ->withSession(['active_role' => 'employer'])
            ->postJson(route('user.requests.reject', $request))
            ->assertUnprocessable();

        $this->assertDatabaseHas('requests', [
            'id' => $request->id,
            'status' => 'rejected',
        ]);
    }

    public function test_pending_request_cannot_be_reverted(): void
    {
        [$employer, $request] = $this->createEmployerRequest('pending');

        $this->actingAs($employer)
            ->withSession(['active_role' => 'employer'])
            ->postJson(route('user.requests.revert', $request))
            ->assertUnprocessable();

        $this->assertDatabaseHas('requests', [
            'id' => $request->id,
            'status' => 'pending',
        ]);
    }

    public function test_another_employer_cannot_manage_request(): void
    {
        [, $request] = $this->createEmployerRequest('pending');

        $otherEmployer = $this->createUser([
            'role' => 'employer',
        ]);

        $this->createProfile($otherEmployer, 'employer');

        foreach ([
            'user.requests.accept',
            'user.requests.reject',
            'user.requests.revert',
        ] as $routeName) {
            $this->actingAs($otherEmployer)
                ->withSession(['active_role' => 'employer'])
                ->postJson(route($routeName, $request))
                ->assertForbidden();

            $this->assertDatabaseHas('requests', [
                'id' => $request->id,
                'status' => 'pending',
            ]);
        }
    }

    /**
     * @return array{
     *     0: User,
     *     1: Request
     * }
     */
    private function createEmployerRequest(string $status): array
    {
        $employer = $this->createUser([
            'role' => 'employer',
        ]);

        $project = $this->createProject($employer);

        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');

        $request = $this->createCollaborationRequest(
            $project,
            $specialist,
            $status,
        );

        return [$employer, $request];
    }
}
