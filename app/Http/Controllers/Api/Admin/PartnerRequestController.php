<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\PartnerRequest\PartnerRequestService;
use App\Enums\BePartnerRequestStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PartnerRequestController extends Controller
{
    private const LOG_PREFIX = '[PartnerRequestController] ';

    private PartnerRequestService $partnerRequestService;

    public function __construct(PartnerRequestService $partnerRequestService)
    {
        $this->partnerRequestService = $partnerRequestService;
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|integer',
            'search' => 'nullable|string',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $searchTerm = $request->input('search', '');
        $statusFilter = $request->input('status', '');
        $perPage = $request->input('per_page', 15);

        try {
            $partnerRequests = $this->partnerRequestService->getFilteredPartnerRequests(
                $searchTerm,
                $statusFilter,
                $perPage
            );

            return response()->json([
                'status' => true,
                'data' => $partnerRequests->items(),
                'pagination' => [
                    'current_page' => $partnerRequests->currentPage(),
                    'per_page' => $partnerRequests->perPage(),
                    'total' => $partnerRequests->total(),
                    'last_page' => $partnerRequests->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error fetching partner requests: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Error fetching partner requests'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $id)
    {
        try {
            $partnerRequest = $this->partnerRequestService->getPartnerRequestById($id);

            if (!$partnerRequest) {
                return response()->json([
                    'status' => false,
                    'message' => 'Partner request not found'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'status' => true,
                'data' => $partnerRequest
            ]);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error fetching partner request: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Error fetching partner request'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'company_name' => 'required|string|max:255',
            'business_sector_id' => 'required|exists:business_sectors,id',
            'platform_url' => 'nullable|string|url',
            'platform_description' => 'nullable|string',
            'partnership_reason' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $userId = $request->input('user_id');
            if ($this->partnerRequestService->hasInProgressRequest($userId)) {
                return response()->json([
                    'status' => false,
                    'message' => 'You already have a partner request in progress'
                ], Response::HTTP_BAD_REQUEST);
            }

            $data = array_merge($validator->validated(), [
                'user_id' => $userId,
                'status' => BePartnerRequestStatus::InProgress->value,
                'request_date' => now(),
                'created_by' => $userId
            ]);

            $partnerRequest = $this->partnerRequestService->createPartnerRequest($data);

            if (!$partnerRequest) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to create partner request'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'status' => true,
                'message' => 'Partner request created successfully',
                'data' => $partnerRequest
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error creating partner request: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Error creating partner request'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|integer|in:1,2,3,4',
            'note' => 'nullable|string',
            'examination_date' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $partnerRequest = $this->partnerRequestService->getPartnerRequestById($id);

            if (!$partnerRequest) {
                return response()->json([
                    'status' => false,
                    'message' => 'Partner request not found'
                ], Response::HTTP_NOT_FOUND);
            }

            $data = array_merge($validator->validated(), [
                'examiner_id' => Auth::id(),
                'examination_date' => now(),
                'updated_by' => Auth::id()
            ]);

            $updatedPartnerRequest = $this->partnerRequestService->updatePartnerRequest($id, $data);

            if (!$updatedPartnerRequest) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to update partner request'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'status' => true,
                'message' => 'Partner request updated successfully',
                'data' => $updatedPartnerRequest
            ]);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error updating partner request: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Error updating partner request'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function getByStatus(int $status)
    {
        try {
            if (!in_array($status, [1, 2, 3, 4])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid status'
                ], Response::HTTP_BAD_REQUEST);
            }

            $partnerRequests = $this->partnerRequestService->getPartnerRequestsByStatus($status);

            return response()->json([
                'status' => true,
                'data' => $partnerRequests
            ]);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error fetching partner requests by status: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Error fetching partner requests'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function approve(int $id)
    {
        try {
            $partnerRequest = $this->partnerRequestService->getPartnerRequestById($id);

            if (!$partnerRequest) {
                return response()->json([
                    'status' => false,
                    'message' => 'Partner request not found'
                ], Response::HTTP_NOT_FOUND);
            }

            $data = [
                'status' => BePartnerRequestStatus::Validated->value,
                'examiner_id' => Auth::id(),
                'examination_date' => now(),
                'updated_by' => Auth::id()
            ];

            $updatedPartnerRequest = $this->partnerRequestService->updatePartnerRequest($id, $data);

            if (!$updatedPartnerRequest) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to approve partner request'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'status' => true,
                'message' => 'Partner request approved successfully',
                'data' => $updatedPartnerRequest
            ]);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error approving partner request: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Error approving partner request'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function reject(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'note' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $partnerRequest = $this->partnerRequestService->getPartnerRequestById($id);

            if (!$partnerRequest) {
                return response()->json([
                    'status' => false,
                    'message' => 'Partner request not found'
                ], Response::HTTP_NOT_FOUND);
            }

            $data = [
                'status' => BePartnerRequestStatus::Rejected->value,
                'note' => $request->input('note'),
                'examiner_id' => Auth::id(),
                'examination_date' => now(),
                'updated_by' => Auth::id()
            ];

            $updatedPartnerRequest = $this->partnerRequestService->updatePartnerRequest($id, $data);

            if (!$updatedPartnerRequest) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to reject partner request'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'status' => true,
                'message' => 'Partner request rejected successfully',
                'data' => $updatedPartnerRequest
            ]);
        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error rejecting partner request: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Error rejecting partner request'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

