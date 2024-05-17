<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('flashCoefficient')->nullable();
            $table->integer('flashDeadline')->nullable();
            $table->string('flashNote')->nullable();
            $table->float('flashMinAmount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('flashCoefficient');
            $table->dropColumn('flashDeadline');
            $table->dropColumn('flashNote');
            $table->dropColumn('flashMinAmount');
        });
    }
};
