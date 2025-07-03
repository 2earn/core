<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    const TABLE_NAME = 'platforms';

    public function up(): void
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->unsignedBigInteger('owner_id')->foreign('user_id')->nullable()->references('id')->on('user')->onDelete('cascade');
            $table->unsignedBigInteger('marketing_manager_id')->foreign('user_id')->nullable()->references('id')->on('user')->onDelete('cascade');
            $table->dropColumn('administrative_manager_id');
        });
    }

    public function down(): void
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            //
        });
    }
};
