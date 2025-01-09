<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    const TABLE_NAME = 'items';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('ref')->nullable();
            $table->string('photo_link')->nullable();
            $table->float('price')->nullable();
            $table->float('discount')->nullable();
            $table->string('description',512)->nullable();
            $table->float('stock')->nullable();
            $table->unsignedBigInteger('created_by')->foreign('user_id')->nullable()->references('id')->on('user')->onDelete('cascade');
            $table->unsignedBigInteger('seller_id')->foreign('seller_id')->nullable()->references('id')->on('user')->onDelete('cascade');
            $table->unsignedBigInteger('deal_id')->foreign('deal_id')->nullable()->references('id')->on('deals')->onDelete('cascade');
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
