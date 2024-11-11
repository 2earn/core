<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->float('number')->default(1);
            $table->float('total');
            $table->unsignedBigInteger('product_id')->foreign('product_id')->nullable()->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('order_id')->foreign('order_id')->nullable()->references('id')->on('orders')->onDelete('cascade');
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
        Schema::dropIfExists('order_items');
    }
};
