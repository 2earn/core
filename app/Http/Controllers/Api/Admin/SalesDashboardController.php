<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\SalesDashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for Sales Dashboard KPIs
 * Provides top KPI cards for sales analytics
 */
class SalesDashboardController extends Controller
{
    private const LOG_PREFIX = '[SalesDashboardController] ';

    private SalesDashboardService $dashboardService;

    public function __construct(SalesDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Get KPI metrics for sales dashboard
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getKpis(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'platform_id' => 'nullable|integer|exists:platforms,id',
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
            ];

            // Remove null values
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
}

