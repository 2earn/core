<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranslatetabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translatetab', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->longText('name');
            $table->longText('value');
            $table->longText('valueFr');
            $table->longText('valueEn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translatetab');
    }
}
