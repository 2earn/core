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
        Schema::create('vip', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('idUser', 9);
            $table->integer('flashCoefficient')->nullable();
            $table->integer('flashDeadline')->nullable();
            $table->string('flashNote')->nullable();
            $table->float('flashMinAmount')->nullable();
            $table->dateTime('dateFNS')->nullable();
            $table->float('maxShares')->nullable();
            $table->float('solde')->nullable();
            $table->boolean('declenched')->default(false);
            $table->dateTime('declenchedDate')->nullable();
            $table->boolean('closed')->default(false);
            $table->dateTime('closedDate')->nullable();



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vip');
    }
};
