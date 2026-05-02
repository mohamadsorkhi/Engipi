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
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('short_id', 16)->unique();
            $table->foreignUuid('employer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('employer_profile_id')->nullable()->constrained('user_profiles')->nullOnDelete();
            $table->foreignUuid('skill_domain_id')->nullable()->constrained('skill_domains')->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->enum('work_type', ['remote', 'onsite', 'hybrid']);
            $table->unsignedBigInteger('view_count')->default(0);

            $table->unsignedInteger('duration_days')->nullable();
            $table->date('deadline_date')->nullable();
            $table->decimal('budget_min', 12, 2)->nullable();
            $table->decimal('budget_max', 12, 2)->nullable();

            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();

            $table->index(['employer_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
