<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    const TABLE_NAME = 'balance_operations';

    public function up(): void
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->unsignedBigInteger('balance_operations_id')->foreign('balance_operations_id')->nullable()->references('id')->on('operation_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->dropColumn([
                'balance_operations_id'
            ]);
        });
    }
};
