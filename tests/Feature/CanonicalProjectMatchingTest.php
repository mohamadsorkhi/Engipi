<?php

namespace Tests\Feature;

use App\Models\Process;
use App\Models\Project;
use App\Models\Skill;
use App\Models\SkillDomain;
use App\Models\Subdomain;
use App\Services\Taxonomy\SkillSubdomainAuthority;
use Illuminate\Support\Str;
use Tests\Feature\Authorization\AuthorizationTestCase;

final class CanonicalProjectMatchingTest extends AuthorizationTestCase
{
    public function test_same_skill_id_matches_and_exposes_matching_count(): void
    {
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');

        $employer = $this->createUser([
            'role' => 'employer',
        ]);

        $project = $this->createProject($employer);
        $skill = $this->createCanonicalSkill(
            'Direct canonical skill',
        );

        $specialist->skills()->attach($skill->id, [
            'level' => 'intermediate',
            'years_of_experience' => 2,
        ]);

        $project->skills()->attach($skill->id, [
            'level' => 'intermediate',
            'years_of_experience' => 1,
        ]);

        $matchedProject = Project::forWorkerMatches($specialist)
            ->whereKey($project->id)
            ->first();

        $this->assertNotNull($matchedProject);

        $this->assertSame(
            1,
            (int) $matchedProject->matching_skills_count,
        );
    }

    public function test_same_display_name_with_different_skill_ids_does_not_match(): void
    {
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');

        $employer = $this->createUser([
            'role' => 'employer',
        ]);

        $project = $this->createProject($employer);

        $specialistSkill = $this->createCanonicalSkill(
            'Mutable duplicate name',
        );

        $projectSkill = $this->createCanonicalSkill(
            'Mutable duplicate name',
        );

        $this->assertNotSame(
            $specialistSkill->id,
            $projectSkill->id,
        );

        $specialist->skills()->attach($specialistSkill->id, [
            'level' => 'intermediate',
            'years_of_experience' => 2,
        ]);

        $project->skills()->attach($projectSkill->id, [
            'level' => 'intermediate',
            'years_of_experience' => 1,
        ]);

        $this->assertFalse(
            Project::forWorkerMatches($specialist)
                ->whereKey($project->id)
                ->exists(),
        );
    }

    public function test_different_skill_ids_with_same_canonical_process_id_match(): void
    {
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');

        $employer = $this->createUser([
            'role' => 'employer',
        ]);

        $project = $this->createProject($employer);

        $domain = SkillDomain::query()->create([
            'name' => 'Shared process domain '.Str::random(8),
        ]);

        $firstSubdomain = Subdomain::query()->create([
            'name' => 'First shared process subdomain '.Str::random(8),
            'skill_domain_id' => $domain->id,
        ]);

        $secondSubdomain = Subdomain::query()->create([
            'name' => 'Second shared process subdomain '.Str::random(8),
            'skill_domain_id' => $domain->id,
        ]);

        $process = Process::query()->create([
            'skill_domain_id' => $domain->id,
            'name' => 'Shared canonical process '.Str::random(8),
        ]);

        $authority = app(SkillSubdomainAuthority::class);

        $specialistSkill = $authority->create([
            'name' => 'Shared software tool',
            'skill_type' => 'software',
            'process_id' => $process->id,
            'subdomain_id' => $firstSubdomain->id,
        ]);

        $projectSkill = $authority->create([
            'name' => 'Shared software tool',
            'skill_type' => 'software',
            'process_id' => $process->id,
            'subdomain_id' => $secondSubdomain->id,
        ]);

        $this->assertNotSame(
            $specialistSkill->id,
            $projectSkill->id,
        );

        $this->assertSame(
            $specialistSkill->process_id,
            $projectSkill->process_id,
        );

        $specialist->skills()->attach($specialistSkill->id, [
            'level' => 'intermediate',
            'years_of_experience' => 2,
        ]);

        $project->skills()->attach($projectSkill->id, [
            'level' => 'intermediate',
            'years_of_experience' => 1,
        ]);

        $matchedProject = Project::forWorkerMatches($specialist)
            ->whereKey($project->id)
            ->first();

        $this->assertNotNull($matchedProject);

        $this->assertSame(
            1,
            (int) $matchedProject->matching_skills_count,
        );
    }

