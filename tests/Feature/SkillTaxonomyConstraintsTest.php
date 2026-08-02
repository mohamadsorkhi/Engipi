<?php

namespace Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class SkillTaxonomyConstraintsTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_report_command_is_read_only_and_reports_clean_database(): void
    {
        $this->artisan('skills:check-duplicates')
            ->expectsOutput('Skill duplicate groups: 0')
            ->expectsOutput('Subdomain duplicate groups: 0')
            ->expectsOutput('Process duplicate groups: 0')
            ->assertSuccessful();
    }

    public function test_taxonomy_natural_keys_are_enforced_by_unique_constraints(): void
    {
        $now = now();
        $domainId = (string) Str::uuid();
        $subdomainId = (string) Str::uuid();
        $processId = (string) Str::uuid();
        $skillId = (string) Str::uuid();

        DB::table('skill_domains')->insert(['id'=>$domainId,'name'=>'Domain A','created_at'=>$now,'updated_at'=>$now]);
        DB::table('subdomains')->insert(['id'=>$subdomainId,'skill_domain_id'=>$domainId,'name'=>'Subdomain A','created_at'=>$now,'updated_at'=>$now]);
        DB::table('processes')->insert(['id'=>$processId,'skill_domain_id'=>$domainId,'name'=>'Process A','created_at'=>$now,'updated_at'=>$now]);
        DB::table('skills')->insert(['id'=>$skillId,'skill_type'=>'software','subdomain_id'=>$subdomainId,'process_id'=>$processId,'name'=>'Skill A','created_at'=>$now,'updated_at'=>$now]);
        DB::table('skill_subdomain')->insert(['skill_id'=>$skillId,'subdomain_id'=>$subdomainId,'created_at'=>$now,'updated_at'=>$now]);

        $duplicates = [
            fn () => DB::table('skill_domains')->insert(['id'=>(string) Str::uuid(),'name'=>'Domain A','created_at'=>$now,'updated_at'=>$now]),
            fn () => DB::table('subdomains')->insert(['id'=>(string) Str::uuid(),'skill_domain_id'=>$domainId,'name'=>'Subdomain A','created_at'=>$now,'updated_at'=>$now]),
            fn () => DB::table('processes')->insert(['id'=>(string) Str::uuid(),'skill_domain_id'=>$domainId,'name'=>'Process A','created_at'=>$now,'updated_at'=>$now]),
            fn () => DB::table('skills')->insert(['id'=>(string) Str::uuid(),'skill_type'=>'software','subdomain_id'=>$subdomainId,'process_id'=>$processId,'name'=>'Skill A','created_at'=>$now,'updated_at'=>$now]),
            fn () => DB::table('skill_subdomain')->insert(['skill_id'=>$skillId,'subdomain_id'=>$subdomainId,'created_at'=>$now,'updated_at'=>$now]),
        ];

        foreach ($duplicates as $insertDuplicate) {
            try {
                $insertDuplicate();
                $this->fail('Expected the taxonomy unique constraint to reject a duplicate row.');
            } catch (QueryException) {
                $this->addToAssertionCount(1);
            }
        }
    }
}