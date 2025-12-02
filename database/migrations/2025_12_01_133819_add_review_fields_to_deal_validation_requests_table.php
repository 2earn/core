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
        Schema::table('deal_validation_requests', function (Blueprint $table) {
            // Add requested_by field (for consistency with other request tables)
            $table->unsignedBigInteger('requested_by')->nullable()->after('requested_by_id');

            // Add review fields
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('requested_by');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');

            // Add cancelled fields
            $table->unsignedBigInteger('cancelled_by')->nullable()->after('rejection_reason');
            $table->text('cancelled_reason')->nullable()->after('cancelled_by');

            // Add foreign keys
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cancelled_by')->references('id')->on('users')->onDelete('set null');

            // Modify status enum to include 'cancelled'
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deal_validation_requests', function (Blueprint $table) {
            // Check if columns exist before dropping
            if (Schema::hasColumn('deal_validation_requests', 'requested_by')) {
                $table->dropForeign(['requested_by']);
                $table->dropColumn('requested_by');
            }

            if (Schema::hasColumn('deal_validation_requests', 'reviewed_by')) {
                $table->dropForeign(['reviewed_by']);
                $table->dropColumn('reviewed_by');
            }

            if (Schema::hasColumn('deal_validation_requests', 'reviewed_at')) {
                $table->dropColumn('reviewed_at');
            }

            if (Schema::hasColumn('deal_validation_requests', 'cancelled_by')) {
                $table->dropForeign(['cancelled_by']);
                $table->dropColumn('cancelled_by');
            }

            if (Schema::hasColumn('deal_validation_requests', 'cancelled_reason')) {
                $table->dropColumn('cancelled_reason');
            }

            // Revert status enum to original values
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->change();
        });
    }
};
