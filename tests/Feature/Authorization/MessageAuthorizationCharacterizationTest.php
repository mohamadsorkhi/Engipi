<?php

namespace Tests\Feature\Authorization;

use Illuminate\Support\Str;

class MessageAuthorizationCharacterizationTest extends AuthorizationTestCase
{
    public function test_guest_is_redirected_from_messages(): void
    {
        $this->get(route('user.messages.index'))
            ->assertRedirect(route('login'));
    }

    public function test_existing_participant_can_open_thread_and_incoming_message_is_marked_read(): void
    {
        $sender = $this->createUser();
        $this->createProfile($sender, 'specialist');
        $receiver = $this->createUser(['role' => 'employer']);
        $this->createProfile($receiver, 'employer');
        $message = $this->createMessage($receiver, $sender);

        $this->actingAs($sender)
            ->withSession(['active_role' => 'specialist'])
            ->get(route('user.messages.show', $receiver))
            ->assertOk()
            ->assertViewIs('user.messages.show')
            ->assertViewHas('user', fn ($viewUser) => $viewUser->is($receiver));

        $this->assertNotNull($message->fresh()->read_at);
    }

    public function test_pending_project_participants_can_open_and_send_through_existing_unlinked_form(): void
    {
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');
        $employer = $this->createUser(['role' => 'employer']);
        $this->createProfile($employer, 'employer');
        $project = $this->createProject($employer);
        $this->createCollaborationRequest($project, $specialist);

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->get(route('user.messages.show', $employer))
            ->assertOk();

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->post(route('user.messages.store'), [
                'receiver_id' => $employer->id,
                'body' => 'Authorized project message.',
            ])
            ->assertRedirect(route('user.messages.show', $employer))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('messages', [
            'sender_id' => $specialist->id,
            'receiver_id' => $employer->id,
            'project_id' => null,
        ]);
    }

    public function test_accepted_project_relationship_allows_employer_to_message_specialist(): void
    {
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');
        $employer = $this->createUser(['role' => 'employer']);
        $this->createProfile($employer, 'employer');
        $project = $this->createProject($employer);
        $this->createCollaborationRequest($project, $specialist, 'accepted');

        $this->actingAs($employer)
            ->withSession(['active_role' => 'employer'])
            ->post(route('user.messages.store'), [
                'receiver_id' => $specialist->id,
                'project_id' => $project->id,
                'body' => 'Accepted collaboration message.',
            ])
            ->assertRedirect(route('user.messages.show', $specialist))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('messages', [
            'sender_id' => $employer->id,
            'receiver_id' => $specialist->id,
            'project_id' => $project->id,
        ]);
    }

    public function test_unrelated_user_cannot_open_conversation(): void
    {
        $user = $this->createUser();
        $this->createProfile($user, 'specialist');
        $unrelated = $this->createUser(['role' => 'employer']);

        $this->actingAs($user)
            ->withSession(['active_role' => 'specialist'])
            ->get(route('user.messages.show', $unrelated))
            ->assertNotFound()
            ->assertDontSee($unrelated->full_name);
    }

    public function test_unrelated_recipient_and_project_are_rejected_without_persisting_message(): void
    {
        $sender = $this->createUser();
        $this->createProfile($sender, 'specialist');
        $receiver = $this->createUser(['role' => 'employer']);
        $projectOwner = $this->createUser(['role' => 'employer']);
        $project = $this->createProject($projectOwner);

        $this->actingAs($sender)
            ->withSession(['active_role' => 'specialist'])
            ->from(route('user.messages.index'))
            ->post(route('user.messages.store'), [
                'receiver_id' => $receiver->id,
                'project_id' => $project->id,
                'body' => 'Unauthorized unrelated message.',
            ])
            ->assertRedirect(route('user.messages.index'))
            ->assertSessionHasErrors('body');

        $this->assertDatabaseCount('messages', 0);
    }

    public function test_existing_legacy_conversation_can_continue_without_project_relationship(): void
    {
        $sender = $this->createUser();
        $this->createProfile($sender, 'specialist');
        $receiver = $this->createUser(['role' => 'employer']);
        $this->createMessage($receiver, $sender);

        $this->actingAs($sender)
            ->withSession(['active_role' => 'specialist'])
            ->post(route('user.messages.store'), [
                'receiver_id' => $receiver->id,
                'body' => 'Legacy conversation continuation.',
            ])
            ->assertRedirect(route('user.messages.show', $receiver))
            ->assertSessionHas('success');

        $this->assertDatabaseCount('messages', 2);
    }

