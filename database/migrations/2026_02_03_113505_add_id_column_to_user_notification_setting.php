<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_notification_setting', function (Blueprint $table) {
            $table->dropPrimary();
            $table->id()->first();
            $table->unique(['idNotification', 'idUser']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_notification_setting', function (Blueprint $table) {
            $table->dropUnique(['idNotification', 'idUser']);
            $table->dropColumn('id');
            $table->primary(['idNotification', 'idUser']);
        });
    }
};
