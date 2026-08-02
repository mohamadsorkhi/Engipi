<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class CheckSkillTaxonomyDuplicates extends Command
{
    protected $signature = 'skills:check-duplicates {--json : Output machine-readable JSON}';

    protected $description = 'Report skill taxonomy duplicate groups and their references without changing data';

    public function handle(): int
    {
        $report = [
            'skills' => $this->duplicateGroups('skills', ['skill_type', 'subdomain_id', 'name'], true),
            'subdomains' => $this->duplicateGroups('subdomains', ['skill_domain_id', 'name']),
            'processes' => $this->duplicateGroups('processes', ['skill_domain_id', 'name'], false, true),
        ];

        if ($this->option('json')) {
            $this->line(json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            return self::SUCCESS;
        }

        $labels = ['skills' => 'Skill', 'subdomains' => 'Subdomain', 'processes' => 'Process'];
        foreach ($report as $type => $groups) {
            $this->line($labels[$type].' duplicate groups: '.count($groups));
            foreach ($groups as $index => $group) {
                $this->newLine();
                $this->warn($labels[$type].' duplicate #'.($index + 1));
                $this->line('  Key: '.json_encode($group['key'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                $this->line('  IDs: '.implode(', ', $group['ids']));
                foreach ($group['references'] as $reference => $count) {
                    $this->line("  {$reference}: {$count}");
                }
            }
        }

        return self::SUCCESS;
    }

    private function duplicateGroups(
        string $table,
        array $columns,
        bool $includeSkillReferences = false,
        bool $includeProcessReferences = false,
    ): array {
        $groups = DB::table($table)
            ->select($columns)
            ->selectRaw('COUNT(*) AS duplicate_count')
            ->groupBy($columns)
            ->havingRaw('COUNT(*) > 1')
            ->get();

        return $groups->map(function (object $group) use ($table, $columns, $includeSkillReferences, $includeProcessReferences): array {
            $query = DB::table($table);
            $key = [];
            foreach ($columns as $column) {
                $value = $group->{$column};
                $key[$column] = $value;
                $value === null ? $query->whereNull($column) : $query->where($column, $value);
            }

            $ids = $query->orderBy('id')->pluck('id')->map(static fn ($id): string => (string) $id)->all();
            $references = [];
            if ($includeSkillReferences) {
                $references = [
                    'user_skills' => $this->referenceCount('user_skills', 'skill_id', $ids),
                    'project_skills' => $this->referenceCount('project_skills', 'skill_id', $ids),
                    'skill_subdomain' => $this->referenceCount('skill_subdomain', 'skill_id', $ids),
                ];
            }
            if ($includeProcessReferences) {
                $references = [
                    'skills' => $this->referenceCount('skills', 'process_id', $ids),
                    'profile_processes' => $this->referenceCount('profile_processes', 'process_id', $ids),
                    'project_processes' => $this->referenceCount('project_processes', 'process_id', $ids),
                ];
            }

            return ['key' => $key, 'ids' => $ids, 'references' => $references];
        })->all();
    }

    private function referenceCount(string $table, string $column, array $ids): int
    {
        return $ids === [] ? 0 : DB::table($table)->whereIn($column, $ids)->count();
    }
}