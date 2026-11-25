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
        Schema::create('service_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_service_id')->constrained('order_services')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('menu_month')->nullable();
            $table->text('additional_info')->nullable();
            $table->unsignedTinyInteger('min_selection_percent')->default(70);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['order_service_id', 'menu_month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_menus');
    }
};

