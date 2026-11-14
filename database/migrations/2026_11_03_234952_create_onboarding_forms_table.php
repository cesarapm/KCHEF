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
        Schema::create('onboarding_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('onboarding_id')->constrained('onboardings')->onDelete('cascade');

            // Información Básica
            $table->string('primary_clients')->nullable();
            $table->date('start_date')->nullable();
            $table->text('household_address')->nullable();
            $table->integer('number_of_adults')->default(0);
            $table->integer('number_of_kids')->default(0);
            $table->string('kids_ages')->nullable();

            // Objetivos (JSON array)
            $table->json('household_goals')->nullable();

            // Preferencias Dietéticas (JSON array)
            $table->json('dietary_preferences')->nullable();

            // Preferencias Nutricionales (JSON array)
            $table->json('nutrition_preferences')->nullable();

            // Alimentos a Evitar (JSON array)
            $table->json('foods_to_avoid')->nullable();

            // Cuisinas Favoritas (JSON array)
            $table->json('favorite_cuisines')->nullable();

            // Restaurantes Favoritos (JSON array)
            $table->json('favorite_restaurants')->nullable();

            // Preferencias de los Niños (JSON)
            $table->json('children_preferences')->nullable();

            // Estado
            $table->string('status')->default('draft'); // draft, completed, reviewed
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboarding_forms');
    }
};
