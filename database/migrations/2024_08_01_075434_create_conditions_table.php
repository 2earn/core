<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    const TABLE_NAME = 'conditions';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string('operand')->nullable();
            $table->string('operator')->nullable();
            $table->string('value')->nullable();
            $table->unsignedBigInteger('target_id')->foreign('target_id')->nullable()->references('id')->on('target')->onDelete('cascade');
            $table->unsignedBigInteger('group_id')->foreign('group_id')->nullable()->references('id')->on('groups')->onDelete('cascade');
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
