<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class BackfillSkillSubdomains extends Command
{
    protected $signature = 'skills:backfill-subdomains
        {--dry-run : Report what would change without inserting rows}
        {--chunk=500 : Number of skills processed per batch}
        {--force : Allow writes when APP_ENV is production}';

    protected $description = 'Copy legacy skills.subdomain_id relations into skill_subdomain without deleting data';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $chunkSize = max(1, min(5000, (int) $this->option('chunk')));

        if (app()->environment('production') && ! $dryRun && ! $this->option('force')) {
            $this->error('Production write blocked. Run with --dry-run, or use --force only during an approved rollout.');
            return self::FAILURE;
        }

        $total = DB::table('skills')->count();
        $nullSkills = $this->skillsWithoutSubdomain();
        $invalidSkills = DB::table('skills as s')
            ->leftJoin('subdomains as sd', 'sd.id', '=', 's.subdomain_id')
            ->whereNotNull('s.subdomain_id')
            ->whereNull('sd.id')
            ->select('s.id', 's.name', 's.skill_type', 's.process_id', 's.subdomain_id')
            ->orderBy('s.id')
            ->get();

        $created = 0;
        $eligible = 0;

        DB::table('skills as s')
            ->join('subdomains as sd', 'sd.id', '=', 's.subdomain_id')
            ->whereNotNull('s.subdomain_id')
            ->select('s.id', 's.subdomain_id')
            ->orderBy('s.id')
            ->chunk($chunkSize, function ($skills) use ($dryRun, &$created, &$eligible): void {
                $eligible += $skills->count();
                $pairs = $skills->map(static fn (object $skill): array => [
                    'skill_id' => $skill->id,
                    'subdomain_id' => $skill->subdomain_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->all();

                if ($dryRun) {
                    foreach ($skills as $skill) {
                        $exists = DB::table('skill_subdomain')
                            ->where('skill_id', $skill->id)
                            ->where('subdomain_id', $skill->subdomain_id)
                            ->exists();
                        if (! $exists) {
                            $created++;
                        }
                    }
                    return;
                }

                $created += DB::table('skill_subdomain')->insertOrIgnore($pairs);
            });

        $existing = $eligible - $created;
        $skipped = $nullSkills->count() + $invalidSkills->count();

        $this->line('Skills checked: '.$total);
        $this->line(($dryRun ? 'Relations pending: ' : 'Relations created: ').$created);
        $this->line('Already existing: '.$existing);
        $this->line('Skipped: '.$skipped);

        if ($nullSkills->isNotEmpty()) {
            $this->newLine();
            $this->warn('Skills without subdomain_id (report only):');
            $this->table(
                ['ID', 'Name', 'Type', 'Process ID', 'Probable domain'],
                $nullSkills->map(static fn (object $skill): array => [
                    $skill->id,
                    $skill->name,
                    $skill->skill_type,
                    $skill->process_id ?: '-',
                    $skill->probable_domain_name ?: '-',
                ])->all()
            );
        }

        if ($invalidSkills->isNotEmpty()) {
            $this->newLine();
            $this->error('Skills referencing a missing subdomain were skipped:');
            $this->table(
                ['ID', 'Name', 'Type', 'Process ID', 'Missing subdomain ID'],
                $invalidSkills->map(static fn (object $skill): array => [
                    $skill->id,
                    $skill->name,
                    $skill->skill_type,
                    $skill->process_id ?: '-',
                    $skill->subdomain_id,
                ])->all()
            );
        }

        return self::SUCCESS;
    }

    private function skillsWithoutSubdomain()
    {
        return DB::table('skills as s')
            ->leftJoin('processes as p', 'p.id', '=', 's.process_id')
            ->leftJoin('skill_domains as d', 'd.id', '=', 'p.skill_domain_id')
            ->whereNull('s.subdomain_id')
            ->select(
                's.id',
                's.name',
                's.skill_type',
                's.process_id',
                'd.id as probable_domain_id',
                'd.name as probable_domain_name'
            )
            ->orderBy('s.name')
            ->get();
    }
}