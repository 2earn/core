<?php

namespace App\Services\Deals;

use App\Models\Deal;
use App\Models\DealValidationRequest;
use Illuminate\Database\Eloquent\Collection;

class PendingDealValidationRequestsInlineService
{
    /**
     * Get pending deal validation requests
     *
     * @param int|null $limit
     * @return Collection
     */
    public function getPendingRequests(?int $limit = null): Collection
    {
        $query = DealValidationRequest::with(['deal.platform', 'requestedBy'])
            ->where('status', 'pending')
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
        return DealValidationRequest::where('status', 'pending')->count();
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
     * Find a deal validation request by ID
     *
     * @param int $requestId
     * @return DealValidationRequest
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findRequest(int $requestId): DealValidationRequest
    {
        return DealValidationRequest::findOrFail($requestId);
    }

    /**
     * Find a deal validation request by ID with relationships
     *
     * @param int $requestId
     * @param array $relations
     * @return DealValidationRequest
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findRequestWithRelations(int $requestId, array $relations = []): DealValidationRequest
    {
        return DealValidationRequest::with($relations)->findOrFail($requestId);
    }

    /**
     * Approve a deal validation request and validate the deal
     *
     * @param int $requestId
     * @param int $reviewedBy
     * @return DealValidationRequest
     * @throws \Exception
     */
    public function approveRequest(int $requestId, int $reviewedBy): DealValidationRequest
    {
        $request = $this->findRequest($requestId);

        if ($request->status !== 'pending') {
            throw new \Exception('This request has already been processed');
        }

        $deal = $request->deal;
        $deal->validated = true;
        $deal->updated_by = $reviewedBy;
        $deal->save();

        $request->status = 'approved';
        $request->reviewed_by = $reviewedBy;
        $request->reviewed_at = now();
        $request->save();

        return $request;
    }

    /**
     * Reject a deal validation request
     *
     * @param int $requestId
     * @param int $reviewedBy
     * @param string $rejectionReason
     * @return DealValidationRequest
     * @throws \Exception
     */
    public function rejectRequest(int $requestId, int $reviewedBy, string $rejectionReason): DealValidationRequest
    {
        $request = $this->findRequest($requestId);

        if ($request->status !== 'pending') {
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
     * Get filtered deal validation requests query
     *
     * @param string|null $statusFilter
     * @param string|null $search
     * @param int|null $userId
     * @param bool $isSuperAdmin
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getFilteredQuery(
        ?string $statusFilter = null,
        ?string $search = null,
        ?int $userId = null,
        bool $isSuperAdmin = false
    ): \Illuminate\Database\Eloquent\Builder {
        $query = DealValidationRequest::with(['deal.platform', 'requestedBy'])
            ->orderBy('created_at', 'desc');

        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('deal', function ($subQ) use ($search) {
                    $subQ->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('requestedBy', function ($subQ) use ($search) {
                    $subQ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            });
        }

        if (!$isSuperAdmin && $userId) {
            $query->whereHas('deal.platform', function ($q) use ($userId) {
                $q->whereHas('roles', function ($roleQuery) use ($userId) {
                    $roleQuery->where('user_id', $userId);
                });
            });
        }

        return $query;
    }

    /**
     * Get paginated filtered deal validation requests
     *
     * @param string|null $statusFilter
     * @param string|null $search
     * @param int|null $userId
     * @param bool $isSuperAdmin
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginatedRequests(
        ?string $statusFilter = null,
        ?string $search = null,
        ?int $userId = null,
        bool $isSuperAdmin = false,
        int $perPage = 10
    ): \Illuminate\Contracts\Pagination\LengthAwarePaginator {
        return $this->getFilteredQuery($statusFilter, $search, $userId, $isSuperAdmin)
            ->paginate($perPage);
    }
}