    public function test_existing_conversation_can_continue_after_relationship_is_rejected(): void
    {
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');
        $employer = $this->createUser(['role' => 'employer']);
        $project = $this->createProject($employer);
        $this->createCollaborationRequest($project, $specialist, 'rejected');
        $this->createMessage($employer, $specialist, $project);

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->get(route('user.messages.show', $employer))
            ->assertOk();

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->post(route('user.messages.store'), [
                'receiver_id' => $employer->id,
                'body' => 'Existing thread continuation after rejection.',
            ])
            ->assertRedirect(route('user.messages.show', $employer))
            ->assertSessionHas('success');

        $this->assertDatabaseCount('messages', 2);
    }

    public function test_existing_legacy_conversation_cannot_attach_unrelated_project(): void
    {
        $sender = $this->createUser();
        $this->createProfile($sender, 'specialist');
        $receiver = $this->createUser(['role' => 'employer']);
        $this->createMessage($receiver, $sender);
        $projectOwner = $this->createUser(['role' => 'employer']);
        $project = $this->createProject($projectOwner);

        $this->actingAs($sender)
            ->withSession(['active_role' => 'specialist'])
            ->from(route('user.messages.show', $receiver))
            ->post(route('user.messages.store'), [
                'receiver_id' => $receiver->id,
                'project_id' => $project->id,
                'body' => 'Invalid project association.',
            ])
            ->assertRedirect(route('user.messages.show', $receiver))
            ->assertSessionHasErrors('body');

        $this->assertDatabaseCount('messages', 1);
    }

    public function test_rejected_relationship_cannot_start_new_conversation(): void
    {
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');
        $employer = $this->createUser(['role' => 'employer']);
        $project = $this->createProject($employer);
        $this->createCollaborationRequest($project, $specialist, 'rejected');

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->get(route('user.messages.show', $employer))
            ->assertNotFound();

        $this->actingAs($specialist)
            ->withSession(['active_role' => 'specialist'])
            ->from(route('user.messages.index'))
            ->post(route('user.messages.store'), [
                'receiver_id' => $employer->id,
                'project_id' => $project->id,
                'body' => 'Rejected relationship message.',
            ])
            ->assertRedirect(route('user.messages.index'))
            ->assertSessionHasErrors('body');

        $this->assertDatabaseCount('messages', 0);
    }

    public function test_invalid_conversation_recipient_returns_not_found(): void
    {
        $user = $this->createUser();
        $this->createProfile($user, 'specialist');

        $this->actingAs($user)
            ->withSession(['active_role' => 'specialist'])
            ->get('/user/messages/'.Str::uuid())
            ->assertNotFound();
    }

    public function test_invalid_message_recipient_is_rejected_by_existing_validation(): void
    {
        $user = $this->createUser();
        $this->createProfile($user, 'specialist');

        $this->actingAs($user)
            ->withSession(['active_role' => 'specialist'])
            ->from(route('user.messages.index'))
            ->post(route('user.messages.store'), [
                'receiver_id' => (string) Str::uuid(),
                'body' => 'Invalid recipient.',
            ])
            ->assertRedirect(route('user.messages.index'))
            ->assertSessionHasErrors('receiver_id');

        $this->assertDatabaseCount('messages', 0);
    }

    public function test_self_message_is_rejected_and_not_persisted(): void
    {
        $user = $this->createUser();
        $this->createProfile($user, 'specialist');

        $this->actingAs($user)
            ->withSession(['active_role' => 'specialist'])
            ->from(route('user.messages.index'))
            ->post(route('user.messages.store'), [
                'receiver_id' => $user->id,
                'body' => 'Self message',
            ])
            ->assertRedirect(route('user.messages.index'))
            ->assertSessionHasErrors('body');

        $this->assertDatabaseCount('messages', 0);
    }

    public function test_self_conversation_retains_forbidden_response(): void
    {
        $user = $this->createUser();
        $this->createProfile($user, 'specialist');

        $this->actingAs($user)
            ->withSession(['active_role' => 'specialist'])
            ->get(route('user.messages.show', $user))
            ->assertForbidden();
    }
}
