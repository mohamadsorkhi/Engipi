<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('skills as s')
            ->join('subdomains as sd', 'sd.id', '=', 's.subdomain_id')
            ->whereNotNull('s.subdomain_id')
            ->select('s.id', 's.subdomain_id')
            ->orderBy('s.id')
            ->chunk(500, function ($skills): void {
                $timestamp = now();

                $relations = $skills
                    ->map(static fn (object $skill): array => [
                        'skill_id' => $skill->id,
                        'subdomain_id' => $skill->subdomain_id,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ])
                    ->all();

                DB::table('skill_subdomain')->insertOrIgnore($relations);
            });
    }

    public function down(): void
    {
        // Intentionally irreversible. Removing these rows could delete
        // canonical relations created before or after this migration.
    }
};
