<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRechargeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharge_requests', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->timestamp('Date')->default('current_timestamp()');
            $table->string('idUser', 15);
            $table->string('idPayee', 15);
            $table->string('userPhone', 50);
            $table->string('payeePhone', 50);
            $table->decimal('amount', 10, 0);
            $table->integer('validated')->default(0);
            $table->integer('type_user')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recharge_requests');
    }
}
