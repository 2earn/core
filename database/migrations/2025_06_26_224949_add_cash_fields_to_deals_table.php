<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    const TABLE_NAME = 'deals';

    public function up(): void
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->decimal('cash_company_profit', 15, 7)->default(0)->after('total_unused_cashback_value');
            $table->decimal('cash_jackpot', 15, 7)->default(0)->after('cash_company_profit');
            $table->decimal('cash_tree', 15, 7)->default(0)->after('cash_jackpot');
            $table->decimal('cash_cashback', 15, 7)->default(0)->after('cash_tree');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropColumn([
                'cash_company_profit',
                'cash_jackpot',
                'cash_tree',
                'cash_cashback',
            ]);

        });
    }
};
