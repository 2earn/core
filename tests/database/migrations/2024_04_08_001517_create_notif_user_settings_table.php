<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notif_user_settings', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('idUser', 15);
            $table->boolean('change_pwd_sms')->default(0);
            $table->boolean('validate_phone_email')->default(1);
            $table->boolean('iden_valide_sms')->default(0);
            $table->boolean('new_cnx_sms')->default(0);
            $table->boolean('cont_inscri_sms')->default(0);
            $table->boolean('cont_inscri_email')->default(1);
            $table->boolean('invit_achat_email')->default(1);
            $table->boolean('cart_validate_email')->default(1);
            $table->boolean('tree_dead_sms')->default(0);
            $table->boolean('delivery_sms')->default(1);
            $table->boolean('discount_sms')->default(1);
            $table->boolean('rappel_formation_sms')->default(1);
            $table->boolean('campagnes_sms')->default(1);
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
}
