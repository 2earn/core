<?php

namespace App\Services\DealChangeRequest;

use App\Models\DealChangeRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class DealChangeRequestService
{
    /**
     * Get paginated deal change requests with filters
     *
     * @param string|null $search
     * @param string $statusFilter
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedRequests(
        ?string $search = null,
        string $statusFilter = 'pending',
        int $perPage = 10
    ): LengthAwarePaginator
    {
        try {
            $query = DealChangeRequest::with(['deal.platform', 'requestedBy', 'reviewedBy']);

            // Apply search filter
            if (!is_null($search) && $search !== '') {
                $query->whereHas('deal', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('id', 'like', '%' . $search . '%');
                });
            }

            // Apply status filter
            if ($statusFilter !== 'all') {
                $query->where('status', $statusFilter);
            }

            return $query->orderBy('created_at', 'desc')
                         ->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching paginated deal change requests: ' . $e->getMessage());
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage);
        }
    }

    /**
     * Get all deal change requests with optional filters
     *
     * @param string|null $search
     * @param string|null $statusFilter
     * @return Collection
     */
    public function getAllRequests(?string $search = null, ?string $statusFilter = null): Collection
    {
        try {
            $query = DealChangeRequest::with(['deal.platform', 'requestedBy', 'reviewedBy']);

            if (!is_null($search) && $search !== '') {
                $query->whereHas('deal', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('id', 'like', '%' . $search . '%');
                });
            }

            if (!is_null($statusFilter) && $statusFilter !== 'all') {
                $query->where('status', $statusFilter);
            }

            return $query->orderBy('created_at', 'desc')->get();
        } catch (\Exception $e) {
            Log::error('Error fetching all deal change requests: ' . $e->getMessage());
            return new Collection();
        }
    }

    /**
     * Get deal change request by ID
     *
     * @param int $id
     * @return DealChangeRequest|null
     */
    public function getRequestById(int $id): ?DealChangeRequest
    {
        try {
            return DealChangeRequest::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching deal change request by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get deal change request by ID with relationships
     *
     * @param int $id
     * @param array $with
     * @return DealChangeRequest|null
     */
    public function getRequestByIdWithRelations(int $id, array $with = []): ?DealChangeRequest
    {
        try {
            $query = DealChangeRequest::query();

            if (!empty($with)) {
                $query->with($with);
            }

            return $query->find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching deal change request with relations: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a new deal change request
     *
     * @param array $data
     * @return DealChangeRequest|null
     */
    public function createRequest(array $data): ?DealChangeRequest
    {
        try {
            return DealChangeRequest::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating deal change request: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update deal change request
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateRequest(int $id, array $data): bool
    {
        try {
            return DealChangeRequest::where('id', $id)->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating deal change request: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Approve a deal change request
     *
     * @param int $id
     * @param int $reviewedBy
     * @return bool
     */
    public function approveRequest(int $id, int $reviewedBy): bool
    {
        try {
            return $this->updateRequest($id, [
                'status' => 'approved',
                'reviewed_by' => $reviewedBy,
                'reviewed_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error approving deal change request: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Reject a deal change request
     *
     * @param int $id
     * @param int $reviewedBy
     * @param string $rejectionReason
     * @return bool
     */
    public function rejectRequest(int $id, int $reviewedBy, string $rejectionReason): bool
    {
        try {
            return $this->updateRequest($id, [
                'status' => 'rejected',
                'rejection_reason' => $rejectionReason,
                'reviewed_by' => $reviewedBy,
                'reviewed_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error rejecting deal change request: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get requests by status
     *
     * @param string $status
     * @return Collection
     */
    public function getRequestsByStatus(string $status): Collection
    {
        try {
            return DealChangeRequest::where('status', $status)
                ->with(['deal.platform', 'requestedBy', 'reviewedBy'])
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching deal change requests by status: ' . $e->getMessage());
            return new Collection();
        }
    }

    /**
     * Get requests by deal ID
     *
     * @param int $dealId
     * @return Collection
     */
    public function getRequestsByDealId(int $dealId): Collection
    {
        try {
            return DealChangeRequest::where('deal_id', $dealId)
                ->with(['requestedBy', 'reviewedBy'])
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching deal change requests by deal ID: ' . $e->getMessage());
            return new Collection();
        }
    }

    /**
     * Count pending requests
     *
     * @return int
     */
    public function countPendingRequests(): int
    {
        try {
            return DealChangeRequest::where('status', 'pending')->count();
        } catch (\Exception $e) {
            Log::error('Error counting pending deal change requests: ' . $e->getMessage());
            return 0;
        }
    }
}

