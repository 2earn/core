<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->timestamps();
            $table->string('nom');
            $table->string('prenom');
            $table->string('phone_mobile');
            $table->string('phone_fix')->nullable();
            $table->string('tel_livraison', 255)->nullable();
            $table->string('tel_facturation', 255)->nullable();
            $table->string('email');
            $table->string('email_livraison', 255)->nullable();
            $table->string('email_facturation', 255)->nullable();
            $table->string('pays')->nullable();
            $table->string('ville')->nullable();
            $table->string('adresse', 200);
            $table->string('ville_livraison', 255)->nullable();
            $table->string('ville_facturation', 255)->nullable();
            $table->string('societe')->nullable();
            $table->string('code_tva')->nullable();
            $table->string('adresse_client', 255)->nullable();
            $table->string('adresse_facture')->nullable();
            $table->string('adresse_livraison')->nullable();
            $table->string('password');
            $table->integer('codepostal')->nullable();
            $table->string('idERP', 200)->nullable();
            $table->text('carte_fidelite')->nullable();
            $table->date('naissance')->nullable();
            $table->integer('gift')->nullable();
            $table->integer('first_cmd')->nullable();
            $table->integer('adderp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
