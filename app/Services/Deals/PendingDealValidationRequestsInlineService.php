<?php

namespace App\Services\Deals;

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
}