    public function test_skill_process_id_matches_project_process_id_without_name_equality(): void
    {
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');

        $employer = $this->createUser([
            'role' => 'employer',
        ]);

        $project = $this->createProject($employer);

        [
            'domain' => $domain,
            'subdomain' => $subdomain,
        ] = $this->createTaxonomy(
            'Stable process taxonomy',
        );

        $process = Process::query()->create([
            'skill_domain_id' => $domain->id,
            'name' => 'Canonical process name',
        ]);

        $skill = app(SkillSubdomainAuthority::class)->create([
            'name' => 'Different skill display name',
            'skill_type' => 'software',
            'process_id' => $process->id,
            'subdomain_id' => $subdomain->id,
        ]);

        $specialist->skills()->attach($skill->id, [
            'level' => 'intermediate',
            'years_of_experience' => 2,
        ]);

        $project->processes()->attach($process->id, [
            'desired_levels' => json_encode(['proficient']),
        ]);

        $matchedProject = Project::forWorkerMatches($specialist)
            ->whereKey($project->id)
            ->first();

        $this->assertNotNull($matchedProject);

        $this->assertSame(
            1,
            (int) $matchedProject->matching_skills_count,
        );
    }

    public function test_legacy_profile_process_still_matches_by_process_id(): void
    {
        $specialist = $this->createUser();

        $profile = $this->createProfile(
            $specialist,
            'specialist',
        );

        $employer = $this->createUser([
            'role' => 'employer',
        ]);

        $project = $this->createProject($employer);

        $domain = SkillDomain::query()->create([
            'name' => 'Legacy process domain '.Str::random(8),
        ]);

        $process = Process::query()->create([
            'skill_domain_id' => $domain->id,
            'name' => 'Legacy canonical process '.Str::random(8),
        ]);

        $profile->processes()->attach($process->id, [
            'level' => 'proficient',
        ]);

        $project->processes()->attach($process->id, [
            'desired_levels' => json_encode(['proficient']),
        ]);

        $matchedProject = Project::forWorkerMatches($specialist)
            ->whereKey($project->id)
            ->first();

        $this->assertNotNull($matchedProject);

        $this->assertSame(
            1,
            (int) $matchedProject->matching_skills_count,
        );
    }

    public function test_projects_are_ordered_by_canonical_match_count(): void
    {
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');

        $employer = $this->createUser([
            'role' => 'employer',
        ]);

        $singleMatchProject = $this->createProject(
            $employer,
            ['title' => 'Single match project'],
        );

        $doubleMatchProject = $this->createProject(
            $employer,
            ['title' => 'Double match project'],
        );

        $firstSkill = $this->createCanonicalSkill(
            'First ranking skill',
        );

        $secondSkill = $this->createCanonicalSkill(
            'Second ranking skill',
        );

        $specialist->skills()->attach([
            $firstSkill->id => [
                'level' => 'intermediate',
                'years_of_experience' => 2,
            ],
            $secondSkill->id => [
                'level' => 'intermediate',
                'years_of_experience' => 3,
            ],
        ]);

        $singleMatchProject->skills()->attach($firstSkill->id, [
            'level' => 'intermediate',
            'years_of_experience' => 1,
        ]);

        $doubleMatchProject->skills()->attach([
            $firstSkill->id => [
                'level' => 'intermediate',
                'years_of_experience' => 1,
            ],
            $secondSkill->id => [
                'level' => 'intermediate',
                'years_of_experience' => 1,
            ],
        ]);

        $projects = Project::forWorkerMatches($specialist)
            ->orderByDesc('matching_skills_count')
            ->get();

        $this->assertSame(
            [
                $doubleMatchProject->id,
                $singleMatchProject->id,
            ],
            $projects->pluck('id')->all(),
        );

        $this->assertSame(
            [2, 1],
            $projects
                ->pluck('matching_skills_count')
                ->map(static fn ($count): int => (int) $count)
                ->all(),
        );
    }

    private function createCanonicalSkill(
        string $name,
    ): Skill {
        ['subdomain' => $subdomain] = $this->createTaxonomy(
            $name.' taxonomy',
        );

        return app(SkillSubdomainAuthority::class)->create([
            'name' => $name,
            'skill_type' => 'field',
            'subdomain_id' => $subdomain->id,
        ]);
    }

    /**
     * @return array{
     *     domain: SkillDomain,
     *     subdomain: Subdomain
     * }
     */
    private function createTaxonomy(string $name): array
    {
        $domain = SkillDomain::query()->create([
            'name' => $name.' domain '.Str::random(8),
        ]);

        $subdomain = Subdomain::query()->create([
            'name' => $name.' subdomain '.Str::random(8),
            'skill_domain_id' => $domain->id,
        ]);

        return [
            'domain' => $domain,
            'subdomain' => $subdomain,
        ];
    }
}
