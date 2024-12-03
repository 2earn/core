<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{    const TABLE_NAME = 'user_current_balance_vericals';
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
            $table->integer('balance_id')->nullable();
            $table->double('current_balance')->nullable();
            $table->double('previous_balance')->nullable();
            $table->dateTime('last_operation_date')->nullable();
            $table->double('last_operation_value')->nullable();
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
