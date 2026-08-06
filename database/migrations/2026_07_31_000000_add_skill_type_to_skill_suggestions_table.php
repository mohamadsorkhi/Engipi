<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const OLD_UNIQUE = 'skill_suggestions_one_pending_name_per_user';
    private const NEW_UNIQUE = 'skill_suggestions_one_pending_name_per_user_type_subdomain';

    public function up(): void
    {
        Schema::table('skill_suggestions', function (Blueprint $table): void {
            $table->dropUnique(self::OLD_UNIQUE);
            $table->string('skill_type')->default('field')->after('skill_name');
            $table->unique(['user_id', 'skill_type', 'subdomain_id', 'pending_name'], self::NEW_UNIQUE);
            $table->index(['skill_type', 'status', 'created_at'], 'skill_suggestions_type_status_index');
        });
    }

    public function down(): void
    {
        Schema::table('skill_suggestions', function (Blueprint $table): void {
            $table->dropIndex('skill_suggestions_type_status_index');
            $table->dropUnique(self::NEW_UNIQUE);
            $table->dropColumn('skill_type');
            $table->unique(['user_id', 'pending_name'], self::OLD_UNIQUE);
        });
    }
};
