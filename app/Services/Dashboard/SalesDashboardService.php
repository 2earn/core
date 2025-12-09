<?php

namespace App\Services\Dashboard;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Services\Platform\PlatformService;
use Core\Enum\OrderEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesDashboardService
{
    private const LOG_PREFIX = '[SalesDashboardService] ';

    private PlatformService $platformService;

    public function __construct(PlatformService $platformService)
    {
        $this->platformService = $platformService;
    }

    /**
     * Get KPI data for sales dashboard
     *
     * @param array $filters
     * @return array
     * @throws \Exception
     */
    public function getKpiData(array $filters = []): array
    {
        try {
            // Check if user has role in platform when both are provided
            if (!empty($filters['user_id']) && !empty($filters['platform_id'])) {
                if (!$this->platformService->userHasRoleInPlatform($filters['user_id'], $filters['platform_id'])) {
                    Log::warning(self::LOG_PREFIX . 'User does not have role in platform', [
                        'user_id' => $filters['user_id'],
                        'platform_id' => $filters['platform_id']
                    ]);
                    throw new \Exception('User does not have a role in this platform');
                }
            }

            $query = $this->buildBaseQuery($filters);

            // Get total sales (all orders)
            $totalSales = (clone $query)->count();

            // Get orders in progress (Ready status)
            $ordersInProgress = (clone $query)
                ->where('orders.status', OrderEnum::Ready->value)
                ->count();

            // Get successful orders (Dispatched status)
            $ordersSuccessful = (clone $query)
                ->where('orders.status', OrderEnum::Dispatched->value)
                ->count();

            // Get failed orders (Failed status)
            $ordersFailed = (clone $query)
                ->where('orders.status', OrderEnum::Failed->value)
                ->count();

            // Get total unique customers
            $totalCustomers = (clone $query)
                ->distinct('orders.user_id')
                ->count('orders.user_id');

            return [
                'total_sales' => $totalSales,
                'orders_in_progress' => $ordersInProgress,
                'orders_successful' => $ordersSuccessful,
                'orders_failed' => $ordersFailed,
                'total_customers' => $totalCustomers,
            ];
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error fetching KPI data: ' . $e->getMessage(), [
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Build base query with filters
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildBaseQuery(array $filters)
    {
        $query = Order::query();

        // Filter by date range
        if (!empty($filters['start_date'])) {
            $query->where('orders.created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('orders.created_at', '<=', $filters['end_date']);
        }

        // Filter by platform_id through order_details and items
        if (!empty($filters['platform_id'])) {
            $query->whereHas('OrderDetails.item', function ($q) use ($filters) {
                $q->where('items.platform_id', $filters['platform_id']);
            });
        }

        return $query;
    }

    /**
     * Get top-selling products/services
     *
     * @param array $filters (start_date, end_date, platform_id, user_id, deal_id, limit)
     * @return array
     * @throws \Exception
     */
    public function getTopSellingProducts(array $filters = []): array
    {
        try {
            if (!empty($filters['user_id']) && !empty($filters['platform_id'])) {
                if (!$this->platformService->userHasRoleInPlatform($filters['user_id'], $filters['platform_id'])) {
                    Log::warning(self::LOG_PREFIX . 'User does not have role in platform', [
                        'user_id' => $filters['user_id'],
                        'platform_id' => $filters['platform_id']
                    ]);
                    throw new \Exception('User does not have a role in this platform');
                }
            }

            $limit = $filters['limit'] ?? 10;

            $query = OrderDetail::query()
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->join('items', 'order_details.item_id', '=', 'items.id')
                ->where('orders.status', OrderEnum::Dispatched->value);

            if (!empty($filters['start_date'])) {
                $query->where('orders.created_at', '>=', $filters['start_date']);
            }

            if (!empty($filters['end_date'])) {
                $query->where('orders.created_at', '<=', $filters['end_date']);
            }

            if (!empty($filters['platform_id'])) {
                $query->where('items.platform_id', $filters['platform_id']);
            }

            if (!empty($filters['deal_id'])) {
                $query->where('items.deal_id', $filters['deal_id']);
            }

            $topProducts = $query
                ->select('items.name as product_name', DB::raw('SUM(order_details.qty) as sale_count'))
                ->groupBy('items.id', 'items.name')
                ->orderByDesc('sale_count')
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    return [
                        'product_name' => $item->product_name,
                        'sale_count' => (int) $item->sale_count,
                    ];
                });

            Log::info(self::LOG_PREFIX . 'Top-selling products retrieved successfully', [
                'filters' => $filters,
                'count' => $topProducts->count()
            ]);

            return $topProducts->toArray();
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error fetching top-selling products: ' . $e->getMessage(), [
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}

