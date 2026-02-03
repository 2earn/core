<?php

namespace Tests\Unit\Services;

use App\Services\Items\ItemService;
use App\Services\OrderDetailService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrderDetailServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected OrderDetailService $orderDetailService;
    protected ItemService $itemService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->itemService = new ItemService();
        $this->orderDetailService = new OrderDetailService($this->itemService);
    }

    /**
     * Test getTopSellingProducts method
     */
    public function test_get_top_selling_products_works()
    {
        // Arrange
        $platform = \App\Models\Platform::factory()->create();
        $itemA = \App\Models\Item::factory()->create(['platform_id' => $platform->id, 'name' => 'Item A']);
        $itemB = \App\Models\Item::factory()->create(['platform_id' => $platform->id, 'name' => 'Item B']);

        // Create dispatched orders with details
        $order1 = \App\Models\Order::factory()->dispatched()->create(['platform_id' => $platform->id, 'payment_result' => true]);
        $order2 = \App\Models\Order::factory()->dispatched()->create(['platform_id' => $platform->id, 'payment_result' => true]);

        \App\Models\OrderDetail::factory()->create(['order_id' => $order1->id, 'item_id' => $itemA->id, 'qty' => 5, 'amount_after_partner_discount' => 50]);
        \App\Models\OrderDetail::factory()->create(['order_id' => $order2->id, 'item_id' => $itemA->id, 'qty' => 2, 'amount_after_partner_discount' => 20]);
        \App\Models\OrderDetail::factory()->create(['order_id' => $order1->id, 'item_id' => $itemB->id, 'qty' => 1, 'amount_after_partner_discount' => 10]);

        // Act
        $result = $this->orderDetailService->getTopSellingProducts(['platform_id' => $platform->id, 'limit' => 5]);

        // Assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertEquals('Item A', $result[0]['product_name']);
        $this->assertEquals(7, $result[0]['sale_count']);
    }

    /**
     * Test getSalesEvolutionData method
     */
    public function test_get_sales_evolution_data_works()
    {
        // Arrange
        $start = now()->subDays(3)->format('Y-m-d');
        $end = now()->format('Y-m-d');

        $order1 = \App\Models\Order::factory()->create([ 'created_at' => now()->subDays(2)->startOfDay(), 'payment_result' => true ]);
        $order2 = \App\Models\Order::factory()->create([ 'created_at' => now()->subDays(1)->startOfDay(), 'payment_result' => true ]);

        \App\Models\OrderDetail::factory()->create(['order_id' => $order1->id, 'amount_after_deal_discount' => 100]);
        \App\Models\OrderDetail::factory()->create(['order_id' => $order2->id, 'amount_after_deal_discount' => 50]);

        // Act
        $result = $this->orderDetailService->getSalesEvolutionData(['start_date' => $start, 'end_date' => $end, 'view_mode' => 'daily']);

        // Assert
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $dates = array_column($result, 'date');
        $this->assertContains(now()->subDays(2)->format('Y-m-d'), $dates);
    }

    /**
     * Test getSalesTransactionData method
     */
    public function test_get_sales_transaction_data_works()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $platform = \App\Models\Platform::factory()->create();

        $order = \App\Models\Order::factory()->paid()->create([ 'user_id' => $user->id, 'platform_id' => $platform->id, 'payment_result' => true ]);

        // Act
        $result = $this->orderDetailService->getSalesTransactionData(['per_page' => 10, 'page' => 1]);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('total', $result);
    }

    /**
     * Test getSalesTransactionDetailsData method
     */
    public function test_get_sales_transaction_details_data_works()
    {
        // Arrange
        $order = \App\Models\Order::factory()->create();
        \App\Models\OrderDetail::factory()->count(3)->create(['order_id' => $order->id]);

        // Act
        $result = $this->orderDetailService->getSalesTransactionDetailsData(['order_id' => $order->id]);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals(3, $result['count']);
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
