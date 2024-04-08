<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsercontactnumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usercontactnumber', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('idUser', 9)->nullable();
            $table->string('mobile', 15)->nullable();
            $table->integer('codeP')->nullable();
            $table->tinyInteger('active')->nullable();
            $table->string('isoP', 2)->nullable();
            $table->string('fullNumber', 45)->nullable();
            $table->integer('isID')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usercontactnumber');
    }
}
