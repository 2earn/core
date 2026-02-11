<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Platform\PendingPlatformRoleAssignmentsInlineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PendingPlatformRoleAssignmentsInlineController extends Controller
{
    protected PendingPlatformRoleAssignmentsInlineService $pendingService;

    public function __construct(PendingPlatformRoleAssignmentsInlineService $pendingService)
    {
        $this->pendingService = $pendingService;
    }

    /**
     * Get pending platform role assignments
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

            $assignments = $this->pendingService->getPendingAssignments($limit);

            return response()->json([
                'success' => true,
                'data' => $assignments
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching pending assignments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get total count of pending assignments
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
     * Get pending assignments with total count
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

            $data = $this->pendingService->getPendingAssignmentsWithTotal($limit);

            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching pending assignments: ' . $e->getMessage()
            ], 500);
        }
    }
}

