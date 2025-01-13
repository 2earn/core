<?php

namespace App\Services\Orders;

use App\Models\Order;
use App\Models\User;
use App\Services\Balances\Balances;
use Core\Enum\OrderEnum;
use Core\Services\BalancesManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
class Ordering
{
    public function __construct(private BalancesManager $balancesManager)
    {
    }

    public static function runChecks(User $user, $order): bool
    {
        Log::info('simulating runChecks ' . $order->id);
        $shippingSum = 0;
        $price_of_products_out_of_deal = 0;
        $balances = Balances::getStoredUserBalances($user->idUser);
        // to review
        foreach ($order->orderDetails as $orderDetail) {
            $shippingSum = $shippingSum + $orderDetail->shipping;
            if ($orderDetail->item->deal()->exists()) {
                $deal_amount_before_discount = $orderDetail->unit_price * $orderDetail->qty;
            } else {
                $price_of_products_out_of_deal = $orderDetail->unit_price * $orderDetail->qty;
            }
        }
        // to review

        $out_of_deal_amount = $shippingSum + $price_of_products_out_of_deal;

        Log::info('shippingSum : ' . $shippingSum);
        Log::info('$deal_amount_before_discount : ' . $deal_amount_before_discount);
        Log::info('$price_of_products_out_of_deal : ' . $price_of_products_out_of_deal);
        $order->update([
            'out_of_deal_amount' => $out_of_deal_amount,
            'deal_amount_before_discount' => $deal_amount_before_discount
        ]);

        return true;
    }

    public static function simulateDiscount(User $user, Order $order): bool
    {
        Log::info('simulating discount');
        return true;
    }

    public static function simulateBFSs(User $user, Order $order): bool
    {
        Log::info('simulating bfs');
        return true;
    }

    public static function simulateCash(User $user, Order $order): bool
    {
        Log::info('simulating cash');
        return true;
    }

    public static function simulate(User $user, Order $order): bool
    {
        Log::info('simulating ' . $order->id);

        if (self::runChecks($user, $order)) {
            self::simulateDiscount($user, $order);
            self::simulateBFSs($user, $order);
            self::simulateCash($user, $order);
            $order->updateStatus(OrderEnum::Simulated);

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
