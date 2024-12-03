<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{    const TABLE_NAME = 'user_current_balance_horisontals';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string('	user_id', 180)->nullable();
            $table->unsignedBigInteger('user_id_auto')->foreign('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->double('cash_balance')->nullable();
            $table->longText('bfss_balance')->nullable();
            $table->double('discount_balance')->nullable();
            $table->double('tree_balance')->nullable();
            $table->double('sms_balance')->nullable();
            $table->double('share_balance')->nullable();
            $table->longText('chances_balance')->nullable();
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
