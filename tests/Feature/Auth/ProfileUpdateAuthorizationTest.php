<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileUpdateAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_update_profile(): void
    {
        [, $profile] = $this->userWithProfile('employer');

        $this->putJson(route('profiles.update', $profile), [
            'company_name' => 'Unauthorized company',
        ])->assertUnauthorized();

        $this->assertDatabaseMissing('user_profiles', [
            'id' => $profile->id,
            'company_name' => 'Unauthorized company',
        ]);
    }

    public function test_owner_can_update_employer_profile(): void
    {
        [$user, $profile] = $this->userWithProfile('employer');

        $this->actingAs($user)
            ->putJson(route('profiles.update', $profile), [
                'company_name' => 'Updated Engineering Company',
                'headline' => 'Engineering project employer',
                'bio' => 'Updated employer profile biography.',
            ])
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath(
                'profile.company_name',
                'Updated Engineering Company',
            );

        $this->assertDatabaseHas('user_profiles', [
            'id' => $profile->id,
            'user_id' => $user->id,
            'type' => 'employer',
            'company_name' => 'Updated Engineering Company',
            'headline' => 'Engineering project employer',
            'bio' => 'Updated employer profile biography.',
        ]);
    }

    public function test_owner_can_update_specialist_profile_with_headline(): void
    {
        [$user, $profile] = $this->userWithProfile('specialist');

        $this->actingAs($user)
            ->putJson(route('profiles.update', $profile), [
                'headline' => 'Structural engineering specialist',
                'bio' => 'Specialist profile biography.',
            ])
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath(
                'profile.headline',
                'Structural engineering specialist',
            );

        $this->assertDatabaseHas('user_profiles', [
            'id' => $profile->id,
            'user_id' => $user->id,
            'type' => 'specialist',
            'headline' => 'Structural engineering specialist',
            'bio' => 'Specialist profile biography.',
        ]);
    }

    public function test_user_cannot_update_another_users_profile(): void
    {
        [$owner, $profile] = $this->userWithProfile(
            'employer',
            [
                'company_name' => 'Original company',
                'headline' => 'Original headline',
            ],
        );

        $attacker = User::factory()->create();

        $this->actingAs($attacker)
            ->putJson(route('profiles.update', $profile), [
                'company_name' => 'Attacker company',
                'headline' => 'Attacker headline',
                'bio' => 'Unauthorized change.',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('user_profiles', [
            'id' => $profile->id,
            'user_id' => $owner->id,
            'company_name' => 'Original company',
            'headline' => 'Original headline',
        ]);

        $this->assertDatabaseMissing('user_profiles', [
            'id' => $profile->id,
            'company_name' => 'Attacker company',
        ]);
    }

    public function test_invalid_profile_data_is_rejected_without_mutation(): void
    {
        [$user, $profile] = $this->userWithProfile(
            'employer',
            [
                'company_name' => 'Original company',
                'headline' => 'Original headline',
            ],
        );

        $this->actingAs($user)
            ->putJson(route('profiles.update', $profile), [
                'company_name' => str_repeat('a', 256),
                'headline' => str_repeat('b', 256),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'company_name',
                'headline',
            ]);

        $this->assertDatabaseHas('user_profiles', [
            'id' => $profile->id,
            'company_name' => 'Original company',
            'headline' => 'Original headline',
        ]);
    }

    public function test_specialist_headline_is_required(): void
    {
        [$user, $profile] = $this->userWithProfile(
            'specialist',
            [
                'headline' => 'Original specialist headline',
                'bio' => 'Original biography.',
            ],
        );

        $this->actingAs($user)
            ->putJson(route('profiles.update', $profile), [
                'bio' => 'Biography without a headline.',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('headline');

        $this->assertDatabaseHas('user_profiles', [
            'id' => $profile->id,
            'headline' => 'Original specialist headline',
            'bio' => 'Original biography.',
        ]);
    }

    public function test_protected_profile_identity_fields_cannot_be_changed(): void
    {
        [$user, $profile] = $this->userWithProfile(
            'specialist',
            [
                'headline' => 'Original specialist headline',
            ],
        );

        $otherUser = User::factory()->create();

        $this->actingAs($user)
            ->putJson(route('profiles.update', $profile), [
                'headline' => 'Updated specialist headline',
                'user_id' => $otherUser->id,
                'type' => 'employer',
            ])
            ->assertOk()
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('user_profiles', [
            'id' => $profile->id,
            'user_id' => $user->id,
            'type' => 'specialist',
            'headline' => 'Updated specialist headline',
        ]);
    }

    /**
     * @return array{0: User, 1: UserProfile}
     */
    private function userWithProfile(
        string $type,
        array $profileAttributes = [],
    ): array {
        $user = User::factory()->create();

        $profile = UserProfile::query()->create(array_merge([
            'user_id' => $user->id,
            'type' => $type,
        ], $profileAttributes));

        return [$user, $profile];
    }
}
