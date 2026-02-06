<?php

namespace App\Http\Controllers\Api\payment;

use App\Enums\OrderEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SimulationOrder;
use App\Services\Orders\Ordering;
use App\Services\SimulationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderSimulationController extends Controller
{
    private const LOG_PREFIX = '[OrderSimulationController] ';

    private SimulationService $simulationService;

    public function __construct(SimulationService $simulationService)
    {
        $this->simulationService = $simulationService;
    }

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

            // Log the simulation result
            Log::info(self::LOG_PREFIX . 'Order simulation result', [
                'order_id' => $orderId,
                'simulation' => $simulation
            ]);


            if (!$simulation) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Simulation Failed.',
                    'simulation_result' => false,
                    'simulation_details' => $order->simulation_details ?? 'No details available',
                    'simulation_datetime' => $order->simulation_datetime ?? now()->toIso8601String(),
                    'user' => [
                        'id' => $order->user_id,
                        'name' => $order->user->name ?? null,
                        'email' => $order->user->email ?? null,
                    ]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Compare with last saved simulation
            $comparisonResult = $this->simulationService->compareWithLastSimulation($orderId, $simulation);
            if (!$comparisonResult['matches']) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Simulation mismatch error. Current simulation differs from last saved simulation.',
                    'error_code' => 'SIMULATION_MISMATCH',
                    'details' => $comparisonResult['details']
                ], Response::HTTP_CONFLICT);
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

    /**
     * Step 1: Simulate order only (without running it)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function simulateOrder(Request $request): JsonResponse
    {
        Log::info(self::LOG_PREFIX . 'Incoming order simulation request', ['request' => $request->all()]);

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

            // Check if order status is eligible for simulation
            if (!in_array($order->status->value, [OrderEnum::Simulated->value, OrderEnum::Ready->value])) {
                Log::warning(self::LOG_PREFIX . 'Order status not eligible for simulation', [
                    'order_id' => $orderId,
                    'status' => $order->status->value
                ]);
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Order status is not eligible for simulation.',
                    'current_status' => $order->status->value
                ], Response::HTTP_LOCKED);
            }

            // Perform simulation only
            $simulation = Ordering::simulate($order, true);

            // Log the simulation result
            Log::info(self::LOG_PREFIX . 'Order simulation result', [
                'order_id' => $orderId,
                'simulation' => $simulation
            ]);


            if (!$simulation) {
                Log::error(self::LOG_PREFIX . 'Simulation failed', ['order_id' => $orderId]);
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Simulation failed.',
                    'simulation_result' => false,
                    'simulation_details' => $order->simulation_details ?? 'No details available',
                    'simulation_datetime' => $order->simulation_datetime ?? now()->toIso8601String(),
                    'user' => [
                        'id' => $order->user_id,
                        'name' => $order->user->name ?? null,
                        'email' => $order->user->email ?? null,
                    ]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            Log::info(self::LOG_PREFIX . 'Simulation completed successfully', [
                'order_id' => $orderId,
                'simulation_summary' => [
                    'total_amount' => $simulation['total_amount'] ?? null,
                    'final_amount' => $simulation['final_amount'] ?? null,
                    'total_discount' => $simulation['total_discount'] ?? null,
                ]
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'Order simulation completed successfully',
                'data' => [
                    'order_id' => $orderId,
                    'simulation' => $simulation,
                    'note' => 'Simulation complete. Use runSimulation endpoint to execute the order.'
                ]
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Exception during simulation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'An error occurred during simulation: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Step 2: Run the simulation (simulate and execute)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function runSimulation(Request $request): JsonResponse
    {
        Log::info(self::LOG_PREFIX . 'Incoming run simulation request', ['request' => $request->all()]);

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

            // Check if order status is eligible for simulation
            if (!in_array($order->status->value, [OrderEnum::Simulated->value])) {
                Log::warning(self::LOG_PREFIX . 'Order status not eligible for running simulation', [
                    'order_id' => $orderId,
                    'status' => $order->status->value
                ]);
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Order status is not eligible for running simulation.',
                    'current_status' => $order->status->value
                ], Response::HTTP_LOCKED);
            }

            // Step 1: Simulate
            $simulation = Ordering::simulate($order);

            // Log the simulation result
            Log::info(self::LOG_PREFIX . 'Order simulation result', [
                'order_id' => $orderId,
                'simulation' => $simulation
            ]);


            if (!$simulation) {
                Log::error(self::LOG_PREFIX . 'Simulation failed during run', ['order_id' => $orderId]);
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Simulation failed.',
                    'simulation_result' => false,
                    'simulation_details' => $order->simulation_details ?? 'No details available',
                    'simulation_datetime' => $order->simulation_datetime ?? now()->toIso8601String(),
                    'user' => [
                        'id' => $order->user_id,
                        'name' => $order->user->name ?? null,
                        'email' => $order->user->email ?? null,
                    ]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Compare with last saved simulation
            $comparisonResult = $this->simulationService->compareWithLastSimulation($orderId, $simulation);
            if (!$comparisonResult['matches']) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Simulation mismatch error. Current simulation differs from last saved simulation.',
                    'error_code' => 'SIMULATION_MISMATCH',
                    'details' => $comparisonResult['details']
                ], Response::HTTP_CONFLICT);
            }


            Log::info(self::LOG_PREFIX . 'Simulation successful, now running order', ['order_id' => $orderId]);

            // Step 2: Run the simulation
            Ordering::run($simulation);

            // Refresh order to get updated status
            $order->refresh();

            // Check if order was successfully dispatched
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

                Log::info(self::LOG_PREFIX . 'Order dispatched successfully', $responseData);

                return response()->json($responseData, Response::HTTP_OK);
            }

            // If order not dispatched, return current status
            Log::warning(self::LOG_PREFIX . 'Order not dispatched after running simulation', [
                'order_id' => $orderId,
                'status' => $order->status->value
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Order could not be dispatched',
                'order_id' => $orderId,
                'current_status' => $order->status->value,
                'order' => $order->load('orderDetails')
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Exception during run simulation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'An error occurred during order execution: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
