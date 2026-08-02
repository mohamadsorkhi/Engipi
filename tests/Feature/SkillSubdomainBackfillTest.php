<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class SkillSubdomainBackfillTest extends TestCase
{
    use RefreshDatabase;

    public function test_backfill_creates_only_missing_pivot_and_is_idempotent(): void
    {
        [$domainId, $subdomainId, $processId] = $this->taxonomyParents();
        $skillId = (string) Str::uuid();
        $now = now();
        DB::table('skills')->insert(['id'=>$skillId,'name'=>'Existing Skill','skill_type'=>'software','process_id'=>$processId,'subdomain_id'=>$subdomainId,'created_at'=>$now,'updated_at'=>$now]);

        $this->artisan('skills:backfill-subdomains')
            ->expectsOutput('Skills checked: 1')
            ->expectsOutput('Relations created: 1')
            ->expectsOutput('Already existing: 0')
            ->expectsOutput('Skipped: 0')
            ->assertSuccessful();

        $this->assertDatabaseHas('skill_subdomain', ['skill_id'=>$skillId,'subdomain_id'=>$subdomainId]);
        $this->assertDatabaseHas('skills', ['id'=>$skillId,'subdomain_id'=>$subdomainId,'name'=>'Existing Skill']);

        $this->artisan('skills:backfill-subdomains')
            ->expectsOutput('Relations created: 0')
            ->expectsOutput('Already existing: 1')
            ->assertSuccessful();

        $this->assertSame(1, DB::table('skill_subdomain')->count());
    }

    public function test_skill_without_subdomain_is_reported_and_not_assigned(): void
    {
        [, , $processId] = $this->taxonomyParents();
        $skillId = (string) Str::uuid();
        $now = now();
        DB::table('skills')->insert(['id'=>$skillId,'name'=>'Unassigned Skill','skill_type'=>'field','process_id'=>$processId,'subdomain_id'=>null,'created_at'=>$now,'updated_at'=>$now]);

        $this->artisan('skills:backfill-subdomains')
            ->expectsOutput('Skills checked: 1')
            ->expectsOutput('Relations created: 0')
            ->expectsOutput('Already existing: 0')
            ->expectsOutput('Skipped: 1')
            ->assertSuccessful();

        $this->assertDatabaseHas('skills', ['id'=>$skillId,'subdomain_id'=>null]);
        $this->assertDatabaseCount('skill_subdomain', 0);
    }

    private function taxonomyParents(): array
    {
        $domainId = (string) Str::uuid();
        $subdomainId = (string) Str::uuid();
        $processId = (string) Str::uuid();
        $now = now();
        DB::table('skill_domains')->insert(['id'=>$domainId,'name'=>'Domain','created_at'=>$now,'updated_at'=>$now]);
        DB::table('subdomains')->insert(['id'=>$subdomainId,'name'=>'Subdomain','skill_domain_id'=>$domainId,'created_at'=>$now,'updated_at'=>$now]);
        DB::table('processes')->insert(['id'=>$processId,'name'=>'Process','skill_domain_id'=>$domainId,'created_at'=>$now,'updated_at'=>$now]);
        return [$domainId, $subdomainId, $processId];
    }
}