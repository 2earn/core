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

    public static function runChecks(User $user, Order $order): bool
    {
        Log::info('simulating runChecks ' . $order->id);
        $shippingSum = 0;
        $price_of_products_out_of_deal = 0;
        $balances = Balances::getStoredUserBalances($user->idUser);
        $deal_amount_before_discount = 0;
        foreach ($order->orderDetails as $orderDetail) {
            $shippingSum = $shippingSum + $orderDetail->shipping;
            if ($orderDetail->item->deal()->exists()) {
                $deal_amount_before_discount = $deal_amount_before_discount + ($orderDetail->unit_price * $orderDetail->qty);
            } else {
                $price_of_products_out_of_deal = $price_of_products_out_of_deal + ($orderDetail->unit_price * $orderDetail->qty);
            }
        }

        $out_of_deal_amount = $shippingSum + $price_of_products_out_of_deal;

        Log::info('shippingSum : ' . $shippingSum);
        Log::info('$deal_amount_before_discount : ' . $deal_amount_before_discount);
        Log::info('$price_of_products_out_of_deal : ' . $price_of_products_out_of_deal);
        $order->update([
            'out_of_deal_amount' => $out_of_deal_amount,
            'deal_amount_before_discount' => $deal_amount_before_discount
        ]);
        $orderTotal = $out_of_deal_amount + $deal_amount_before_discount;
        if ($orderTotal <= $balances->cash_balance + $balances->discount_balance + Balances::getTotolBfs($balances)) {
            return true;
        }
        return false;
    }

    public static function simulateDiscount(User $user, Order $order): bool
    {
        Log::info('simulating discount');
        $itemsDeals = [];
        foreach ($order->orderDetails as $orderDetail) {
            if ($orderDetail->item->deal()->exists()) {
                $itemDeal = [
                    'deal' => $orderDetail->item->deal->id,
                    'itemName' => $orderDetail->item->name,
                    'unitPrice' => $orderDetail->unit_price,
                    'qty' => $orderDetail->qty,
                    'totalAmount' => $orderDetail->total_amount,
                    'partnerDiscountPercentage' => $orderDetail->item->discount,
                    'partnerDiscount' => $orderDetail->total_amount / 100 * $orderDetail->item->discount,
                    'amount_after_partner_discount' => $orderDetail->total_amount- ($orderDetail->total_amount / 100 * $orderDetail->item->discount),
                ];
                array_push($itemsDeals, $itemDeal);
            }
        }
        Log::info(json_encode($itemsDeals));
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
