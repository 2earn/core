<?php

namespace App\Services\InstructorRequest;

use App\Models\InstructorRequest;
use Core\Enum\BeInstructorRequestStatus;
use Illuminate\Support\Facades\Log;

class InstructorRequestService
{
    /**
     * Get the last instructor request for a user
     *
     * @param int $userId
     * @return InstructorRequest|null
     */
    public function getLastInstructorRequest(int $userId): ?InstructorRequest
    {
        try {
            return InstructorRequest::where('user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->first();
        } catch (\Exception $e) {
            Log::error('Error fetching last instructor request: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the last instructor request with a specific status
     *
     * @param int $userId
     * @param int $status
     * @return InstructorRequest|null
     */
    public function getLastInstructorRequestByStatus(int $userId, int $status): ?InstructorRequest
    {
        try {
            return InstructorRequest::where('user_id', $userId)
                ->where('status', $status)
                ->orderBy('created_at', 'DESC')
                ->first();
        } catch (\Exception $e) {
            Log::error('Error fetching last instructor request by status: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a new instructor request
     *
     * @param array $data
     * @return InstructorRequest|null
     */
    public function createInstructorRequest(array $data): ?InstructorRequest
    {
        try {
            return InstructorRequest::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating instructor request: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if user has an in-progress instructor request
     *
     * @param int $userId
     * @return bool
     */
    public function hasInProgressRequest(int $userId): bool
    {
        try {
            return InstructorRequest::where('user_id', $userId)
                ->where('status', BeInstructorRequestStatus::InProgress->value)
                ->exists();
        } catch (\Exception $e) {
            Log::error('Error checking in-progress instructor request: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get instructor request by ID
     *
     * @param int $id
     * @return InstructorRequest|null
     */
    public function getInstructorRequestById(int $id): ?InstructorRequest
    {
        try {
            return InstructorRequest::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching instructor request by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update instructor request
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateInstructorRequest(int $id, array $data): bool
    {
        try {
            return InstructorRequest::where('id', $id)->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating instructor request: ' . $e->getMessage());
            return false;
        }
    }
}

