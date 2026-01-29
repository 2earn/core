<?php
namespace Database\Factories;
use App\Enums\CommissionTypeEnum;
use App\Models\CommissionBreakDown;
use App\Models\Deal;
use App\Models\Order;
use App\Models\Platform;
use Illuminate\Database\Eloquent\Factories\Factory;
class CommissionBreakDownFactory extends Factory
{
    protected $model = CommissionBreakDown::class;
    public function definition(): array
    {
        $purchaseValue = $this->faker->randomFloat(2, 100, 10000);
        $commissionPercentage = $this->faker->randomFloat(2, 1, 50);
        $commissionValue = $purchaseValue * ($commissionPercentage / 100);
        return [
            'trigger' => $this->faker->randomElement(['purchase', 'sale', 'commission_adjustment']),
            'type' => $this->faker->randomElement([CommissionTypeEnum::IN, CommissionTypeEnum::OUT]),
            'order_id' => Order::factory(),
            'deal_id' => Deal::factory(),
            'platform_id' => Platform::factory(),
            'new_turnover' => $this->faker->randomFloat(2, 0, 100000),
            'old_turnover' => $this->faker->randomFloat(2, 0, 100000),
            'purchase_value' => $purchaseValue,
            'commission_percentage' => $commissionPercentage,
            'commission_value' => $commissionValue,
            'cash_company_profit' => $this->faker->randomFloat(2, 0, 1000),
            'cash_jackpot' => $this->faker->randomFloat(2, 0, 1000),
            'cash_tree' => $this->faker->randomFloat(2, 0, 500),
            'cash_cashback' => $this->faker->randomFloat(2, 0, 500),
            'camembert' => $this->faker->optional()->text(255),
            'deal_paid_amount' => $this->faker->randomFloat(2, 0, 5000),
            'additional_amount' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
    public function withDeal(int $dealId): self
    {
        return $this->state(fn (array $attributes) => [
            'deal_id' => $dealId,
        ]);
    }
    public function withOrder(int $orderId): self
    {
        return $this->state(fn (array $attributes) => [
            'order_id' => $orderId,
        ]);
    }
    public function typeIn(): self
    {
        return $this->state(fn (array $attributes) => [
            'type' => CommissionTypeEnum::IN,
        ]);
    }
    public function typeOut(): self
    {
        return $this->state(fn (array $attributes) => [
            'type' => CommissionTypeEnum::OUT,
        ]);
    }
}