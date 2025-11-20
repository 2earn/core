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

            // Apply platform filter if provided
            if (!empty($filters['platform_id'])) {
                $query->where('platform_id', $filters['platform_id']);
            }

            // Apply status filter if provided
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            // Apply date range filter if provided
            if (!empty($filters['start_date'])) {
                $query->where('created_at', '>=', $filters['start_date']);
            }

            if (!empty($filters['end_date'])) {
                $query->where('created_at', '<=', $filters['end_date']);
            }

            // Apply ordering
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
}

