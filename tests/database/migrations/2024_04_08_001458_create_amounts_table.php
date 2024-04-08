<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amounts', function (Blueprint $table) {
            $table->integer('idamounts')->primary();
            $table->string('amountsname', 100);
            $table->string('amountswithholding_tax', 1);
            $table->string('amountspaymentrequest', 1);
            $table->string('amountstransfer', 1);
            $table->string('amountscash', 1);
            $table->string('amountsactive', 1);
            $table->string('amountsshortname', 45);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amounts');
    }
}
