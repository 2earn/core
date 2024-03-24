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
        Schema::create('notif_user_settings', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('idUser', 15);
            $table->tinyInteger('change_pwd_sms')->default(0);
            $table->tinyInteger('validate_phone_email')->default(1);
            $table->tinyInteger('iden_valide_sms')->default(0);
            $table->tinyInteger('new_cnx_sms')->default(0);
            $table->tinyInteger('cont_inscri_sms')->default(0);
            $table->tinyInteger('cont_inscri_email')->default(1);
            $table->tinyInteger('invit_achat_email')->default(1);
            $table->tinyInteger('cart_validate_email')->default(1);
            $table->tinyInteger('tree_dead_sms')->default(0);
            $table->tinyInteger('delivery_sms')->default(1);
            $table->tinyInteger('discount_sms')->default(1);
            $table->tinyInteger('rappel_formation_sms')->default(1);
            $table->tinyInteger('campagnes_sms')->default(1);
            $table->integer('discount_sms_p')->default(20);
            $table->integer('discount_email_p')->default(10);
            $table->integer('discount_plateforme_p')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notif_user_settings');
    }
};
