<?php

namespace App\Console\Commands;

use App\Models\SkillSuggestion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

final class AuditSkillTaxonomy extends Command
{
    protected $signature = 'skills:audit-taxonomy {--json : Output machine-readable JSON}';

    protected $description = 'Audit skill taxonomy relationships and values without changing data';

    public function handle(): int
    {
        $issues = [
            'unassigned_skills' => $this->ids(
                DB::table('skills')->whereNull('subdomain_id')
            ),
            'missing_primary_subdomains' => $this->ids(
                DB::table('skills as s')
                    ->leftJoin('subdomains as sd', 'sd.id', '=', 's.subdomain_id')
                    ->whereNotNull('s.subdomain_id')
                    ->whereNull('sd.id'),
                's.id'
            ),
            'missing_primary_pivots' => $this->ids(
                DB::table('skills as s')
                    ->join('subdomains as sd', 'sd.id', '=', 's.subdomain_id')
                    ->leftJoin('skill_subdomain as ss', function ($join): void {
                        $join->on('ss.skill_id', '=', 's.id')
                            ->on('ss.subdomain_id', '=', 's.subdomain_id');
                    })
                    ->whereNull('ss.id'),
                's.id'
            ),
            'cross_domain_processes' => $this->ids(
                DB::table('skills as s')
                    ->join('subdomains as sd', 'sd.id', '=', 's.subdomain_id')
                    ->join('processes as p', 'p.id', '=', 's.process_id')
                    ->whereColumn('sd.skill_domain_id', '!=', 'p.skill_domain_id'),
                's.id'
            ),
            'invalid_skill_types' => $this->ids(
                DB::table('skills')->whereNotIn('skill_type', SkillSuggestion::types())
            ),
        ];

        $issueCount = array_sum(array_map('count', $issues));
        $report = [
            'status' => $issueCount === 0 ? 'clean' : 'inconsistent',
            'issue_count' => $issueCount,
            'issues' => $issues,
        ];

        if ($this->option('json')) {
            $this->line(json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        } else {
            $this->line('Taxonomy audit: '.$report['status']);
            $this->line('Issues found: '.$issueCount);
            foreach ($issues as $name => $ids) {
                $this->line('  '.$name.': '.count($ids).($ids === [] ? '' : ' ['.implode(', ', $ids).']'));
            }
        }

        return $issueCount === 0 ? self::SUCCESS : self::FAILURE;
    }

    private function ids($query, string $column = 'id'): array
    {
        return $query->orderBy($column)->pluck($column)->map(static fn ($id): string => (string) $id)->all();
    }
}
