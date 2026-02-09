<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Orders\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Get user orders
     */
    public function getUserOrders(Request $request, int $userId)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
            'platform_id' => 'nullable|integer',
            'status' => 'nullable|string',
            'search' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $result = $this->orderService->getUserOrders(
            $userId,
            $request->only(['platform_id', 'status', 'note', 'start_date', 'end_date']),
            $request->input('page'),
            $request->input('limit', 5)
        );

        return response()->json(['status' => true, 'data' => $result]);
    }

    /**
     * Find user order
     */
    public function findUserOrder(int $userId, int $orderId)
    {
        $order = $this->orderService->findUserOrder($userId, $orderId);

        if (!$order) {
            return response()->json(['status' => false, 'message' => 'Order not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $order]);
    }

    /**
     * Get order dashboard statistics
     */
    public function getDashboardStatistics(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'deal_id' => 'nullable|integer',
            'product_id' => 'nullable|integer',
            'user_id' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $stats = $this->orderService->getOrderDashboardStatistics(
                $request->input('start_date'),
                $request->input('end_date'),
                $request->input('deal_id'),
                $request->input('product_id'),
                $request->input('user_id')
            );

            return response()->json(['status' => true, 'data' => $stats]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all orders paginated
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $orders = $this->orderService->getAllOrdersPaginated($perPage);

        return response()->json([
            'status' => true,
            'data' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'last_page' => $orders->lastPage()
            ]
        ]);
    }

    /**
     * Create order
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'platform_id' => 'required|integer|exists:platforms,id',
            'note' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $order = $this->orderService->createOrder($request->all());
            return response()->json(['status' => true, 'data' => $order], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get pending orders count
     */
    public function getPendingCount(Request $request, int $userId)
    {
        $validator = Validator::make($request->all(), [
            'statuses' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $count = $this->orderService->getPendingOrdersCount($userId, $request->input('statuses'));

        return response()->json(['status' => true, 'count' => $count]);
    }

    /**
     * Get orders by IDs for user
     */
    public function getOrdersByIds(Request $request, int $userId)
    {
        $validator = Validator::make($request->all(), [
            'order_ids' => 'required|array',
            'statuses' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $orders = $this->orderService->getOrdersByIdsForUser(
            $userId,
            $request->input('order_ids'),
            $request->input('statuses', [])
        );

        return response()->json(['status' => true, 'data' => $orders]);
    }

    /**
     * Create orders from cart
     */
    public function createFromCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'orders_data' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $orderIds = $this->orderService->createOrdersFromCartItems(
                $request->input('user_id'),
                $request->input('orders_data')
            );

            return response()->json(['status' => true, 'order_ids' => $orderIds], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Cancel order
     */
    public function cancel(int $orderId)
    {
        $result = $this->orderService->cancelOrder($orderId);

        return response()->json([
            'status' => $result['success'],
            'message' => $result['message']
        ], $result['success'] ? 200 : 404);
    }

    /**
     * Make order ready
     */
    public function makeReady(int $orderId)
    {
        $result = $this->orderService->makeOrderReady($orderId);

        return response()->json([
            'status' => $result['success'],
            'message' => $result['message'],
            'data' => $result['order'] ?? null
        ], $result['success'] ? 200 : 400);
    }
}

