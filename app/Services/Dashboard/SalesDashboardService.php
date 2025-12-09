<?php

namespace App\Services\Dashboard;

use App\Models\Order;
use App\Models\OrderDetail;
use Core\Enum\OrderEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesDashboardService
{
    private const LOG_PREFIX = '[SalesDashboardService] ';

    /**
     * Get KPI data for sales dashboard
     *
     * @param array $filters
     * @return array
     */
    public function getKpiData(array $filters = []): array
    {
        try {
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
}

