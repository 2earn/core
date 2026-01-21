<?php

namespace Database\Factories;

use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $qty = $this->faker->numberBetween(1, 10);
        $unitPrice = $this->faker->randomFloat(2, 10, 500);
        $totalAmount = $qty * $unitPrice;

        // Calculate discounts
        $partnerDiscountPercentage = $this->faker->randomFloat(2, 0, 20);
        $partnerDiscount = $totalAmount * ($partnerDiscountPercentage / 100);
        $amountAfterPartnerDiscount = $totalAmount - $partnerDiscount;

        $earnDiscountPercentage = $this->faker->randomFloat(2, 0, 15);
        $earnDiscount = $amountAfterPartnerDiscount * ($earnDiscountPercentage / 100);
        $amountAfterEarnDiscount = $amountAfterPartnerDiscount - $earnDiscount;

        $dealDiscountPercentage = $this->faker->randomFloat(2, 0, 10);
        $dealDiscount = $amountAfterEarnDiscount * ($dealDiscountPercentage / 100);
        $amountAfterDealDiscount = $amountAfterEarnDiscount - $dealDiscount;

        $totalDiscount = $partnerDiscount + $earnDiscount + $dealDiscount;

        return [
            'qty' => $qty,
            'shipping' => $this->faker->randomFloat(2, 0, 50),
            'unit_price' => $unitPrice,
            'total_amount' => $totalAmount,

            'partner_discount_percentage' => $partnerDiscountPercentage,
            'partner_discount' => $partnerDiscount,
            'amount_after_partner_discount' => $amountAfterPartnerDiscount,

            'earn_discount_percentage' => $earnDiscountPercentage,
            'earn_discount' => $earnDiscount,
            'amount_after_earn_discount' => $amountAfterEarnDiscount,

            'deal_discount_percentage' => $dealDiscountPercentage,
            'deal_discount' => $dealDiscount,
            'amount_after_deal_discount' => $amountAfterDealDiscount,

            'total_discount' => $totalDiscount,

            'order_id' => Order::factory(),
            'item_id' => Item::factory(),

            'note' => $this->faker->optional()->sentence(),
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the order detail has no discounts.
     */
    public function noDiscount(): static
    {
        return $this->state(function (array $attributes) {
            $totalAmount = $attributes['total_amount'];

            return [
                'partner_discount_percentage' => 0,
                'partner_discount' => 0,
                'amount_after_partner_discount' => $totalAmount,

                'earn_discount_percentage' => 0,
                'earn_discount' => 0,
                'amount_after_earn_discount' => $totalAmount,

                'deal_discount_percentage' => 0,
                'deal_discount' => 0,
                'amount_after_deal_discount' => $totalAmount,

                'total_discount' => 0,
            ];
        });
    }

    /**
     * Indicate that the order detail has a high discount.
     */
    public function highDiscount(): static
    {
        return $this->state(function (array $attributes) {
            $totalAmount = $attributes['total_amount'];

            $partnerDiscountPercentage = 30;
            $partnerDiscount = $totalAmount * 0.3;
            $amountAfterPartnerDiscount = $totalAmount - $partnerDiscount;

            $earnDiscountPercentage = 20;
            $earnDiscount = $amountAfterPartnerDiscount * 0.2;
            $amountAfterEarnDiscount = $amountAfterPartnerDiscount - $earnDiscount;

            $dealDiscountPercentage = 15;
            $dealDiscount = $amountAfterEarnDiscount * 0.15;
            $amountAfterDealDiscount = $amountAfterEarnDiscount - $dealDiscount;

            return [
                'partner_discount_percentage' => $partnerDiscountPercentage,
                'partner_discount' => $partnerDiscount,
                'amount_after_partner_discount' => $amountAfterPartnerDiscount,

                'earn_discount_percentage' => $earnDiscountPercentage,
                'earn_discount' => $earnDiscount,
                'amount_after_earn_discount' => $amountAfterEarnDiscount,

                'deal_discount_percentage' => $dealDiscountPercentage,
                'deal_discount' => $dealDiscount,
                'amount_after_deal_discount' => $amountAfterDealDiscount,

                'total_discount' => $partnerDiscount + $earnDiscount + $dealDiscount,
            ];
        });
    }
}
