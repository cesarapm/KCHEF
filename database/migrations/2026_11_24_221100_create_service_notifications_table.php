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
        Schema::create('service_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_service_id')->constrained('order_services')->cascadeOnDelete();
            $table->string('channel');
            $table->string('recipient')->nullable();
            $table->json('payload')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_notifications');
    }
};

