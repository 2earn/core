<?php

namespace Database\Factories;

use App\Enums\OrderEnum;
use App\Models\Order;
use App\Models\User;
use App\Models\Platform;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalOrder = $this->faker->randomFloat(2, 50, 1000);
        $dealAmountBeforeDiscount = $this->faker->randomFloat(2, 0, $totalOrder);
        $outOfDealAmount = $totalOrder - $dealAmountBeforeDiscount;
        $totalFinalDiscountPercentage = $this->faker->randomFloat(2, 0, 30);
        $dealAmountAfterDiscounts = $dealAmountBeforeDiscount * (1 - ($totalFinalDiscountPercentage / 100));
        $amountAfterDiscount = $totalOrder - ($dealAmountBeforeDiscount * ($totalFinalDiscountPercentage / 100));
        $commission2Earn = $dealAmountAfterDiscounts * $this->faker->randomFloat(2, 0.05, 0.15);
        $dealAmountForPartner = $dealAmountAfterDiscounts - $commission2Earn;

        return [
            'out_of_deal_amount' => $outOfDealAmount,
            'deal_amount_before_discount' => $dealAmountBeforeDiscount,
            'total_order' => $totalOrder,
            'total_order_quantity' => $this->faker->numberBetween(1, 20),
            'deal_amount_after_discounts' => $dealAmountAfterDiscounts,
            'amount_after_discount' => $amountAfterDiscount,
            'paid_cash' => $amountAfterDiscount,
            'commission_2_earn' => $commission2Earn,
            'deal_amount_for_partner' => $dealAmountForPartner,
            'commission_for_camembert' => $this->faker->randomFloat(2, 0, 50),
            'total_final_discount' => $dealAmountBeforeDiscount * ($totalFinalDiscountPercentage / 100),
            'total_final_discount_percentage' => $totalFinalDiscountPercentage,
            'total_lost_discount' => $this->faker->randomFloat(2, 0, 20),
            'total_lost_discount_percentage' => $this->faker->randomFloat(2, 0, 10),
            'user_id' => User::factory(),
            'platform_id' => Platform::factory(),
            'note' => $this->faker->optional()->sentence(),
            'status' => $this->faker->randomElement([
                OrderEnum::New->value,
                OrderEnum::Ready->value,
                OrderEnum::Simulated->value,
                OrderEnum::Paid->value,
                OrderEnum::Failed->value,
                OrderEnum::Dispatched->value,
            ]),
            'simulation_datetime' => $this->faker->optional()->dateTimeBetween('-30 days', 'now'),
            'simulation_result' => $this->faker->boolean(80),
            'simulation_details' => $this->faker->optional()->sentence(),
            'payment_datetime' => $this->faker->optional()->dateTimeBetween('-30 days', 'now'),
            'payment_result' => $this->faker->boolean(85),
            'payment_details' => $this->faker->optional()->sentence(),
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the order is new.
     */
    public function newOrder(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderEnum::New->value,
            'payment_datetime' => null,
            'payment_result' => null,
        ]);
    }

    /**
     * Indicate that the order is ready.
     */
    public function ready(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderEnum::Ready->value,
        ]);
    }

    /**
     * Indicate that the order is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderEnum::Paid->value,
            'payment_datetime' => now(),
            'payment_result' => true,
        ]);
    }

    /**
     * Indicate that the order failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderEnum::Failed->value,
            'payment_result' => false,
        ]);
    }

    /**
     * Indicate that the order is dispatched.
     */
    public function dispatched(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderEnum::Dispatched->value,
            'payment_result' => true,
            'payment_datetime' => now(),
        ]);
    }

    /**
     * Indicate that the order is simulated.
     */
    public function simulated(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OrderEnum::Simulated->value,
            'simulation_result' => true,
            'simulation_datetime' => now(),
        ]);
    }

    /**
     * Indicate that the simulation was successful.
     */
    public function simulationSuccess(): static
    {
        return $this->state(fn (array $attributes) => [
            'simulation_result' => true,
            'simulation_datetime' => now(),
        ]);
    }

    /**
     * Indicate that the simulation failed.
     */
    public function simulationFailed(): static
    {
        return $this->state(fn (array $attributes) => [
            'simulation_result' => false,
            'simulation_datetime' => now(),
        ]);
    }

    /**
     * Indicate that the payment was successful.
     */
    public function paymentSuccess(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_result' => true,
            'payment_datetime' => now(),
            'status' => OrderEnum::Paid->value,
        ]);
    }

    /**
     * Indicate that the payment failed.
     */
    public function paymentFailed(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_result' => false,
            'payment_datetime' => now(),
        ]);
    }
}
