<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    const TABLE_NAME = 'order_details';

    public function up()
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {

            $table->float('qty')->nullable();
            $table->float('shipping')->nullable();
            $table->float('unit_price')->nullable();
            $table->float('total_amount')->nullable();
            $table->float('partner_discount')->nullable();
            $table->float('partner_discount_percentage')->nullable();
            $table->float('amount_after_partner_discount')->nullable();
            $table->float('earn_discount_percentage')->nullable();
            $table->float('earn_discount')->nullable();
            $table->float('amount_after_earn_discount')->nullable();
            $table->float('deal_discount_percentage')->nullable();
            $table->float('deal_discount')->nullable();
            $table->float('amount_after_deal_discount')->nullable();
            $table->float('total_discount')->nullable();
            $table->float('total_discount_percentage')->nullable();
            $table->float('refund_dispatching')->nullable();
            $table->float('final_amount')->nullable();
            $table->float('final_discount')->nullable();
            $table->float('final_discount_percentage')->nullable();
            $table->float('missed_discount')->nullable();
        });
    }

    public function down()
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropColumn('qty');
            $table->dropColumn('unit_price');
            $table->dropColumn('total_amount');
            $table->dropColumn('partner_discount_percentage');
            $table->dropColumn('amount_after_partner_discount');
            $table->dropColumn('2_earn_discount_percentage');
            $table->dropColumn('2_earn_discount');
            $table->dropColumn('amount_after_2_earn_discount');
            $table->dropColumn('deal_discount_percentage');
            $table->dropColumn('deal_discount');
            $table->dropColumn('amount_after_deal_discount');
            $table->dropColumn('total_discount');
            $table->dropColumn('total_discount_percentage');
            $table->dropColumn('refund_dispatching');
            $table->dropColumn('final_amount');
            $table->dropColumn('final_discount');
            $table->dropColumn('final_discount_percentage');
            $table->dropColumn('missed_discount');

        });
    }
};
