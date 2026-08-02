<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const DOMAIN_UNIQUE = 'taxonomy_skill_domains_name_unique';
    private const SUBDOMAIN_UNIQUE = 'taxonomy_subdomains_domain_name_unique';
    private const SKILL_UNIQUE = 'taxonomy_skills_type_subdomain_name_unique';
    private const PROCESS_UNIQUE = 'taxonomy_processes_domain_name_unique';
    private const PIVOT_UNIQUE = 'taxonomy_skill_subdomain_unique';

    public function up(): void
    {
        $this->assertNoDuplicates('skill_domains', ['name']);
        $this->assertNoDuplicates('subdomains', ['skill_domain_id', 'name']);
        $this->assertNoDuplicates('skills', ['skill_type', 'subdomain_id', 'name']);
        $this->assertNoDuplicates('processes', ['skill_domain_id', 'name']);
        $this->assertNoDuplicates('skill_subdomain', ['skill_id', 'subdomain_id']);

        $this->addUniqueIfMissing('skill_domains', ['name'], self::DOMAIN_UNIQUE);
        $this->addUniqueIfMissing('subdomains', ['skill_domain_id', 'name'], self::SUBDOMAIN_UNIQUE);
        $this->addUniqueIfMissing('skills', ['skill_type', 'subdomain_id', 'name'], self::SKILL_UNIQUE);
        $this->addUniqueIfMissing('processes', ['skill_domain_id', 'name'], self::PROCESS_UNIQUE);
        $this->addUniqueIfMissing('skill_subdomain', ['skill_id', 'subdomain_id'], self::PIVOT_UNIQUE);
    }

    public function down(): void
    {
        $this->dropOwnedIndexIfPresent('skill_subdomain', self::PIVOT_UNIQUE);
        $this->dropOwnedIndexIfPresent('processes', self::PROCESS_UNIQUE);
        $this->dropOwnedIndexIfPresent('skills', self::SKILL_UNIQUE);
        $this->dropOwnedIndexIfPresent('subdomains', self::SUBDOMAIN_UNIQUE);
        $this->dropOwnedIndexIfPresent('skill_domains', self::DOMAIN_UNIQUE);
    }

    private function assertNoDuplicates(string $table, array $columns): void
    {
        $groups = DB::table($table)
            ->select($columns)
            ->selectRaw('COUNT(*) AS duplicate_count')
            ->groupBy($columns)
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($groups->isEmpty()) {
            return;
        }

        $details = $groups->map(function (object $group) use ($table, $columns): array {
            $query = DB::table($table);
            $key = [];
            foreach ($columns as $column) {
                $value = $group->{$column};
                $key[$column] = $value;
                $value === null ? $query->whereNull($column) : $query->where($column, $value);
            }

            return [
                'key' => $key,
                'ids' => $query->orderBy('id')->pluck('id')->map(static fn ($id): string => (string) $id)->all(),
            ];
        })->all();

        throw new RuntimeException(
            "Cannot add taxonomy unique constraints; duplicates exist in {$table}: ".
            json_encode($details, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }

    private function addUniqueIfMissing(string $table, array $columns, string $name): void
    {
        if ($this->hasUniqueColumns($table, $columns)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($columns, $name): void {
            $blueprint->unique($columns, $name);
        });
    }

    private function hasUniqueColumns(string $table, array $columns): bool
    {
        return collect(Schema::getIndexes($table))->contains(function (array $index) use ($columns): bool {
            return ($index['unique'] ?? false) === true
                && array_values($index['columns'] ?? []) === array_values($columns);
        });
    }

    private function dropOwnedIndexIfPresent(string $table, string $name): void
    {
        if (! Schema::hasIndex($table, $name)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($name): void {
            $blueprint->dropUnique($name);
        });
    }
};