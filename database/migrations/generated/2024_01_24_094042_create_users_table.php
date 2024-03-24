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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->nullable();
            $table->string('email', 255)->nullable()->unique();
            $table->string('idUpline', 9)->default('0');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->string('idUser', 9)->unique('idUser_UNIQUE');
            $table->string('activationCodeValue', 9);
            $table->string('mobile', 15);
            $table->string('fullphone_number', 15);
            $table->integer('idCountry')->nullable();
            $table->integer('status')->default(0);
            $table->timestamp('asked_at')->nullable();
            $table->tinyInteger('is_public')->default(0);
            $table->boolean('has_image')->default(false);
            $table->tinyInteger('iden_notif')->default(1);
            $table->string('OptActivation', 45)->nullable();
            $table->timestamp('OptActivation_at')->nullable();
            $table->integer('typeProfil')->default(1);
            $table->integer('id_phone');
            $table->boolean('email_verified')->default(false);
            $table->string('avatar', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
