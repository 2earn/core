<?php

namespace App\Services;

use App\Models\vip;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class VipService
{
    /**
     * Get active VIP record for a user
     *
     * @param int $userId
     * @return vip|null
     */
    public function getActiveVipByUserId(int $userId): ?vip
    {
        try {
            return vip::where('idUser', '=', $userId)
                ->where('closed', '=', false)
                ->first();
        } catch (\Exception $e) {
            Log::error('Error fetching active VIP for user', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get all active VIP records for a user
     *
     * @param int $userId
     * @return Collection
     */
    public function getActiveVipsByUserId(int $userId): Collection
    {
        try {
            return vip::where('idUser', '=', $userId)
                ->where('closed', '=', false)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching active VIPs for user', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            return collect();
        }
    }

    /**
     * Close a VIP record
     *
     * @param int $userId
     * @return bool
     */
    public function closeVip(int $userId): bool
    {
        try {
            return vip::where('idUser', $userId)
                ->where('closed', '=', 0)
                ->update([
                    'closed' => 1,
                    'closedDate' => now()
                ]) > 0;
        } catch (\Exception $e) {
            Log::error('Error closing VIP for user', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Trigger (declench) a VIP record
     *
     * @param int $userId
     * @return bool
     */
    public function declenchVip(int $userId): bool
    {
        try {
            return vip::where('idUser', $userId)
                ->where('closed', '=', 0)
                ->update([
                    'declenched' => 1,
                    'declenchedDate' => now()
                ]) > 0;
        } catch (\Exception $e) {
            Log::error('Error declenching VIP for user', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Trigger and close a VIP record
     *
     * @param int $userId
     * @return bool
     */
    public function declenchAndCloseVip(int $userId): bool
    {
        try {
            return vip::where('idUser', $userId)
                ->where('closed', '=', 0)
                ->update([
                    'closed' => 1,
                    'closedDate' => now(),
                    'declenched' => 1,
                    'declenchedDate' => now()
                ]) > 0;
        } catch (\Exception $e) {
            Log::error('Error declenching and closing VIP for user', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Check if user has active VIP
     *
     * @param int $userId
     * @return bool
     */
    public function hasActiveVip(int $userId): bool
    {
        try {
            return vip::where('idUser', '=', $userId)
                ->where('closed', '=', false)
                ->exists();
        } catch (\Exception $e) {
            Log::error('Error checking active VIP for user', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Check if VIP is valid (not expired)
     *
     * @param vip $vip
     * @return bool
     */
    public function isVipValid(vip $vip): bool
    {
        try {
            $dateStart = new \DateTime($vip->dateFNS);
            $dateEnd = $dateStart->modify($vip->flashDeadline . ' hour');
            return $dateEnd > now();
        } catch (\Exception $e) {
            Log::error('Error checking VIP validity', [
                'vipId' => $vip->id ?? null,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}

