<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_balances', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('id_item')->nullable();
            $table->string('item_title', 200)->nullable();
            $table->integer('id_plateform')->nullable();
            $table->dateTime('Date')->nullable();
            $table->integer('idBalancesOperation')->nullable();
            $table->string('Description', 512)->nullable();
            $table->string('idSource', 15)->nullable();
            $table->string('idUser', 9);
            $table->integer('idamount')->nullable();
            $table->decimal('value', 10, 3)->default(0.000);
            $table->decimal('Balance', 10, 3)->nullable();
            $table->decimal('WinPurchaseAmount', 10, 3)->default(0.000);
            $table->string('PU', 20)->nullable();
            $table->integer('gifted_shares')->nullable();
            $table->boolean('Block_trait')->default(0);
            $table->string('ref', 100)->nullable();
            $table->decimal('PrixUnitaire', 10, 0)->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_balances');
    }
}
