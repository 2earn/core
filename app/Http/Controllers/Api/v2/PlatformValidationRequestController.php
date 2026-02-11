<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Platform\PlatformValidationRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlatformValidationRequestController extends Controller
{
    protected PlatformValidationRequestService $platformValidationRequestService;

    public function __construct(PlatformValidationRequestService $platformValidationRequestService)
    {
        $this->platformValidationRequestService = $platformValidationRequestService;
    }

    /**
     * Get paginated validation requests with filters
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

            $requests = $this->platformValidationRequestService->getPaginatedRequests($statusFilter, $search, $perPage);

            return response()->json([
                'success' => true,
                'data' => $requests
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching validation requests: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending validation requests
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

            $requests = $this->platformValidationRequestService->getPendingRequests($limit);

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
            $count = $this->platformValidationRequestService->getTotalPending();

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

            $data = $this->platformValidationRequestService->getPendingRequestsWithTotal($limit);

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
     * Get a specific validation request by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $request = $this->platformValidationRequestService->findRequest($id);

            return response()->json([
                'success' => true,
                'data' => $request
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation request not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching validation request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new validation request
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'platform_id' => 'required|integer|exists:platforms,id',
                'requested_by' => 'required|integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validationRequest = $this->platformValidationRequestService->createRequest(
                $request->platform_id,
                $request->requested_by
            );

            return response()->json([
                'success' => true,
                'message' => 'Validation request created successfully',
                'data' => $validationRequest
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating validation request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a validation request
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

            $validationRequest = $this->platformValidationRequestService->approveRequest($id, $request->reviewed_by);

            return response()->json([
                'success' => true,
                'message' => 'Validation request approved successfully',
                'data' => $validationRequest
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation request not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Reject a validation request
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

            $validationRequest = $this->platformValidationRequestService->rejectRequest(
                $id,
                $request->reviewed_by,
                $request->rejection_reason
            );

            return response()->json([
                'success' => true,
                'message' => 'Validation request rejected successfully',
                'data' => $validationRequest
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation request not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Cancel a validation request
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function cancel(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'cancelled_by' => 'required|integer|exists:users,id',
                'rejection_reason' => 'required|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validationRequest = $this->platformValidationRequestService->cancelRequest(
                $id,
                $request->cancelled_by,
                $request->rejection_reason
            );

            return response()->json([
                'success' => true,
                'message' => 'Validation request cancelled successfully',
                'data' => $validationRequest
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation request not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}

