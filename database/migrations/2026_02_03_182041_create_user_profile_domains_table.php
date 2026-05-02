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
        Schema::create('user_profile_domains', function (Blueprint $table) {
            $table->foreignUuid('profile_id')->constrained('user_profiles')->cascadeOnDelete();
            $table->foreignUuid('skill_domain_id')->constrained('skill_domains')->cascadeOnDelete();
            $table->timestamps();
            
            $table->primary(['profile_id', 'skill_domain_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profile_domains');
    }
};
