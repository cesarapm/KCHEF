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
        Schema::table('onboarding_documents', function (Blueprint $table) {
            $table->string('document_type')->nullable()->after('requirement_id')->comment('Tipo de documento (texto libre)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('onboarding_documents', function (Blueprint $table) {
            $table->dropColumn('document_type');
        });
    }
};
