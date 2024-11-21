<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const TABLE_NAME = 'chance_balances';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chance_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('deal_id')->nullable()->foreign('deal_id')->nullable()->references('id')->on('deals')->onDelete('cascade');
            $table->unsignedBigInteger('order_id')->nullable()->foreign('order_id')->nullable()->references('id')->on('orders')->onDelete('cascade');
            $table->unsignedBigInteger('platform_id')->nullable()->foreign('platform_id')->nullable()->references('id')->on('platforms')->onDelete('cascade');
            $table->unsignedBigInteger('order_detail_id')->nullable()->foreign('order_detail_id')->nullable()->references('id')->on('order_details')->onDelete('cascade');
            $table->unsignedBigInteger('balance_operation_id')->nullable()->foreign('balance_operation_id')->nullable()->references('id')->on('balance_operations')->onDelete('cascade');
            $table->unsignedBigInteger('operator_id')->foreign('operator_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('beneficiary_id')->foreign('beneficiary_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->double('value')->nullable();
            $table->double('actual_balance')->nullable();
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('chance_balances');
    }
};
