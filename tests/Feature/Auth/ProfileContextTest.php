<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\UserProfile;
use App\Support\Auth\ProfileContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ProfileContextTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware(['web', 'auth', 'active_role:employer'])
            ->get('/_test/profile-context/employer', fn () => response('employer'));
        Route::middleware(['web', 'auth', 'active_role:specialist'])
            ->get('/_test/profile-context/specialist', fn () => response('specialist'));
    }

    public function test_user_with_zero_profiles_can_log_in(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $this->post('/login', ['login' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('profile.select'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_single_employer_profile_is_selected_transparently(): void
    {
        [$user, $profile] = $this->userWithProfile('employer');

        $this->actingAs($user)->get(route('user.dashboard'))->assertOk()
            ->assertSessionHas(ProfileContext::SESSION_KEY, $profile->id)
            ->assertSessionHas(ProfileContext::LEGACY_SESSION_KEY, 'employer');
    }

    public function test_single_specialist_profile_is_selected_transparently(): void
    {
        [$user, $profile] = $this->userWithProfile('specialist');

        $this->actingAs($user)->get(route('user.dashboard'))->assertOk()
            ->assertSessionHas(ProfileContext::SESSION_KEY, $profile->id);
    }

    public function test_dual_profile_user_without_context_must_choose(): void
    {
        [$user] = $this->userWithProfile('employer');
        $this->profile($user, 'specialist');

        $this->actingAs($user)->get(route('user.dashboard'))
            ->assertRedirect(route('profile.select'))
            ->assertSessionMissing(ProfileContext::SESSION_KEY);
    }

    public function test_user_can_switch_safely_between_owned_profiles(): void
    {
        [$user, $employer] = $this->userWithProfile('employer');
        $specialist = $this->profile($user, 'specialist');

        $this->actingAs($user)->post(route('profile.activate'), ['profile_id' => $employer->id])
            ->assertRedirect(route('root'))
            ->assertSessionHas(ProfileContext::SESSION_KEY, $employer->id);

        $this->post(route('profile.activate'), ['profile_id' => $specialist->id])
            ->assertSessionHas(ProfileContext::SESSION_KEY, $specialist->id)
            ->assertSessionHas(ProfileContext::LEGACY_SESSION_KEY, 'specialist');
    }

    public function test_another_users_profile_id_is_rejected(): void
    {
        [$user] = $this->userWithProfile('employer');
        [$other, $foreign] = $this->userWithProfile('specialist');

        $this->actingAs($user)->post(route('profile.activate'), ['profile_id' => $foreign->id])
            ->assertRedirect(route('profile.select'))
            ->assertSessionMissing(ProfileContext::SESSION_KEY);
    }

    public function test_stale_active_profile_id_is_cleared(): void
    {
        [$user] = $this->userWithProfile('employer');
        $this->profile($user, 'specialist');

        $this->actingAs($user)->withSession([ProfileContext::SESSION_KEY => fake()->uuid()])
            ->get(route('user.dashboard'))->assertRedirect(route('profile.select'))
            ->assertSessionMissing(ProfileContext::SESSION_KEY);
    }

    public function test_deleted_active_profile_is_safely_recovered(): void
    {
        [$user, $profile] = $this->userWithProfile('employer');
        $profileId = $profile->id;
        $profile->delete();

        $this->actingAs($user)->withSession([ProfileContext::SESSION_KEY => $profileId])
            ->get(route('user.dashboard'))->assertRedirect(route('profile.select'))
            ->assertSessionMissing(ProfileContext::SESSION_KEY);
    }

    public function test_valid_legacy_role_is_translated_to_owned_profile_id(): void
    {
        [$user, $profile] = $this->userWithProfile('specialist');

        $this->actingAs($user)->withSession([ProfileContext::LEGACY_SESSION_KEY => 'specialist'])
            ->get(route('user.dashboard'))->assertOk()
            ->assertSessionHas(ProfileContext::SESSION_KEY, $profile->id);
    }

    public function test_ambiguous_or_invalid_legacy_state_is_cleared(): void
    {
        [$user] = $this->userWithProfile('employer');
        $this->profile($user, 'specialist');

        $this->actingAs($user)->withSession([ProfileContext::LEGACY_SESSION_KEY => 'admin'])
            ->get(route('user.dashboard'))->assertRedirect(route('profile.select'))
            ->assertSessionMissing(ProfileContext::LEGACY_SESSION_KEY);
    }

    public function test_incomplete_specialist_profile_does_not_block_login(): void
    {
        [$user] = $this->userWithProfile('specialist', ['password' => bcrypt('password')]);

        $this->post('/login', ['login' => $user->email, 'password' => 'password'])
            ->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_access_does_not_require_an_active_profile(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'role' => 'worker']);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
    }

    public function test_non_admin_cannot_gain_admin_access_from_legacy_values(): void
    {
        $user = User::factory()->create(['is_admin' => false, 'role' => 'admin']);

        $this->actingAs($user)->withSession([
            ProfileContext::LEGACY_SESSION_KEY => 'admin',
            ProfileContext::SESSION_KEY => 'admin',
        ])->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_route_access_follows_selected_owned_profile(): void
    {
        [$user, $employer] = $this->userWithProfile('employer');
        $specialist = $this->profile($user, 'specialist');

        $this->actingAs($user)->withSession([ProfileContext::SESSION_KEY => $employer->id])
            ->get('/_test/profile-context/employer')->assertOk();
        $this->get('/_test/profile-context/specialist')->assertRedirect(route('profile.select'));

        $this->withSession([ProfileContext::SESSION_KEY => $specialist->id])
            ->get('/_test/profile-context/specialist')->assertOk();
        $this->get('/_test/profile-context/employer')->assertRedirect(route('profile.select'));
    }

    private function userWithProfile(string $type, array $userAttributes = []): array
    {
        $user = User::factory()->create($userAttributes);

        return [$user, $this->profile($user, $type)];
    }

    private function profile(User $user, string $type): UserProfile
    {
        return UserProfile::query()->create(['user_id' => $user->id, 'type' => $type]);
    }
}
