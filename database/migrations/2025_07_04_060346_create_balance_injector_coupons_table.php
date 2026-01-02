<?php

use App\Enums\BalanceEnum;
use App\Enums\CouponStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    const TABLE_NAME = 'balance_injector_coupons';

    public function up(): void
    {
        Schema::create(self::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string('sn')->unique();
            $table->string('pin')->unique();
            $table->dateTime('attachment_date')->nullable();
            $table->dateTime('consumption_date')->nullable();
            $table->float('value')->nullable();
            $table->boolean('consumed')->default(false);
            $table->enum('status', [CouponStatusEnum::available->value, CouponStatusEnum::reserved->value, CouponStatusEnum::purchased->value, CouponStatusEnum::consumed->value])->default(CouponStatusEnum::available->value);
            $table->enum('category', [BalanceEnum::CASH->value, BalanceEnum::BFS->value, BalanceEnum::DB->value])->default(BalanceEnum::CASH->value);
            $table->string('type')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('platform_id')->nullable()->foreign('platform_id')->default(1)->nullable()->references('id')->on('platforms')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
