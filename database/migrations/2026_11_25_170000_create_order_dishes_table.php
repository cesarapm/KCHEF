<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_dishes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('master_menu_dish_id')->constrained('master_menu_dishes')->cascadeOnDelete();
            $table->boolean('is_selected_by_client')->default(false);
            $table->timestamp('selected_at')->nullable();
            $table->timestamps();

            $table->unique(['order_id', 'master_menu_dish_id']);
            $table->index(['order_id', 'is_selected_by_client']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_dishes');
    }
};

