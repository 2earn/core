<?php

namespace App\Services\Orders;

use App\Models\Order;
use App\Models\OrderDetail;
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

        $order->update([
            'out_of_deal_amount' => $out_of_deal_amount,
            'deal_amount_before_discount' => $deal_amount_before_discount,
            'total_order_quantity' => $totalOrderQuantity,
            'amount_before_discount' => $out_of_deal_amount + $deal_amount_before_discount
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
        $dealAmountAfterPartnerDiscount = 0;
        $dealAmountAfter2earnDiscount = 0;
        $dealAmountAfterDealDiscount = 0;
        $itemsDeals = [];
        $balances = Balances::getStoredUserBalances($user->idUser);
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
                $dealAmountAfterPartnerDiscount = $dealAmountAfterPartnerDiscount + $amountAfterPartnerDiscount;
                $dealAmountAfter2earnDiscount = $dealAmountAfter2earnDiscount + $amountAfter2EarnDiscount;


                $dealAmountAfterDealDiscount = $dealAmountAfterDealDiscount + $amountAfterDealDiscount;
                $totalDiscount = $partnerDiscount + $earnDiscount + $dealDiscount;

                $itemDeal = [
                    'id' => $orderDetail->id,
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
                    'totalDiscount' => $totalDiscount,
                ];
                array_push($itemsDeals, $itemDeal);
            }
        }
        $hasLostedDiscount = $balances->discount_balance < $finalDiscountValue;
        $finalDiscountPersontage = $order->deal_amount_before_discount != 0 ? $finalDiscountValue / $order->deal_amount_before_discount * 100 : 0;
        $order->update([
            'final_discount_value' => $finalDiscountValue,
            'lost_discount_amount' => !$hasLostedDiscount ? 0 : $finalDiscountValue - $balances->discount_balance,
            'final_discount_percentage' => $finalDiscountPersontage,
            'deal_amount_after_partner_discount' => $dealAmountAfterPartnerDiscount,
            'deal_amount_after_2earn_discount' => $dealAmountAfter2earnDiscount,
            'deal_amount_after_deal_discount' => $dealAmountAfterDealDiscount,
        ]);

        Log::info(json_encode($itemsDeals));
        foreach ($itemsDeals as &$itemDeal) {
            $itemDeal['finalDiscountPercentage'] = $itemDeal['totalAmount'] * $itemDeal['totalDiscount'] / $finalDiscountValue;
            $itemDeal['refundDispatching'] = $hasLostedDiscount ? $itemDeal['finalDiscountPercentage'] * $itemDeal['totalDiscount'] * $finalDiscountValue : 0;
            $itemDeal['finalAmount'] = $itemDeal['amountAfterDealDiscount'] + $itemDeal['refundDispatching'];
            $itemDeal['finalDiscount'] = $itemDeal['totalDiscount'] - $itemDeal['refundDispatching'];
            $itemDeal['finalDiscountPercentage1'] = $itemDeal['totalDiscount'] - $itemDeal['refundDispatching'];
        }

        foreach ($itemsDeals as $itemDeal) {
            OrderDetail::find($itemDeal['id'])->update([
                'partner_discount_percentage' => $itemDeal['partnerDiscountPercentage'],
                'partner_discount' => $itemDeal['partnerDiscount'],
                'amount_after_partner_discount' => $itemDeal['amountAfterPartnerDiscount'],
                'earn_discount_percentage' => $itemDeal['2earnDiscountPercentage'],
                'earn_discount' => $itemDeal['2earnDiscount'],
                'amount_after_earn_discount' => $itemDeal['amountAfter2EarnDiscount'],
                'deal_discount_percentage' => $itemDeal['dealDiscountPercentage'],
                'deal_discount' => $itemDeal['dealDiscount'],
                'amount_after_deal_discount' => $itemDeal['finalDiscountPercentage'],
            ]);
        }
        $dealAmountAfterDiscounts = array_sum(array_column($itemsDeals, 'finalAmount'));
        $order->update([
            'deal_amount_after_discounts' => $dealAmountAfterDiscounts,
            'amount_after_discount' => $order->out_of_deal_amount + $dealAmountAfterDiscounts,
        ]);

        Log::info(json_encode($itemsDeals));
        Log::info(json_encode($order));
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
