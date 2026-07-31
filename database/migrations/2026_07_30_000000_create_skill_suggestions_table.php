<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill_suggestions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->string('skill_name');
            $table->string('normalized_name');
            $table->string('pending_name')->nullable();
            $table->foreignUuid('subdomain_id')->constrained('subdomains')->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignUuid('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'normalized_name', 'status'], 'skill_suggestions_duplicate_lookup');
            $table->unique(['user_id', 'pending_name'], 'skill_suggestions_one_pending_name_per_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_suggestions');
    }
};