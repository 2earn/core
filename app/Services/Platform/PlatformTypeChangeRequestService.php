<?php

namespace App\Services\Platform;

use App\Models\PlatformTypeChangeRequest;
use App\Models\Platform;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PlatformTypeChangeRequestService
{
    /**
     * Get pending platform type change requests
     *
     * @param int|null $limit
     * @return Collection
     */
    public function getPendingRequests(?int $limit = null): Collection
    {
        $query = PlatformTypeChangeRequest::with(['platform', 'requestedBy'])
            ->where('status', PlatformTypeChangeRequest::STATUS_PENDING)
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Get total count of pending requests
     *
     * @return int
     */
    public function getTotalPending(): int
    {
        return PlatformTypeChangeRequest::where('status', PlatformTypeChangeRequest::STATUS_PENDING)->count();
    }

    /**
     * Get pending requests with total count
     *
     * @param int|null $limit
     * @return array
     */
    public function getPendingRequestsWithTotal(?int $limit = null): array
    {
        return [
            'pendingRequests' => $this->getPendingRequests($limit),
            'totalPending' => $this->getTotalPending()
        ];
    }

    /**
     * Find a platform type change request by ID
     *
     * @param int $requestId
     * @return PlatformTypeChangeRequest
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findRequest(int $requestId): PlatformTypeChangeRequest
    {
        return PlatformTypeChangeRequest::findOrFail($requestId);
    }

    /**
     * Find a platform type change request by ID with relationships
     *
     * @param int $requestId
     * @param array $relations
     * @return PlatformTypeChangeRequest
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findRequestWithRelations(int $requestId, array $relations = []): PlatformTypeChangeRequest
    {
        return PlatformTypeChangeRequest::with($relations)->findOrFail($requestId);
    }

    /**
     * Approve a platform type change request and update platform type
     *
     * @param int $requestId
     * @param int $reviewedBy
     * @return PlatformTypeChangeRequest
     * @throws \Exception
     */
    public function approveRequest(int $requestId, int $reviewedBy): PlatformTypeChangeRequest
    {
        $request = $this->findRequest($requestId);

        if ($request->status !== PlatformTypeChangeRequest::STATUS_PENDING) {
            throw new \Exception('This request has already been processed');
        }

        $platform = $request->platform;
        $platform->type = $request->new_type;
        $platform->updated_by = $reviewedBy;
        $platform->save();

        $request->status = PlatformTypeChangeRequest::STATUS_APPROVED;
        $request->reviewed_by = $reviewedBy;
        $request->reviewed_at = now();
        $request->save();

        return $request;
    }

    /**
     * Reject a platform type change request
     *
     * @param int $requestId
     * @param int $reviewedBy
     * @param string $rejectionReason
     * @return PlatformTypeChangeRequest
     * @throws \Exception
     */
    public function rejectRequest(int $requestId, int $reviewedBy, string $rejectionReason): PlatformTypeChangeRequest
    {
        $request = $this->findRequest($requestId);

        if ($request->status !== PlatformTypeChangeRequest::STATUS_PENDING) {
            throw new \Exception('This request has already been processed');
        }

        $request->status = PlatformTypeChangeRequest::STATUS_REJECTED;
        $request->rejection_reason = $rejectionReason;
        $request->reviewed_by = $reviewedBy;
        $request->updated_by = $reviewedBy;
        $request->reviewed_at = now();
        $request->save();

        return $request;
    }

    /**
     * Get filtered platform type change requests query
     *
     * @param string|null $statusFilter
     * @param string|null $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getFilteredQuery(?string $statusFilter = null, ?string $search = null): \Illuminate\Database\Eloquent\Builder
    {
        $query = PlatformTypeChangeRequest::with(['platform', 'requestedBy', 'reviewedBy']);

        if ($search) {
            $query->whereHas('platform', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get paginated filtered platform type change requests
     *
     * @param string|null $statusFilter
     * @param string|null $search
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedRequests(?string $statusFilter = null, ?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return $this->getFilteredQuery($statusFilter, $search)->paginate($perPage);
    }

    /**
     * Create a new platform type change request
     *
     * @param int $platformId
     * @param int $oldType
     * @param int $newType
     * @param int $requestedBy
     * @return PlatformTypeChangeRequest
     */
    public function createRequest(int $platformId, int $oldType, int $newType, int $requestedBy): PlatformTypeChangeRequest
    {
        return PlatformTypeChangeRequest::create([
            'platform_id' => $platformId,
            'old_type' => $oldType,
            'new_type' => $newType,
            'status' => PlatformTypeChangeRequest::STATUS_PENDING,
            'requested_by' => $requestedBy,
            'updated_by' => $requestedBy
        ]);
    }
}

