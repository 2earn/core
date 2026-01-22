<?php

namespace App\Services;

use App\Enums\RequestStatus;
use App\Models\InstructorRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class InstructorRequestService
{
    /**
     * Get all in-progress instructor requests
     *
     * @return Collection
     */
    public function getInProgressRequests(): Collection
    {
        try {
            return InstructorRequest::where('status', RequestStatus::InProgress->value)->get();
        } catch (\Exception $e) {
            Log::error('Error fetching in-progress instructor requests: ' . $e->getMessage());
            return new Collection();
        }
    }

    /**
     * Get instructor requests by status
     *
     * @param string|int $status
     * @return Collection
     */
    public function getByStatus($status): Collection
    {
        try {
            return InstructorRequest::where('status', $status)->get();
        } catch (\Exception $e) {
            Log::error('Error fetching instructor requests by status: ' . $e->getMessage(), ['status' => $status]);
            return new Collection();
        }
    }

    /**
     * Get instructor request by ID
     *
     * @param int $id
     * @return InstructorRequest|null
     */
    public function getById(int $id): ?InstructorRequest
    {
        try {
            return InstructorRequest::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching instructor request by ID: ' . $e->getMessage(), ['id' => $id]);
            return null;
        }
    }

    /**
     * Get all instructor requests
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        try {
            return InstructorRequest::all();
        } catch (\Exception $e) {
            Log::error('Error fetching all instructor requests: ' . $e->getMessage());
            return new Collection();
        }
    }

    /**
     * Update instructor request status
     *
     * @param int $id
     * @param string|int $status
     * @return bool
     */
    public function updateStatus(int $id, $status): bool
    {
        try {
            $request = InstructorRequest::findOrFail($id);
            return $request->update([
                'status' => $status,
                'examination_date' => now(),
                'examiner_id' => auth()->user()->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating instructor request status: ' . $e->getMessage(), [
                'id' => $id,
                'status' => $status
            ]);
            return false;
        }
    }

    /**
     * Update instructor request with note (for rejection)
     *
     * @param int $id
     * @param string|int $status
     * @param string $note
     * @return bool
     */
    public function updateStatusWithNote(int $id, $status, string $note): bool
    {
        try {
            $request = InstructorRequest::findOrFail($id);
            return $request->update([
                'status' => $status,
                'examination_date' => now(),
                'note' => $note,
                'examiner_id' => auth()->user()->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating instructor request status with note: ' . $e->getMessage(), [
                'id' => $id,
                'status' => $status,
                'note' => $note
            ]);
            return false;
        }
    }

    /**
     * Get instructor requests by user ID
     *
     * @param int $userId
     * @return Collection
     */
    public function getByUserId(int $userId): Collection
    {
        try {
            return InstructorRequest::where('user_id', $userId)->get();
        } catch (\Exception $e) {
            Log::error('Error fetching instructor requests by user ID: ' . $e->getMessage(), ['userId' => $userId]);
            return new Collection();
        }
    }

    /**
     * Delete instructor request
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $request = InstructorRequest::findOrFail($id);
            return $request->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting instructor request: ' . $e->getMessage(), ['id' => $id]);
            return false;
        }
    }
}

