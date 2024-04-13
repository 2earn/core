<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalanceoperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balanceoperations', function (Blueprint $table) {
            $table->integer('idBalanceOperations')->primary();
            $table->string('Designation', 90)->nullable();
            $table->string('IO', 2)->nullable();
            $table->string('idSource', 45)->nullable();
            $table->string('Mode', 45)->nullable();
            $table->integer('idamounts');
            $table->string('Note', 45)->nullable();
            $table->boolean('MODIFY_AMOUNT')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balanceoperations');
    }
}
