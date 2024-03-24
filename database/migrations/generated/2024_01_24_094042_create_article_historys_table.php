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
        Schema::create('article_historys', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('article_id');
            $table->string('champ', 255)->nullable();
            $table->string('value', 255)->nullable();
            $table->string('new_value', 255)->nullable();
            $table->bigInteger('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_historys');
    }
};
