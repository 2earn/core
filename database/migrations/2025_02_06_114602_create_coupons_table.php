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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('pin')->unique();
            $table->dateTime('attachment_date')->nullable();
            $table->dateTime('purchase_date')->nullable();
            $table->dateTime('consumption_date')->nullable();
            $table->float('value')->nullable();
            $table->boolean('consumed')->default(false);
            $table->timestamps();
            $table->unsignedBigInteger('platform_id')->nullable()->foreign('platform_id')->default(1)->nullable()->references('id')->on('platforms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
