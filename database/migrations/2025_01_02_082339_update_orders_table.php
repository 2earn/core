<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Core\Enum\OrderEnum;

return new class extends Migration {

    const TABLE_NAME = 'orders';

    public function up()
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropColumn('tax');
            $table->float('additional_tax')->nullable();
            $table->dropColumn('shipping');
            $table->float('total_shipping')->nullable();
            $table->dropColumn('total');
            $table->float('total_price')->nullable();
            $table->float('total_price_after_discount')->nullable();
            $table->float('total_price_after_bfss')->nullable();
            $table->float('total_discount_gain')->nullable();
            $table->float('total_bfs_paid')->nullable();
            $table->float('total_supplement')->nullable();
            $table->float('total_supplement_after_bfs')->nullable();
            $table->float('total_supplement_paid')->nullable();
            $table->integer('status')->default(OrderEnum::New->value);
        });
    }

    public function down()
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->renameColumn('additional_tax', 'tax');
            $table->renameColumn('total_shipping', 'shipping');
            $table->renameColumn('total_price', 'price');
            $table->dropColumn('total_price_after_discount');
            $table->dropColumn('total_price_after_bfss');
            $table->dropColumn('total_discount_gain');
            $table->dropColumn('total_bfs_paid');
            $table->dropColumn('total_supplement');
            $table->dropColumn('total_supplement_after_bfs');
            $table->dropColumn('total_supplement_paid');
            $table->dropColumn('status');
        });
    }
};
