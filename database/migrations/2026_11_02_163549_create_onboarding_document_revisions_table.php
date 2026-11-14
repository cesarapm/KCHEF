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
        Schema::create('onboarding_document_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('onboarding_documents')->cascadeOnDelete();
            $table->foreignId('admin_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['aprobado', 'rechazado', 'requiere_correccion'])->default('requiere_correccion');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboarding_document_revisions');
    }
};
