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
        Schema::table('orders', function (Blueprint $table) {
            $table->dateTime('simulation_datetime')->nullable();
            $table->boolean('simulation_result')->nullable();
            $table->string('simulation_details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('simulation_datetime');
            $table->dropColumn('simulation_result');
            $table->dropColumn('simulation_details');
        });
    }
};
