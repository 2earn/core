<?php

namespace App\Services\Platform;

use App\Models\AssignPlatformRole;
use Core\Models\Platform;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssignPlatformRoleService
{
    /**
     * Get paginated role assignments with filters
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedAssignments(array $filters = [], int $perPage = 10)
    {
        $query = AssignPlatformRole::with(['platform', 'user'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        // Filter by search
        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->whereHas('user', function($userQuery) use ($filters) {
                    $userQuery->where('name', 'like', '%' . $filters['search'] . '%')
                             ->orWhere('email', 'like', '%' . $filters['search'] . '%');
                })
                ->orWhereHas('platform', function($platformQuery) use ($filters) {
                    $platformQuery->where('name', 'like', '%' . $filters['search'] . '%');
                })
                ->orWhere('role', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Approve a role assignment
     *
     * @param int $assignmentId
     * @param int $approvedBy
     * @return array ['success' => bool, 'message' => string]
     */
    public function approve(int $assignmentId, int $approvedBy): array
    {
        try {
            DB::beginTransaction();

            $assignment = AssignPlatformRole::findOrFail($assignmentId);

            // Check if already processed
            if ($assignment->status !== AssignPlatformRole::STATUS_PENDING) {
                return [
                    'success' => false,
                    'message' => 'This assignment has already been processed.'
                ];
            }

            // Update platform with the new role assignment
            $platform = Platform::findOrFail($assignment->platform_id);

            switch ($assignment->role) {
                case 'owner':
                    $platform->owner_id = $assignment->user_id;
                    break;
                case 'marketing_manager':
                    $platform->marketing_manager_id = $assignment->user_id;
                    break;
                case 'financial_manager':
                    $platform->financial_manager_id = $assignment->user_id;
                    break;
                default:
                    throw new \Exception('Invalid role: ' . $assignment->role);
            }

            $platform->updated_by = $approvedBy;
            $platform->save();

            // Update assignment status
            $assignment->status = AssignPlatformRole::STATUS_APPROVED;
            $assignment->updated_by = $approvedBy;
            $assignment->save();

            DB::commit();

            Log::info('[AssignPlatformRoleService] Role assignment approved', [
                'assignment_id' => $assignmentId,
                'user_id' => $assignment->user_id,
                'platform_id' => $assignment->platform_id,
                'role' => $assignment->role,
                'approved_by' => $approvedBy
            ]);

            return [
                'success' => true,
                'message' => 'Role assignment approved successfully.'
            ];

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('[AssignPlatformRoleService] Failed to approve role assignment', [
                'assignment_id' => $assignmentId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to approve assignment: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Reject a role assignment
     *
     * @param int $assignmentId
     * @param string $rejectionReason
     * @param int $rejectedBy
     * @return array ['success' => bool, 'message' => string]
     */
    public function reject(int $assignmentId, string $rejectionReason, int $rejectedBy): array
    {
        try {
            DB::beginTransaction();

            $assignment = AssignPlatformRole::findOrFail($assignmentId);

            // Check if already processed
            if ($assignment->status !== AssignPlatformRole::STATUS_PENDING) {
                return [
                    'success' => false,
                    'message' => 'This assignment has already been processed.'
                ];
            }

            // Update assignment status
            $assignment->status = AssignPlatformRole::STATUS_REJECTED;
            $assignment->rejection_reason = $rejectionReason;
            $assignment->updated_by = $rejectedBy;
            $assignment->save();

            // Send notification to user
            if ($assignment->user) {
                $assignment->user->notify(new \App\Notifications\PlatformRoleAssignmentRejected(
                    $assignment->platform,
                    $assignment->role,
                    $rejectionReason
                ));
            }

            DB::commit();

            Log::info('[AssignPlatformRoleService] Role assignment rejected', [
                'assignment_id' => $assignmentId,
                'user_id' => $assignment->user_id,
                'platform_id' => $assignment->platform_id,
                'role' => $assignment->role,
                'rejection_reason' => $rejectionReason,
                'rejected_by' => $rejectedBy
            ]);

            return [
                'success' => true,
                'message' => 'Role assignment rejected successfully.'
            ];

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('[AssignPlatformRoleService] Failed to reject role assignment', [
                'assignment_id' => $assignmentId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to reject assignment: ' . $e->getMessage()
            ];
        }
    }
}

