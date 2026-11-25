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
        Schema::create('service_grocery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_grocery_list_id')->constrained('service_grocery_lists')->cascadeOnDelete();
            $table->string('item');
            $table->string('quantity')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_checked')->default(false);
            $table->timestamp('checked_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_grocery_items');
    }
};

