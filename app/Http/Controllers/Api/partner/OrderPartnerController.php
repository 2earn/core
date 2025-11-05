<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Core\Enum\OrderEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
            'out_of_deal_amount' => 'nullable|numeric',
            'deal_amount_before_discount' => 'nullable|numeric',
            'total_order' => 'nullable|numeric',
            'total_order_quantity' => 'nullable|integer',
            'deal_amount_after_discounts' => 'nullable|numeric',
            'amount_after_discount' => 'nullable|numeric',
            'paid_cash' => 'nullable|numeric',
            'commission_2_earn' => 'nullable|numeric',
            'deal_amount_for_partner' => 'nullable|numeric',
            'commission_for_camembert' => 'nullable|numeric',
            'total_final_discount' => 'nullable|numeric',
            'total_final_discount_percentage' => 'nullable|numeric',
            'total_lost_discount' => 'nullable|numeric',
            'total_lost_discount_percentage' => 'nullable|numeric',
            'note' => 'nullable|string',
            'user_id' => 'required|integer|exists:users,id',
            'status' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $userId = $request->input('user_id');

        $data = $validator->validated();
        $data['user_id'] = $userId;
        $data['status'] = OrderEnum::New;

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
    public function update(Request $request, $orderId): JsonResponse
    {
        $validator = Validator::make($request->all() + ['order_id' => $orderId], [
            'order_id' => 'required|integer|exists:orders,id',
            'out_of_deal_amount' => 'nullable|numeric',
            'deal_amount_before_discount' => 'nullable|numeric',
            'total_order' => 'nullable|numeric',
            'total_order_quantity' => 'nullable|integer',
            'deal_amount_after_discounts' => 'nullable|numeric',
            'amount_after_discount' => 'nullable|numeric',
            'paid_cash' => 'nullable|numeric',
            'commission_2_earn' => 'nullable|numeric',
            'deal_amount_for_partner' => 'nullable|numeric',
            'commission_for_camembert' => 'nullable|numeric',
            'total_final_discount' => 'nullable|numeric',
            'total_final_discount_percentage' => 'nullable|numeric',
            'total_lost_discount' => 'nullable|numeric',
            'total_lost_discount_percentage' => 'nullable|numeric',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $order = Order::find( $orderId) ;

        if (!$order) {
            Log::error(self::LOG_PREFIX . 'Order not found or does not belong to user',
                ['order_id' => $orderId]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Order not found or access denied'
            ], \Illuminate\Http\Response::HTTP_NOT_FOUND);
        }

        $data = $validator->validated();
        unset($data['order_id']);
        unset($data['user_id']);
        unset($data['status']);

        $order->update($data);
        return response()->json([
            'status' => true,
            'data' => $order
        ]);
    }

}
