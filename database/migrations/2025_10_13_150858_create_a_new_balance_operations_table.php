<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        if (Schema::hasTable('balance_operations')) {
            Schema::rename('balance_operations', 'balance_operations_old');
        }

        Schema::create('balance_operations', function (Blueprint $table) {
            $table->id();
            $table->string('ref')->unique();
            $table->unsignedBigInteger('operation_category_id');
            $table->string('operation');
            $table->enum('direction', ['IN', 'OUT']);
            $table->unsignedBigInteger('balance_id');
            $table->unsignedBigInteger('parent_operation_id')->nullable();
            $table->boolean('relateble')->default(false);
            $table->string('relateble_model')->nullable();
            $table->string('relateble_types')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a_new_balance_operations');
    }
};
