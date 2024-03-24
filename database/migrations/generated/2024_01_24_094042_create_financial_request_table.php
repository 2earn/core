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
        Schema::create('financial_request', function (Blueprint $table) {
            $table->string('numeroReq', 8)->primary();
            $table->integer('idSender')->nullable();
            $table->dateTime('date')->nullable();
            $table->decimal('amount', 13, 6)->nullable();
            $table->integer('status')->nullable();
            $table->integer('idUserAccepted')->nullable();
            $table->dateTime('dateAccepted')->nullable();
            $table->integer('typeReq')->default(2);
            $table->string('securityCode', 45);
            $table->boolean('vu')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('financial_request');
    }
};
