<?php

namespace Tests\Feature;

use App\Http\Controllers\Employer\ProjectController;
use App\Models\Skill;
use App\Models\SkillDomain;
use App\Models\Subdomain;
use App\Models\User;
use App\Models\UserProfile;
use App\Services\Taxonomy\SkillSubdomainAuthority;
use App\Support\Auth\ProfileContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

final class SkillSubdomainCanonicalReadTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_reads_skills_from_canonical_relation(): void
    {
        [
            'skill' => $skill,
            'canonicalSubdomain' => $canonicalSubdomain,
        ] = $this->createSkillWithAdditionalCanonicalSubdomain();

        $response = $this->getJson(
            '/api/skills/'.$canonicalSubdomain->id,
        );

        $response
            ->assertOk()
            ->assertJsonFragment([
                'id' => $skill->id,
                'name' => $skill->name,
                'skill_type' => $skill->skill_type,
            ]);
    }

    public function test_admin_filter_uses_canonical_relation(): void
    {
        [
            'skill' => $skill,
            'canonicalSubdomain' => $canonicalSubdomain,
        ] = $this->createSkillWithAdditionalCanonicalSubdomain();

        $admin = User::factory()->create([
            'is_admin' => true,
            'role' => 'admin',
            'active' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.skills.index', [
                'subdomain_id' => $canonicalSubdomain->id,
            ]))
            ->assertOk()
            ->assertSeeText($skill->name)
            ->assertSeeText($canonicalSubdomain->name);
    }

    public function test_project_creation_view_reads_canonical_relations(): void
    {
        [
            'skill' => $skill,
            'canonicalSubdomain' => $canonicalSubdomain,
        ] = $this->createSkillWithAdditionalCanonicalSubdomain();

        $view = app(ProjectController::class)->create();

        $viewSkills = $view->getData()['skills'];

        $viewSkill = $viewSkills->firstWhere(
            'id',
            $skill->id,
        );

        $this->assertNotNull($viewSkill);

        $this->assertTrue(
            $viewSkill->subdomains->contains(
                'id',
                $canonicalSubdomain->id,
            ),
        );

        $rendered = $view->render();

        $this->assertStringContainsString(
            $skill->name,
            $rendered,
        );

        $this->assertStringContainsString(
            $canonicalSubdomain->name,
            $rendered,
        );
    }

    public function test_simple_project_form_reads_canonical_relations(): void
    {
        [
            'skill' => $skill,
            'canonicalSubdomain' => $canonicalSubdomain,
        ] = $this->createSkillWithAdditionalCanonicalSubdomain();

        $view = app(ProjectController::class)->createSimple();

        $domains = $view->getData()['domains'];

        $viewSubdomain = $domains
            ->flatMap->subdomains
            ->firstWhere(
                'id',
                $canonicalSubdomain->id,
            );

        $this->assertNotNull($viewSubdomain);

        $this->assertTrue(
            $viewSubdomain->canonicalSkills->contains(
                'id',
                $skill->id,
            ),
        );

        view()->share(
            'errors',
            new ViewErrorBag,
        );

        $rendered = $view->render();

        $this->assertStringContainsString(
            $skill->name,
            $rendered,
        );
    }

    public function test_skill_selection_validates_domains_using_canonical_relation(): void
    {
        [
            'skill' => $skill,
            'canonicalDomain' => $canonicalDomain,
        ] = $this->createSkillWithAdditionalCanonicalSubdomain();

        $user = User::factory()->create([
            'active' => true,
        ]);

        $profile = UserProfile::query()->create([
            'user_id' => $user->id,
            'type' => 'specialist',
        ]);

        $this->actingAs($user)
            ->withSession([
                ProfileContext::SESSION_KEY => $profile->id,
            ])
            ->postJson(route('skill.save'), [
                'skills' => [
                    [
                        'skill_id' => $skill->id,
                        'level' => 'متوسط',
                        'years' => 3,
                    ],
                ],
                'domains' => [
                    $canonicalDomain->id,
                ],
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('user_skills', [
            'user_id' => $user->id,
            'skill_id' => $skill->id,
            'level' => 'متوسط',
            'years_of_experience' => 3,
        ]);
    }

    /**
     * @return array{
     *     skill: Skill,
     *     canonicalSubdomain: Subdomain,
     *     canonicalDomain: SkillDomain
     * }
     */
    private function createSkillWithAdditionalCanonicalSubdomain(): array
    {
        $legacySubdomain = $this->createSubdomain(
            'Legacy subdomain',
        );

        $canonicalSubdomain = $this->createSubdomain(
            'Canonical subdomain',
        );

        $authority = app(
            SkillSubdomainAuthority::class,
        );

        $skill = $authority->create([
            'name' => 'Canonical read skill',
            'skill_type' => 'field',
            'subdomain_id' => $legacySubdomain->id,
        ]);

        $authority->attach(
            $skill,
            $canonicalSubdomain->id,
        );

        $this->assertSame(
            $legacySubdomain->id,
            $skill->subdomain_id,
        );

        return [
            'skill' => $skill,
            'canonicalSubdomain' => $canonicalSubdomain,
            'canonicalDomain' => $canonicalSubdomain->domain,
        ];
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
