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
        Schema::create('usercurrentbalances', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('idUser', 9);
            $table->integer('idamounts');
            $table->decimal('value', 10, 3)->default(0);
            $table->float('dernier_value', 10, 0)->default(0);
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
};
