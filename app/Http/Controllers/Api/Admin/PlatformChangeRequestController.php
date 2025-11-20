<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformChangeRequest;
use Core\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Controller for managing platform change request approvals
 * This should be accessible only to admin users
 */
class PlatformChangeRequestController extends Controller
{
    private const LOG_PREFIX = '[PlatformChangeRequestController] ';

    public function __construct()
    {
        // Add your admin middleware here
        // $this->middleware('admin');
    }

    /**
     * Get all pending change requests
     */
    public function pending(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 20);
        $platformId = $request->input('platform_id');

        $query = PlatformChangeRequest::where('status', 'pending')
            ->with(['platform', 'requestedBy']);

        if ($platformId) {
            $query->where('platform_id', $platformId);
        }

        $changeRequests = $query->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'status' => true,
            'data' => $changeRequests->items(),
            'pagination' => [
                'current_page' => $changeRequests->currentPage(),
                'per_page' => $changeRequests->perPage(),
                'total' => $changeRequests->total(),
                'last_page' => $changeRequests->lastPage()
            ]
        ]);
    }

    /**
     * Get all change requests (with filtering)
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|in:pending,approved,rejected',
            'platform_id' => 'nullable|exists:platforms,id',
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

        $status = $request->input('status');
        $platformId = $request->input('platform_id');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 20);

        $query = PlatformChangeRequest::with(['platform', 'requestedBy', 'reviewedBy']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($platformId) {
            $query->where('platform_id', $platformId);
        }

        $changeRequests = $query->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'status' => true,
            'data' => $changeRequests->items(),
            'pagination' => [
                'current_page' => $changeRequests->currentPage(),
                'per_page' => $changeRequests->perPage(),
                'total' => $changeRequests->total(),
                'last_page' => $changeRequests->lastPage()
            ]
        ]);
    }

    /**
     * Get a specific change request
     */
    public function show($id)
    {
        $changeRequest = PlatformChangeRequest::with(['platform', 'requestedBy', 'reviewedBy'])
            ->find($id);

        if (!$changeRequest) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Change request not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => true,
            'data' => $changeRequest
        ]);
    }

    /**
     * Approve a change request and apply changes to the platform
     */
    public function approve(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reviewed_by' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $reviewedBy = $request->input('reviewed_by');

        // Use transaction to ensure atomicity
        DB::beginTransaction();

        try {
            $changeRequest = PlatformChangeRequest::where('id', $id)
                ->where('status', 'pending')
                ->lockForUpdate()
                ->first();

            if (!$changeRequest) {
                DB::rollBack();
                return response()->json([
                    'status' => 'Failed',
                    'message' => 'Change request not found or already processed'
                ], Response::HTTP_NOT_FOUND);
            }

            $platform = Platform::findOrFail($changeRequest->platform_id);

            // Apply all changes to the platform
            foreach ($changeRequest->changes as $field => $change) {
                $platform->{$field} = $change['new'];
            }

            // Set updated_by if it was in the changes
            if (isset($changeRequest->changes['updated_by'])) {
                $platform->updated_by = $reviewedBy;
            }

            $platform->save();

            // Update the change request status
            $changeRequest->update([
                'status' => 'approved',
                'reviewed_by' => $reviewedBy,
                'reviewed_at' => now()
            ]);

            DB::commit();

            Log::info(self::LOG_PREFIX . 'Change request approved and applied', [
                'change_request_id' => $changeRequest->id,
                'platform_id' => $platform->id,
                'reviewed_by' => $reviewedBy
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Change request approved and applied successfully',
                'data' => [
                    'change_request' => $changeRequest->fresh(['platform', 'requestedBy', 'reviewedBy']),
                    'platform' => $platform->fresh()
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(self::LOG_PREFIX . 'Error approving change request', [
                'change_request_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'Failed',
                'message' => 'Failed to approve change request',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Reject a change request
     */
    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reviewed_by' => 'required|exists:users,id',
            'rejection_reason' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $reviewedBy = $request->input('reviewed_by');
        $rejectionReason = $request->input('rejection_reason');

        $changeRequest = PlatformChangeRequest::where('id', $id)
            ->where('status', 'pending')
            ->first();

        if (!$changeRequest) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Change request not found or already processed'
            ], Response::HTTP_NOT_FOUND);
        }

        $changeRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $rejectionReason,
            'reviewed_by' => $reviewedBy,
            'reviewed_at' => now()
        ]);

        Log::info(self::LOG_PREFIX . 'Change request rejected', [
            'change_request_id' => $changeRequest->id,
            'platform_id' => $changeRequest->platform_id,
            'reviewed_by' => $reviewedBy,
            'reason' => $rejectionReason
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Change request rejected successfully',
            'data' => $changeRequest->fresh(['platform', 'requestedBy', 'reviewedBy'])
        ]);
    }

    /**
     * Bulk approve multiple change requests
     */
    public function bulkApprove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reviewed_by' => 'required|exists:users,id',
            'change_request_ids' => 'required|array',
            'change_request_ids.*' => 'required|exists:platform_change_requests,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $reviewedBy = $request->input('reviewed_by');
        $changeRequestIds = $request->input('change_request_ids');

        $approved = [];
        $failed = [];

        foreach ($changeRequestIds as $id) {
            DB::beginTransaction();
            try {
                $changeRequest = PlatformChangeRequest::where('id', $id)
                    ->where('status', 'pending')
                    ->lockForUpdate()
                    ->first();

                if (!$changeRequest) {
                    $failed[] = ['id' => $id, 'reason' => 'Not found or already processed'];
                    DB::rollBack();
                    continue;
                }

                $platform = Platform::findOrFail($changeRequest->platform_id);

                foreach ($changeRequest->changes as $field => $change) {
                    $platform->{$field} = $change['new'];
                }

                $platform->save();

                $changeRequest->update([
                    'status' => 'approved',
                    'reviewed_by' => $reviewedBy,
                    'reviewed_at' => now()
                ]);

                DB::commit();
                $approved[] = $id;

            } catch (\Exception $e) {
                DB::rollBack();
                $failed[] = ['id' => $id, 'reason' => $e->getMessage()];
            }
        }

        Log::info(self::LOG_PREFIX . 'Bulk approve completed', [
            'approved_count' => count($approved),
            'failed_count' => count($failed),
            'reviewed_by' => $reviewedBy
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Bulk approval completed',
            'data' => [
                'approved' => $approved,
                'failed' => $failed,
                'summary' => [
                    'total' => count($changeRequestIds),
                    'approved' => count($approved),
                    'failed' => count($failed)
                ]
            ]
        ]);
    }

    /**
     * Get statistics about change requests
     */
    public function statistics()
    {
        $stats = [
            'pending_count' => PlatformChangeRequest::where('status', 'pending')->count(),
            'approved_count' => PlatformChangeRequest::where('status', 'approved')->count(),
            'rejected_count' => PlatformChangeRequest::where('status', 'rejected')->count(),
            'total_count' => PlatformChangeRequest::count(),
            'recent_pending' => PlatformChangeRequest::where('status', 'pending')
                ->with(['platform', 'requestedBy'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'today_count' => PlatformChangeRequest::whereDate('created_at', today())->count(),
            'this_week_count' => PlatformChangeRequest::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count()
        ];

        return response()->json([
            'status' => true,
            'data' => $stats
        ]);
    }
}

