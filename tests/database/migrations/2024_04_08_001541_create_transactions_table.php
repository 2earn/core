<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('tran_ref', 255)->nullable();
            $table->string('tran_type', 255)->nullable();
            $table->string('cart_id', 255)->nullable();
            $table->string('cart_currency', 255)->nullable();
            $table->decimal('cart_amount', 10, 2)->nullable();
            $table->string('tran_currency', 255)->nullable();
            $table->decimal('tran_total', 10, 2)->nullable();
            $table->string('customer_phone', 255)->nullable();
            $table->string('response_status', 255)->nullable();
            $table->string('response_code', 255)->nullable();
            $table->text('response_message')->nullable();
            $table->timestamp('transaction_time')->default('current_timestamp()');
            $table->string('payment_method', 255)->nullable();
            $table->string('card_type', 255)->nullable();
            $table->string('card_scheme', 255)->nullable();
            $table->string('payment_description', 255)->nullable();
            $table->integer('expiry_month')->nullable();
            $table->integer('expiry_year')->nullable();
            $table->string('issuer_country', 255)->nullable();
            $table->string('issuer_name', 255)->nullable();
            $table->tinyInteger('success')->nullable();
            $table->tinyInteger('failed')->nullable();
            $table->timestamps()->default('current_timestamp()');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
