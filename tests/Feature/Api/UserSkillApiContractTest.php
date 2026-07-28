<?php

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\UserSkillController;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserSkillApiContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_route_uses_intended_controller_and_sanctum_authentication(): void
    {
        $route = collect(Route::getRoutes()->getRoutes())
            ->first(fn ($route) => $route->uri() === 'api/user-skill'
                && in_array('POST', $route->methods(), true));

        $this->assertNotNull($route);
        $this->assertSame(UserSkillController::class.'@store', $route->getActionName());
        $this->assertContains('api', $route->gatherMiddleware());
        $this->assertContains('auth:sanctum', $route->gatherMiddleware());
    }

    public function test_unauthenticated_request_is_rejected_without_a_write(): void
    {
        $skill = $this->createSkill();

        $this->postJson('/api/user-skill', [
            'skill_id' => $skill->id,
        ])->assertUnauthorized();

        $this->assertDatabaseCount('user_skills', 0);
    }

    public function test_authenticated_specialist_can_add_a_skill_to_their_own_account(): void
    {
        $specialist = $this->createUserWithProfile('specialist');
        $skill = $this->createSkill();
        Sanctum::actingAs($specialist);

        $this->postJson('/api/user-skill', [
            'skill_id' => $skill->id,
        ])->assertOk()
            ->assertJsonStructure(['message']);

        $this->assertDatabaseHas('user_skills', [
            'user_id' => $specialist->id,
            'skill_id' => $skill->id,
        ]);
    }

    public function test_request_cannot_select_another_user_as_the_skill_owner(): void
    {
        $specialist = $this->createUserWithProfile('specialist');
        $otherUser = $this->createUserWithProfile('specialist');
        $skill = $this->createSkill();
        Sanctum::actingAs($specialist);

        $this->postJson('/api/user-skill', [
            'skill_id' => $skill->id,
            'user_id' => $otherUser->id,
        ])->assertOk();

        $this->assertDatabaseHas('user_skills', [
            'user_id' => $specialist->id,
            'skill_id' => $skill->id,
        ]);
        $this->assertDatabaseMissing('user_skills', [
            'user_id' => $otherUser->id,
            'skill_id' => $skill->id,
        ]);
    }

    public function test_non_specialist_is_denied_without_a_write(): void
    {
        $employer = $this->createUserWithProfile('employer');
        $skill = $this->createSkill();
        Sanctum::actingAs($employer);

        $this->postJson('/api/user-skill', [
            'skill_id' => $skill->id,
        ])->assertForbidden();

        $this->assertDatabaseCount('user_skills', 0);
    }

    public function test_missing_malformed_and_unknown_skill_ids_are_validation_errors(): void
    {
        $specialist = $this->createUserWithProfile('specialist');
        Sanctum::actingAs($specialist);

        $this->postJson('/api/user-skill', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('skill_id');

        $this->postJson('/api/user-skill', ['skill_id' => 'not-a-uuid'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('skill_id');

        $this->postJson('/api/user-skill', ['skill_id' => (string) Str::uuid()])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('skill_id');

        $this->assertDatabaseCount('user_skills', 0);
    }

    public function test_duplicate_skill_returns_conflict_without_creating_another_row(): void
    {
        $specialist = $this->createUserWithProfile('specialist');
        $skill = $this->createSkill();
        $specialist->skills()->attach($skill->id);
        Sanctum::actingAs($specialist);

        $this->postJson('/api/user-skill', [
            'skill_id' => $skill->id,
        ])->assertConflict()
            ->assertJsonStructure(['message']);

        $this->assertDatabaseCount('user_skills', 1);
    }

    public function test_database_rejects_duplicate_user_skill_rows(): void
    {
        $specialist = $this->createUserWithProfile('specialist');
        $skill = $this->createSkill();

        $specialist->skills()->attach($skill->id);

        $this->expectException(QueryException::class);

        DB::table('user_skills')->insert([
            'user_id' => $specialist->id,
            'skill_id' => $skill->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createUserWithProfile(string $type): User
    {
        $user = User::factory()->create();

        UserProfile::query()->create([
            'user_id' => $user->id,
            'type' => $type,
        ]);

        return $user;
    }

    private function createSkill(): Skill
    {
        return Skill::query()->create([
            'name' => 'API contract skill '.Str::random(10),
        ]);
    }
}
