<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->integer('idSETTINGS')->primary();
            $table->string('ParameterName', 45)->nullable();
            $table->integer('IntegerValue')->nullable();
            $table->string('StringValue', 255)->nullable();
            $table->string('DecimalValue', 45)->nullable();
            $table->string('Unit', 5)->nullable();
            $table->integer('Automatically_calculated')->nullable();
            $table->string('Description', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
