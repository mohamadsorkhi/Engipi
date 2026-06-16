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
        Schema::table('subdomains', function (Blueprint $table) {
            $table->index('skill_domain_id');
        });
    }

    public function down(): void
    {
        Schema::table('subdomains', function (Blueprint $table) {
            $table->dropIndex(['skill_domain_id']);
        });
    }
};
