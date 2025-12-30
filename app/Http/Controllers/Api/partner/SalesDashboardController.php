<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\SalesDashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SalesDashboardController extends Controller
{
    private const LOG_PREFIX = '[SalesDashboardController] ';

    private SalesDashboardService $dashboardService;

    public function __construct(SalesDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function getKpis(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'platform_ids' => 'nullable|array',
            'platform_ids.*' => 'integer|exists:platforms,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $filters = [
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'platform_ids' => $request->input('platform_ids'),
                'user_id' => $request->input('user_id'),
            ];

            $filters = array_filter($filters, function ($value) {
                return !is_null($value);
            });

            $kpis = $this->dashboardService->getKpiData($filters);

            Log::info(self::LOG_PREFIX . 'KPIs retrieved successfully', [
                'filters' => $filters,
                'kpis' => $kpis
            ]);

            return response()->json([
                'status' => true,
                'message' => 'KPIs retrieved successfully',
                'data' => $kpis
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error retrieving KPIs: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Error retrieving KPIs',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getSalesEvolutionChart(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'platform_ids' => 'nullable|array',
            'platform_ids.*' => 'integer|exists:platforms,id',
            'user_id' => 'required|integer|exists:users,id',
            'view_mode' => 'nullable|in:daily,weekly,monthly',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed for sales evolution chart', [
                'errors' => $validator->errors()
            ]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $filters = [
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'platform_ids' => $request->input('platform_ids'),
                'user_id' => $request->input('user_id'),
                'view_mode' => $request->input('view_mode', 'daily'),
            ];

            $filters = array_filter($filters, function ($value) {
                return !is_null($value);
            });

            $chartData = $this->dashboardService->getSalesEvolutionChart($filters);

            Log::info(self::LOG_PREFIX . 'Sales evolution chart retrieved successfully', [
                'filters' => $filters,
                'data_points' => count($chartData['chart_data'])
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Sales evolution chart retrieved successfully',
                'data' => $chartData
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error retrieving sales evolution chart: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Error retrieving sales evolution chart',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getTopSellingProducts(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'platform_ids' => 'nullable|array',
            'platform_ids.*' => 'integer|exists:platforms,id',
            'deal_id' => 'nullable|integer|exists:deals,id',
            'user_id' => 'required|integer|exists:users,id',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed for top-selling products', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $filters = [
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'platform_ids' => $request->input('platform_ids'),
                'deal_id' => $request->input('deal_id'),
                'user_id' => $request->input('user_id'),
                'limit' => $request->input('limit', 10),
            ];

            $filters = array_filter($filters, function ($value) {
                return !is_null($value);
            });

            $topProducts = $this->dashboardService->getTopSellingProducts($filters);

            Log::info(self::LOG_PREFIX . 'Top-selling products retrieved successfully', [
                'filters' => $filters,
                'count' => count($topProducts)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Top-selling products retrieved successfully',
                'data' => $topProducts
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error retrieving top-selling products: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Error retrieving top-selling products',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get top-selling deals chart data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTransactions(Request $request): JsonResponse
    {
        try {

            $validator = Validator::make($request->all(), [
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'platform_ids' => 'nullable|array',
                'platform_ids.*' => 'integer|exists:platforms,id',
                'order_id' => 'nullable|integer|exists:orders,id',
                'status' => 'nullable|string',
                'note' => 'nullable|string',
                'country' => 'nullable|string',
                'user_id' => 'nullable|integer|exists:users,id',
                'page' => 'nullable|integer|min:1',
                'per_page' => 'nullable|integer|min:1|max:100',
            ]);

            if ($validator->fails()) {
                Log::error(self::LOG_PREFIX . 'Validation failed for transactions', [
                    'errors' => $validator->errors()
                ]);
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $filters = [
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'platform_ids' => $request->input('platform_ids'),
                'order_id' => $request->input('order_id'),
                'status' => $request->input('status'),
                'note' => $request->input('note'),
                'country' => $request->input('country'),
                'user_id' => $request->input('user_id'),
                'page' => $request->input('page', 1),
                'per_page' => $request->input('per_page', 15),
            ];

            $filters = array_filter($filters, function ($value) {
                return !is_null($value);
            });

            $transactions = $this->dashboardService->getTransactions($filters);
            Log::info(self::LOG_PREFIX . 'Transactions retrieved successfully', [
                'filters' => $filters,
                'total' => $transactions['total'] ?? 0,
                'current_page' => $transactions['current_page'] ?? 1
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Transactions retrieved successfully',
                'data' => [
                    'filters' => $transactions['filters'] ?? [],
                    'transactions' => $transactions['data'] ?? [],
                    'pagination' => [
                        'current_page' => $transactions['current_page'] ?? 1,
                        'per_page' => $transactions['per_page'] ?? 15,
                        'total' => $transactions['total'] ?? 0,
                        'last_page' => $transactions['last_page'] ?? 1,
                        'from' => $transactions['from'] ?? null,
                        'to' => $transactions['to'] ?? null,
                    ]
                ]
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error retrieving Transactions: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Error retrieving Transactions',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get top-selling deals chart data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTransactionsDetails(Request $request): JsonResponse
    {
        try {

            $validator = Validator::make($request->all(), [
                'order_id' => 'required|integer|exists:orders,id',
            ]);

            if ($validator->fails()) {
                Log::error(self::LOG_PREFIX . 'Validation failed for top-selling deals', [
                    'errors' => $validator->errors()
                ]);
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $filters = [
                'order_id' => $request->input('order_id'),
            ];

            $filters = array_filter($filters, function ($value) {
                return !is_null($value);
            });

            $transactions = $this->dashboardService->getTransactionsDetails($filters);

            Log::info(self::LOG_PREFIX . 'Transactions deals retrieved successfully', [
                'filters' => $filters,
                'count' => count($transactions)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Transactions deals retrieved successfully',
                'data' => [
                    'transactions' => $transactions
                ]
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error retrieving Transactions: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Error retrieving Transactions',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getTopSellingDeals(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'platform_ids' => 'nullable|array',
            'platform_ids.*' => 'integer|exists:platforms,id',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed for top-selling deals', [
                'errors' => $validator->errors()
            ]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $filters = [
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'platform_ids' => $request->input('platform_ids'),
                'limit' => $request->input('limit', 5),
            ];

            $filters = array_filter($filters, function ($value) {
                return !is_null($value);
            });

            $topDeals = $this->dashboardService->getTopSellingDeals($filters);

            Log::info(self::LOG_PREFIX . 'Top-selling deals retrieved successfully', [
                'filters' => $filters,
                'count' => count($topDeals)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Top-selling deals retrieved successfully',
                'data' => [
                    'top_deals' => $topDeals
                ]
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error retrieving top-selling deals: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Error retrieving top-selling deals',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

