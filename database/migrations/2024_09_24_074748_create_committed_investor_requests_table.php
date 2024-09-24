<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    const TABLE_NAME = 'committed_investor_requests';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->integer('status')->nullable();
            $table->string('note', 455)->nullable();
            $table->dateTime('examination_date')->nullable();
            $table->dateTime('request_date')->nullable();
            $table->unsignedBigInteger('user_id')->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->unsignedBigInteger('examiner_id')->foreign('examiner_id')->nullable()->references('id')->on('user')->onDelete('cascade');
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
