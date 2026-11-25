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
        Schema::create('service_menu_selections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_menu_id')->constrained('service_menus')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('draft');
            $table->unsignedInteger('selected_count')->default(0);
            $table->unsignedInteger('required_min_count')->default(0);
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->unique(['service_menu_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_menu_selections');
    }
};

