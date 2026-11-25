<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_menu_options', function (Blueprint $table) {
            $table->foreignId('master_menu_dish_id')
                ->nullable()
                ->after('service_menu_id')
                ->constrained('master_menu_dishes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('service_menu_options', function (Blueprint $table) {
            $table->dropConstrainedForeignId('master_menu_dish_id');
        });
    }
};

