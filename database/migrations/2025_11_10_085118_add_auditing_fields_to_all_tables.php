<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * List of tables to add auditing fields to
     */
    protected array $tables = [
        'users',
        'deals',
        'items',
        'orders',
        'order_details',
        'order_deals',
        'shares',
        'item_deal_histories',
        'news',
        'likes',
        'comments',
        'surveys',
        'survey_questions',
        'survey_question_choices',
        'survey_responses',
        'survey_response_items',
        'targets',
        'groups',
        'conditions',
        'images',
        'faqs',
        'events',
        'hashtags',
        'coupons',
        'balance_injector_coupons',
        'business_sectors',
        'commission_break_downs',
        'carts',
        'cart_items',
        'pools',
        'trees',
        'cash_balances',
        'bfss_balances',
        'discount_balances',
        'sms_balances',
        'shares_balances',
        'tree_balances',
        'chance_balances',
        'current_balances',
        'activities',
        'sms',
        'user_current_balance_horisontals',
        'user_current_balance_verticals',
        'contact_users',
        'committed_investor_requests',
        'instructor_requests',
        'user_guides',
        'translale_models',
        'operation_categories',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    if (!Schema::hasColumn($table->getTable(), 'created_by')) {
                        $table->unsignedBigInteger('created_by')->nullable()->after('created_at');
                        $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                    }
                    if (!Schema::hasColumn($table->getTable(), 'updated_by')) {
                        $table->unsignedBigInteger('updated_by')->nullable()->after('updated_at');
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
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) {
                    if (Schema::hasColumn($table->getTable(), 'created_by')) {
                        $table->dropForeign(['created_by']);
                        $table->dropColumn('created_by');
                    }
                    if (Schema::hasColumn($table->getTable(), 'updated_by')) {
                        $table->dropForeign(['updated_by']);
                        $table->dropColumn('updated_by');
                    }
                });
            }
        }
    }
};

