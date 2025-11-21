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
        $query = PlatformChangeRequest::where('status', 'pending')
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
     * Get pending change requests
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
     * Get statistics about change requests
     *
     * @return array
     */
    public function getStatistics(): array
    {
        return [
            'pending_count' => PlatformChangeRequest::where('status', 'pending')->count(),
            'approved_count' => PlatformChangeRequest::where('status', 'approved')->count(),
            'rejected_count' => PlatformChangeRequest::where('status', 'rejected')->count(),
            'total_count' => PlatformChangeRequest::count(),
            'recent_pending' => PlatformChangeRequest::where('status', 'pending')
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
        return PlatformChangeRequest::where('status', 'pending')->count();
    }
}

