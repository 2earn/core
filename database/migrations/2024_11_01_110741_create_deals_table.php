<?php

use Core\Enum\DealStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    const TABLE_NAME = 'deals';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $status = [
                DealStatus::New->value,
                DealStatus::Open->value,
                DealStatus::Closed->value,
                DealStatus::Archived->value
            ];
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', $status);
            $table->float('objective_turnover')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->float('items_profit_average')->nullable();
            $table->float('out_provider_turnover')->nullable();
            $table->float('initial_commission')->nullable();
            $table->float('final_commission')->nullable();
            $table->float('precision')->nullable();
            $table->float('progressive_commission')->nullable();
            $table->float('margin_percentage')->nullable();
            $table->float('cash_back_margin_percentage')->nullable();
            $table->float('proactive_consumption_margin_percentage')->nullable();
            $table->float('shareholder_benefits_margin_percentage')->nullable();
            $table->float('tree_margin_percentage')->nullable();
            $table->float('current_turnover')->nullable();
            $table->float('item_price')->nullable();
            $table->float('current_turnover_index')->nullable();
            $table->unsignedBigInteger('created_by')->foreign('user_id')->nullable()->references('id')->on('user')->onDelete('cascade');
            $table->unsignedBigInteger('platform_id')->foreign('platform_id')->nullable()->references('id')->on('platforms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
