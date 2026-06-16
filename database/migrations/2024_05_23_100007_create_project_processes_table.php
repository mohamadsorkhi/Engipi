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
        Schema::create('project_processes', function (Blueprint $table) {
            $table->foreignUuid('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignUuid('process_id')->constrained('processes')->cascadeOnDelete();
            $table->enum('desired_level', ['practical', 'proficient', 'advanced'])->nullable();
            $table->timestamps();

            $table->primary(['project_id', 'process_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_processes');
    }
};
