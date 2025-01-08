<?php

namespace App\Services\Orders;

use Core\Services\BalancesManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
class Ordering
{
    public function __construct(private BalancesManager $balancesManager)
    {
    }

    public function runChecks(int $userId, int $orderId): bool
    {
    }

    public function simulateDiscount(int $userId, int $orderId): bool
    {
    }

    public function simulateBFSs(int $userId, int $orderId): bool
    {
    }

    public function simulateCash(int $userId, int $orderId): bool
    {
    }

    public function simulate(int $userId, int $orderId): bool
    {
        $this->simulateDiscount($userId, $orderId);
        $this->simulateBFSs($userId, $orderId);
        $this->simulateCash($userId, $orderId);
    }

    public function run()
    {
        DB::beginTransaction();
        try {

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
        }
    }

}
