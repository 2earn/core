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
            $table->float('out_of_deal_amount')->nullable();
            $table->float('deal_amount_before_discount')->nullable();
            $table->float('total_order_quantity')->nullable();
            $table->float('amount_before_discount')->nullable();
            $table->float('deal_amount_after_partner_discount')->nullable();
            $table->float('deal_amount_after_2earn_discount')->nullable();
            $table->float('deal_amount_after_deal_discount')->nullable();
            $table->float('lost_discount_amount')->nullable();
            $table->float('final_discount_value')->nullable();
            $table->float('final_discount_percentage')->nullable();
            $table->float('deal_amount_after_discounts');
            $table->float('amount_after_discount');
            $table->float('paid_cash');
            $table->float('commission_2_earn');
            $table->float('deal_amount_for_partner');
            $table->float('commission_for_camembert');
            $table->float('missed_discount');
        });
    }

    public function down()
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropColumn('out_of_deal_amount');
            $table->dropColumn('deal_amount_before_discount');
            $table->dropColumn('amount_before_discount');
            $table->dropColumn('deal_amount_after_partner_discount');
            $table->dropColumn('deal_amount_after_2earn_discount');
            $table->dropColumn('deal_amount_after_deal_discount');
            $table->dropColumn('lost_discount_amount');
            $table->dropColumn('final_discount_value');
            $table->dropColumn('final_discount_percentage');
            $table->dropColumn('deal_amount_after_discounts');
            $table->dropColumn('amount_after_discount');
            $table->dropColumn('paid_cash');
            $table->dropColumn('commission_2_earn');
            $table->dropColumn('deal_amount_for_partner');
            $table->dropColumn('commission_for_camembert');
            $table->dropColumn('missed_discount');
        });
    }
};
