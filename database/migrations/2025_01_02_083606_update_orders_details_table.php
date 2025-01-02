<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    const TABLE_NAME = 'order_details';

    public function up()
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropColumn('number');
            $table->float('qte')->nullable();
            $table->float('unit_price')->nullable();
            $table->float('shipping')->nullable();
            $table->dropColumn('total');
            $table->float('price')->nullable();
            $table->float('price_after_discount')->nullable();
            $table->float('price_after_bfs')->nullable();
            $table->float('discount_gain')->nullable();
            $table->float('bfs_paid')->nullable();
            $table->float('cash_paid')->nullable();
            $table->boolean('solded_item')->nullable();
        });
    }

    public function down()
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropColumn('unit_price');
            $table->dropColumn('shipping');
            $table->dropColumn('price_after_discount');
            $table->dropColumn('discount_gain');
            $table->dropColumn('bfs_paid');
            $table->dropColumn('cash_paid');
            $table->dropColumn('solded_item');
        });
    }
};
