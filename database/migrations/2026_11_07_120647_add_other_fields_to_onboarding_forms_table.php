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
        Schema::table('onboarding_forms', function (Blueprint $table) {
            // Agregar campos "otros" para texto libre
            $table->text('household_goals_other')->nullable()->after('household_goals');
            $table->text('dietary_preferences_other')->nullable()->after('dietary_preferences');
            $table->text('nutrition_preferences_other')->nullable()->after('nutrition_preferences');
            $table->text('favorite_cuisines_other')->nullable()->after('favorite_cuisines');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('onboarding_forms', function (Blueprint $table) {
            $table->dropColumn([
                'household_goals_other',
                'dietary_preferences_other',
                'nutrition_preferences_other',
                'favorite_cuisines_other'
            ]);
        });
    }
};
