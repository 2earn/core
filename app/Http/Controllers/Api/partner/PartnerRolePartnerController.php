<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Models\PartnerRoleRequest;
use App\Models\Partner;
use App\Models\User;
use App\Services\EntityRole\EntityRoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PartnerRolePartnerController extends Controller
{
    private const LOG_PREFIX = '[PartnerRolePartnerController] ';

    protected $entityRoleService;

    public function __construct(EntityRoleService $entityRoleService)
    {
        $this->middleware('check.url');
        $this->entityRoleService = $entityRoleService;
    }

    /**
     * Get all partner role requests
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'partner_id' => 'required|integer|exists:partners,id',
            'status' => 'nullable|in:all,pending,approved,rejected,cancelled',
            'user_id' => 'nullable|integer|exists:users,id',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed for index', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $partnerId = $request->input('partner_id');
            $status = $request->input('status', 'all');
            $userId = $request->input('user_id');
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 15);

            $query = PartnerRoleRequest::with(['partner', 'user', 'requestedBy', 'reviewedBy', 'cancelledBy'])
                ->where('partner_id', $partnerId);

            if ($status !== 'all') {
                $query->where('status', $status);
            }

            if ($userId) {
                $query->where('user_id', $userId);
            }

            $totalCount = $query->count();

            $requests = $query->orderBy('created_at', 'desc')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();

            $stats = [
                'total_requests' => PartnerRoleRequest::where('partner_id', $partnerId)->count(),
                'pending_requests' => PartnerRoleRequest::where('partner_id', $partnerId)->pending()->count(),
                'approved_requests' => PartnerRoleRequest::where('partner_id', $partnerId)->approved()->count(),
                'rejected_requests' => PartnerRoleRequest::where('partner_id', $partnerId)->rejected()->count(),
            ];

            Log::info(self::LOG_PREFIX . 'Partner role requests retrieved successfully', [
                'partner_id' => $partnerId,
                'count' => $requests->count()
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'Partner role requests retrieved successfully',
                'data' => [
                    'requests' => $requests,
                    'statistics' => $stats,
                    'pagination' => [
                        'current_page' => $page,
                        'per_page' => $limit,
                        'total' => $totalCount,
                        'total_pages' => ceil($totalCount / $limit),
                    ]
                ]
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error retrieving partner role requests', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Failed to retrieve partner role requests',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get a specific partner role request by ID
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'partner_id' => 'required|integer|exists:partners,id',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed for show', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $partnerId = $request->input('partner_id');

            $roleRequest = PartnerRoleRequest::with(['partner', 'user', 'requestedBy', 'reviewedBy', 'cancelledBy'])
                ->where('id', $id)
                ->where('partner_id', $partnerId)
                ->first();

            if (!$roleRequest) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Partner role request not found'
                ], Response::HTTP_NOT_FOUND);
            }

            Log::info(self::LOG_PREFIX . 'Partner role request retrieved successfully', [
                'request_id' => $id,
                'partner_id' => $partnerId
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'Partner role request retrieved successfully',
                'data' => $roleRequest
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::error(self::LOG_PREFIX . 'Error retrieving partner role request', [
                'request_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Failed to retrieve partner role request',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create a new partner role request
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'partner_id' => 'required|integer|exists:partners,id',
            'user_id' => 'required|integer|exists:users,id',
            'role_name' => 'required|string|max:255',
            'reason' => 'nullable|string',
            'requested_by' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed for store', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();

            // Check if there's already a pending request for this user and partner
            $existingRequest = PartnerRoleRequest::where('partner_id', $request->input('partner_id'))
                ->where('user_id', $request->input('user_id'))
                ->where('role_name', $request->input('role_name'))
                ->pending()
                ->first();

            if ($existingRequest) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'A pending request already exists for this user and role'
                ], Response::HTTP_CONFLICT);
            }

            $roleRequest = PartnerRoleRequest::create([
                'partner_id' => $request->input('partner_id'),
                'user_id' => $request->input('user_id'),
                'role_name' => $request->input('role_name'),
                'reason' => $request->input('reason'),
                'requested_by' => $request->input('requested_by'),
                'status' => PartnerRoleRequest::STATUS_PENDING,
            ]);

            $roleRequest->load(['partner', 'user', 'requestedBy']);

            DB::commit();

            Log::info(self::LOG_PREFIX . 'Partner role request created successfully', [
                'request_id' => $roleRequest->id,
                'partner_id' => $roleRequest->partner_id,
                'user_id' => $roleRequest->user_id
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'Partner role request created successfully',
                'data' => $roleRequest
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(self::LOG_PREFIX . 'Error creating partner role request', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Failed to create partner role request',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Cancel a partner role request
     */
    public function cancel(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'cancelled_by' => 'required|integer|exists:users,id',
            'cancelled_reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_PREFIX . 'Validation failed for cancel', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();

            $roleRequest = PartnerRoleRequest::findOrFail($id);

            if (!$roleRequest->canBeCancelled()) {
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'This request cannot be cancelled. Current status: ' . $roleRequest->status
                ], Response::HTTP_CONFLICT);
            }

            $roleRequest->update([
                'status' => PartnerRoleRequest::STATUS_CANCELLED,
                'cancelled_reason' => $request->input('cancelled_reason'),
                'cancelled_by' => $request->input('cancelled_by'),
                'cancelled_at' => now(),
            ]);

            $roleRequest->load(['partner', 'user', 'requestedBy', 'cancelledBy']);

            DB::commit();

            Log::info(self::LOG_PREFIX . 'Partner role request cancelled successfully', [
                'request_id' => $id
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'Partner role request cancelled successfully',
                'data' => $roleRequest
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(self::LOG_PREFIX . 'Error cancelling partner role request', [
                'request_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Failed to cancel partner role request',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
