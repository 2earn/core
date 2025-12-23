<?php

namespace App\Services\PartnerRequest;

use App\Models\PartnerRequest;
use Core\Enum\BePartnerRequestStatus;
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
}

