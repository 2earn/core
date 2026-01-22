<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Services\Deals\DealProductChangeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class DealProductChangeController extends Controller
{
    private const LOG_PREFIX = '[DealProductChangeController] ';

    protected DealProductChangeService $service;

    public function __construct(DealProductChangeService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only(['deal_id', 'item_id', 'action', 'changed_by', 'from_date', 'to_date']);
            $perPage = $request->get('per_page', 15);

            $changes = $this->service->getFilteredChanges($filters, $perPage);

            Log::info(self::LOG_PREFIX . 'Product changes retrieved', [
                'filters' => $filters,
                'count' => $changes->total()
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $changes
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Failed to retrieve product changes', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Failed to retrieve product changes',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $change = $this->service->getChangeById($id);

            if (!$change) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Product change not found'
                ], Response::HTTP_NOT_FOUND);
            }

            Log::info(self::LOG_PREFIX . 'Product change retrieved', [
                'id' => $id
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $change
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Failed to retrieve product change', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Product change not found',
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function statistics(Request $request)
    {
        try {
            $filters = $request->only(['deal_id', 'from_date', 'to_date']);

            $statistics = $this->service->getStatistics($filters);

            Log::info(self::LOG_PREFIX . 'Statistics retrieved', [
                'filters' => $filters,
                'total' => $statistics['total_changes']
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $statistics
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Failed to retrieve statistics', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

