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
        Schema::table('service_chef_notes', function (Blueprint $table) {
            if (!Schema::hasColumn('service_chef_notes', 'priority')) {
                $table->enum('priority', ['low', 'medium', 'high'])
                    ->default('medium')
                    ->after('note');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_chef_notes', function (Blueprint $table) {
            if (Schema::hasColumn('service_chef_notes', 'priority')) {
                $table->dropColumn('priority');
            }
        });
    }
};
