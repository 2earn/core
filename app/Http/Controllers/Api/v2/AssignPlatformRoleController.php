<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Services\Platform\AssignPlatformRoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssignPlatformRoleController extends Controller
{
    protected AssignPlatformRoleService $assignPlatformRoleService;

    public function __construct(AssignPlatformRoleService $assignPlatformRoleService)
    {
        $this->assignPlatformRoleService = $assignPlatformRoleService;
    }

    /**
     * Get paginated role assignments with filters
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'status' => $request->get('status', 'all'),
                'search' => $request->get('search', '')
            ];

            $perPage = (int) $request->get('per_page', 10);
            $perPage = min(max($perPage, 1), 100);

            $assignments = $this->assignPlatformRoleService->getPaginatedAssignments($filters, $perPage);

            return response()->json([
                'success' => true,
                'data' => $assignments
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching assignments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a role assignment
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function approve(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'approved_by' => 'required|integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $result = $this->assignPlatformRoleService->approve($id, $request->approved_by);

            $status = $result['success'] ? 200 : 400;

            return response()->json($result, $status);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error approving assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a role assignment
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function reject(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'rejected_by' => 'required|integer|exists:users,id',
                'rejection_reason' => 'required|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $result = $this->assignPlatformRoleService->reject(
                $id,
                $request->rejection_reason,
                $request->rejected_by
            );

            $status = $result['success'] ? 200 : 400;

            return response()->json($result, $status);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting assignment: ' . $e->getMessage()
            ], 500);
        }
    }
}

