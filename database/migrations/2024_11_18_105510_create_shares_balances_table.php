<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    const TABLE_NAME = 'shares_balances';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('balance_operation_id')->nullable()->foreign('balance_operation_id')->nullable()->references('id')->on('balance_operations')->onDelete('cascade');
            $table->unsignedBigInteger('operator_id')->foreign('operator_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('beneficiary_id')->foreign('beneficiary_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->double('value')->nullable();
            $table->double('actual_balance')->nullable();
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
            $table->float('unit_price')->nullable();
            $table->integer('payed')->nullable();
            $table->double('amount')->nullable();
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
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
