<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('projects')
            ->whereNotNull('skill_domain_id')
            ->get()
            ->each(function ($project) {
                DB::table('project_domains')->insert([
                    'project_id' => $project->id,
                    'skill_domain_id' => $project->skill_domain_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['skill_domain_id']);
            $table->dropColumn('skill_domain_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignUuid('skill_domain_id')->nullable()->constrained('skill_domains')->nullOnDelete();
        });

        DB::table('project_domains')
            ->get()
            ->each(function ($pivot) {
                DB::table('projects')
                    ->where('id', $pivot->project_id)
                    ->update(['skill_domain_id' => $pivot->skill_domain_id]);
            });
    }
};
