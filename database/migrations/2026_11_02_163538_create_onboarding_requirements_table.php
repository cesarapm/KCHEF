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
        Schema::create('onboarding_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('onboarding_id')->constrained('onboardings')->cascadeOnDelete();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->boolean('required')->default(true);
            $table->json('allowed_formats')->nullable();
            $table->integer('max_file_size')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboarding_requirements');
    }
};
