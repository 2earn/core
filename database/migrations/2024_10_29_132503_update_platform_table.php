<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    const OLD_TABLE_NAME = 'plateformes';
    const TABLE_NAME = 'platforms';


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
            $table->string('name')->nullable();
            $table->boolean('show_profile')->default(false);
            $table->boolean('enabled')->default(false);
            $table->tinyInteger('type')->nullable()->default(\Core\Enum\PlatformType::Child->value);
            $table->string('link')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('administrative_manager_id')->foreign('user_id')->nullable()->references('id')->on('user')->onDelete('cascade');
            $table->unsignedBigInteger('financial_manager_id')->foreign('user_id')->nullable()->references('id')->on('user')->onDelete('cascade');
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
    }
};
