<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill_subdomain', function (Blueprint $table) {

            $table->id();

            $table->foreignUuid('skill_id')
                ->constrained('skills')
                ->cascadeOnDelete();

            $table->foreignUuid('subdomain_id')
                ->constrained('subdomains')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['skill_id', 'subdomain_id']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_subdomain');
    }
};