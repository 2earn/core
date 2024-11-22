<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    const TABLE_NAME = 'chance_balances';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->morphs('chanceable');
            $table->unsignedBigInteger('platform_id')->nullable()->foreign('platform_id')->nullable()->references('id')->on('platforms')->onDelete('cascade');
            $table->unsignedBigInteger('activity_id')->nullable()->foreign('activity_id')->nullable()->references('id')->on('activities')->onDelete('cascade');
            $table->unsignedBigInteger('balance_operation_id')->nullable()->foreign('balance_operation_id')->nullable()->references('id')->on('balance_operations')->onDelete('cascade');
            $table->unsignedBigInteger('operator_id')->foreign('operator_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('beneficiary_id')->foreign('beneficiary_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('pool_id')->foreign('pool_id')->nullable()->references('id')->on('pools')->onDelete('cascade');
            $table->string('description', 512)->nullable();
            $table->string('ref')->nullable();
            $table->string('reference')->nullable();
            $table->double('value')->nullable();
            $table->double('total_balance')->nullable();
            $table->double('total_amount')->nullable();

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
