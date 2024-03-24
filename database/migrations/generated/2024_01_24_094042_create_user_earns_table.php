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
        Schema::create('user_earns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('idUser', 9);
            $table->string('name', 20)->nullable();
            $table->dateTime('registred_at')->nullable();
            $table->integer('registred_from')->default(3);
            $table->string('idUpline', 9);
            $table->tinyInteger('isSMSSended');
            $table->dateTime('activationCode_at')->nullable();
            $table->string('activationCodeValue', 9);
            $table->dateTime('activationDone_at')->nullable();
            $table->tinyInteger('activationDone');
            $table->dateTime('KYCIdentified_at')->nullable();
            $table->string('isKYCIdentified', 3);
            $table->string('idKYC', 25);
            $table->string('password', 25);
            $table->string('diallingCode', 10);
            $table->string('mobile', 15);
            $table->string('fullphone_number', 15);
            $table->string('change_to', 15)->nullable();
            $table->integer('idCountry');
            $table->tinyInteger('isCountryRepresentative');
            $table->tinyInteger('is_completed')->default(0);
            $table->string('verify_code', 15)->nullable();
            $table->tinyInteger('email_verified')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_earns');
    }
};
