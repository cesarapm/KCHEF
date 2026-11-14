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
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone', 20);
            $table->string('email')->unique();
            $table->string('city')->nullable();
            $table->enum('status', ['pendiente', 'aprobado', 'enlace_enviado', 'cliente_registrado', 'en_onboarding', 'completado', 'rechazado'])->default('pendiente');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prospects');
    }
};
