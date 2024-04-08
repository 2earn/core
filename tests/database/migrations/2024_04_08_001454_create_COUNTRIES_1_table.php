<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCOUNTRIES1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('COUNTRIES_1', function (Blueprint $table) {
            $table->string('CODE', 2)->primary();
            $table->string('COUNTRY_NAME', 255);
            $table->string('FULL_NAME', 255);
            $table->char('ISO3', 3);
            $table->integer('COUNTRY_NUMBER');
            $table->string('CONTINENT_CODE', 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('COUNTRIES_1');
    }
}
