<?php

namespace App\Services\PartnerRequest;

use App\Enums\BePartnerRequestStatus;
use App\Models\PartnerRequest;
use Illuminate\Support\Facades\Log;

class PartnerRequestService
{
    /**
     * Get the last partner request for a user
     *
     * @param int $userId
     * @return PartnerRequest|null
     */
    public function getLastPartnerRequest(int $userId): ?PartnerRequest
    {
        try {
            return PartnerRequest::where('user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->first();
        } catch (\Exception $e) {
            Log::error('Error fetching last partner request: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the last partner request with a specific status
     *
     * @param int $userId
     * @param int $status
     * @return PartnerRequest|null
     */
    public function getLastPartnerRequestByStatus(int $userId, int $status): ?PartnerRequest
    {
        try {
            return PartnerRequest::where('user_id', $userId)
                ->where('status', $status)
                ->orderBy('created_at', 'DESC')
                ->first();
        } catch (\Exception $e) {
            Log::error('Error fetching last partner request by status: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a new partner request
     *
     * @param array $data
     * @return PartnerRequest|null
     */
    public function createPartnerRequest(array $data): ?PartnerRequest
    {
        try {
            return PartnerRequest::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating partner request: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if user has an in-progress partner request
     *
     * @param int $userId
     * @return bool
     */
    public function hasInProgressRequest(int $userId): bool
    {
        try {
            return PartnerRequest::where('user_id', $userId)
                ->where('status', BePartnerRequestStatus::InProgress->value)
                ->exists();
        } catch (\Exception $e) {
            Log::error('Error checking in-progress partner request: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get partner request by ID
     *
     * @param int $id
     * @return PartnerRequest|null
     */
    public function getPartnerRequestById(int $id): ?PartnerRequest
    {
        try {
            return PartnerRequest::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching partner request by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update partner request
     *
     * @param int $id
     * @param array $data
     * @return PartnerRequest|null
     */
    public function updatePartnerRequest(int $id, array $data): ?PartnerRequest
    {
        try {
            $partnerRequest = PartnerRequest::find($id);
            if ($partnerRequest) {
                $partnerRequest->update($data);
                return $partnerRequest;
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Error updating partner request: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all partner requests with a specific status
     *
     * @param int $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPartnerRequestsByStatus(int $status)
    {
        try {
            return PartnerRequest::where('status', $status)
                ->orderBy('created_at', 'DESC')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching partner requests by status: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get filtered and paginated partner requests
     *
     * @param string $searchTerm
     * @param string $statusFilter
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getFilteredPartnerRequests(string $searchTerm = '', string $statusFilter = '', int $perPage = 15)
    {
        try {
            $query = PartnerRequest::with(['user', 'businessSector'])->orderBy('created_at', 'DESC');

            if (!empty($searchTerm)) {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('company_name', 'like', '%' . $searchTerm . '%')
                        ->orWhereHas('user', function ($q) use ($searchTerm) {
                            $q->where('name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('email', 'like', '%' . $searchTerm . '%');
                        });
                });
            }

            if (!empty($statusFilter)) {
                $query->where('status', $statusFilter);
            }

            return $query->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching filtered partner requests: ' . $e->getMessage());
            return PartnerRequest::with(['user', 'businessSector'])->paginate($perPage);
        }
    }
}

