<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    const TABLE_NAME = 'balance_operations';

    public function up(): void
    {
        Schema::table(self::TABLE_NAME, function (Blueprint $table) {
            $table->string('ref')->nullable()->unique();
            $table->unsignedBigInteger('operation_category_id')->foreign('operation_category_id')->nullable()->references('id')->on('operation_categories')->onDelete('cascade');
            // parent_operation_id ===>   parent_id
            $table->enum('direction', ['IN', 'OUT']);
            $table->enum('balance_type', ['cash', 'bfs', 'discount', 'tree', 'sms', 'share', 'chance']);
            $table->boolean('relatable')->default(false);
            $table->string('relatable_model')->nullable();
            $table->string('relatable_type')->nullable();
            $table->index('operation_category_id');
            $table->index('balance_type');
            $table->index('relatable_model');
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
