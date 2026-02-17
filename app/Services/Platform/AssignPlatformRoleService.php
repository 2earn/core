<?php

namespace App\Services\Platform;

use App\Models\AssignPlatformRole;
use App\Models\Platform;
use App\Models\EntityRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->whereHas('user', function ($userQuery) use ($filters) {
                    $userQuery->where('name', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('email', 'like', '%' . $filters['search'] . '%');
                })
                    ->orWhereHas('platform', function ($platformQuery) use ($filters) {
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

            if ($assignment->status !== AssignPlatformRole::STATUS_PENDING) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'This assignment has already been processed.'
                ];
            }

            $platform = Platform::find($assignment->platform_id);
            if (!$platform) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Failed to approve assignment: Platform not found.'
                ];
            }

            // Check if role already exists for this platform
            $existingRole = EntityRole::where('roleable_type', 'App\\Models\\Platform')
                ->where('roleable_id', $assignment->platform_id)
                ->where('name', $assignment->role)
                ->first();

            if ($existingRole) {
                // Update existing role to new user
                $existingRole->user_id = $assignment->user_id;
                $existingRole->updated_by = $approvedBy;
                $existingRole->save();
            } else {
                // Create new EntityRole
                EntityRole::create([
                    'user_id' => $assignment->user_id,
                    'name' => $assignment->role,
                    'roleable_type' => 'App\\Models\\Platform',
                    'roleable_id' => $assignment->platform_id,
                    'created_by' => $approvedBy,
                    'updated_by' => $approvedBy,
                ]);
            }

            $assignment->status = AssignPlatformRole::STATUS_APPROVED;
            $assignment->updated_by = $approvedBy;
            $assignment->save();

            DB::commit();

            Log::info('[AssignPlatformRoleService] Role assignment approved via EntityRole', [
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

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e; // Re-throw to let controller handle 404
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

            if ($assignment->status !== AssignPlatformRole::STATUS_PENDING) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'This assignment has already been processed.'
                ];
            }

            // Mark assignment as rejected
            // Note: We don't create or modify EntityRole records on rejection
            // EntityRole records are only created when an assignment is approved
            $assignment->status = AssignPlatformRole::STATUS_REJECTED;
            $assignment->rejection_reason = $rejectionReason;
            $assignment->updated_by = $rejectedBy;
            $assignment->save();

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

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e; // Re-throw to let controller handle 404
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

