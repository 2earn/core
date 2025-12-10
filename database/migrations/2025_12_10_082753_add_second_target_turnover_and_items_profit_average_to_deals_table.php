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
        Schema::table('deals', function (Blueprint $table) {
            $table->decimal('second_target_turnover', 15, 2)->nullable()->after('target_turnover');
            $table->decimal('items_profit_average', 10, 2)->nullable()->after('cash_cashback');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->dropColumn(['second_target_turnover', 'items_profit_average']);
        });
    }
};

