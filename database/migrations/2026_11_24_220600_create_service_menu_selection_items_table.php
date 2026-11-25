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
        Schema::create('service_menu_selection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_menu_selection_id')->constrained('service_menu_selections')->cascadeOnDelete();
            $table->foreignId('service_menu_option_id')->constrained('service_menu_options')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['service_menu_selection_id', 'service_menu_option_id'], 'selection_option_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_menu_selection_items');
    }
};

