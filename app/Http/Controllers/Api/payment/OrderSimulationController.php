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
