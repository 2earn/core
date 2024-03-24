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
        Schema::create('user_contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('idUser', 9);
            $table->string('name', 25);
            $table->string('lastName', 255)->nullable();
            $table->string('mobile', 15);
            $table->string('fullphone_number', 25)->nullable();
            $table->string('availablity', 255);
            $table->tinyInteger('disponible');
            $table->tinyInteger('notif_sms')->default(0);
            $table->tinyInteger('notif_email')->default(0);
            $table->string('phonecode', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_contacts');
    }
};
