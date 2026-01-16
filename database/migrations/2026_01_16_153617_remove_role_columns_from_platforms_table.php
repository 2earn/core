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
        Schema::table('platforms', function (Blueprint $table) {
            // Drop foreign keys if they exist
            $table->dropForeign(['owner_id']);
            $table->dropForeign(['marketing_manager_id']);
            $table->dropForeign(['financial_manager_id']);

            // Drop the columns
            $table->dropColumn(['owner_id', 'marketing_manager_id', 'financial_manager_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('platforms', function (Blueprint $table) {
            // Restore the columns
            $table->unsignedBigInteger('owner_id')->nullable()->after('image_link');
            $table->unsignedBigInteger('marketing_manager_id')->nullable()->after('owner_id');
            $table->unsignedBigInteger('financial_manager_id')->nullable()->after('marketing_manager_id');

            // Restore foreign keys
            $table->foreign('owner_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('marketing_manager_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('financial_manager_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }
};
