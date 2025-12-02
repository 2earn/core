<?php

namespace App\Services\Platform;

use App\Models\PlatformChangeRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PlatformChangeRequestService
{
    /**
     * Get pending platform change requests with pagination
     *
     * @param int $page
     * @param int $perPage
     * @param int|null $platformId
     * @return LengthAwarePaginator
     */
    public function getPendingRequestsPaginated(int $page = 1, int $perPage = 20, ?int $platformId = null): LengthAwarePaginator
    {
        $query = PlatformChangeRequest::where('status', PlatformChangeRequest::STATUS_PENDING)
            ->with(['platform', 'requestedBy']);

        if ($platformId) {
            $query->where('platform_id', $platformId);
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Get change requests with filtering and pagination
     *
     * @param string|null $status
     * @param int|null $platformId
     * @param int $page
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getChangeRequestsPaginated(?string $status = null, ?int $platformId = null, int $page = 1, int $perPage = 20): LengthAwarePaginator
    {
        $query = PlatformChangeRequest::with(['platform', 'requestedBy', 'reviewedBy']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($platformId) {
            $query->where('platform_id', $platformId);
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Get a specific change request by ID
     *
     * @param int $id
     * @return PlatformChangeRequest|null
     */
    public function getChangeRequestById(int $id): ?PlatformChangeRequest
    {
        return PlatformChangeRequest::with(['platform', 'requestedBy', 'reviewedBy'])
            ->find($id);
    }

    /**
     * Find a platform change request by ID
     *
     * @param int $requestId
     * @return PlatformChangeRequest
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findRequest(int $requestId): PlatformChangeRequest
    {
        return PlatformChangeRequest::findOrFail($requestId);
    }

    /**
     * Find a platform change request by ID with relationships
     *
     * @param int $requestId
     * @param array $relations
     * @return PlatformChangeRequest
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findRequestWithRelations(int $requestId, array $relations = []): PlatformChangeRequest
    {
        return PlatformChangeRequest::with($relations)->findOrFail($requestId);
    }

    /**
     * Approve a platform change request and apply changes
     *
     * @param int $requestId
     * @param int $reviewedBy
     * @return PlatformChangeRequest
     * @throws \Exception
     */
    public function approveRequest(int $requestId, int $reviewedBy): PlatformChangeRequest
    {
        $request = $this->findRequest($requestId);

        if ($request->status !== PlatformChangeRequest::STATUS_PENDING) {
            throw new \Exception('This request has already been processed');
        }

        $platform = $request->platform;

        // Apply the changes to the platform
        foreach ($request->changes as $field => $change) {
            $platform->{$field} = $change['new'];
        }

        $platform->updated_by = $reviewedBy;
        $platform->save();

        // Update request status
        $request->status = PlatformChangeRequest::STATUS_APPROVED;
        $request->reviewed_by = $reviewedBy;
        $request->reviewed_at = now();
        $request->save();

        return $request;
    }

    /**
     * Reject a platform change request
     *
     * @param int $requestId
     * @param int $reviewedBy
     * @param string $rejectionReason
     * @return PlatformChangeRequest
     * @throws \Exception
     */
    public function rejectRequest(int $requestId, int $reviewedBy, string $rejectionReason): PlatformChangeRequest
    {
        $request = $this->findRequest($requestId);

        if ($request->status !== PlatformChangeRequest::STATUS_PENDING) {
            throw new \Exception('This request has already been processed');
        }

        $request->status = 'rejected';
        $request->rejection_reason = $rejectionReason;
        $request->reviewed_by = $reviewedBy;
        $request->reviewed_at = now();
        $request->save();

        return $request;
    }

    /**
     * Get filtered platform change requests query
     *
     * @param string|null $statusFilter
     * @param string|null $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getFilteredQuery(?string $statusFilter = null, ?string $search = null): \Illuminate\Database\Eloquent\Builder
    {
        $query = PlatformChangeRequest::with(['platform', 'requestedBy', 'reviewedBy']);

        // Apply search filter
        if ($search) {
            $query->whereHas('platform', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        // Apply status filter
        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get paginated filtered platform change requests
     *
     * @param string|null $statusFilter
     * @param string|null $search
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedRequests(?string $statusFilter = null, ?string $search = null, int $perPage = 10): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->getFilteredQuery($statusFilter, $search)->paginate($perPage);
    }

    /**
     * Create a new platform change request
     *
     * @param int $platformId
     * @param array $changes
     * @param int $requestedBy
     * @return PlatformChangeRequest
     */
    public function createRequest(int $platformId, array $changes, int $requestedBy): PlatformChangeRequest
    {
        return PlatformChangeRequest::create([
            'platform_id' => $platformId,
            'changes' => $changes,
            'status' => PlatformChangeRequest::STATUS_PENDING,
            'requested_by' => $requestedBy
        ]);
    }

    /**
     * Cancel a platform change request
     *
     * @param int $requestId
     * @return PlatformChangeRequest
     * @throws \Exception
     */
    public function cancelRequest(int $requestId): PlatformChangeRequest
    {
        $changeRequest = $this->findRequest($requestId);

        if (!$changeRequest->canBeCancelled()) {
            throw new \Exception('Only pending change requests can be cancelled. Current status: ' . $changeRequest->status);
        }

        $changeRequest->status = PlatformChangeRequest::STATUS_CANCELLED;
        $changeRequest->save();

        return $changeRequest;
    }

    /**
     * Get pending change requests
     *
     * @param int|null $limit
     * @return Collection
     */
    public function getPendingRequests(?int $limit = null): Collection
    {
        $query = PlatformChangeRequest::with(['platform', 'requestedBy'])
            ->where('status', PlatformChangeRequest::STATUS_PENDING)
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Get statistics about change requests
     *
     * @return array
     */
    public function getStatistics(): array
    {
        return [
            'pending_count' => PlatformChangeRequest::where('status', PlatformChangeRequest::STATUS_PENDING)->count(),
            'approved_count' => PlatformChangeRequest::where('status', PlatformChangeRequest::STATUS_APPROVED)->count(),
            'rejected_count' => PlatformChangeRequest::where('status', PlatformChangeRequest::STATUS_REJECTED)->count(),
            'total_count' => PlatformChangeRequest::count(),
            'recent_pending' => PlatformChangeRequest::where('status', PlatformChangeRequest::STATUS_PENDING)
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
    }

    /**
     * Get total count of pending requests
     *
     * @return int
     */
    public function getTotalPending(): int
    {
        return PlatformChangeRequest::where('status', PlatformChangeRequest::STATUS_PENDING)->count();
    }
}

