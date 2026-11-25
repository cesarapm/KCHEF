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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->foreignId('onboarding_id')->nullable()->constrained('onboardings')->nullOnDelete();
            $table->string('payment_status')->default('pending');
            $table->string('payment_reference')->nullable()->unique();
            $table->timestamp('paid_at')->nullable();
            $table->unsignedTinyInteger('service_count')->default(4);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

