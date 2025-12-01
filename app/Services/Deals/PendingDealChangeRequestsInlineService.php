<?php

namespace App\Services\Deals;

use App\Models\DealChangeRequest;
use Illuminate\Database\Eloquent\Collection;

class PendingDealChangeRequestsInlineService
{
    /**
     * Get pending deal change requests
     *
     * @param int|null $limit
     * @return Collection
     */
    public function getPendingRequests(?int $limit = null): Collection
    {
        $query = DealChangeRequest::with(['deal.platform', 'requestedBy'])
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
        return DealChangeRequest::where('status', 'pending')->count();
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
     * Find a deal change request by ID
     *
     * @param int $requestId
     * @return DealChangeRequest
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findRequest(int $requestId): DealChangeRequest
    {
        return DealChangeRequest::findOrFail($requestId);
    }

    /**
     * Find a deal change request by ID with relationships
     *
     * @param int $requestId
     * @param array $relations
     * @return DealChangeRequest
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findRequestWithRelations(int $requestId, array $relations = []): DealChangeRequest
    {
        return DealChangeRequest::with($relations)->findOrFail($requestId);
    }
}

