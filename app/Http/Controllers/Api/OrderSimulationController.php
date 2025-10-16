<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Orders\Ordering;
use Core\Enum\OrderEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderSimulationController extends Controller
{
    public function processOrder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->query(), [
            'order_id' => 'required|integer|exists:orders,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $orderId = $request->query('order_id');

        try {
            $order = Order::findOrFail($orderId);
            if (!in_array($order->status->value, [OrderEnum::Simulated->value, OrderEnum::Ready->value])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order status is not eligible for simulation.',
                    'order' => $order
                ], 400);
            }

            $simulation = Ordering::simulate($order);

            Ordering::run($simulation);

            $order->refresh();

            if ($order->status->value === OrderEnum::Dispatched->value) {
                return response()->json([
                    'order_id' => (string)$order->id,
                    'status' => 'success',
                    'amount' => $order->total_order,
                    'currency' => 'USD', // Assuming USD, adjust if currency is stored elsewhere
                    'Discount-available' => $order->total_final_discount,
                    'Lost-Discount' => $order->total_lost_discount,
                    'paid-with-BFS' => $order->total_order - $order->paid_cash, // Assuming this is the remainder
                    'paid-with-Cash' => $order->paid_cash,
                    'transaction_id' => 'TXN-' . $order->id, // Assuming a transaction ID format
                    'message' => 'Paiement effectué avec succès',
                    'timestamp' => $order->updated_at->toIso8601String(),
                ]);
            }

            return response()->json([
                'success' => false,
                'order' => $order->load('orderDetails')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
