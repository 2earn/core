<?php

namespace App\Services\Platform;

use App\Models\PlatformChangeRequest;
use Illuminate\Database\Eloquent\Collection;

class PendingPlatformChangeRequestsInlineService
{
    /**
     * Get pending platform change requests
     *
     * @param int|null $limit
     * @return Collection
     */
    public function getPendingRequests(?int $limit = null): Collection
    {
        $query = PlatformChangeRequest::with(['platform', 'requestedBy'])
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
        return PlatformChangeRequest::where('status', 'pending')->count();
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

