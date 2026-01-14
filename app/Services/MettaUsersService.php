<?php

namespace App\Services;

use App\Models\metta_user;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class MettaUsersService
{
    /**
     * Get metta user info by user ID
     *
     * @param int $userId
     * @return Collection
     */
    public function getMettaUserInfo(int $userId): Collection
    {
        try {
            $mettaUser = metta_user::where('idUser', $userId)->first();
            return collect($mettaUser);
        } catch (\Exception $e) {
            Log::error('Error fetching metta user info', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            return collect();
        }
    }

    /**
     * Get metta user by user ID
     *
     * @param int $userId
     * @return metta_user|null
     */
    public function getMettaUser(int $userId): ?metta_user
    {
        try {
            return metta_user::where('idUser', $userId)->first();
        } catch (\Exception $e) {
            Log::error('Error fetching metta user', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get metta user full name
     *
     * @param int $userId
     * @return string
     */
    public function getMettaUserFullName(int $userId): string
    {
        try {
            $mettaUser = $this->getMettaUser($userId);

            if (!$mettaUser) {
                return '';
            }

            $firstName = $mettaUser->enFirstName ?? '';
            $lastName = $mettaUser->enLastName ?? '';

            return trim($firstName . ' ' . $lastName);
        } catch (\Exception $e) {
            Log::error('Error fetching metta user full name', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            return '';
        }
    }

    /**
     * Check if metta user exists
     *
     * @param int $userId
     * @return bool
     */
    public function mettaUserExists(int $userId): bool
    {
        try {
            return metta_user::where('idUser', $userId)->exists();
        } catch (\Exception $e) {
            Log::error('Error checking metta user existence', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Create a new metta user
     *
     * @param array $data
     * @return metta_user|null
     */
    public function createMettaUser(array $data): ?metta_user
    {
        try {
            return metta_user::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating metta user', [
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Update metta user
     *
     * @param int $userId
     * @param array $data
     * @return bool
     */
    public function updateMettaUser(int $userId, array $data): bool
    {
        try {
            $mettaUser = metta_user::where('idUser', $userId)->first();

            if (!$mettaUser) {
                Log::warning('Metta user not found for update', ['userId' => $userId]);
                return false;
            }

            return $mettaUser->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating metta user', [
                'userId' => $userId,
                'data' => $data,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}


