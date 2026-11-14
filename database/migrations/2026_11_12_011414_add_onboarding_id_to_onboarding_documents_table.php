<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('onboarding_documents', function (Blueprint $table) {
            $table->foreignId('onboarding_id')
                ->nullable()
                ->after('id')
                ->constrained('onboardings')
                ->nullOnDelete();
        });

        DB::statement('
            UPDATE onboarding_documents d
            INNER JOIN onboarding_requirements r ON r.id = d.requirement_id
            SET d.onboarding_id = r.onboarding_id
            WHERE d.onboarding_id IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('onboarding_documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('onboarding_id');
        });
    }
};
