<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get the database name
        $databaseName = DB::connection()->getDatabaseName();

        // Get all foreign keys on the platforms table
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = 'platforms'
            AND COLUMN_NAME IN ('owner_id', 'marketing_manager_id', 'financial_manager_id')
            AND CONSTRAINT_NAME != 'PRIMARY'
        ", [$databaseName]);

        // Drop foreign keys if they exist
        Schema::table('platforms', function (Blueprint $table) use ($foreignKeys) {
            foreach ($foreignKeys as $fk) {
                $table->dropForeign($fk->CONSTRAINT_NAME);
            }
        });

        // Drop the columns in a separate Schema call
        Schema::table('platforms', function (Blueprint $table) {
            if (Schema::hasColumn('platforms', 'owner_id')) {
                $table->dropColumn('owner_id');
            }
            if (Schema::hasColumn('platforms', 'marketing_manager_id')) {
                $table->dropColumn('marketing_manager_id');
            }
            if (Schema::hasColumn('platforms', 'financial_manager_id')) {
                $table->dropColumn('financial_manager_id');
            }
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
