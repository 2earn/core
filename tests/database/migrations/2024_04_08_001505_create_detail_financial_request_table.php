<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailFinancialRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_financial_request', function (Blueprint $table) {
            $table->string('numeroRequest', 8);
            $table->integer('idUser');
            $table->string('response', 1)->nullable();
            $table->dateTime('dateResponse')->nullable();
            $table->boolean('vu')->default(0);
            
            $table->primary(['numeroRequest', 'idUser']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_financial_request');
    }
}
