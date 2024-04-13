<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigInteger('id_S');
            $table->bigInteger('id')->primary();
            $table->float('C_price');
            $table->float('P_price');
            $table->float('F_price');
            $table->integer('platforme');
            $table->integer('Forniseur');
            $table->string('title', 255)->nullable();
            $table->integer('etat')->default(0);
            $table->float('step')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
