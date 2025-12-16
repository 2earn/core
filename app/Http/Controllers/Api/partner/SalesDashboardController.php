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
            'platform_id' => 'nullable|integer|exists:platforms,id',
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
                'platform_id' => $request->input('platform_id'),
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
            'platform_id' => 'nullable|integer|exists:platforms,id',
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
                'platform_id' => $request->input('platform_id'),
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
            'platform_id' => 'nullable|integer|exists:platforms,id',
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
                'platform_id' => $request->input('platform_id'),
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
    public function getTopSellingDeals(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'platform_id' => 'nullable|integer|exists:platforms,id',
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
                'platform_id' => $request->input('platform_id'),
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

