<?php

namespace Tests\Unit\Services;

use App\Services\OrderDetailService;
use Tests\TestCase;

class OrderDetailServiceTest extends TestCase
{

    protected OrderDetailService $orderDetailService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderDetailService = new OrderDetailService();
    }

    /**
     * Test getTopSellingProducts method
     * TODO: Implement actual test logic
     */
    public function test_get_top_selling_products_works()
    {
        // Arrange
        // TODO: Set up test data

        // Act
        // $result = $this->service->getTopSellingProducts();

        // Assert
        // TODO: Add assertions
        $this->markTestIncomplete('Test for getTopSellingProducts not yet implemented');
    }

    /**
     * Test getSalesEvolutionData method
     * TODO: Implement actual test logic
     */
    public function test_get_sales_evolution_data_works()
    {
        // Arrange
        // TODO: Set up test data

        // Act
        // $result = $this->service->getSalesEvolutionData();

        // Assert
        // TODO: Add assertions
        $this->markTestIncomplete('Test for getSalesEvolutionData not yet implemented');
    }

    /**
     * Test getSalesTransactionData method
     * TODO: Implement actual test logic
     */
    public function test_get_sales_transaction_data_works()
    {
        // Arrange
        // TODO: Set up test data

        // Act
        // $result = $this->service->getSalesTransactionData();

        // Assert
        // TODO: Add assertions
        $this->markTestIncomplete('Test for getSalesTransactionData not yet implemented');
    }

    /**
     * Test getSalesTransactionDetailsData method
     * TODO: Implement actual test logic
     */
    public function test_get_sales_transaction_details_data_works()
    {
        // Arrange
        // TODO: Set up test data

        // Act
        // $result = $this->service->getSalesTransactionDetailsData();

        // Assert
        // TODO: Add assertions
        $this->markTestIncomplete('Test for getSalesTransactionDetailsData not yet implemented');
    }

    /**
     * Test getSumOfPaidItemQuantities returns correct sum
     */
    public function test_get_sum_of_paid_item_quantities_returns_correct_sum()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $item = \App\Models\Item::factory()->create();
        $order1 = \App\Models\Order::factory()->create([
            'user_id' => $user->id,
            'status' => \App\Enums\OrderEnum::Paid->value,
        ]);
        $order2 = \App\Models\Order::factory()->create([
            'user_id' => $user->id,
            'status' => \App\Enums\OrderEnum::Paid->value,
        ]);
        \App\Models\OrderDetail::factory()->create([
            'order_id' => $order1->id,
            'item_id' => $item->id,
            'qty' => 3,
        ]);
        \App\Models\OrderDetail::factory()->create([
            'order_id' => $order2->id,
            'item_id' => $item->id,
            'qty' => 5,
        ]);

        // Act
        $result = $this->orderDetailService->getSumOfPaidItemQuantities($item->id);

        // Assert
        $this->assertEquals(8, $result);
    }

    /**
     * Test getSumOfPaidItemQuantities returns zero when no paid orders
     */
    public function test_get_sum_of_paid_item_quantities_returns_zero_when_no_paid_orders()
    {
        // Arrange
        $item = \App\Models\Item::factory()->create();

        // Act
        $result = $this->orderDetailService->getSumOfPaidItemQuantities($item->id);

        // Assert
        $this->assertEquals(0, $result);
    }
}
