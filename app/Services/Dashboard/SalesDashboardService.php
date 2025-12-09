<?php

namespace App\Services\Dashboard;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Services\Platform\PlatformService;
use Core\Enum\OrderEnum;
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

    public function getSalesEvolutionChart(array $filters = []): array
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

            $viewMode = $filters['view_mode'] ?? 'daily';
            $startDate = $filters['start_date'] ?? now()->subDays(30)->format('Y-m-d');
            $endDate = $filters['end_date'] ?? now()->format('Y-m-d');

            $dateFormat = $this->getDateFormatByViewMode($viewMode);
            $dateGroupBy = $this->getDateGroupByViewMode($viewMode);

            $query = OrderDetail::query()
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->where('orders.payment_result', true)
                ->whereBetween('orders.created_at', [$startDate, $endDate]);

            if (!empty($filters['platform_id'])) {
                $query->join('items', 'order_details.item_id', '=', 'items.id')
                    ->where('items.platform_id', $filters['platform_id']);
            }

            $chartData = $query
                ->selectRaw("$dateGroupBy as date, SUM(order_details.amount_after_deal_discount) as revenue")
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get()
                ->map(function ($item) use ($dateFormat) {
                    return [
                        'date' => \Carbon\Carbon::parse($item->date)->format($dateFormat),
                        'revenue' => (float) $item->revenue
                    ];
                })
                ->toArray();

            return [
                'chart_data' => $chartData,
                'view_mode' => $viewMode,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_revenue' => array_sum(array_column($chartData, 'revenue')),
            ];
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error fetching sales evolution chart: ' . $e->getMessage(), [
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function getDateFormatByViewMode(string $viewMode): string
    {
        return match ($viewMode) {
            'daily' => 'Y-m-d',
            'weekly' => 'Y-W',
            'monthly' => 'Y-m',
            default => 'Y-m-d',
        };
    }

    private function getDateGroupByViewMode(string $viewMode): string
    {
        return match ($viewMode) {
            'daily' => "DATE(orders.created_at)",
            'weekly' => "DATE_FORMAT(orders.created_at, '%Y-%u')",
            'monthly' => "DATE_FORMAT(orders.created_at, '%Y-%m')",
            default => "DATE(orders.created_at)",
        };
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

