<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_skills', function (Blueprint $table) {

            $table->string('level')
                ->nullable()
                ->after('skill_id');

            $table->integer('years_of_experience')
                ->nullable()
                ->after('level');

            $table->boolean('is_custom')
                ->default(false)
                ->after('years_of_experience');

            $table->string('custom_title')
                ->nullable()
                ->after('is_custom');

        });
    }

    public function down(): void
    {
        Schema::table('user_skills', function (Blueprint $table) {

            $table->dropColumn([
                'level',
                'years_of_experience',
                'is_custom',
                'custom_title'
            ]);

        });
    }
};