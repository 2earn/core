<?php

namespace App\Services\Orders;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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

    /**
     * Get order dashboard statistics by deal and product
     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @param int|null $dealId
     * @param int|null $productId
     * @param int|null $userId
     * @return array
     */
    public function getOrderDashboardStatistics(
        ?string $startDate = null,
        ?string $endDate = null,
        ?int $dealId = null,
        ?int $productId = null,
        ?int $userId = null
    ): array
    {
        try {
            // Base query for orders
            $query = Order::query();

            // Apply date filters
            if ($startDate) {
                $query->where('payment_datetime', '>=', $startDate);
            }

            if ($endDate) {
                $query->where('payment_datetime', '<=', $endDate);
            }

            // Apply user filter
            if ($userId) {
                $query->where('user_id', $userId);
            }

            // Filter by deal or product through order_details -> items
            if ($dealId || $productId) {
                $query->whereHas('OrderDetails', function ($q) use ($dealId, $productId) {
                    $q->whereHas('item', function ($itemQuery) use ($dealId, $productId) {
                        if ($dealId) {
                            $itemQuery->where('deal_id', $dealId);
                        }
                        if ($productId) {
                            $itemQuery->where('id', $productId);
                        }
                    });
                });
            }

            // Clone query for different calculations
            $baseQuery = clone $query;

            // Total orders count
            $totalOrders = $query->count();

            // Orders by status
            $ordersByStatus = (clone $baseQuery)
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->status->value => $item->count];
                });

            // Total revenue (sum of total_order)
            $totalRevenue = (clone $baseQuery)
                ->sum('total_order') ?? 0;

            // Total paid amount
            $totalPaid = (clone $baseQuery)
                ->whereNotNull('payment_datetime')
                ->sum('paid_cash') ?? 0;

            // Total items sold (sum of quantities)
            $totalItemsSold = (clone $baseQuery)
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->sum('order_details.qty') ?? 0;

            // Average order value
            $averageOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;

            // Orders by deal (top 10)
            $ordersByDeal = DB::table('orders')
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->join('items', 'order_details.item_id', '=', 'items.id')
                ->join('deals', 'items.deal_id', '=', 'deals.id')
                ->when($startDate, function ($q) use ($startDate) {
                    return $q->where('orders.payment_datetime', '>=', $startDate);
                })
                ->when($endDate, function ($q) use ($endDate) {
                    return $q->where('orders.payment_datetime', '<=', $endDate);
                })
                ->when($userId, function ($q) use ($userId) {
                    return $q->where('orders.user_id', $userId);
                })
                ->when($dealId, function ($q) use ($dealId) {
                    return $q->where('deals.id', $dealId);
                })
                ->select(
                    'deals.id as deal_id',
                    'deals.name as deal_name',
                    DB::raw('COUNT(DISTINCT orders.id) as orders_count'),
                    DB::raw('SUM(order_details.total_amount) as total_revenue'),
                    DB::raw('SUM(order_details.qty) as items_sold')
                )
                ->groupBy('deals.id', 'deals.name')
                ->orderBy('total_revenue', 'desc')
                ->limit(10)
                ->get();

            // Top products
            $topProducts = DB::table('orders')
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->join('items', 'order_details.item_id', '=', 'items.id')
                ->when($startDate, function ($q) use ($startDate) {
                    return $q->where('orders.payment_datetime', '>=', $startDate);
                })
                ->when($endDate, function ($q) use ($endDate) {
                    return $q->where('orders.payment_datetime', '<=', $endDate);
                })
                ->when($userId, function ($q) use ($userId) {
                    return $q->where('orders.user_id', $userId);
                })
                ->when($dealId, function ($q) use ($dealId) {
                    return $q->where('items.deal_id', $dealId);
                })
                ->when($productId, function ($q) use ($productId) {
                    return $q->where('items.id', $productId);
                })
                ->select(
                    'items.id as product_id',
                    'items.name as product_name',
                    'items.ref as product_ref',
                    DB::raw('SUM(order_details.qty) as quantity_sold'),
                    DB::raw('SUM(order_details.total_amount) as total_revenue'),
                    DB::raw('COUNT(DISTINCT orders.id) as orders_count')
                )
                ->groupBy('items.id', 'items.name', 'items.ref')
                ->orderBy('total_revenue', 'desc')
                ->limit(10)
                ->get();

            // Recent orders list
            $ordersList = Order::query()
                ->when($startDate, function ($q) use ($startDate) {
                    return $q->where('payment_datetime', '>=', $startDate);
                })
                ->when($endDate, function ($q) use ($endDate) {
                    return $q->where('payment_datetime', '<=', $endDate);
                })
                ->when($userId, function ($q) use ($userId) {
                    return $q->where('user_id', $userId);
                })
                ->when($dealId || $productId, function ($q) use ($dealId, $productId) {
                    $q->whereHas('OrderDetails', function ($detailQuery) use ($dealId, $productId) {
                        $detailQuery->whereHas('item', function ($itemQuery) use ($dealId, $productId) {
                            if ($dealId) {
                                $itemQuery->where('deal_id', $dealId);
                            }
                            if ($productId) {
                                $itemQuery->where('id', $productId);
                            }
                        });
                    });
                })
                ->whereNotNull('payment_datetime')
                ->select('id', 'payment_datetime', 'total_order', 'paid_cash', 'status', 'user_id')
                ->with('user:id,name,email')
                ->orderBy('payment_datetime', 'desc')
                ->limit(50)
                ->get();

            return [
                'summary' => [
                    'total_orders' => $totalOrders,
                    'total_revenue' => round($totalRevenue, 2),
                    'total_paid' => round($totalPaid, 2),
                    'total_items_sold' => (int)$totalItemsSold,
                    'average_order_value' => $averageOrderValue,
                ],
                'orders_by_status' => $ordersByStatus,
                'orders_by_deal' => $ordersByDeal,
                'top_products' => $topProducts,
                'orders_list' => $ordersList,
            ];
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error getting order dashboard statistics: ' . $e->getMessage(), [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'deal_id' => $dealId,
                'product_id' => $productId,
                'user_id' => $userId
            ]);
            throw $e;
        }
    }

    /**
     * Create a new order
     *
     * @param array $data
     * @return Order
     */
    public function createOrder(array $data): Order
    {
        try {
            return Order::create($data);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error creating order: ' . $e->getMessage(), ['data' => $data]);
            throw $e;
        }
    }
}


