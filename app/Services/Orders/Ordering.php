<?php

namespace App\Services\Orders;

use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\CommissionBreakDown;
use App\Models\Deal;
use App\Models\DiscountBalances;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Services\Balances\Balances;
use App\Services\Balances\BalancesFacade;
use Core\Enum\BalanceOperationsEnum;
use Core\Enum\CommissionTypeEnum;
use Core\Enum\OrderEnum;
use Core\Models\BalanceOperation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
class Ordering
{

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
        ]);

        $orderTotal = $out_of_deal_amount + $deal_amount_before_discount;

        if ($orderTotal <= $balances->cash_balance + $balances->discount_balance + Balances::getTotalBfs($balances)) {
            return true;
        }
        return false;
    }

    public static function initDealItem(OrderDetail $orderDetail)
    {
        return [
            'id' => $orderDetail->id,
            'deal' => $orderDetail->item->deal->id,
            'itemName' => $orderDetail->item->name,
            'unitPrice' => $orderDetail->unit_price,
            'qty' => $orderDetail->qty,
            'totalAmount' => $orderDetail->total_amount,
        ];
    }

    public static function fillPartnerDiscount(OrderDetail $orderDetail, array $itemDeal)
    {
        $hasPartnerDiscount = (!is_null($orderDetail->item->discount) || $orderDetail->item->discount != 0);
        $partnerDiscount = $hasPartnerDiscount ? $orderDetail->total_amount / 100 * $orderDetail->item->discount : 0;
        $amountAfterPartnerDiscount = $hasPartnerDiscount ? $orderDetail->total_amount - $partnerDiscount : $orderDetail->total_amount;
        $partnerDiscountData = [
            'partnerDiscountPercentage' => $orderDetail->item->discount,
            'partnerDiscount' => $partnerDiscount,
            'amountAfterPartnerDiscount' => $amountAfterPartnerDiscount
        ];
        return array_merge($itemDeal, $partnerDiscountData);
    }

    public static function fillEarnDiscount(OrderDetail $orderDetail, array $itemDeal)
    {
        $has2EarnDiscount = (!is_null($orderDetail->item->discount_2earn) || $orderDetail->item->discount_2earn != 0);
        $earnDiscount = $has2EarnDiscount ? $itemDeal['amountAfterPartnerDiscount'] / 100 * $orderDetail->item->discount_2earn : 0;
        $amountAfter2EarnDiscount = $has2EarnDiscount ? $itemDeal['amountAfterPartnerDiscount'] - $earnDiscount : $itemDeal['amountAfterPartnerDiscount'];
        $earnDiscountData = [
            '2earnDiscountPercentage' => $has2EarnDiscount ? $orderDetail->item->discount_2earn : 0,
            '2earnDiscount' => $earnDiscount,
            'amountAfter2EarnDiscount' => $amountAfter2EarnDiscount,
        ];
        return array_merge($itemDeal, $earnDiscountData);
    }

    public static function fillDealDiscount(OrderDetail $orderDetail, array $itemDeal)
    {
        $hasDealDiscount = (!is_null($orderDetail->item->deal->discount) || $orderDetail->item->deal->discount != 0);
        $dealDiscount = $hasDealDiscount ? $itemDeal['amountAfter2EarnDiscount'] / 100 * $orderDetail->item->deal->discount : 0;
        $amountAfterDealDiscount = $hasDealDiscount ? $itemDeal['amountAfter2EarnDiscount'] - $dealDiscount : $itemDeal['amountAfter2EarnDiscount'];
        $dealDiscountData = [
            'dealDiscountPercentage' => $hasDealDiscount ? $orderDetail->item->deal->discount : 0,
            'dealDiscount' => $dealDiscount,
            'amountAfterDealDiscount' => $amountAfterDealDiscount,
        ];
        return array_merge($itemDeal, $dealDiscountData);
    }

    public static function simulateDiscount(Order $order)
    {
        $balances = Balances::getStoredUserBalances($order->user()->first()->idUser);
        $itemsDeals = [];
        $dealsTurnOver = [];
        $dealTotals = 0;
        foreach ($order->orderDetails as $orderDetail) {
            if ($orderDetail->item->deal()->exists()) {
                $itemDeal = Ordering::initDealItem($orderDetail);
                $itemDeal = Ordering::fillPartnerDiscount($orderDetail, $itemDeal);
                $itemDeal = Ordering::fillEarnDiscount($orderDetail, $itemDeal);
                $itemDeal = Ordering::fillDealDiscount($orderDetail, $itemDeal);

                if (!isset($dealsTurnOver[$orderDetail->item->deal->id])) {
                    $dealsTurnOver[$orderDetail->item->deal->id]['total'] = $itemDeal['amountAfterPartnerDiscount'];
                } else {
                    $dealsTurnOver[$orderDetail->item->deal->id]['total'] = $dealsTurnOver[$orderDetail->item->deal->id]['total'] + $itemDeal['amountAfterPartnerDiscount'];
                }
                $dealTotals = $dealTotals + $itemDeal['amountAfterPartnerDiscount'];

                $totalDiscount = $itemDeal['partnerDiscount'] + $itemDeal['2earnDiscount'] + $itemDeal['dealDiscount'];

                $itemDeal = array_merge($itemDeal, ['totalDiscountWithDiscountPartner' => $totalDiscount, 'ponderationWithDiscountPartner' => ($orderDetail->total_amount * $totalDiscount)]);
                $itemsDeals[] = $itemDeal;
            }
        }

        $dealAmountAfterPartnerDiscount = array_sum(array_column($itemsDeals, 'amountAfterPartnerDiscount'));
        $dealAmountAfter2earnDiscount = array_sum(array_column($itemsDeals, 'amountAfter2EarnDiscount'));
        $dealAmountAfterDealDiscount = array_sum(array_column($itemsDeals, 'amountAfterDealDiscount'));
        $totalPonderation = array_sum(array_column($itemsDeals, 'ponderationWithDiscountPartner'));

        $finalDiscountValue = array_sum(array_column($itemsDeals, 'totalDiscountWithDiscountPartner'));
        $lostDiscountAmount = $finalDiscountValue < $balances->discount_balance ? 0 : $finalDiscountValue - $balances->discount_balance;

        foreach ($dealsTurnOver as $key => $turnOver) {
            $dealsTurnOver[$key] = ['total' => 0, 'dispatching' => 0, 'deal_paid_amount' => 0, 'additional' => 0];
        }

        foreach ($itemsDeals as $key => $itemDeal) {
            $itemDeal['totalDiscountPercentageWithDiscountPartner'] = $itemDeal['ponderationWithDiscountPartner'] / $totalPonderation * 100;
            $itemDeal['refundDispatching'] = $itemDeal['totalDiscountPercentageWithDiscountPartner'] * $lostDiscountAmount / 100;
            $itemDeal['finalAmount'] = $itemDeal['amountAfterDealDiscount'] + $itemDeal['refundDispatching'];
            $itemDeal['finalDiscount'] = $itemDeal['totalDiscountWithDiscountPartner'] - $itemDeal['refundDispatching'];
            $itemDeal['finalDiscountWithoutDiscountPartner'] = $itemDeal['2earnDiscount'] + $itemDeal['dealDiscount'];
            $itemDeal['valueDiscountPartner'] = $itemDeal['finalDiscountWithoutDiscountPartner'] * $itemDeal['amountAfterPartnerDiscount'];

            $itemsDeals[$key] = $itemDeal;
            $dealID = $itemDeal['deal'];

            $dealsTurnOver[$dealID]['total'] = $dealsTurnOver[$dealID]['total'] + $itemDeal['finalDiscountWithoutDiscountPartner'];
            $dealsTurnOver[$dealID]['deal_paid_amount'] = $dealsTurnOver[$dealID]['deal_paid_amount'] + $itemDeal['finalAmount'];
            $dealsTurnOver[$dealID]['dispatching'] = $dealsTurnOver[$dealID]['total'] / $dealTotals * 100;
            $dealsTurnOver[$dealID]['additional'] = $dealsTurnOver[$dealID]['dispatching'] * $lostDiscountAmount;
        }
        $totalValueDiscountPartner = array_sum(array_column($itemsDeals, 'valueDiscountPartner'));
        foreach ($itemsDeals as $key => $itemDeal) {
            $itemDeal['discountValueWithoutDiscountPartner'] = $itemDeal['valueDiscountPartner'] / $totalValueDiscountPartner;
            $itemsDeals[$key] = $itemDeal;
        }
        $sumDiscountWithoutDiscountPartner = array_sum(array_column($itemsDeals, 'discountValueWithoutDiscountPartner'));

        Ordering::updateOrderDeals(
            $order,
            $finalDiscountValue,
            $lostDiscountAmount,
            $dealAmountAfterPartnerDiscount,
            $dealAmountAfter2earnDiscount,
            $dealAmountAfterDealDiscount
        );

        foreach ($itemsDeals as $key => $itemDeal) {
            $itemDeal['discountPercentageWithoutDiscountPartner'] = $itemDeal['discountValueWithoutDiscountPartner'] / $sumDiscountWithoutDiscountPartner * 100;
            $itemsDeals[$key] = $itemDeal;
        }

        Ordering::updateItemDeals($itemsDeals);
        // CHECK WITH KHALIL
        // $dealAmountAfterDiscounts = array_sum(array_column($itemsDeals, 'finalAmount'));
        $dealAmountAfterDiscounts = $order->deal_amount_before_discount - ($finalDiscountValue - $lostDiscountAmount);

        $order->update(['deal_amount_after_discounts' => $dealAmountAfterDiscounts, 'amount_after_discount' => $order->out_of_deal_amount + $dealAmountAfterDiscounts]);
        return $dealsTurnOver;
    }

    public static function updateOrderDeals($orderDeal, $finalDiscountValue, $lostDiscountAmount, $dealAmountAfterPartnerDiscount, $dealAmountAfter2earnDiscount, $dealAmountAfterDealDiscount)
    {
        $orderDeal->update([
            'final_discount_value' => $finalDiscountValue,
            'lost_discount_amount' => $lostDiscountAmount,
            'deal_amount_after_partner_discount' => $dealAmountAfterPartnerDiscount,
            'deal_amount_after_2earn_discount' => $dealAmountAfter2earnDiscount,
            'deal_amount_after_deal_discount' => $dealAmountAfterDealDiscount,
        ]);
    }

    public static function updateItemDeals($itemsDeals)
    {
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
                'total_discount_with_discount_partner' => $itemDeal['totalDiscountWithDiscountPartner'],
                'ponderation_with_discount_partner' => $itemDeal['ponderationWithDiscountPartner'],
                'total_discount_percentage_with_discount_partner' => $itemDeal['totalDiscountPercentageWithDiscountPartner'],
                'refund_dispatching' => $itemDeal['refundDispatching'],
                'final_amount' => $itemDeal['finalAmount'],
                'final_discount' => $itemDeal['finalDiscount'],
                'final_discount_without_discount_partner' => $itemDeal['finalDiscountWithoutDiscountPartner'],
                'discount_value_without_discount_partner' => $itemDeal['discountValueWithoutDiscountPartner'],
                'discount_percentage_without_discount_partner' => $itemDeal['discountPercentageWithoutDiscountPartner'],
            ]);
        }
    }
    public static function initBfssTable($user)
    {
        $bfssTables = [];
        $bfsValue = Balances::getStoredBfss($user->idUser, BFSsBalances::BFS_100);
        if ($bfsValue > 0) {
            $bfssTables[BFSsBalances::BFS_100] = ['available' => Balances::getStoredBfss($user->idUser, BFSsBalances::BFS_100)];
        }
        $bfsValue = Balances::getStoredBfss($user->idUser, BFSsBalances::BFS_50);
        if ($bfsValue > 0) {
            $bfssTables[BFSsBalances::BFS_50] = ['available' => Balances::getStoredBfss($user->idUser, BFSsBalances::BFS_50)];
        }
        return $bfssTables;
    }

    public static function simulateBFSs(Order $order)
    {
        $bfssTables = Ordering::initBfssTable($order->user()->first());
        Log::alert(json_encode($bfssTables));
        $amount_after_discount = $order->amount_after_discount;
        if (!empty($bfssTables)) {
            foreach ($bfssTables as $key => $bfs) {
                $available = floatval($bfs['available']);
                $toCover = $amount_after_discount * floatval($key) / 100;
                $toSubstruct = min($available, $toCover);
                $amount_after_discount = $amount_after_discount - $toSubstruct;
                $bfs['toSubstruct'] = $toSubstruct;
                $bfs['balance'] = $available - $toSubstruct;
                $bfs['amount'] = $amount_after_discount;

                $bfssTables[$key] = $bfs;

                if ($amount_after_discount == 0) {
                    Log::alert('++++++' . json_encode($bfssTables));
                    return array_splice($bfssTables, 0, $key);
                }
            }
        }
        return $bfssTables;
    }

    public static function simulateCash(Order $order, $amount_after_discount)
    {
        return $order->update(['paid_cash' => $amount_after_discount]);
    }

    public static function simulate(Order $order)
    {
        if (self::runChecks($order)) {
            $dealsTurnOver = self::simulateDiscount($order);
            $bfssTables = self::simulateBFSs($order);
            if (!empty($bfssTables)) {
                $amount = array_last($bfssTables)['amount'];
            }else{
                $amount=  $order->amount_after_discount;
            }
            self::simulateCash($order, $amount);
            return ['order' => $order, 'dealsTurnOver' => $dealsTurnOver, 'bfssTables' => $bfssTables];
        }
        return false;
    }

    public static function runDiscount(Order $order, $balances)
    {
        $countedDiscount = $order->final_discount_value - $order->lost_discount_amount;
        if ($countedDiscount > 0) {
            $currentBalance = $balances->discount_balance + BalanceOperation::getMultiplicator(BalanceOperationsEnum::ORDER_BFS->value) * $countedDiscount;
            $discountData = [
                'balance_operation_id' => BalanceOperationsEnum::ORDER_DISCOUNT->value,
                'operator_id' => Balances::SYSTEM_SOURCE_ID,
                'beneficiary_id' => $order->user()->first()->idUser,
                'reference' => BalancesFacade::getReference(BalanceOperationsEnum::ORDER_DISCOUNT->value),
                'description' => $countedDiscount . ' from ordering (id) ' . $order->id . ' / Discount : ' . $balances->discount_balance . ' - ' . $countedDiscount . ' = ' . $currentBalance,
                'value' => $countedDiscount,
                'current_balance' => $currentBalance
            ];
            DiscountBalances::addLine($discountData, null, null, $order->id, null, null);
        }
    }

    public static function runBFS(Order $order, $bfssTables, $balances)
    {
        foreach ($bfssTables as $key => $bfs) {
            $currentBalance = $balances->getBfssBalance($key) + (BalanceOperation::getMultiplicator(BalanceOperationsEnum::ORDER_BFS->value) * $bfs['amount']);
            $bfsData = [
                'balance_operation_id' => BalanceOperationsEnum::ORDER_BFS->value,
                'operator_id' => Balances::SYSTEM_SOURCE_ID,
                'beneficiary_id' => $order->user()->first()->idUser,
                'reference' => BalancesFacade::getReference(BalanceOperationsEnum::ORDER_BFS->value),
                'percentage' => $key,
                'description' => $bfs['amount'] . ' from ordering (id) ' . $order->id . ' / BFSs (' . $key . ') : ' . $balances->getBfssBalance($key) . ' - ' . $bfs['amount'] . ' = ' . $currentBalance,
                'value' => $bfs['amount'],
                'current_balance' => $currentBalance
            ];
            BFSsBalances::addLine($bfsData, null, null, $order->id, null, null);
        }
    }

    public static function runCASH(Order $order, $balances)
    {
        if ($order->paid_cash) {
            $currentBalance = $balances->cash_balance + (BalanceOperation::getMultiplicator(BalanceOperationsEnum::ORDER_CASH->value) * $order->paid_cash);
            $cashData = [
                'balance_operation_id' => BalanceOperationsEnum::ORDER_CASH->value,
                'operator_id' => Balances::SYSTEM_SOURCE_ID,
                'beneficiary_id' => $order->user()->first()->idUser,
                'reference' => BalancesFacade::getReference(BalanceOperationsEnum::ORDER_CASH->value),
                'description' => $order->paid_cash . ' from ordering (id) ' . $order->id . ' / Cash : ' . $balances->cash_balance . ' - ' . $order->paid_cash . ' = ' . $currentBalance,
                'value' => $order->paid_cash,
                'current_balance' => $currentBalance
            ];
            CashBalances::addLine($cashData, null, null, $order->id, null, null);
        }
    }

    public static function runPartition(Order $order, array $dealsTurnOver)
    {
        foreach ($dealsTurnOver as $dealId => $turnOver) {
            $deal = Deal::find($dealId);
            $oldTurnOver = $deal->current_turnover;
            $newTurnOver = $deal->updateTurnover($turnOver['total']);

            if (CommissionBreakDown::where('deal_id', $dealId)->orderBy('created_at', 'DESC')->exists()) {
                $oldCommissionPercentage = 0;
            } else {
                $lastCommission = CommissionBreakDown::where('deal_id', $dealId)->orderBy('created_at', 'DESC')->first();
                $oldCommissionPercentage = $lastCommission?->pluck('commission_percentage') ?? 0;
            }

            $commissionPercentage = Deal::getCommissionPercentage($deal,$newTurnOver);

            $cumulative = CommissionBreakDown::getSum($dealId, 'cumulative_commission');
            $cumulativeCashback = CommissionBreakDown::getSum($dealId, 'cumulative_cashback');

            $cbData = [
                'order_id' => $order->id,
                'deal_id' => $dealId,
                'trigger' => 0,
                'type' => CommissionTypeEnum::IN->value,
                'new_turnover' => $newTurnOver,
                'old_turnover' => $oldTurnOver,
                'purchase_value' => $turnOver['total'],
                'additional_amount' => $turnOver['additional'],
                'deal_paid_amount' => $turnOver['deal_paid_amount'],
                'commission_percentage' =>  $commissionPercentage,
            ];

            $cbData['commission_value'] = ($turnOver['total'] * $commissionPercentage / 100) + $turnOver['additional'];

            $cbData['cumulative_commission'] = $cumulative + $cbData['commission_value'];
            $cbData['cumulative_commission_percentage'] = $cbData['cumulative_commission'] / $cbData['new_turnover'] * 100;
            $cbData['cash_company_profit'] = $cbData['commission_value'] * $deal->earn_profit / 100;
            $cbData['cash_jackpot'] = $cbData['commission_value'] * $deal->jackpot / 100;
            $cbData['cash_tree'] = $cbData['commission_value'] * $deal->tree_remuneration / 100;
            $cbData['cash_cashback'] = $cbData['commission_value'] * $deal->proactive_cashback / 100;
            $cbData['cumulative_cashback'] = $cumulativeCashback + $cbData['cash_cashback'];

            $cbData['commission_difference'] = $commissionPercentage + $oldCommissionPercentage;
            $cbData['additional_commission_value'] = $oldCommissionPercentage * ($cbData['commission_difference'] / 100);
            $cbData['cumulative_commission'] =  $cbData['cumulative_commission'] + $cbData['additional_commission_value'];

            $cbData['cashback_allocation'] = $cbData['cumulative_cashback'] != 0 ? $cbData['cash_cashback'] / $cbData['cumulative_cashback'] * 100 : 0;
            $cbData['earned_cashback'] = $cbData['cumulative_cashback'] * $cbData['cashback_allocation'] / 100;
            $cbData['final_cashback'] = min($cbData['deal_paid_amount'], $cbData['earned_cashback']);
            $cbData['final_cashback_percentage'] = $cbData['final_cashback'] / $cbData['deal_paid_amount'] * 100;
            CommissionBreakDown::create($cbData);
        }
        $param = DB::table('settings')->where("ParameterName", "=", 'GATEWAY_PAYMENT_FEE')->first();
        if (!is_null($param)) {
            $SettingCommissionPercentage = $param->DecimalValue;
        } else {
            $SettingCommissionPercentage = 2;
        }

        CommissionBreakDown::create([
            'trigger' => 0,
            'type' => CommissionTypeEnum::OUT->value,
            'order_id' => $order->id,
            'purchase_value' => $order->out_of_deal_amount,
            'commission_percentage' => $SettingCommissionPercentage,
            'commission_value' => $order->out_of_deal_amount / 100 * $SettingCommissionPercentage,
        ]);
    }

    public static function run($simulation)
    {
        DB::beginTransaction();
        try {
            $balances = Balances::getStoredUserBalances($simulation['order']->user()->first()->idUser);
            Ordering::runDiscount($simulation['order'], $balances);
            $balances = Balances::getStoredUserBalances($simulation['order']->user()->first()->idUser);
            Ordering::runBFS($simulation['order'], $simulation['bfssTables'], $balances);
            $balances = Balances::getStoredUserBalances($simulation['order']->user()->first()->idUser);
            Ordering::runCASH($simulation['order'], $balances);
            $simulation['order']->updateStatus(OrderEnum::Paid);
            Ordering::runPartition($simulation['order'], $simulation['dealsTurnOver']);
            DB::commit();
            return $simulation['order']->updateStatus(OrderEnum::Dispatched);
        } catch (Exception $exception) {
            DB::rollBack();
            $simulation['order']->updateStatus(OrderEnum::Failed);
            Log::error($exception->getMessage());
        }
    }

}
