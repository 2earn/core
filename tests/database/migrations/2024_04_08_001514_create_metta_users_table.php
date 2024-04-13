<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMettaUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metta_users', function (Blueprint $table) {
            $table->id();
            $table->string('idUser', 9);
            $table->string('arFirstName', 25)->nullable();
            $table->string('arLastName', 25)->nullable();
            $table->string('enFirstName', 25)->nullable();
            $table->string('enLastName', 25)->nullable();
            $table->string('personaltitle', 9)->nullable();
            $table->integer('idCountry')->nullable();
            $table->integer('childrenCount')->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender', 12)->nullable();
            $table->string('email', 225)->nullable();
            $table->string('secondEmail', 225)->nullable();
            $table->string('idLanguage', 50)->nullable();
            $table->string('nationalID', 25)->nullable();
            $table->string('internationalISD', 25)->nullable();
            $table->string('adresse', 225)->nullable();
            $table->integer('idState')->nullable();
            $table->text('note')->nullable();
            $table->text('interests')->default('[]');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metta_users');
    }
}
