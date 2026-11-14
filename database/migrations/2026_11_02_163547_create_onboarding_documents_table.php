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
        Schema::create('onboarding_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requirement_id')->constrained('onboarding_requirements')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('file_path', 255);
            $table->string('file_name', 255);
            $table->integer('file_size')->nullable();
            $table->enum('status', ['pendiente', 'cargado', 'en_revision', 'aprobado', 'rechazado'])->default('pendiente');
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboarding_documents');
    }
};
