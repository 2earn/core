<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Core\Enum\CommissionTypeEnum;

return new class extends Migration {
    const TABLE_NAME = 'commission_break_downs';

    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->integer('type')->nullable()->default(CommissionTypeEnum::OUT->value);
            $table->unsignedBigInteger('trigger')->nullable();
            $table->float('new_turnover')->nullable();
            $table->float('old_turnover')->nullable();
            $table->float('percentage')->nullable();
            $table->float('purchase_value')->nullable();
            $table->float('commission_percentage')->nullable();
            $table->float('commission_value')->nullable();
            $table->float('cumulative_commission')->nullable();
            $table->float('cumulative_commission_percentage')->nullable();
            $table->float('cash_company_profit')->nullable();
            $table->float('cashback_proactif')->nullable();
            $table->float('cash_jackpot')->nullable();
            $table->float('cash_tree')->nullable();
            $table->float('cash_cashback')->nullable();
            $table->float('cumulative_cashback')->nullable();
            $table->float('cashback_allocation')->nullable();
            $table->float('earned_cashback')->nullable();
            $table->float('commission_difference')->nullable();
            $table->float('additional_commission_value')->nullable();
            $table->float('final_cashback')->nullable();
            $table->float('final_cashback_percentage')->nullable();
            $table->unsignedBigInteger('deal_id')->nullable()->foreign('deal_id')->nullable()->references('id')->on('deals')->onDelete('cascade');
            $table->unsignedBigInteger('order_id')->nullable()->foreign('order_id')->nullable()->references('id')->on('orders')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
