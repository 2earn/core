<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    const TABLE_NAME = 'plateformes';


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropColumn('afficherProfil');
            $table->boolean('show_profile')->default(false);
            $table->tinyInteger('type')->nullable()->default(\Core\Enum\PlatformType::Child->value);
            $table->string('link')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('administrative_manager_id')->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->unsignedBigInteger('financial_manager_id')->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
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
        //
    }
};
