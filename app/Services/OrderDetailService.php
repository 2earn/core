<?php

namespace App\Services;

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
}

