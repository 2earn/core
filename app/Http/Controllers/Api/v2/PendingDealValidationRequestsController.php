<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Deals\PendingDealValidationRequestsInlineService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PendingDealValidationRequestsController extends Controller
{
    private PendingDealValidationRequestsInlineService $service;

    public function __construct(PendingDealValidationRequestsInlineService $service)
    {
        $this->service = $service;
    }

    /**
     * Get pending validation requests
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $requests = $this->service->getPendingRequests($request->input('limit'));

        return response()->json([
            'status' => true,
            'data' => $requests
        ]);
    }

    /**
     * Get paginated validation requests
     */
    public function getPaginated(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status_filter' => 'nullable|string',
            'search' => 'nullable|string',
            'user_id' => 'nullable|integer|exists:users,id',
            'is_super_admin' => 'required|boolean',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $requests = $this->service->getPaginatedRequests(
            $request->input('status_filter'),
            $request->input('search'),
            $request->input('user_id'),
            $request->boolean('is_super_admin'),
            $request->input('per_page', 10)
        );

        return response()->json([
            'status' => true,
            'data' => $requests->items(),
            'pagination' => [
                'current_page' => $requests->currentPage(),
                'per_page' => $requests->perPage(),
                'total' => $requests->total(),
                'last_page' => $requests->lastPage()
            ]
        ]);
    }

    /**
     * Get total pending count
     */
    public function getTotalPending()
    {
        $count = $this->service->getTotalPending();

        return response()->json([
            'status' => true,
            'total_pending' => $count
        ]);
    }

    /**
     * Get pending requests with total
     */
    public function getPendingWithTotal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $this->service->getPendingRequestsWithTotal($request->input('limit'));

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    /**
     * Get validation request by ID
     */
    public function show(int $id)
    {
        try {
            $request = $this->service->findRequest($id);

            return response()->json([
                'status' => true,
                'data' => $request
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation request not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Get validation request with relations
     */
    public function showWithRelations(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'relations' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $validationRequest = $this->service->findRequestWithRelations(
                $id,
                $request->input('relations', [])
            );

            return response()->json([
                'status' => true,
                'data' => $validationRequest
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation request not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Approve validation request
     */
    public function approve(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'reviewed_by' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $validationRequest = $this->service->approveRequest($id, $request->input('reviewed_by'));

            return response()->json([
                'status' => true,
                'message' => 'Validation request approved successfully',
                'data' => $validationRequest
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Reject validation request
     */
    public function reject(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'reviewed_by' => 'required|integer|exists:users,id',
            'rejection_reason' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $validationRequest = $this->service->rejectRequest(
                $id,
                $request->input('reviewed_by'),
                $request->input('rejection_reason')
            );

            return response()->json([
                'status' => true,
                'message' => 'Validation request rejected successfully',
                'data' => $validationRequest
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}

