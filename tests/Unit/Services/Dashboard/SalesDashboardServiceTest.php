<?php

namespace Tests\Unit\Services\Dashboard;

use App\Enums\OrderEnum;
use App\Models\Deal;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Platform;
use App\Models\User;
use App\Services\Dashboard\SalesDashboardService;
use App\Services\OrderDetailService;
use App\Services\Platform\PlatformService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SalesDashboardServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected SalesDashboardService $salesDashboardService;
    protected PlatformService $platformService;
    protected OrderDetailService $orderDetailService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->platformService = $this->createMock(PlatformService::class);
        $this->orderDetailService = $this->createMock(OrderDetailService::class);
        $this->salesDashboardService = new SalesDashboardService(
            $this->platformService,
            $this->orderDetailService
        );
    }

    /**
     * Test getKpiData returns KPI metrics
     */
    public function test_get_kpi_data_works()
    {
        // Arrange
        $user = User::factory()->create();
        $platform = Platform::factory()->create();

        // Create orders with different statuses
        $deal = Deal::factory()->create(['platform_id' => $platform->id]);
        $item = Item::factory()->create(['deal_id' => $deal->id, 'platform_id' => $platform->id]);

        Order::factory()->create(['status' => OrderEnum::Ready->value, 'user_id' => $user->id]);
        Order::factory()->create(['status' => OrderEnum::Dispatched->value, 'user_id' => User::factory()]);
        Order::factory()->create(['status' => OrderEnum::Failed->value, 'user_id' => $user->id]);

        $this->platformService
            ->method('userHasRoleInPlatform')
            ->willReturn(true);

        $filters = [
            'user_id' => $user->id,
            'platform_ids' => [$platform->id],
        ];

        // Act
        $result = $this->salesDashboardService->getKpiData($filters);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('total_sales', $result);
        $this->assertArrayHasKey('orders_in_progress', $result);
        $this->assertArrayHasKey('orders_successful', $result);
        $this->assertArrayHasKey('orders_failed', $result);
        $this->assertArrayHasKey('total_customers', $result);
    }

    /**
     * Test getKpiData throws exception when user doesn't have role
     */
    public function test_get_kpi_data_throws_exception_without_role()
    {
        // Arrange
        $user = User::factory()->create();
        $platform = Platform::factory()->create();

        $this->platformService
            ->method('userHasRoleInPlatform')
            ->willReturn(false);

        $filters = [
            'user_id' => $user->id,
            'platform_ids' => [$platform->id],
        ];

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('User does not have a role in one or more platforms');

        // Act
        $this->salesDashboardService->getKpiData($filters);
    }

    /**
     * Test getTransactions returns transaction data
     */
    public function test_get_transactions_works()
    {
        // Arrange
        $user = User::factory()->create();
        $platform = Platform::factory()->create();

        $this->platformService
            ->method('userHasRoleInPlatform')
            ->willReturn(true);

        $this->orderDetailService
            ->method('getSalesTransactionData')
            ->willReturn(['transactions' => []]);

        $filters = [
            'user_id' => $user->id,
            'platform_ids' => [$platform->id],
        ];

        // Act
        $result = $this->salesDashboardService->getTransactions($filters);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('filters', $result);
        $this->assertArrayHasKey('data', $result);
    }

    /**
     * Test getTransactionsDetails returns transaction details
     */
    public function test_get_transactions_details_works()
    {
        // Arrange
        $user = User::factory()->create();
        $platform = Platform::factory()->create();
        $order = Order::factory()->create();

        $this->platformService
            ->method('userHasRoleInPlatform')
            ->willReturn(true);

        $this->orderDetailService
            ->method('getSalesTransactionDetailsData')
            ->willReturn(['details' => []]);

        $filters = [
            'user_id' => $user->id,
            'platform_id' => $platform->id,
            'order_id' => $order->id,
        ];

        // Act
        $result = $this->salesDashboardService->getTransactionsDetails($filters);

        // Assert
        $this->assertIsArray($result);
    }

    /**
     * Test getSalesEvolutionChart returns chart data
     */
    public function test_get_sales_evolution_chart_works()
    {
        // Arrange
        $user = User::factory()->create();
        $platform = Platform::factory()->create();

        $this->platformService
            ->method('userHasRoleInPlatform')
            ->willReturn(true);

        $this->orderDetailService
            ->method('getSalesEvolutionData')
            ->willReturn([
                ['date' => '2024-01-01', 'revenue' => 1000],
                ['date' => '2024-01-02', 'revenue' => 1500],
            ]);

        $filters = [
            'user_id' => $user->id,
            'platform_id' => $platform->id,
            'view_mode' => 'daily',
        ];

        // Act
        $result = $this->salesDashboardService->getSalesEvolutionChart($filters);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('chart_data', $result);
        $this->assertArrayHasKey('view_mode', $result);
        $this->assertArrayHasKey('start_date', $result);
        $this->assertArrayHasKey('end_date', $result);
        $this->assertArrayHasKey('total_revenue', $result);
    }

    /**
     * Test getTopSellingProducts returns top products
     */
    public function test_get_top_selling_products_works()
    {
        // Arrange
        $user = User::factory()->create();
        $platform = Platform::factory()->create();

        $this->platformService
            ->method('userHasRoleInPlatform')
            ->willReturn(true);

        $this->orderDetailService
            ->method('getTopSellingProducts')
            ->willReturn([
                ['product_id' => 1, 'total_sales' => 100],
                ['product_id' => 2, 'total_sales' => 80],
            ]);

        $filters = [
            'user_id' => $user->id,
            'platform_id' => $platform->id,
        ];

        // Act
        $result = $this->salesDashboardService->getTopSellingProducts($filters);

        // Assert
        $this->assertIsArray($result);
    }

    /**
     * Test getTopSellingDeals returns top deals
     */
    public function test_get_top_selling_deals_works()
    {
        // Arrange
        $user = User::factory()->create();
        $platform = Platform::factory()->create();

        $this->platformService
            ->method('userHasRoleInPlatform')
            ->willReturn(true);

        $filters = [
            'user_id' => $user->id,
            'platform_id' => $platform->id,
            'limit' => 5,
        ];

        // Act
        $result = $this->salesDashboardService->getTopSellingDeals($filters);

        // Assert
        $this->assertIsArray($result);
    }

    /**
     * Test getTopSellingPlatforms returns top platforms
     */
    public function test_get_top_selling_platforms_works()
    {
        // Arrange
        $user = User::factory()->create();

        $filters = [
            'user_id' => $user->id,
            'limit' => 10,
        ];

        // Act
        $result = $this->salesDashboardService->getTopSellingPlatforms($filters);

        // Assert
        $this->assertIsArray($result);
    }
}
