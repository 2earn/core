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
    public function declenchVip($userId): bool
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

    /**
     * Calculate VIP actions
     *
     * @param vip $vip
     * @param int $totalActions
     * @param int $maxBonus
     * @param float $flashCoefficient
     * @return int
     */
    public function calculateVipActions(vip $vip, int $totalActions, int $maxBonus, float $flashCoefficient): int
    {
        try {
            return find_actions(
                $vip->solde,
                $totalActions,
                $maxBonus,
                $flashCoefficient,
                $vip->flashCoefficient
            );
        } catch (\Exception $e) {
            Log::error('Error calculating VIP actions', [
                'vipId' => $vip->id ?? null,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Calculate VIP benefits
     *
     * @param vip $vip
     * @param int $actions
     * @param float $actualActionValue
     * @return float
     */
    public function calculateVipBenefits(vip $vip, int $actions, float $actualActionValue): float
    {
        try {
            return ($vip->solde - $actions) * $actualActionValue;
        } catch (\Exception $e) {
            Log::error('Error calculating VIP benefits', [
                'vipId' => $vip->id ?? null,
                'error' => $e->getMessage()
            ]);
            return 0.0;
        }
    }

    /**
     * Calculate VIP cost
     *
     * @param vip $vip
     * @param int $actions
     * @param float $actualActionValue
     * @return float
     */
    public function calculateVipCost(vip $vip, int $actions, float $actualActionValue): float
    {
        try {
            $denominator = ($actions * $vip->flashCoefficient) + getGiftedActions($actions);

            if ($denominator == 0) {
                return 0.0;
            }

            return formatSolde(
                ($actions * $actualActionValue) / $denominator,
                2
            );
        } catch (\Exception $e) {
            Log::error('Error calculating VIP cost', [
                'vipId' => $vip->id ?? null,
                'error' => $e->getMessage()
            ]);
            return 0.0;
        }
    }

    /**
     * Get VIP flash status with formatted date
     *
     * @param vip $vip
     * @return array
     */
    public function getVipFlashStatus(vip $vip): array
    {
        try {
            $currentDateTime = new \DateTime();
            $flashWindowEnd = (new \DateTime($vip->dateFNS))
                ->add(new \DateInterval('PT' . $vip->flashDeadline . 'H'));

            return [
                'isFlashActive' => $currentDateTime < $flashWindowEnd,
                'flashEndDate' => $flashWindowEnd->format('F j, Y G:i:s'),
                'flashTimes' => $vip->flashCoefficient,
                'flashPeriod' => $vip->flashDeadline,
                'flashMinShares' => $vip->flashMinAmount,
            ];
        } catch (\Exception $e) {
            Log::error('Error getting VIP flash status', [
                'vipId' => $vip->id ?? null,
                'error' => $e->getMessage()
            ]);
            return [
                'isFlashActive' => false,
                'flashEndDate' => '',
                'flashTimes' => 1,
                'flashPeriod' => 0,
                'flashMinShares' => -1,
            ];
        }
    }

    /**
     * Get complete VIP calculations
     *
     * @param vip $vip
     * @param int $totalActions
     * @param int $maxBonus
     * @param float $flashCoefficient
     * @param float $actualActionValue
     * @return array
     */
    public function getVipCalculations(vip $vip, int $totalActions, int $maxBonus, float $flashCoefficient, float $actualActionValue): array
    {
        try {
            $actions = $this->calculateVipActions($vip, $totalActions, $maxBonus, $flashCoefficient);
            $benefits = $this->calculateVipBenefits($vip, $actions, $actualActionValue);
            $cost = $this->calculateVipCost($vip, $actions, $actualActionValue);
            $flashStatus = $this->getVipFlashStatus($vip);

            return [
                'actions' => $actions,
                'benefits' => $benefits,
                'cost' => $cost,
                'flashStatus' => $flashStatus,
            ];
        } catch (\Exception $e) {
            Log::error('Error getting VIP calculations', [
                'vipId' => $vip->id ?? null,
                'error' => $e->getMessage()
            ]);
            return [
                'actions' => 0,
                'benefits' => 0.0,
                'cost' => 0.0,
                'flashStatus' => [
                    'isFlashActive' => false,
                    'flashEndDate' => '',
                    'flashTimes' => 1,
                    'flashPeriod' => 0,
                    'flashMinShares' => -1,
                ],
            ];
        }
    }

    /**
     * Check if user has an active flash VIP
     *
     * @param string $idUser User's business ID
     * @return bool
     */
    public function hasActiveFlashVip(string $idUser): bool
    {
        try {
            return vip::where('idUser', '=', $idUser)
                ->where('closed', '=', false)
                ->whereRaw('DATE_ADD(dateFNS, INTERVAL flashDeadline HOUR) > NOW()')
                ->exists();
        } catch (\Exception $e) {
            Log::error('Error checking active flash VIP for user', [
                'idUser' => $idUser,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}

