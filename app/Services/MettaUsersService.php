<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
            $mettaUser = DB::table('metta_users')
                ->where('idUser', $userId)
                ->first();

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
     * @return object|null
     */
    public function getMettaUser(int $userId): ?object
    {
        try {
            return DB::table('metta_users')
                ->where('idUser', $userId)
                ->first();
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
            return DB::table('metta_users')
                ->where('idUser', $userId)
                ->exists();
        } catch (\Exception $e) {
            Log::error('Error checking metta user existence', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}

