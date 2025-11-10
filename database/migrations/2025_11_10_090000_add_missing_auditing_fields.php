<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tables that have timestamps but are missing created_by/updated_by
     */
    protected array $tablesWithTimestamps = [
        'translatetab',
        'transactions',
        'targetables',
        'sms_balances',
        'roles',
        'pool',
        'platforms',
        'balance_operations',
    ];

    /**
     * Tables that are missing both timestamps and auditing fields
     */
    protected array $tablesWithoutTimestamps = [
        'user_contacts',
        'vip',  // Note: table name is 'vip' not 'vips'
        'user_earns',
        'user_balances',
        'usercontactnumber',
        'states',
        'settings',
        'role_has_permissions',
        'representatives',
        'metta_users',
        'financial_request',
        'detail_financial_request',
        'countries',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add created_by and updated_by to tables that already have timestamps
        foreach ($this->tablesWithTimestamps as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'created_by')) {
                        $table->unsignedBigInteger('created_by')->nullable()->after('created_at');
                        $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                    }
                    if (!Schema::hasColumn($tableName, 'updated_by')) {
                        $table->unsignedBigInteger('updated_by')->nullable()->after('updated_at');
                        $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
                    }
                });
            }
        }

        // Add all four fields (timestamps + auditing) to tables without timestamps
        foreach ($this->tablesWithoutTimestamps as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'created_at')) {
                        $table->timestamp('created_at')->nullable();
                    }
                    if (!Schema::hasColumn($tableName, 'updated_at')) {
                        $table->timestamp('updated_at')->nullable();
                    }
                    if (!Schema::hasColumn($tableName, 'created_by')) {
                        $table->unsignedBigInteger('created_by')->nullable();
                        $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                    }
                    if (!Schema::hasColumn($tableName, 'updated_by')) {
                        $table->unsignedBigInteger('updated_by')->nullable();
                        $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove auditing fields from tables with timestamps
        foreach ($this->tablesWithTimestamps as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (Schema::hasColumn($tableName, 'created_by')) {
                        $table->dropForeign(['created_by']);
                        $table->dropColumn('created_by');
                    }
                    if (Schema::hasColumn($tableName, 'updated_by')) {
                        $table->dropForeign(['updated_by']);
                        $table->dropColumn('updated_by');
                    }
                });
            }
        }

        // Remove all four fields from tables that didn't have timestamps
        foreach ($this->tablesWithoutTimestamps as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (Schema::hasColumn($tableName, 'created_by')) {
                        $table->dropForeign(['created_by']);
                        $table->dropColumn('created_by');
                    }
                    if (Schema::hasColumn($tableName, 'updated_by')) {
                        $table->dropForeign(['updated_by']);
                        $table->dropColumn('updated_by');
                    }
                    if (Schema::hasColumn($tableName, 'created_at')) {
                        $table->dropColumn('created_at');
                    }
                    if (Schema::hasColumn($tableName, 'updated_at')) {
                        $table->dropColumn('updated_at');
                    }
                });
            }
        }
    }
};

