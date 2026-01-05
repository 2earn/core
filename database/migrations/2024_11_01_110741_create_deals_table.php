<?php

use App\Enums\DealStatus;
use App\Enums\DealTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    const TABLE_NAME = 'deals';

    public function up()
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $status = [DealStatus::New->value, DealStatus::Opened->value, DealStatus::Closed->value, DealStatus::Archived->value];
            $table->id();
            $table->string('name')->nullable();
            $table->string('description',512)->nullable();
            $table->boolean('validated')->nullable();
            $table->enum('type', [DealTypeEnum::public->value, DealTypeEnum::coupons->value])->default(DealTypeEnum::public->value);
            $table->enum('status', $status);
            $table->float('current_turnover')->nullable();
            $table->float('target_turnover')->nullable();
            $table->boolean('is_turnover')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->float('items_profit_average')->nullable();
            $table->float('initial_commission')->nullable();
            $table->float('final_commission')->nullable();
            $table->float('earn_profit')->nullable();
            $table->float('jackpot')->nullable();
            $table->float('tree_remuneration')->nullable();
            $table->float('proactive_cashback')->nullable();
            $table->float('total_commission_value')->nullable();
            $table->float('total_unused_cashback_value')->nullable();
            $table->float('discount')->nullable();
            $table->unsignedBigInteger('created_by_id')->foreign('user_id')->nullable()->references('id')->on('user')->onDelete('cascade');
            $table->unsignedBigInteger('platform_id')->nullable()->foreign('platform_id')->default(1)->nullable()->references('id')->on('platforms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
