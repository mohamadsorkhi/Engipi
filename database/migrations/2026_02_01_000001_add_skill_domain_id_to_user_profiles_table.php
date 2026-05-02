<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('user_profiles')) {
            return;
        }

        if (!Schema::hasColumn('user_profiles', 'skill_domain_id')) {
            Schema::table('user_profiles', function (Blueprint $table) {
                $table->foreignUuid('skill_domain_id')->nullable()->constrained('skill_domains')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('user_profiles')) {
            return;
        }

        if (Schema::hasColumn('user_profiles', 'skill_domain_id')) {
            Schema::table('user_profiles', function (Blueprint $table) {
                $table->dropForeign(['skill_domain_id']);
                $table->dropColumn('skill_domain_id');
            });
        }
    }
};
