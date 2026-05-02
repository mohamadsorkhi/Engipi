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
        Schema::create('processes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('skill_domain_id')->constrained('skill_domains')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();

            $table->unique(['skill_domain_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processes');
    }
};
