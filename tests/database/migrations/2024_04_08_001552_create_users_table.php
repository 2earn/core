<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->string('email', 255)->nullable()->unique('users_email_unique');
            $table->string('idUpline', 9)->default('0');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->string('idUser', 9)->unique('idUser_UNIQUE');
            $table->string('activationCodeValue', 9)->default('0');
            $table->string('mobile', 15);
            $table->string('fullphone_number', 15);
            $table->integer('idCountry')->nullable();
            $table->integer('status')->default(0);
            $table->timestamp('asked_at')->nullable();
            $table->boolean('is_public')->default(0);
            $table->boolean('has_image')->default(0);
            $table->boolean('iden_notif')->default(1);
            $table->string('OptActivation', 45)->nullable();
            $table->timestamp('OptActivation_at')->nullable();
            $table->integer('typeProfil')->default(1);
            $table->integer('id_phone');
            $table->boolean('email_verified')->default(0);
            $table->string('avatar', 45)->nullable();
            $table->integer('registred_from')->default(3);
            $table->string('pass', 255)->nullable();
            $table->integer('is_representative')->default(0);
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
}
