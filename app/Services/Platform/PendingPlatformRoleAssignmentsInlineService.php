<?php

namespace App\Services\Platform;

use App\Models\AssignPlatformRole;
use Illuminate\Database\Eloquent\Collection;

class PendingPlatformRoleAssignmentsInlineService
{
    /**
     * Get pending platform role assignments
     *
     * @param int|null $limit
     * @return Collection
     */
    public function getPendingAssignments(?int $limit = null): Collection
    {
        $query = AssignPlatformRole::where('status', AssignPlatformRole::STATUS_PENDING)
            ->with(['platform', 'user'])
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $query->take($limit);
        }

        return $query->get();
    }

    /**
     * Get total count of pending assignments
     *
     * @return int
     */
    public function getTotalPending(): int
    {
        return AssignPlatformRole::where('status', AssignPlatformRole::STATUS_PENDING)->count();
    }

    /**
     * Get pending assignments with total count
     *
     * @param int|null $limit
     * @return array
     */
    public function getPendingAssignmentsWithTotal(?int $limit = null): array
    {
        return [
            'pendingAssignments' => $this->getPendingAssignments($limit),
            'totalPending' => $this->getTotalPending()
        ];
    }
}

