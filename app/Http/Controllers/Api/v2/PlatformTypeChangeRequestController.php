<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Platform\PlatformTypeChangeRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlatformTypeChangeRequestController extends Controller
{
    protected PlatformTypeChangeRequestService $platformTypeChangeRequestService;

    public function __construct(PlatformTypeChangeRequestService $platformTypeChangeRequestService)
    {
        $this->platformTypeChangeRequestService = $platformTypeChangeRequestService;
    }

    /**
     * Get paginated type change requests with filters
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

            $requests = $this->platformTypeChangeRequestService->getPaginatedRequests($statusFilter, $search, $perPage);

            return response()->json([
                'success' => true,
                'data' => $requests
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching type change requests: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending type change requests
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function pending(Request $request): JsonResponse
    {
        try {
            $limit = $request->has('limit') ? (int) $request->get('limit') : null;

            if ($limit !== null) {
                $limit = min(max($limit, 1), 100);
            }

            $requests = $this->platformTypeChangeRequestService->getPendingRequests($limit);

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
    public function pendingCount(): JsonResponse
    {
        try {
            $count = $this->platformTypeChangeRequestService->getTotalPending();

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
    public function pendingWithTotal(Request $request): JsonResponse
    {
        try {
            $limit = $request->has('limit') ? (int) $request->get('limit') : null;

            if ($limit !== null) {
                $limit = min(max($limit, 1), 100);
            }

            $data = $this->platformTypeChangeRequestService->getPendingRequestsWithTotal($limit);

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

    /**
     * Get a specific type change request by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $request = $this->platformTypeChangeRequestService->findRequest($id);

            return response()->json([
                'success' => true,
                'data' => $request
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Type change request not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching type change request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new type change request
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'platform_id' => 'required|integer|exists:platforms,id',
                'old_type' => 'required|integer',
                'new_type' => 'required|integer',
                'requested_by' => 'required|integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $typeChangeRequest = $this->platformTypeChangeRequestService->createRequest(
                $request->platform_id,
                $request->old_type,
                $request->new_type,
                $request->requested_by
            );

            return response()->json([
                'success' => true,
                'message' => 'Type change request created successfully',
                'data' => $typeChangeRequest
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating type change request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a type change request
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

            $typeChangeRequest = $this->platformTypeChangeRequestService->approveRequest($id, $request->reviewed_by);

            return response()->json([
                'success' => true,
                'message' => 'Type change request approved successfully',
                'data' => $typeChangeRequest
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Type change request not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Reject a type change request
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

            $typeChangeRequest = $this->platformTypeChangeRequestService->rejectRequest(
                $id,
                $request->reviewed_by,
                $request->rejection_reason
            );

            return response()->json([
                'success' => true,
                'message' => 'Type change request rejected successfully',
                'data' => $typeChangeRequest
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Type change request not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}

