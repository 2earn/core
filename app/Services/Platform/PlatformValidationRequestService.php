<?php

namespace App\Services\Platform;

use App\Models\PlatformValidationRequest;
use Core\Models\Platform;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PlatformValidationRequestService
{
    /**
     * Get pending platform validation requests
     *
     * @param int|null $limit
     * @return Collection
     */
    public function getPendingRequests(?int $limit = null): Collection
    {
        $query = PlatformValidationRequest::with(['platform', 'requestedBy'])
            ->where('status', PlatformValidationRequest::STATUS_PENDING)
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
        return PlatformValidationRequest::where('status', 'pending')->count();
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
     * Find a platform validation request by ID
     *
     * @param int $requestId
     * @return PlatformValidationRequest
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findRequest(int $requestId): PlatformValidationRequest
    {
        return PlatformValidationRequest::findOrFail($requestId);
    }

    /**
     * Find a platform validation request by ID with relationships
     *
     * @param int $requestId
     * @param array $relations
     * @return PlatformValidationRequest
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findRequestWithRelations(int $requestId, array $relations = []): PlatformValidationRequest
    {
        return PlatformValidationRequest::with($relations)->findOrFail($requestId);
    }

    /**
     * Approve a platform validation request and enable the platform
     *
     * @param int $requestId
     * @param int $reviewedBy
     * @return PlatformValidationRequest
     * @throws \Exception
     */
    public function approveRequest(int $requestId, int $reviewedBy): PlatformValidationRequest
    {
        $request = $this->findRequest($requestId);

        if ($request->status !== 'pending') {
            throw new \Exception('This request has already been processed');
        }

        // Enable the platform
        $platform = $request->platform;
        $platform->enabled = true;
        $platform->updated_by = $reviewedBy;
        $platform->save();

        // Update request status
        $request->status = PlatformValidationRequest::STATUS_APPROVED;
        $request->reviewed_by = $reviewedBy;
        $request->reviewed_at = now();
        $request->save();

        return $request;
    }

    /**
     * Reject a platform validation request
     *
     * @param int $requestId
     * @param int $reviewedBy
     * @param string $rejectionReason
     * @return PlatformValidationRequest
     * @throws \Exception
     */
    public function rejectRequest(int $requestId, int $reviewedBy, string $rejectionReason): PlatformValidationRequest
    {
        $request = $this->findRequest($requestId);

        if ($request->status !== 'pending') {
            throw new \Exception('This request has already been processed');
        }

        // Update request status
        $request->status = PlatformValidationRequest::STATUS_REJECTED;
        $request->rejection_reason = $rejectionReason;
        $request->reviewed_by = $reviewedBy;
        $request->updated_by = $reviewedBy;
        $request->reviewed_at = now();
        $request->save();

        return $request;
    }

    /**
     * Get filtered platform validation requests query
     *
     * @param string|null $statusFilter
     * @param string|null $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getFilteredQuery(?string $statusFilter = null, ?string $search = null): \Illuminate\Database\Eloquent\Builder
    {
        $query = PlatformValidationRequest::with(['platform', 'requestedBy', 'reviewedBy']);

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
     * Get paginated filtered platform validation requests
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
}

