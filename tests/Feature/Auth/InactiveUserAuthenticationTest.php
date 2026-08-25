<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InactiveUserAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware(['web', 'auth'])
            ->get('/_test/auth/active-user', fn () => response('ACTIVE_USER_OK'));

        Route::middleware('auth:sanctum')
            ->get('/_test/api/active-user', fn () => response()->json([
                'status' => 'ACTIVE_USER_OK',
            ]));
    }

    public function test_inactive_user_cannot_log_in(): void
    {
        $user = User::factory()->create([
            'active' => false,
            'password' => Hash::make('password'),
        ]);

        $this->post('/login', [
            'login' => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('login');

        $this->assertGuest();
    }

    public function test_existing_web_session_is_terminated_after_deactivation(): void
    {
        $user = User::factory()->create([
            'active' => false,
        ]);

        $this->actingAs($user)
            ->get('/_test/auth/active-user')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_authenticated_api_request_is_rejected_after_deactivation(): void
    {
        $user = User::factory()->create([
            'active' => false,
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/_test/api/active-user')
            ->assertUnauthorized();
    }

    public function test_active_user_can_access_authenticated_routes(): void
    {
        $user = User::factory()->create([
            'active' => true,
        ]);

        $this->actingAs($user)
            ->get('/_test/auth/active-user')
            ->assertOk()
            ->assertSeeText('ACTIVE_USER_OK');
    }
}
