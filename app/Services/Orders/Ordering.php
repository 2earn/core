<?php

namespace App\Services\Orders;

use App\Models\BFSsBalances;
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

    public static function runChecks(Order $order): bool
    {
        $shippingSum = 0;
        $price_of_products_out_of_deal = 0;
        $balances = Balances::getStoredUserBalances($order->user()->first()->idUser);
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
        if ($orderTotal <= $balances->cash_balance + $balances->discount_balance + Balances::getTotalBfs($balances)) {
            return true;
        }
        return false;
    }

    public static function simulateDiscount(Order $order): bool
    {
        $finalDiscountValue = 0;
        $dealAmountAfterPartnerDiscount = 0;
        $dealAmountAfter2earnDiscount = 0;
        $dealAmountAfterDealDiscount = 0;
        $totalponderation = 0;
        $itemsDeals = [];
        $balances = Balances::getStoredUserBalances($order->user()->first()->idUser);
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

                $dealAmountAfterPartnerDiscount = $dealAmountAfterPartnerDiscount + $amountAfterPartnerDiscount;
                $dealAmountAfter2earnDiscount = $dealAmountAfter2earnDiscount + $amountAfter2EarnDiscount;
                $dealAmountAfterDealDiscount = $dealAmountAfterDealDiscount + $amountAfterDealDiscount;

                $totalDiscount = $partnerDiscount + $earnDiscount + $dealDiscount;
                $finalDiscountValue = $finalDiscountValue + $totalDiscount;

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
                    'ponderation' => $orderDetail->total_amount * $totalDiscount,
                ];
                $totalponderation= $totalponderation + $orderDetail->total_amount * $totalDiscount;
                $itemsDeals[] = $itemDeal;
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

        foreach ($itemsDeals as $key => $itemDeal) {
            $itemDeal['finalDiscountPercentage'] = $itemDeal['ponderation'] * $itemDeal['totalDiscount'] / $totalponderation;
            $itemDeal['refundDispatching'] = $hasLostedDiscount ? $itemDeal['finalDiscountPercentage'] * $itemDeal['lost_discount_amount'] / 100 :0;
            $itemDeal['finalAmount'] = $itemDeal['amountAfterDealDiscount'] + $itemDeal['refundDispatching'];
            $itemDeal['finalDiscount'] = $itemDeal['totalDiscount'] - $itemDeal['refundDispatching'];
            $itemsDeals[$key] = $itemDeal;
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
                'amount_after_deal_discount' => $itemDeal['amountAfterDealDiscount'],
            ]);
        }
        $dealAmountAfterDiscounts = array_sum(array_column($itemsDeals, 'finalAmount'));
        $order->update([
            'deal_amount_after_discounts' => $dealAmountAfterDiscounts,
            'amount_after_discount' => $order->out_of_deal_amount + $dealAmountAfterDiscounts,
        ]);
        return true;
    }

    public static function simulateBFSs(Order $order)
    {
        $bfssTables = [
            BFSsBalances::BFS_100 => [
                'available' => Balances::getStoredBfss($order->user()->first()->idUser, BFSsBalances::BFS_100),
            ],
            BFSsBalances::BFS_50 => [
                'available' => Balances::getStoredBfss($order->user()->first()->idUser, BFSsBalances::BFS_50),
            ],
        ];
        $amount_after_discount = $order->amount_after_discount;
        foreach ($bfssTables as $key => $bfs) {
            $available = floatval($bfs['available']) * $key / 100;
            $bfs['toSubstruct'] = min($available, $amount_after_discount);
            $bfs['balance'] = $available - $amount_after_discount;
            $bfs['amount'] = $amount_after_discount;
            $amount_after_discount = $amount_after_discount - min($available, $amount_after_discount);
            $bfssTables[$key] = $bfs;
            if ($amount_after_discount <= 0) {
                break;
            }
        }
        Log::info(json_encode($bfssTables));

        return $amount_after_discount;
    }

    public static function simulateCash(Order $order, $amount_after_discount)
    {
        return $order->update([
            'paid_cash' => $amount_after_discount,
        ]);
    }

    public static function simulate(Order $order): bool
    {
        if (self::runChecks($order)) {
            self::simulateDiscount($order);
            $amount_after_discount = self::simulateBFSs($order);
            self::simulateCash($order, $amount_after_discount);
            return $order->updateStatus(OrderEnum::Simulated);
        }
        return false;
    }

    public static function run($order)
    {
        DB::beginTransaction();
        try {

            DB::commit();
            return $order->updateStatus(OrderEnum::Paid);
        } catch (Exception $exception) {
            DB::rollBack();
            $order->updateStatus(OrderEnum::Failed);
            Log::error($exception->getMessage());
        }
    }
}
