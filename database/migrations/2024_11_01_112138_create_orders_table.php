<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Core\Enum\OrderEnum;
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->text('note')->nullable();
            $table->integer('status')->nullable()->default(OrderEnum::New->value);
            $table->unsignedBigInteger('user_id')->foreign('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('order_detail_id')->foreign('order_detail_id')->nullable()->references('id')->on('order_details')->onDelete('cascade');
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
        Schema::dropIfExists('orders');
    }
};
