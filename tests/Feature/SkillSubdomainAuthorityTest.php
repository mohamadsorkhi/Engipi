<?php

namespace Tests\Feature;

use App\Actions\Admin\ReviewSkillSuggestionAction;
use App\Models\SkillDomain;
use App\Models\SkillSuggestion;
use App\Models\Subdomain;
use App\Models\User;
use App\Services\Taxonomy\SkillSubdomainAuthority;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SkillSubdomainAuthorityTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_a_skill_writes_legacy_and_canonical_relations(): void
    {
        $subdomain = $this->createSubdomain('Primary subdomain');

        $skill = app(SkillSubdomainAuthority::class)->create([
            'name' => 'Canonical test skill',
            'skill_type' => 'field',
            'subdomain_id' => $subdomain->id,
        ]);

        $this->assertSame($subdomain->id, $skill->subdomain_id);

        $this->assertDatabaseHas('skill_subdomain', [
            'skill_id' => $skill->id,
            'subdomain_id' => $subdomain->id,
        ]);
    }

    public function test_updating_primary_subdomain_preserves_existing_canonical_relations(): void
    {
        $firstSubdomain = $this->createSubdomain('First subdomain');
        $secondSubdomain = $this->createSubdomain('Second subdomain');

        $authority = app(SkillSubdomainAuthority::class);

        $skill = $authority->create([
            'name' => 'Multi subdomain skill',
            'skill_type' => 'field',
            'subdomain_id' => $firstSubdomain->id,
        ]);

        $skill = $authority->updatePrimary($skill, [
            'name' => 'Updated multi subdomain skill',
            'skill_type' => 'field',
            'subdomain_id' => $secondSubdomain->id,
        ]);

        $this->assertSame($secondSubdomain->id, $skill->subdomain_id);

        $this->assertDatabaseHas('skill_subdomain', [
            'skill_id' => $skill->id,
            'subdomain_id' => $firstSubdomain->id,
        ]);

        $this->assertDatabaseHas('skill_subdomain', [
            'skill_id' => $skill->id,
            'subdomain_id' => $secondSubdomain->id,
        ]);

        $this->assertDatabaseCount('skill_subdomain', 2);
    }

    public function test_attaching_the_same_relation_is_idempotent(): void
    {
        $subdomain = $this->createSubdomain('Idempotent subdomain');

        $authority = app(SkillSubdomainAuthority::class);

        $skill = $authority->create([
            'name' => 'Idempotent skill',
            'skill_type' => 'field',
            'subdomain_id' => $subdomain->id,
        ]);

        $authority->attach($skill, $subdomain->id);
        $authority->attach($skill, $subdomain->id);

        $this->assertDatabaseCount('skill_subdomain', 1);
    }

    public function test_approving_an_existing_skill_suggestion_adds_the_canonical_relation(): void
    {
        $firstSubdomain = $this->createSubdomain('Existing primary');
        $secondSubdomain = $this->createSubdomain('Suggested subdomain');

        $authority = app(SkillSubdomainAuthority::class);

        $skill = $authority->create([
            'name' => 'Shared canonical skill',
            'skill_type' => SkillSuggestion::TYPE_FIELD,
            'subdomain_id' => $firstSubdomain->id,
        ]);

        $specialist = User::factory()->create();
        $admin = User::factory()->create([
            'is_admin' => true,
            'role' => 'admin',
        ]);

        $suggestion = SkillSuggestion::query()->create([
            'user_id' => $specialist->id,
            'skill_name' => 'Shared canonical skill',
            'skill_type' => SkillSuggestion::TYPE_FIELD,
            'normalized_name' => SkillSuggestion::normalizeName(
                'Shared canonical skill',
            ),
            'pending_name' => SkillSuggestion::normalizeName(
                'Shared canonical skill',
            ),
            'subdomain_id' => $secondSubdomain->id,
            'status' => SkillSuggestion::STATUS_PENDING,
        ]);

        $approvedSkill = app(ReviewSkillSuggestionAction::class)
            ->approve($suggestion, $admin);

        $this->assertSame($skill->id, $approvedSkill->id);
        $this->assertDatabaseCount('skills', 1);

        $this->assertDatabaseHas('skill_subdomain', [
            'skill_id' => $skill->id,
            'subdomain_id' => $firstSubdomain->id,
        ]);

        $this->assertDatabaseHas('skill_subdomain', [
            'skill_id' => $skill->id,
            'subdomain_id' => $secondSubdomain->id,
        ]);

        $this->assertDatabaseHas('user_skills', [
            'user_id' => $specialist->id,
            'skill_id' => $skill->id,
        ]);
    }

    private function createSubdomain(string $name): Subdomain
    {
        $domain = SkillDomain::query()->create([
            'name' => $name.' domain',
        ]);

        return Subdomain::query()->create([
            'name' => $name,
            'skill_domain_id' => $domain->id,
        ]);
    }
}
