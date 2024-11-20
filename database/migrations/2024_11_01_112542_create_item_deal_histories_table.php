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
        Schema::create('item_deal_histories', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->unsignedBigInteger('item_id')->foreign('item_id')->nullable()->references('id')->on('items')->onDelete('cascade');
            $table->unsignedBigInteger('deal_id')->foreign('deal_id')->nullable()->references('id')->on('deals')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->foreign('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('item_deal_histories');
    }
};
