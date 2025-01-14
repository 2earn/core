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
        $totalOrderQuantity = 0;
        foreach ($order->orderDetails as $orderDetail) {
            $shippingSum = $shippingSum + $orderDetail->shipping;
            $totalOrderQuantity = $totalOrderQuantity + $orderDetail->qty;
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
            'deal_amount_before_discount' => $deal_amount_before_discount,
            'total_order_quantity' => $totalOrderQuantity
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
        $finalDiscountValue = 0;
        $itemsDeals = [];
        foreach ($order->orderDetails as $orderDetail) {
            if ($orderDetail->item->deal()->exists()) {

                $hasPartnerDiscount = (!is_null($orderDetail->item->discount) || $orderDetail->item->discount != 0);
                $has2EarnDiscount = (!is_null($orderDetail->item->deal->discount2earn) || $orderDetail->item->deal->discount2earn != 0);
                $hasDealDiscount = (!is_null($orderDetail->item->deal->discount) || $orderDetail->item->deal->discount != 0);

                $partnerDiscount = $hasPartnerDiscount ? $orderDetail->total_amount / 100 * $orderDetail->item->discount : 0;
                $amountAfterPartnerDiscount = $hasPartnerDiscount ? $orderDetail->total_amount - $partnerDiscount : $orderDetail->total_amount;

                $earnDiscount = $has2EarnDiscount ? $amountAfterPartnerDiscount / 100 * $orderDetail->item->deal->discount2earn : 0;
                $amountAfter2EarnDiscount = $has2EarnDiscount ? $amountAfterPartnerDiscount - $earnDiscount : $amountAfterPartnerDiscount;

                $dealDiscount = $hasDealDiscount ? $amountAfter2EarnDiscount / 100 * $orderDetail->item->deal->discount : 0;
                $amountAfterDealDiscount = $hasDealDiscount ? $amountAfter2EarnDiscount - $dealDiscount : $amountAfter2EarnDiscount;
                $totalDiscount = $partnerDiscount + $earnDiscount + $dealDiscount;

                $finalDiscountValue = $finalDiscountValue + $totalDiscount;
                $itemDeal = [
                    'deal' => $orderDetail->item->deal->id,
                    'itemName' => $orderDetail->item->name,
                    'unitPrice' => $orderDetail->unit_price,
                    'qty' => $orderDetail->qty,
                    'totalAmount' => $orderDetail->total_amount,
                    'partnerDiscountPercentage' => $orderDetail->item->discount,
                    'partnerDiscount' => $partnerDiscount,
                    'amountAfterPartnerDiscount' => $amountAfterPartnerDiscount,
                    '2earnDiscountPercentage' => $has2EarnDiscount ? $orderDetail->item->deal->discount2earn : 0,
                    '2earnDiscount' => $earnDiscount,
                    'amountAfter2EarnDiscount' => $amountAfter2EarnDiscount,
                    'dealDiscountPercentage' => $hasDealDiscount ? $orderDetail->item->deal->discount : 0,
                    'dealDiscount' => $dealDiscount,
                    'amountAfterDealDiscount' => $amountAfterDealDiscount,
                    'totalDiscount' => $partnerDiscount + $earnDiscount + $dealDiscount,
                ];
                array_push($itemsDeals, $itemDeal);
            }
        }
        $finalDiscountPersontage = $order->deal_amount_before_discount != 0 ? $finalDiscountValue / $order->deal_amount_before_discount * 100 : 0;
        $order->update([
            'final_discount_value' => $finalDiscountValue,
            'final_discount_percentage' => $finalDiscountPersontage,
        ]);
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
