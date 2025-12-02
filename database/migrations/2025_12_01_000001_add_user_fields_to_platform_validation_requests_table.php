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
        Schema::table('platform_validation_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('requested_by')->nullable()->after('platform_id');
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('rejection_reason');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->unsignedBigInteger('updated_by')->nullable()->after('reviewed_at');

            $table->foreign('requested_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('platform_validation_requests', function (Blueprint $table) {
            $table->dropForeign(['requested_by']);
            $table->dropForeign(['reviewed_by']);
            $table->dropForeign(['updated_by']);

            $table->dropColumn(['requested_by', 'reviewed_by', 'reviewed_at', 'updated_by']);
        });
    }
};

