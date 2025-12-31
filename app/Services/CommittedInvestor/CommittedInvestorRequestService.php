<?php

namespace App\Services\CommittedInvestor;

use App\Models\CommittedInvestorRequest;
use Core\Enum\RequestStatus;
use Illuminate\Support\Facades\Log;

class CommittedInvestorRequestService
{
    /**
     * Get the last committed investor request for a user
     *
     * @param int $userId
     * @return CommittedInvestorRequest|null
     */
    public function getLastCommittedInvestorRequest(int $userId): ?CommittedInvestorRequest
    {
        try {
            return CommittedInvestorRequest::where('user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->first();
        } catch (\Exception $e) {
            Log::error('Error fetching last committed investor request: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the last committed investor request with a specific status
     *
     * @param int $userId
     * @param int $status
     * @return CommittedInvestorRequest|null
     */
    public function getLastCommittedInvestorRequestByStatus(int $userId, int $status): ?CommittedInvestorRequest
    {
        try {
            return CommittedInvestorRequest::where('user_id', $userId)
                ->where('status', $status)
                ->orderBy('created_at', 'DESC')
                ->first();
        } catch (\Exception $e) {
            Log::error('Error fetching last committed investor request by status: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a new committed investor request
     *
     * @param array $data
     * @return CommittedInvestorRequest|null
     */
    public function createCommittedInvestorRequest(array $data): ?CommittedInvestorRequest
    {
        try {
            return CommittedInvestorRequest::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating committed investor request: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if user has an in-progress committed investor request
     *
     * @param int $userId
     * @return bool
     */
    public function hasInProgressRequest(int $userId): bool
    {
        try {
            return CommittedInvestorRequest::where('user_id', $userId)
                ->where('status', RequestStatus::InProgress->value)
                ->exists();
        } catch (\Exception $e) {
            Log::error('Error checking in-progress committed investor request: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get committed investor request by ID
     *
     * @param int $id
     * @return CommittedInvestorRequest|null
     */
    public function getCommittedInvestorRequestById(int $id): ?CommittedInvestorRequest
    {
        try {
            return CommittedInvestorRequest::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching committed investor request by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update committed investor request
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateCommittedInvestorRequest(int $id, array $data): bool
    {
        try {
            return CommittedInvestorRequest::where('id', $id)->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating committed investor request: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all in-progress committed investor requests
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getInProgressRequests()
    {
        try {
            return CommittedInvestorRequest::where('status', RequestStatus::InProgress->value)->get();
        } catch (\Exception $e) {
            Log::error('Error fetching in-progress committed investor requests: ' . $e->getMessage());
            return new \Illuminate\Database\Eloquent\Collection();
        }
    }
}

