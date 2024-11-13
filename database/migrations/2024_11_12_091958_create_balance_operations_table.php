<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    const OLD_TABLE_NAME = 'balanceoperations';
    const TABLE_NAME = 'balance_operations';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists(self::OLD_TABLE_NAME);
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string('designation', 90)->nullable();
            $table->string('io', 2)->nullable();
            $table->string('source', 45)->nullable();
            $table->string('mode', 45)->nullable();
            $table->integer('amounts_id');
            $table->string('note', 45)->nullable();
            $table->boolean('modify_amount')->nullable()->default(true);
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
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
