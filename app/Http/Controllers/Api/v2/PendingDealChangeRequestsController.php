<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Deals\PendingDealChangeRequestsInlineService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PendingDealChangeRequestsController extends Controller
{
    private PendingDealChangeRequestsInlineService $service;

    public function __construct(PendingDealChangeRequestsInlineService $service)
    {
        $this->service = $service;
    }

    /**
     * Get pending change requests
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
     * Get change request by ID
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
                'message' => 'Change request not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Get change request with relations
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
            $changeRequest = $this->service->findRequestWithRelations(
                $id,
                $request->input('relations', [])
            );

            return response()->json([
                'status' => true,
                'data' => $changeRequest
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Change request not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}

