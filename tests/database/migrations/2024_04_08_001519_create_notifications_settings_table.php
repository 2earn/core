<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications_settings', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('libelle', 45)->nullable();
            $table->string('type', 1)->default('b');
            $table->string('defaultVal', 4)->default('1');
            $table->boolean('payer')->default(0);
            $table->string('typeNotification', 1)->default('m');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications_settings');
    }
}
