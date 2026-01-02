<?php

namespace App\Http\Controllers\Api\payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Orders\Ordering;
use App\Enums\OrderEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderSimulationController extends Controller
{
    private const LOG_PREFIX = '[OrderSimulationController] ';

    /**
     * @OA\Post(
     *     path="/api/order/process",
     *     summary="Process an order simulation",
     *     tags={"Order Simulation"},
     *     operationId="api_ext_order_process",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass order ID to process",
     *         @OA\JsonContent(
     *             required={"order_id"},
     *             @OA\Property(property="order_id", type="integer", format="int64", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful simulation",
     *         @OA\JsonContent(
     *              @OA\Property(property="order_id", type="string", example="1"),
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="amount", type="number", format="float", example=150.75),
     *              @OA\Property(property="currency", type="string", example="USD"),
     *              @OA\Property(property="Discount-available", type="number", format="float", example=10.00),
     *              @OA\Property(property="Lost-Discount", type="number", format="float", example=0.00),
     *              @OA\Property(property="paid-with-BFS", type="number", format="float", example=140.75),
     *              @OA\Property(property="paid-with-Cash", type="number", format="float", example=10.00),
     *              @OA\Property(property="transaction_id", type="string", example="TXN-1"),
     *              @OA\Property(property="message", type="string", example="Payment successfully completed"),
     *              @OA\Property(property="timestamp", type="string", format="date-time", example="2023-10-17T10:00:00.000000Z")
     *         )
     *     ),
     *      @OA\Response(
     *         response=400,
     *         description="Bad Request - Order not eligible",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Order status is not eligible for simulation.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Validation failed"),
     *              @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="An exception occurred during order processing")
     *         )
     *     )
     * )
     */
    public function processOrder(Request $request): JsonResponse
    {
        Log::info(self::LOG_PREFIX . 'Incoming order processing request', ['request' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:orders,id'
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        Log::info(self::LOG_PREFIX . 'Validation passed');

        $orderId = $request->input('order_id');
        try {

            $order = Order::findOrFail($orderId);
            if (!in_array($order->status->value, [OrderEnum::Simulated->value, OrderEnum::Ready->value])) {
                Log::warning(self::LOG_PREFIX . 'Order status not eligible for simulation', ['order_id' => $orderId, 'status' => $order->status->value]);
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Order status is not eligible for simulation.',
                ], Response::HTTP_LOCKED);
            }

            $simulation = Ordering::simulate($order);

            if (!$simulation) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Simulation Failed.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            Ordering::run($simulation);

            $order->refresh();

            if ($order->status->value === OrderEnum::Dispatched->value) {
                $responseData = [
                    'order_id' => (string)$order->id,
                    'status' => 'success',
                    'amount' => $order->total_order,
                    'currency' => 'USD',
                    'discount-available' => $order->total_final_discount,
                    'lost-Discount' => $order->total_lost_discount,
                    'paid-with-BFS' => $order->amount_after_discount - $order->paid_cash,
                    'paid-with-Cash' => $order->paid_cash,
                    'transaction_id' => 'TXN-' . $order->id,
                    'message' => 'Payment successfully completed',
                    'timestamp' => $order->updated_at->toIso8601String(),
                ];
                Log::info(self::LOG_PREFIX . 'Order processed successfully', $responseData);
                return response()->json($responseData);
            }

            Log::warning(self::LOG_PREFIX . 'Order processing failed after simulation', ['order_id' => $orderId, 'status' => $order->status->value]);
            return response()->json([
                'success' => false,
                'order' => $order->load('orderDetails')
            ]);

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'An exception occurred during order processing', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
