<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    const TABLE_NAME = 'commission_break_downs';

    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trigger')->nullable();
            $table->float('amount')->nullable();
            $table->float('percentage')->nullable();
            $table->float('value')->nullable();
            $table->float('additional')->nullable();
            $table->float('camembert')->nullable();
            $table->float('earn')->nullable();
            $table->float('pool')->nullable();
            $table->float('cashback_proactif')->nullable();
            $table->float('tree')->nullable();
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
