<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('ref', 20)->nullable();
            $table->integer('id_send');
            $table->integer('id_reciver')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('date');
            $table->string('type', 255);
            $table->text('reponce')->nullable();
            $table->bigInteger('id_action')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history');
    }
}
