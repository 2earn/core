<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Platform\PendingPlatformChangeRequestsInlineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PendingPlatformChangeRequestsInlineController extends Controller
{
    protected PendingPlatformChangeRequestsInlineService $pendingService;

    public function __construct(PendingPlatformChangeRequestsInlineService $pendingService)
    {
        $this->pendingService = $pendingService;
    }

    /**
     * Get pending platform change requests
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $limit = $request->has('limit') ? (int) $request->get('limit') : null;

            if ($limit !== null) {
                $limit = min(max($limit, 1), 100);
            }

            $requests = $this->pendingService->getPendingRequests($limit);

            return response()->json([
                'success' => true,
                'data' => $requests
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching pending requests: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get total count of pending requests
     *
     * @return JsonResponse
     */
    public function count(): JsonResponse
    {
        try {
            $count = $this->pendingService->getTotalPending();

            return response()->json([
                'success' => true,
                'count' => $count
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching count: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending requests with total count
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function withTotal(Request $request): JsonResponse
    {
        try {
            $limit = $request->has('limit') ? (int) $request->get('limit') : null;

            if ($limit !== null) {
                $limit = min(max($limit, 1), 100);
            }

            $data = $this->pendingService->getPendingRequestsWithTotal($limit);

            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching pending requests: ' . $e->getMessage()
            ], 500);
        }
    }
}

