<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class SkillTaxonomyAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_audit_reports_a_clean_taxonomy(): void
    {
        $this->artisan('skills:audit-taxonomy')
            ->expectsOutput('Taxonomy audit: clean')
            ->expectsOutput('Issues found: 0')
            ->assertSuccessful();
    }

    public function test_audit_detects_relationship_and_value_inconsistencies(): void
    {
        $now = now();
        $firstDomain = (string) Str::uuid();
        $secondDomain = (string) Str::uuid();
        $subdomain = (string) Str::uuid();
        $process = (string) Str::uuid();
        $missingPivotSkill = (string) Str::uuid();
        $crossDomainSkill = (string) Str::uuid();
        $unassignedSkill = (string) Str::uuid();
        $invalidTypeSkill = (string) Str::uuid();

        DB::table('skill_domains')->insert([
            ['id' => $firstDomain, 'name' => 'Domain A', 'created_at' => $now, 'updated_at' => $now],
            ['id' => $secondDomain, 'name' => 'Domain B', 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('subdomains')->insert(['id' => $subdomain, 'skill_domain_id' => $firstDomain, 'name' => 'Subdomain A', 'created_at' => $now, 'updated_at' => $now]);
        DB::table('processes')->insert(['id' => $process, 'skill_domain_id' => $secondDomain, 'name' => 'Process B', 'created_at' => $now, 'updated_at' => $now]);
        DB::table('skills')->insert([
            ['id' => $missingPivotSkill, 'name' => 'Missing pivot', 'skill_type' => 'software', 'subdomain_id' => $subdomain, 'process_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $crossDomainSkill, 'name' => 'Wrong process', 'skill_type' => 'software', 'subdomain_id' => $subdomain, 'process_id' => $process, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $unassignedSkill, 'name' => 'Unassigned', 'skill_type' => 'field', 'subdomain_id' => null, 'process_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => $invalidTypeSkill, 'name' => 'Invalid type', 'skill_type' => 'other', 'subdomain_id' => $subdomain, 'process_id' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('skill_subdomain')->insert([
            ['skill_id' => $crossDomainSkill, 'subdomain_id' => $subdomain, 'created_at' => $now, 'updated_at' => $now],
            ['skill_id' => $invalidTypeSkill, 'subdomain_id' => $subdomain, 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->artisan('skills:audit-taxonomy')
            ->expectsOutput('Taxonomy audit: inconsistent')
            ->expectsOutput('Issues found: 4')
            ->assertFailed();
    }
}
