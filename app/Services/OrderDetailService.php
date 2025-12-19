<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Services\Items\ItemService;
use Core\Enum\OrderEnum;
use Illuminate\Support\Facades\Log;

class OrderDetailService
{
    private const LOG_PREFIX = '[OrderDetailService] ';

    private ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * Get top-selling products/services
     *
     * @param array $filters (start_date, end_date, platform_id, deal_id, limit)
     * @return array
     */
    public function getTopSellingProducts(array $filters = []): array
    {
        try {
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

            $topProducts = $this->itemService->aggregateTopSellingItems($query, $limit);

            Log::info(self::LOG_PREFIX . 'Top-selling products retrieved successfully', [
                'filters' => $filters,
                'count' => count($topProducts)
            ]);

            return $topProducts;
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error fetching top-selling products: ' . $e->getMessage(), [
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Get sales evolution data
     *
     * @param array $filters (start_date, end_date, platform_id, view_mode)
     * @return array
     */
    public function getSalesEvolutionData(array $filters = []): array
    {
        try {
            $viewMode = $filters['view_mode'] ?? 'daily';
            $startDate = $filters['start_date'] ?? now()->subDays(30)->format('Y-m-d');
            $endDate = $filters['end_date'] ?? now()->format('Y-m-d');

            $dateGroupBy = $this->getDateGroupByForViewMode($viewMode);

            $query = OrderDetail::query()
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->where('orders.payment_result', true)
                ->whereBetween('orders.created_at', [$startDate, $endDate]);

            if (!empty($filters['platform_id'])) {
                $query->join('items', 'order_details.item_id', '=', 'items.id')
                    ->where('items.platform_id', $filters['platform_id']);
            }

            $results = $query
                ->selectRaw("$dateGroupBy as date, SUM(order_details.amount_after_deal_discount) as revenue")
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();

            Log::info(self::LOG_PREFIX . 'Sales evolution data retrieved successfully', [
                'filters' => $filters,
                'count' => $results->count()
            ]);

            return $results->toArray();
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error fetching sales evolution data: ' . $e->getMessage(), [
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function getSalesTransactionData(array $filters = []): array
    {
        try {
            $startDate = $filters['start_date'] ?? now()->subDays(30)->format('Y-m-d');
            $endDate = $filters['end_date'] ?? now()->format('Y-m-d');


            $query = Order::query()
                ->where('orders.payment_result', true)
                ->whereBetween('orders.created_at', [$startDate, $endDate]);

            if (!empty($filters['platform_id'])) {
                $query->where('orders.platform_id', $filters['platform_id']);
            }
            if (!empty($filters['order_id'])) {
                $query->where('orders.id', $filters['order_id']);
            }

            if (!empty($filters['start_date'])) {
                $query->where('orders.created_at', '>=', $filters['start_date']);
            }

            if (!empty($filters['end_date'])) {
                $query->where('orders.created_at', '<=', $filters['end_date']);
            }

            if (!empty($filters['status'])) {
                $query->where('orders.status', '<=', $filters['status']);
            }

            if (!empty($filters['note'])) {
                $query->where('orders.note', 'like', '%' . $filters['note'] . '%');
            }

            if (!empty($filters['country'])) {
                $query->join('users', 'orders.user_id', '=', 'users.id')
                    ->where('users.idCountry', $filters['country']);
            }

            if (!empty($filters['user_id'])) {
                $query->where('orders.user_id', $filters['user_id']);
            }

            $query->select("*")
                ->groupBy('orders.id')
                ->orderBy('orders.created_at', 'asc');

            if (!empty($filters['limit'])) {
                $query->limit($filters['limit']);
            }

            $results = $query->get();

            Log::info(self::LOG_PREFIX . 'Sales evolution data retrieved successfully', [
                'filters' => $filters,
                'count' => $results->count()
            ]);

            return [
                'filters' => $filters,
                'data' => $results->toArray(),
                'count' => $results->count()
            ];

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error fetching sales evolution data: ' . $e->getMessage(), [
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    public function getSalesTransactionDetailsData(array $filters = []): array
    {
        try {


            $query = OrderDetail::query()
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->join('items', 'order_details.item_id', '=', 'items.id');


            if (!empty($filters['order_id'])) {
                $query->where('orders.id', $filters['order_id']);
            }

            $query->select("*")
                ->orderBy('order_details.created_at', 'asc');

            $results = $query->get();

            Log::info(self::LOG_PREFIX . 'Sales evolution data retrieved successfully', [
                'filters' => $filters,
                'count' => $results->count()
            ]);

            return [
                'filters' => $filters,
                'data' => $results->toArray(),
                'count' => $results->count()
            ];

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error fetching sales evolution data: ' . $e->getMessage(), [
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Get date grouping SQL for view mode
     *
     * @param string $viewMode
     * @return string
     */
    private function getDateGroupByForViewMode(string $viewMode): string
    {
        return match ($viewMode) {
            'daily' => "DATE(orders.created_at)",
            'weekly' => "DATE_FORMAT(orders.created_at, '%Y-%u')",
            'monthly' => "DATE_FORMAT(orders.created_at, '%Y-%m')",
            default => "DATE(orders.created_at)",
        };
    }
}

