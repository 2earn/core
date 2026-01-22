<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\OrderEnum;

return new class extends Migration {

    const TABLE_NAME = 'orders';

    public function up()
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->float('out_of_deal_amount')->nullable();
            $table->float('deal_amount_before_discount')->nullable();
            $table->float('total_order')->nullable();
            $table->float('total_order_quantity')->nullable();
            $table->float('deal_amount_after_discounts');
            $table->float('amount_after_discount');
            $table->float('paid_cash');
            $table->float('commission_2_earn');
            $table->float('deal_amount_for_partner');
            $table->float('commission_for_camembert');
            $table->float('missed_discount');
            $table->float('total_final_discount');
            $table->float('total_final_discount_percentage');
            $table->float('total_lost_discount');
            $table->float('total_lost_discount_percentage');
        });
    }

    public function down()
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropColumn('out_of_deal_amount');
            $table->dropColumn('deal_amount_before_discount');
            $table->dropColumn('amount_before_discount');
            $table->dropColumn('deal_amount_after_discounts');
            $table->dropColumn('amount_after_discount');
            $table->dropColumn('paid_cash');
            $table->dropColumn('commission_2_earn');
            $table->dropColumn('deal_amount_for_partner');
            $table->dropColumn('commission_for_camembert');
            $table->dropColumn('missed_discount');
            $table->dropColumn('total_final_discount');
            $table->dropColumn('total_final_discount_percentage');
            $table->dropColumn('total_lost_discount');
            $table->dropColumn('total_lost_discount_percentage');
        });
    }
};
