<?php

namespace App\Services\Dashboard;

use App\Models\Order;
use App\Services\OrderDetailService;
use App\Services\Platform\PlatformService;
use Core\Enum\OrderEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesDashboardService
{
    private const LOG_PREFIX = '[SalesDashboardService] ';

    private PlatformService $platformService;
    private OrderDetailService $orderDetailService;

    public function __construct(
        PlatformService    $platformService,
        OrderDetailService $orderDetailService
    )
    {
        $this->platformService = $platformService;
        $this->orderDetailService = $orderDetailService;
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
            if (!empty($filters['user_id']) && !empty($filters['platform_ids'])) {
                foreach ($filters['platform_ids'] as $platformId) {
                    if (!$this->platformService->userHasRoleInPlatform($filters['user_id'], $platformId)) {
                        Log::warning(self::LOG_PREFIX . 'User does not have role in platform', [
                            'user_id' => $filters['user_id'],
                            'platform_id' => $platformId
                        ]);
                        throw new \Exception('User does not have a role in one or more platforms');
                    }
                }
            }

            $query = $this->buildBaseQuery($filters);

            $totalSales = (clone $query)->count();

            $ordersInProgress = (clone $query)
                ->where('orders.status', OrderEnum::Ready->value)
                ->count();

            $ordersSuccessful = (clone $query)
                ->where('orders.status', OrderEnum::Dispatched->value)
                ->count();

            $ordersFailed = (clone $query)
                ->where('orders.status', OrderEnum::Failed->value)
                ->count();

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

    public function getTransactions(array $filters = []): array
    {
        try {
            // Handle platform_ids array - check if user has role in any of the platforms
            if (!empty($filters['user_id']) && !empty($filters['platform_ids'])) {
                foreach ($filters['platform_ids'] as $platformId) {
                    if (!$this->platformService->userHasRoleInPlatform($filters['user_id'], $platformId)) {
                        Log::warning(self::LOG_PREFIX . 'User does not have role in platform', [
                            'user_id' => $filters['user_id'],
                            'platform_id' => $platformId
                        ]);
                        throw new \Exception('User does not have a role in one or more platforms');
                    }
                }
            }

            $startDate = $filters['start_date'] ?? now()->subDays(30)->format('Y-m-d');
            $endDate = $filters['end_date'] ?? now()->addDay()->format('Y-m-d');

            $filtersUpdated = [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'platform_ids' => $filters['platform_ids'] ?? null,
                'order_id' => $filters['order_id'] ?? null,
                'status' => $filters['status'] ?? null,
                'note' => $filters['note'] ?? null,
                'country' => $filters['country'] ?? null,
                'user_id' => $filters['user_id'] ?? null,
                'page' => $filters['page'] ?? 1,
                'per_page' => $filters['per_page'] ?? 15,
            ];
            return ['filters' => $filtersUpdated, 'data' => $this->orderDetailService->getSalesTransactionData($filtersUpdated)];

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error fetching sales evolution chart: ' . $e->getMessage(), [
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getTransactionsDetails(array $filters = []): array
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

            $results = $this->orderDetailService->getSalesTransactionDetailsData([
                'order_id' => $filters['order_id'] ?? null,
            ]);


            return $results;

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error fetching sales evolution chart: ' . $e->getMessage(), [
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

            $results = $this->orderDetailService->getSalesEvolutionData([
                'start_date' => $startDate,
                'end_date' => $endDate,
                'platform_id' => $filters['platform_id'] ?? null,
                'view_mode' => $viewMode,
            ]);

            $chartData = array_map(function ($item) use ($dateFormat, $viewMode) {
                if ($viewMode === 'weekly') {
                    return [
                        'date' => 'Week ' . $item['date'],
                        'revenue' => (float)$item['revenue']
                    ];
                } else {
                    return [
                        'date' => \Carbon\Carbon::parse($item['date'])->format($dateFormat),
                        'revenue' => (float)$item['revenue']
                    ];
                }
            }, $results);

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


    /**
     * Build base query with filters
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildBaseQuery(array $filters)
    {
        $query = Order::query();

        if (!empty($filters['start_date'])) {
            $query->where('orders.created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('orders.created_at', '<=', $filters['end_date']);
        }

        if (!empty($filters['platform_ids']) && is_array($filters['platform_ids'])) {
            $query->whereHas('OrderDetails.item', function ($q) use ($filters) {
                $q->whereIn('items.platform_id', $filters['platform_ids']);
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

            return $this->orderDetailService->getTopSellingProducts($filters);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error in getTopSellingProducts: ' . $e->getMessage(), [
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Get top-selling deals based on sales volume or revenue
     *
     * @param array $filters (start_date, end_date, platform_id, limit)
     * @return array
     * @throws \Exception
     */
    public function getTopSellingDeals(array $filters = []): array
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

            $limit = $filters['limit'] ?? 5;

            // Build query for successful orders (Dispatched status)
            $query = Order::query()
                ->select(
                    'deals.id as deal_id',
                    'deals.name as deal_name',
                    \DB::raw('SUM(order_details.qty) as total_sales'),
                    \DB::raw('COUNT(DISTINCT orders.id) as sales_count')
                )
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->join('items', 'order_details.item_id', '=', 'items.id')
                ->join('deals', 'items.deal_id', '=', 'deals.id')
                ->where('orders.status', OrderEnum::Dispatched->value);

            // Filter by date range
            if (!empty($filters['start_date'])) {
                $query->where('orders.created_at', '>=', $filters['start_date']);
            }

            if (!empty($filters['end_date'])) {
                $query->where('orders.created_at', '<=', $filters['end_date']);
            }

            // Filter by platform_id
            if (!empty($filters['platform_id'])) {
                $query->where('deals.platform_id', $filters['platform_id']);
            }

            // Group by deal and order by total sales
            $topDeals = $query
                ->groupBy('deals.id', 'deals.name')
                ->orderByDesc('total_sales')
                ->limit($limit)
                ->get()
                ->map(function ($deal) {
                    return [
                        'deal_id' => $deal->deal_id,
                        'deal_name' => $deal->deal_name,
                        'total_sales' => (int)$deal->total_sales,
                        'sales_count' => (int)$deal->sales_count,
                    ];
                })
                ->toArray();

            Log::info(self::LOG_PREFIX . 'Top-selling deals retrieved successfully', [
                'filters' => $filters,
                'count' => count($topDeals)
            ]);

            return $topDeals;
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error in getTopSellingDeals: ' . $e->getMessage(), [
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Get top-selling platforms ranked by sales
     *
     * @param array $filters (user_id, start_date, end_date, limit)
     * @return array
     * @throws \Exception
     */
    public function getTopSellingPlatforms(array $filters = []): array
    {
        try {
            $limit = $filters['limit'] ?? 10;
            $userId = $filters['user_id'] ?? null;

            // Build query to get platforms ranked by total sales from commission_break_downs
            $query = DB::table('commission_break_downs')
                ->select(
                    'platforms.id as platform_id',
                    'platforms.name as platform_name',
                    DB::raw('SUM(commission_break_downs.purchase_value) as total_sales')
                )
                ->join('platforms', 'commission_break_downs.platform_id', '=', 'platforms.id')
                ->whereNotNull('commission_break_downs.platform_id')
                ->whereNotNull('commission_break_downs.purchase_value');

            // Filter by user access - only show platforms the user has a role in
            if (!empty($userId)) {
                $query->where(function ($q) use ($userId) {
                    $q->where('platforms.owner_id', $userId)
                        ->orWhere('platforms.marketing_manager_id', $userId)
                        ->orWhere('platforms.financial_manager_id', $userId);
                });
            }

            // Filter by date range
            if (!empty($filters['start_date'])) {
                $query->where('commission_break_downs.created_at', '>=', $filters['start_date']);
            }

            if (!empty($filters['end_date'])) {
                $query->where('commission_break_downs.created_at', '<=', $filters['end_date']);
            }

            // Group by platform and order by total sales descending
            $topPlatforms = $query
                ->groupBy('platforms.id', 'platforms.name')
                ->orderByDesc('total_sales')
                ->limit($limit)
                ->get()
                ->map(function ($platform) {
                    return [
                        'platform_id' => $platform->platform_id,
                        'platform_name' => $platform->platform_name,
                        'total_sales' => formatSolde((float)$platform->total_sales),
                    ];
                })
                ->toArray();

            Log::info(self::LOG_PREFIX . 'Top-selling platforms retrieved successfully', [
                'filters' => $filters,
                'user_id' => $userId,
                'count' => count($topPlatforms)
            ]);

            return $topPlatforms;
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error in getTopSellingPlatforms: ' . $e->getMessage(), [
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}

