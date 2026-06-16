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
        // Migrate existing skill_domain_id data to pivot table
        DB::table('user_profiles')
            ->whereNotNull('skill_domain_id')
            ->get()
            ->each(function ($profile) {
                DB::table('user_profile_domains')->insert([
                    'profile_id' => $profile->id,
                    'skill_domain_id' => $profile->skill_domain_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

        // Drop the old column
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropForeign(['skill_domain_id']);
            $table->dropColumn('skill_domain_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore the column
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->foreignUuid('skill_domain_id')->nullable()->constrained('skill_domains')->nullOnDelete();
        });

        // Migrate data back (take first domain if multiple exist)
        DB::table('user_profile_domains')
            ->get()
            ->groupBy('profile_id')
            ->each(function ($domains, $profileId) {
                $firstDomain = $domains->first();
                DB::table('user_profiles')
                    ->where('id', $profileId)
                    ->update(['skill_domain_id' => $firstDomain->skill_domain_id]);
            });
    }
};
