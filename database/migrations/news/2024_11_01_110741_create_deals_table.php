<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
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
        Schema::dropIfExists('deals');
    }
};
