<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsercurrentbalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usercurrentbalances', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('idUser', 9);
            $table->integer('idamounts');
            $table->decimal('value', 10, 3)->default(0.000);
            $table->float('dernier_value')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usercurrentbalances');
    }
}
