<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    const TABLE_NAME = 'partner_requests';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 255)->nullable();
            $table->unsignedBigInteger('business_sector_id')->nullable();
            $table->string('platform_url', 500)->nullable();
            $table->text('platform_description')->nullable();
            $table->text('partnership_reason')->nullable();
            $table->integer('status')->nullable();
            $table->string('note', 455)->nullable();
            $table->dateTime('examination_date')->nullable();
            $table->dateTime('request_date')->nullable();
            $table->unsignedBigInteger('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->unsignedBigInteger('examiner_id')->nullable()->references('id')->on('user')->onDelete('cascade');
            $table->timestamps();

            $table->foreign('business_sector_id')->references('id')->on('business_sectors')->onDelete('set null');
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

