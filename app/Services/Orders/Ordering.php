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

    public static function runChecks(int $userId, int $orderId): bool
    {
        Log::info('simulating runChecks');
        return true;
    }

    public static function simulateDiscount(int $userId, int $orderId): bool
    {
        Log::info('simulating discount');
        return true;
    }

    public static function simulateBFSs(int $userId, int $orderId): bool
    {
        Log::info('simulating bfs');
        return true;
    }

    public static function simulateCash(int $userId, int $orderId): bool
    {
        Log::info('simulating cash');
        return true;
    }

    public static function simulate(int $userId, int $orderId): bool
    {
        Log::info('simulating');
        if (self::runChecks($userId, $orderId)) {
            self::simulateDiscount($userId, $orderId);
            self::simulateBFSs($userId, $orderId);
            self::simulateCash($userId, $orderId);
            return true;
        }
        return false;
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
