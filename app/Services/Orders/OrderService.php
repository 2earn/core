<?php

namespace App\Services\Orders;

use App\Models\Order;
use App\Enums\OrderEnum;
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
            $query = Order::query();

            if ($startDate) {
                $query->where('payment_datetime', '>=', $startDate);
            }

            if ($endDate) {
                $query->where('payment_datetime', '<=', $endDate);
            }

            if ($userId) {
                $query->where('user_id', $userId);
            }

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

            $baseQuery = clone $query;

            $totalOrders = $query->count();

            $ordersByStatus = (clone $baseQuery)
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->status->value => $item->count];
                });

            $totalRevenue = (clone $baseQuery)
                ->sum('total_order') ?? 0;

            $totalPaid = (clone $baseQuery)
                ->whereNotNull('payment_datetime')
                ->sum('paid_cash') ?? 0;

            $totalItemsSold = (clone $baseQuery)
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->sum('order_details.qty') ?? 0;

            $averageOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;

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

    /**
     * Get all orders paginated
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllOrdersPaginated(int $perPage = 5)
    {
        try {
            return Order::orderBy('created_at', 'desc')->paginate($perPage);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error fetching paginated orders: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get count of pending orders for a user
     *
     * @param int $userId
     * @param array $statuses
     * @return int
     */
    public function getPendingOrdersCount(int $userId, array $statuses = []): int
    {
        try {
            return Order::where('user_id', $userId)
                ->whereIn('status', $statuses)
                ->count();
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error counting pending orders: ' . $e->getMessage(), [
                'user_id' => $userId,
                'statuses' => $statuses
            ]);
            throw $e;
        }
    }

    /**
     * Get pending order IDs for a user
     *
     * @param int $userId
     * @param array $statuses
     * @return array
     */
    public function getPendingOrderIds(int $userId, array $statuses = []): array
    {
        try {
            return Order::where('user_id', $userId)
                ->whereIn('status', $statuses)
                ->pluck('id')
                ->toArray();
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error fetching pending order IDs: ' . $e->getMessage(), [
                'user_id' => $userId,
                'statuses' => $statuses
            ]);
            throw $e;
        }
    }

    /**
     * Get orders by IDs for a specific user with specific statuses
     *
     * @param int $userId
     * @param array $orderIds
     * @param array $statuses
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOrdersByIdsForUser(int $userId, array $orderIds, array $statuses = [])
    {
        try {
            $query = Order::with(['orderDetails.item.deal.platform', 'platform', 'user'])
                ->whereIn('id', $orderIds)
                ->where('user_id', $userId);

            if (!empty($statuses)) {
                $query->whereIn('status', $statuses);
            }

            return $query->get();
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error fetching orders by IDs for user: ' . $e->getMessage(), [
                'user_id' => $userId,
                'order_ids' => $orderIds,
                'statuses' => $statuses
            ]);
            throw $e;
        }
    }

    /**
     * Find order by ID for a specific user
     *
     * @param int $orderId
     * @param int $userId
     * @return Order|null
     */
    public function findOrderForUser(int $orderId, int $userId): ?Order
    {
        try {
            return Order::where('id', $orderId)
                ->where('user_id', $userId)
                ->first();
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error finding order for user: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'user_id' => $userId
            ]);
            throw $e;
        }
    }

    /**
     * Create orders from grouped cart items
     *
     * @param int $userId
     * @param array $ordersData Array of cart items grouped by platform ID
     * @param string $status
     * @return array Array of created order IDs
     */
    public function createOrdersFromCartItems(int $userId, array $ordersData, string $status = 'Ready'): array
    {
        try {
            $createdOrderIds = [];

            foreach ($ordersData as $platformId => $platformItems) {
                $order = Order::create([
                    'user_id' => $userId,
                    'platform_id' => $platformId,
                    'note' => 'Product buy platform ' . $platformId,
                    'status' => OrderEnum::Ready,
                ]);

                foreach ($platformItems as $cartItem) {
                    $order->orderDetails()->create([
                        'qty' => $cartItem->qty,
                        'unit_price' => $cartItem->unit_price,
                        'total_amount' => $cartItem->total_amount,
                        'item_id' => $cartItem->item_id,
                        'shipping' => $cartItem->shipping,
                    ]);
                }

                $createdOrderIds[] = $order->id;
            }

            return $createdOrderIds;
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error creating orders from cart items: ' . $e->getMessage(), [
                'user_id' => $userId,
                'platforms_count' => count($ordersData)
            ]);
            throw $e;
        }
    }

    /**
     * Create a single order with details
     *
     * @param int $userId
     * @param int $platformId
     * @param array $cartItems
     * @param string $status
     * @return Order
     */
    public function createOrderWithDetails(int $userId, int $platformId, array $cartItems, string $status = 'Ready'): Order
    {
        try {
            $order = Order::create([
                'user_id' => $userId,
                'platform_id' => $platformId,
                'note' => 'Product buy platform ' . $platformId,
                'status' => $status,
            ]);

            foreach ($cartItems as $cartItem) {
                $order->orderDetails()->create([
                    'qty' => $cartItem->qty,
                    'unit_price' => $cartItem->unit_price,
                    'total_amount' => $cartItem->total_amount,
                    'item_id' => $cartItem->item_id,
                    'shipping' => $cartItem->shipping,
                ]);
            }

            return $order;
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error creating order with details: ' . $e->getMessage(), [
                'user_id' => $userId,
                'platform_id' => $platformId
            ]);
            throw $e;
        }
    }

    /**
     * Cancel an order by deleting it
     *
     * @param int $orderId
     * @return array Result array with success status and message
     */
    public function cancelOrder(int $orderId): array
    {
        try {
            $order = Order::findOrFail($orderId);

            // Delete the order (this will cascade delete order details if configured)
            $order->delete();

            return [
                'success' => true,
                'message' => 'Order canceled successfully'
            ];
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error(self::LOG_PREFIX . 'Order not found for cancellation: ' . $e->getMessage(), [
                'order_id' => $orderId
            ]);
            return [
                'success' => false,
                'message' => 'Order not found'
            ];
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error canceling order: ' . $e->getMessage(), [
                'order_id' => $orderId
            ]);
            return [
                'success' => false,
                'message' => 'Error canceling order'
            ];
        }
    }

    /**
     * Make an order ready for processing
     *
     * @param int $orderId
     * @return array Result array with success status, message, and order
     */
    public function makeOrderReady(int $orderId): array
    {
        try {
            $order = Order::findOrFail($orderId);

            // Check if order is in New status and has order details
            if ($order->status->value != OrderEnum::New->value) {
                return [
                    'success' => false,
                    'message' => 'Order is not in New status',
                    'order' => $order
                ];
            }

            if ($order->orderDetails->count() === 0) {
                return [
                    'success' => false,
                    'message' => 'Empty order',
                    'order' => $order
                ];
            }

            // Update order status to Ready
            $order->updateStatus(OrderEnum::Ready);

            return [
                'success' => true,
                'message' => 'Order is ready',
                'order' => $order
            ];
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error(self::LOG_PREFIX . 'Order not found for making ready: ' . $e->getMessage(), [
                'order_id' => $orderId
            ]);
            return [
                'success' => false,
                'message' => 'Order not found'
            ];
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error making order ready: ' . $e->getMessage(), [
                'order_id' => $orderId
            ]);
            return [
                'success' => false,
                'message' => 'Error making order ready'
            ];
        }
    }

    /**
     * Validate and execute an order
     *
     * @param int $orderId
     * @param mixed $simulation The simulation result
     * @param bool $isValidated Whether order is already validated
     * @return array Result array with success status, message, order status, and order
     */
    public function validateOrder(int $orderId, $simulation, bool $isValidated): array
    {
        try {
            // Check if already validated
            if ($isValidated) {
                return [
                    'success' => false,
                    'message' => 'Order already validated',
                    'shouldRedirect' => false
                ];
            }

            // Run the order through Ordering system
            $status = \App\Services\Orders\Ordering::run($simulation);

            // Refresh order to get latest status
            $order = Order::findOrFail($orderId);

            return [
                'success' => true,
                'orderStatus' => $status,
                'order' => $order,
                'isDispatched' => $order->status->value == OrderEnum::Dispatched->value,
                'message' => $order->status->value == OrderEnum::Dispatched->value
                    ? 'Ordering succeeded'
                    : 'Ordering Failed'
            ];
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error(self::LOG_PREFIX . 'Order not found for validation: ' . $e->getMessage(), [
                'order_id' => $orderId
            ]);
            return [
                'success' => false,
                'message' => 'Order not found'
            ];
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error validating order: ' . $e->getMessage(), [
                'order_id' => $orderId
            ]);
            return [
                'success' => false,
                'message' => 'Error validating order'
            ];
        }
    }
}


