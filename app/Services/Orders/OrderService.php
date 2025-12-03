<?php

namespace App\Services\Orders;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class OrderService
{
    private const LOG_PREFIX = '[OrderService] ';

    /**
     * Get orders query for a user
     *
     * @param int $userId
     * @param array $filters
     * @return Builder
     */
    public function getOrdersQuery(int $userId, array $filters = []): Builder
    {
        try {
            $query = Order::with(['user', 'OrderDetails'])
                ->where('user_id', $userId);

            if (!empty($filters['platform_id'])) {
                $query->where('platform_id', $filters['platform_id']);
            }

            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (!empty($filters['note'])) {
                $query->where('note', 'LIKE', '%' . $filters['note'] . '%');
            }

            if (!empty($filters['created_by'])) {
                $query->where('created_by', $filters['created_by']);
            }

            if (!empty($filters['user_search'])) {
                $query->whereHas('user', function ($q) use ($filters) {
                    $q->where('email', 'LIKE', '%' . $filters['user_search'] . '%')
                      ->orWhere('name', 'LIKE', '%' . $filters['user_search'] . '%');
                });
            }

            if (!empty($filters['start_date'])) {
                $query->where('created_at', '>=', $filters['start_date']);
            }

            if (!empty($filters['end_date'])) {
                $query->where('created_at', '<=', $filters['end_date']);
            }
            $orderBy = $filters['order_by'] ?? 'created_at';
            $orderDirection = $filters['order_direction'] ?? 'desc';
            $query->orderBy($orderBy, $orderDirection);

            return $query;
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error building orders query: ' . $e->getMessage(), [
                'user_id' => $userId,
                'filters' => $filters
            ]);
            throw $e;
        }
    }

    /**
     * Get orders for a user with optional pagination
     *
     * @param int $userId
     * @param array $filters
     * @param int|null $page
     * @param int $limit
     * @return array
     */
    public function getUserOrders(int $userId, array $filters = [], ?int $page = null, int $limit = 5): array
    {
        try {
            $query = $this->getOrdersQuery($userId, $filters);

            $totalCount = $query->count();
            $orders = !is_null($page)
                ? $query->paginate($limit, ['*'], 'page', $page)
                : $query->get();

            return [
                'orders' => $orders,
                'total' => $totalCount
            ];
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error fetching user orders: ' . $e->getMessage(), [
                'user_id' => $userId,
                'filters' => $filters
            ]);
            throw $e;
        }
    }

    /**
     * Find a specific order for a user
     *
     * @param int $userId
     * @param int $orderId
     * @return Order|null
     */
    public function findUserOrder(int $userId, int $orderId): ?Order
    {
        try {
            return Order::with(['user', 'OrderDetails'])
                ->where('user_id', $userId)
                ->where('id', $orderId)
                ->first();
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error finding user order: ' . $e->getMessage(), [
                'user_id' => $userId,
                'order_id' => $orderId
            ]);
            throw $e;
        }
    }

    /**
     * Get user purchase history query with advanced filtering
     *
     * @param int $userId
     * @param array $selectedStatuses
     * @param array $selectedPlatformIds
     * @param array $selectedDealIds
     * @param array $selectedItemsIds
     * @param array $selectedSectorsIds
     * @return Builder
     */
    public function getUserPurchaseHistoryQuery(
        int $userId,
        array $selectedStatuses = [],
        array $selectedPlatformIds = [],
        array $selectedDealIds = [],
        array $selectedItemsIds = [],
        array $selectedSectorsIds = []
    ): Builder {
        try {
            $query = Order::query();
            $query->where('user_id', $userId);

            if (!empty($selectedStatuses)) {
                $query->whereIn('status', $selectedStatuses);
            }

            if (!empty($selectedPlatformIds)) {
                $query->whereHas('OrderDetails.item', function ($q) use ($selectedPlatformIds) {
                    $q->whereIn('platform_id', $selectedPlatformIds);
                });
            }

            if (!empty($selectedDealIds)) {
                $query->whereHas('OrderDetails.item', function ($q) use ($selectedDealIds) {
                    $q->whereIn('deal_id', $selectedDealIds);
                });
            }

            if (!empty($selectedItemsIds)) {
                $query->whereHas('OrderDetails.item', function ($q) use ($selectedItemsIds) {
                    $q->whereIn('id', $selectedItemsIds);
                });
            }

            if (!empty($selectedSectorsIds)) {
                $query->whereHas('OrderDetails.item.platform.businessSector', function ($q) use ($selectedSectorsIds) {
                    $q->whereIn('id', $selectedSectorsIds);
                });
            }

            $query->orderBy('created_at', 'DESC');

            return $query;
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error building user purchase history query: ' . $e->getMessage(), [
                'user_id' => $userId,
                'selected_statuses' => $selectedStatuses,
                'selected_platform_ids' => $selectedPlatformIds,
                'selected_deal_ids' => $selectedDealIds,
                'selected_items_ids' => $selectedItemsIds,
                'selected_sectors_ids' => $selectedSectorsIds
            ]);
            throw $e;
        }
    }
}

