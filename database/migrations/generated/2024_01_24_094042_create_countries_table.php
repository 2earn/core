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
        Schema::create('countries', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('apha2', 3);
            $table->string('name', 150);
            $table->integer('phonecode');
            $table->string('langage', 50)->default('English');
            $table->double('ExchangeRate', 10, 3);
            $table->string('local_currency', 10);
            $table->string('lang', 2)->default('en');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
};
