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
        Schema::table('project_processes', function (Blueprint $table) {
            $table->dropColumn('desired_level');
        });
        
        Schema::table('project_processes', function (Blueprint $table) {
            $table->json('desired_levels')->nullable()->after('process_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_processes', function (Blueprint $table) {
            $table->dropColumn('desired_levels');
        });
        
        Schema::table('project_processes', function (Blueprint $table) {
            $table->enum('desired_level', ['practical', 'proficient', 'advanced'])->nullable()->after('process_id');
        });
    }
};
