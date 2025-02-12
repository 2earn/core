<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_deals', function (Blueprint $table) {
            $table->id();
            $table->double('total_amount')->nullable();
            $table->double('partner_discount')->nullable();
            $table->double('amount_after_partner_discount')->nullable();
            $table->double('earn_discount')->nullable();
            $table->double('amount_after_earn_discount')->nullable();
            $table->double('deal_discount_percentage')->nullable();
            $table->double('deal_discount')->nullable();
            $table->double('amount_after_deal_discount')->nullable();
            $table->double('total_discount')->nullable();
            $table->double('final_discount')->nullable();
            $table->double('final_discount_percentage')->nullable();
            $table->double('lost_discount')->nullable();
            $table->double('final_amount')->nullable();
            $table->unsignedBigInteger('order_id')->nullable()->foreign('order_id')->nullable()->references('id')->on('orders')->onDelete('cascade');
            $table->unsignedBigInteger('deal_id')->nullable()->foreign('deal_id')->nullable()->references('id')->on('deals')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_deals');
    }
};
