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
        Schema::table('project_skills', function (Blueprint $table) {
            $table->string('level')->nullable()->after('skill_id');
            $table->integer('years_of_experience')->nullable()->after('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_skills', function (Blueprint $table) {
            $table->dropColumn(['level', 'years_of_experience']);
        });
    }
};
