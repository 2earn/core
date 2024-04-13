<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_history', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('title', 255)->nullable();
            $table->text('list_reponce')->nullable();
            $table->integer('reponce')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('action_history');
    }
}
