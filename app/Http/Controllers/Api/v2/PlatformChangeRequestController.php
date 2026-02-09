<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Platform\PlatformChangeRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlatformChangeRequestController extends Controller
{
    protected PlatformChangeRequestService $platformChangeRequestService;

    public function __construct(PlatformChangeRequestService $platformChangeRequestService)
    {
        $this->platformChangeRequestService = $platformChangeRequestService;
    }

    /**
     * Get paginated change requests with filters
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $statusFilter = $request->get('status');
            $search = $request->get('search');
            $perPage = (int) $request->get('per_page', 10);
            $perPage = min(max($perPage, 1), 100);

            $requests = $this->platformChangeRequestService->getPaginatedRequests($statusFilter, $search, $perPage);

            return response()->json([
                'success' => true,
                'data' => $requests
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching change requests: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending change requests (paginated)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function pending(Request $request): JsonResponse
    {
        try {
            $page = (int) $request->get('page', 1);
            $perPage = (int) $request->get('per_page', 20);
            $platformId = $request->has('platform_id') ? (int) $request->get('platform_id') : null;

            $perPage = min(max($perPage, 1), 100);

            $requests = $this->platformChangeRequestService->getPendingRequestsPaginated($page, $perPage, $platformId);

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
     * Get a specific change request by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $request = $this->platformChangeRequestService->getChangeRequestById($id);

            if (!$request) {
                return response()->json([
                    'success' => false,
                    'message' => 'Change request not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $request
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching change request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new change request
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'platform_id' => 'required|integer|exists:platforms,id',
                'changes' => 'required|array',
                'requested_by' => 'required|integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $changeRequest = $this->platformChangeRequestService->createRequest(
                $request->platform_id,
                $request->changes,
                $request->requested_by
            );

            return response()->json([
                'success' => true,
                'message' => 'Change request created successfully',
                'data' => $changeRequest
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating change request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a change request
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function approve(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'reviewed_by' => 'required|integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $changeRequest = $this->platformChangeRequestService->approveRequest($id, $request->reviewed_by);

            return response()->json([
                'success' => true,
                'message' => 'Change request approved successfully',
                'data' => $changeRequest
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Change request not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Reject a change request
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function reject(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'reviewed_by' => 'required|integer|exists:users,id',
                'rejection_reason' => 'required|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $changeRequest = $this->platformChangeRequestService->rejectRequest(
                $id,
                $request->reviewed_by,
                $request->rejection_reason
            );

            return response()->json([
                'success' => true,
                'message' => 'Change request rejected successfully',
                'data' => $changeRequest
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Change request not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Cancel a change request
     *
     * @param int $id
     * @return JsonResponse
     */
    public function cancel(int $id): JsonResponse
    {
        try {
            $changeRequest = $this->platformChangeRequestService->cancelRequest($id);

            return response()->json([
                'success' => true,
                'message' => 'Change request cancelled successfully',
                'data' => $changeRequest
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Change request not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get change request statistics
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->platformChangeRequestService->getStatistics();

            return response()->json([
                'success' => true,
                'data' => $stats
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get total count of pending requests
     *
     * @return JsonResponse
     */
    public function pendingCount(): JsonResponse
    {
        try {
            $count = $this->platformChangeRequestService->getTotalPending();

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
}

