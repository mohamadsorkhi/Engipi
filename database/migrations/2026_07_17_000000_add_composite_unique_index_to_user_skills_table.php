<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const INDEX_NAME = 'user_skills_user_id_skill_id_unique_v2';

    private const USER_FOREIGN_INDEX_NAME = 'user_skills_user_id_foreign';

    public function up(): void
    {
        $duplicates = DB::table('user_skills')
            ->select('user_id', 'skill_id', DB::raw('COUNT(*) as duplicate_count'))
            ->groupBy('user_id', 'skill_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($duplicates->isNotEmpty()) {
            throw new \RuntimeException(
                'Cannot add the user skill unique index; duplicate rows exist: '.$duplicates->toJson()
            );
        }

        Schema::table('user_skills', function (Blueprint $table): void {
            $table->unique(['user_id', 'skill_id'], self::INDEX_NAME);
        });
    }

    public function down(): void
    {
        if (! Schema::hasIndex('user_skills', self::USER_FOREIGN_INDEX_NAME)) {
            Schema::table('user_skills', function (Blueprint $table): void {
                $table->index('user_id', self::USER_FOREIGN_INDEX_NAME);
            });
        }

        Schema::table('user_skills', function (Blueprint $table): void {
            $table->dropUnique(self::INDEX_NAME);
        });
    }
};
