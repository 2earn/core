<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class OrderPartnerController extends Controller
{
    private const LOG_PREFIX = '[OrderPartnerController] ';
    private const PAGINATION_LIMIT = 5;

    public function __construct()
    {
        $this->middleware('check.url');
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'platform_id' => 'nullable|integer|exists:platforms,id',
            'page' => 'nullable|integer|min:1'
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $userId = $request->input('user_id');

        $page = $request->input('page', 1);

        $orders = Order::with(['user', 'OrderDetails'])
            ->where('user_id', $userId)
            ->paginate(self::PAGINATION_LIMIT, ['*'], 'page', $page);

        $totalCount = Order::with(['user', 'OrderDetails'])
            ->where('user_id', $userId)->count();

        return response()->json([
            'status' => true,
            'data' => $orders,
            'total' => $totalCount
        ]);
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'note' => 'nullable|string',
            'user_id' => 'required|integer|exists:users,id',
            'status' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $userId = $request->input('user_id');

        $data = $validator->validated();
        $data['user_id'] = $userId;

        $order = Order::create($data);
        return response()->json($order, Response::HTTP_CREATED);
    }

    /**
     * Display the specified order.
     */
    public function show(Request $request, $orderId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $userId = $request->input('user_id');
        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $order = Order::with(['user', 'OrderDetails'])
            ->where('user_id', $userId)
            ->where('id', $orderId)
            ->first();

        if (!$order) {
            Log::error(self::LOG_PREFIX . 'Order not found', ['deal_id' => $orderId, 'user_id' => $userId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Failed to fetch Order'
            ], \Illuminate\Http\Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => true,
            'data' => $order,
        ]);
    }

    /**
     * Update the specified order.
     */
    public function update(Request $request, Order $order): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'out_of_deal_amount' => 'numeric',
            'deal_amount_before_discount' => 'numeric',
            'total_order' => 'numeric',
            'total_order_quantity' => 'integer',
            'deal_amount_after_discounts' => 'numeric',
            'amount_after_discount' => 'numeric',
            'paid_cash' => 'numeric',
            'commission_2_earn' => 'numeric',
            'deal_amount_for_partner' => 'numeric',
            'commission_for_camembert' => 'numeric',
            'total_final_discount' => 'numeric',
            'total_final_discount_percentage' => 'numeric',
            'total_lost_discount' => 'numeric',
            'total_lost_discount_percentage' => 'numeric',
            'note' => 'nullable|string',
            'user_id' => 'required|integer|exists:users,id',
            'status' => 'string'
        ]);

        $userId = $request->input('user_id');
        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $validator->validated();
        // Ensure user_id cannot be changed
        unset($data['user_id']);

        $order->update($data);
        return response()->json($order);
    }
}
