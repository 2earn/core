<?php

namespace App\Services\Orders;

use App\Enums\BalanceOperationsEnum;
use App\Enums\CommissionTypeEnum;
use App\Enums\OrderEnum;
use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\CommissionBreakDown;
use App\Models\Deal;
use App\Models\DiscountBalances;
use App\Models\Order;
use App\Models\OrderDeal;
use App\Models\OrderDetail;
use App\Notifications\OrderCompleted;
use App\Services\Balances\Balances;
use App\Services\Balances\BalancesFacade;
use Core\Models\BalanceOperation;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Ordering
{
    static $initTurno = [
        'total_amount' => 0,
        'partner_discount' => 0,
        'amount_after_partner_discount' => 0,
        'earn_discount' => 0,
        'amount_after_earn_discount' => 0,
        'deal_discount_percentage' => 0,
        'deal_discount' => 0,
        'amount_after_deal_discount' => 0,
        'total_discount' => 0,
        'final_discount' => 0,
        'final_discount_percentage' => 0,
        'lost_discount' => 0,
        'final_amount' => 0,
    ];

    static $properties = [
        'total_amount',
        'partner_discount',
        'amount_after_partner_discount',
        'earn_discount',
        'amount_after_earn_discount',
        'deal_discount_percentage',
        'deal_discount',
        'amount_after_deal_discount',
        'total_discount',
    ];

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
            if ($orderDetail?->item?->deal()->exists()) {
                if (str_contains($order->note, 'Coupons buy from')) {
                    $deal_amount_before_discount = $deal_amount_before_discount + $orderDetail->unit_price;
                } else {
                    $deal_amount_before_discount = $deal_amount_before_discount + ($orderDetail->unit_price * $orderDetail->qty);
                }
            } else {
                $price_of_products_out_of_deal = $price_of_products_out_of_deal + ($orderDetail->unit_price * $orderDetail->qty);
            }
        }
        if (str_contains($order->note, 'Coupons buy from')) {
            $totalOrderQuantity = 1;
        }
        $out_of_deal_amount = $shippingSum + $price_of_products_out_of_deal;

        $order->update([
            'out_of_deal_amount' => $out_of_deal_amount,
            'deal_amount_before_discount' => $deal_amount_before_discount,
            'total_order_quantity' => $totalOrderQuantity,
        ]);

        $orderTotal = $out_of_deal_amount + $deal_amount_before_discount;

        if ($orderTotal <= $balances->cash_balance + $balances->discount_balance + Balances::getTotalBfs($balances)) {
            $order->update(['simulation_datetime' => now(), 'simulation_result' => true]);
            return true;
        }
        $order->update(
            [
                'simulation_datetime' => now(),
                'simulation_result' => false,
                'simulation_details' => 'Simulation failed due to insufficient funds in the account balances'
            ]
        );
        return false;
    }

    public static function initDealItem(OrderDetail $orderDetail)
    {
        return [
            'id' => $orderDetail->id,
            'deal' => $orderDetail->item->deal->id,
            'item_name' => $orderDetail->item->name,
            'unit_price' => $orderDetail->unit_price,
            'qty' => $orderDetail->qty,
            'total_amount' => $orderDetail->total_amount,
        ];
    }

    public static function fillPartnerDiscount(OrderDetail $orderDetail, array $itemDeal)
    {
        $hasPartnerDiscount = (!is_null($orderDetail->item->discount) || $orderDetail->item->discount != 0);
        $partnerDiscount = $hasPartnerDiscount ? $orderDetail->total_amount / 100 * $orderDetail->item->discount : 0;
        $amountAfterPartnerDiscount = $hasPartnerDiscount ? $orderDetail->total_amount - $partnerDiscount : $orderDetail->total_amount;
        return array_merge($itemDeal, [
            'partner_discount_percentage' => $orderDetail->item->discount,
            'partner_discount' => $partnerDiscount,
            'amount_after_partner_discount' => $amountAfterPartnerDiscount
        ]);
    }

    public static function fillEarnDiscount(OrderDetail $orderDetail, array $itemDeal)
    {
        $has2EarnDiscount = (!is_null($orderDetail->item->discount_2earn) || $orderDetail->item->discount_2earn != 0);
        $earnDiscount = $has2EarnDiscount ? $itemDeal['amount_after_partner_discount'] / 100 * $orderDetail->item->discount_2earn : 0;
        $amountAfter2EarnDiscount = $has2EarnDiscount ? $itemDeal['amount_after_partner_discount'] - $earnDiscount : $itemDeal['amount_after_partner_discount'];
        return array_merge($itemDeal, [
            'earn_discount_percentage' => $has2EarnDiscount ? $orderDetail->item->discount_2earn : 0,
            'earn_discount' => $earnDiscount,
            'amount_after_earn_discount' => $amountAfter2EarnDiscount,
        ]);
    }

    public static function fillDealDiscount(OrderDetail $orderDetail, array $itemDeal)
    {
        $hasDealDiscount = (!is_null($orderDetail->item->deal->discount) || $orderDetail->item->deal->discount != 0);
        $dealDiscount = $hasDealDiscount ? $itemDeal['amount_after_earn_discount'] / 100 * $orderDetail->item->deal->discount : 0;
        $amountAfterDealDiscount = $hasDealDiscount ? $itemDeal['amount_after_earn_discount'] - $dealDiscount : $itemDeal['amount_after_earn_discount'];
        return array_merge($itemDeal, [
            'deal_discount_percentage' => $hasDealDiscount ? $orderDetail->item->deal->discount : 0,
            'deal_discount' => $dealDiscount,
            'amount_after_deal_discount' => $amountAfterDealDiscount,
        ]);
    }

    public static function simulateDiscount(Order $order)
    {
        $balances = Balances::getStoredUserBalances($order->user()->first()->idUser);
        $discount_balance = $balances->discount_balance;
        $itemsDeals = [];
        $order_deal = [];
        $price_of_products_out_of_deal = 0;
        $shippingSum = 0;
        $deal_amount = 0;
        $deal_amount_after_discounts = 0;
        foreach ($order->orderDetails as $orderDetail) {
            $shippingSum = $shippingSum + $orderDetail->shipping;

            if ($orderDetail?->item?->deal()->exists()) {
                $itemDeal = Ordering::initDealItem($orderDetail);
                $itemDeal = Ordering::fillPartnerDiscount($orderDetail, $itemDeal);
                $itemDeal = Ordering::fillEarnDiscount($orderDetail, $itemDeal);
                $itemDeal = Ordering::fillDealDiscount($orderDetail, $itemDeal);
                $dealId = $orderDetail->item->deal->id;
                $itemDeal['total_discount'] = $itemDeal['partner_discount'] + $itemDeal['earn_discount'] + $itemDeal['deal_discount'];

                $itemsDeals[] = $itemDeal;

                if (!isset($order_deal[$dealId])) {
                    $order_deal[$dealId] = array_merge(self::$initTurno, ['deal_id' => $dealId, 'order_id' => $order->id]);
                }

                foreach (self::$properties as $property) {
                    $order_deal[$dealId][$property] = $order_deal[$dealId][$property] + $itemDeal[$property];
                }
                $order_deal[$dealId]['final_discount'] = $discount_balance > 0 ? min($order_deal[$dealId]['total_discount'], $discount_balance) : 0;
                $order_deal[$dealId]['final_discount_percentage'] = $order_deal[$dealId]['total_amount'] != 0 ? $order_deal[$dealId]['final_discount'] / $order_deal[$dealId]['total_amount'] : 0;
                $order_deal[$dealId]['lost_discount'] = $order_deal[$dealId]['total_discount'] - $order_deal[$dealId]['final_discount'];
                $order_deal[$dealId]['final_amount'] = $order_deal[$dealId]['amount_after_deal_discount'] + $order_deal[$dealId]['lost_discount'];
                $discount_balance = $discount_balance - $order_deal[$dealId]['final_discount'];
            } else {
                $price_of_products_out_of_deal = $price_of_products_out_of_deal + ($orderDetail->unit_price * $orderDetail->qty);
            }
        }
        foreach ($order_deal as $order_deal_item) {
            $deal_amount += $order_deal_item['total_amount'];
            $deal_amount_after_discounts += $order_deal_item['final_amount'];
        }
        Ordering::updateItemDeals($itemsDeals);
        $order->update([
            'out_of_deal_amount' => $shippingSum + $price_of_products_out_of_deal,
            'total_order' => $deal_amount + $shippingSum + $price_of_products_out_of_deal,
            'deal_amount_after_discounts' => $deal_amount_after_discounts,
            'amount_after_discount' => $deal_amount_after_discounts + $shippingSum + $price_of_products_out_of_deal,
        ]);

        Ordering::createOrderDeal($order_deal, $order);
        return $order_deal;
    }

    public static function createOrderDeal($order_deal, $order)
    {
        $total_amount = 0;
        $final_discount = 0;
        $lost_discount = 0;
        foreach ($order_deal as $order_deal_item) {
            $final_discount = $final_discount + $order_deal_item['final_discount'];
            $lost_discount = $lost_discount + $order_deal_item['lost_discount'];
            $total_amount = $total_amount + $order_deal_item['total_amount'];
            OrderDeal::create($order_deal_item);
        }
        $order->update([
            'total_final_discount' => $final_discount,
            'total_lost_discount' => $lost_discount,
            'total_final_discount_percentage' => $total_amount > 0 ? $final_discount / $total_amount * 100 : 0,
            'total_lost_discount_percentage' => $total_amount > 0 ? $lost_discount / $total_amount * 100 : 0,
        ]);
    }

    public static function updateItemDeals($itemsDeals)
    {
        foreach ($itemsDeals as $itemDeal) {
            OrderDetail::find($itemDeal['id'])->update([
                'total_amount' => $itemDeal['total_amount'],
                'partner_discount_percentage' => $itemDeal['partner_discount_percentage'],
                'partner_discount' => $itemDeal['partner_discount'],
                'amount_after_partner_discount' => $itemDeal['amount_after_partner_discount'],
                'earn_discount_percentage' => $itemDeal['earn_discount_percentage'],
                'earn_discount' => $itemDeal['earn_discount'],
                'amount_after_earn_discount' => $itemDeal['amount_after_earn_discount'],
                'deal_discount_percentage' => $itemDeal['deal_discount_percentage'],
                'deal_discount' => $itemDeal['deal_discount'],
                'amount_after_deal_discount' => $itemDeal['amount_after_deal_discount'],
                'total_discount' => $itemDeal['total_discount'],
            ]);
        }
    }


    public static function initBfssTable($user)
    {
        $bfssTables = [];
        $userCurrentBalanceHorisontal = Balances::getStoredUserBalances($user->idUser);
        $bfss = $userCurrentBalanceHorisontal->bfss_balance;
        foreach ($bfss as $key => $bfs) {
            if ($bfs['value'] > 0) {
                $bfssTables[$bfs['type']] = ['available' => $bfs['value']];
            }
        }
        krsort($bfssTables);
        return $bfssTables;
    }

    public static function simulateBFSs(Order $order)
    {
        $bfssTables = Ordering::initBfssTable($order->user()->first());

        $amount_after_discount = $order->amount_after_discount;
        if (!empty($bfssTables)) {
            foreach ($bfssTables as $key => $bfs) {
                $available = floatval($bfs['available']);
                $toCover = $amount_after_discount * floatval($key) / 100;
                $toSubstruct = $available > 0 ? min($available, $toCover) : 0;
                $amount_after_discount = $amount_after_discount - $toSubstruct;
                $bfs['toSubstruct'] = $toSubstruct;
                $bfs['balance'] = $available - $toSubstruct;
                $bfs['amount'] = $amount_after_discount;
                $bfssTables[$key] = $bfs;
                if ($amount_after_discount == 0) {
                    return array_splice($bfssTables, ($key == "100.00" ? 0 : 1), 1);
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
            $order_deal = self::simulateDiscount($order);
            $bfssTables = self::simulateBFSs($order);
            if (!empty($bfssTables)) {
                $amount = end($bfssTables)['amount'];
            } else {
                $amount = $order->amount_after_discount;
            }
            self::simulateCash($order, $amount);
            $order->updateStatus(OrderEnum::Simulated);

            return ['order' => $order, 'order_deal' => $order_deal, 'bfssTables' => $bfssTables];
        }
        return false;
    }

    public static function runDiscount(Order $order, $order_deal, $balances)
    {
        foreach ($order_deal as $order_deal_item) {
            $countedDiscount = $order_deal_item['final_discount'];
            if ($countedDiscount > 0) {
                if ($countedDiscount <= $balances->discount_balance) {
                    $currentBalance = $balances->discount_balance + BalanceOperation::getMultiplicator(BalanceOperationsEnum::OLD_ID_59->value) * $countedDiscount;
                    $discountData = [
                        'balance_operation_id' => BalanceOperationsEnum::OLD_ID_58->value,
                        'operator_id' => Balances::SYSTEM_SOURCE_ID,
                        'beneficiary_id' => $order->user()->first()->idUser,
                        'reference' => BalancesFacade::getReference(BalanceOperationsEnum::OLD_ID_58->value),
                        'description' => $countedDiscount . ' From ordering (id) ' . $order->id,
                        'value' => $countedDiscount,
                        'current_balance' => $currentBalance
                    ];
                    DiscountBalances::addLine($discountData, null, null, $order->id, null, null);
                } else {
                    throw new Exception('No discount solde');
                }
            }
        }
    }

    public static function runBFS(Order $order, $bfssTables, $balances)
    {
        foreach ($bfssTables as $key => $bfs) {
            $currentBalance = $balances->getBfssBalance($key) + (BalanceOperation::getMultiplicator(BalanceOperationsEnum::OLD_ID_59->value) * $bfs['toSubstruct']);
            if ($bfs['toSubstruct'] <= $balances->getBfssBalance($key)) {
                $bfsData = [
                    'balance_operation_id' => BalanceOperationsEnum::OLD_ID_59->value,
                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                    'beneficiary_id' => $order->user()->first()->idUser,
                    'reference' => BalancesFacade::getReference(BalanceOperationsEnum::OLD_ID_59->value),
                    'percentage' => $key,
                    'description' => $bfs['toSubstruct'] . ' From ordering (id) ' . $order->id,
                    'value' => $bfs['toSubstruct'],
                    'current_balance' => $currentBalance
                ];
                BFSsBalances::addLine($bfsData, null, null, $order->id, null, null);
            } else {
                throw new Exception('No BFS sold');
            }
        }
    }

    public static function runCASH(Order $order, $balances)
    {
        if ($order->paid_cash) {
            if ($order->paid_cash <= $balances->cash_balance) {
                $currentBalance = $balances->cash_balance + (BalanceOperation::getMultiplicator(BalanceOperationsEnum::OLD_ID_60->value) * $order->paid_cash);
                $cashData = [
                    'balance_operation_id' => BalanceOperationsEnum::OLD_ID_60->value,
                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                    'beneficiary_id' => $order->user()->first()->idUser,
                    'reference' => BalancesFacade::getReference(BalanceOperationsEnum::OLD_ID_60->value),
                    'description' => $order->paid_cash . ' From ordering (id) ' . $order->id,
                    'value' => $order->paid_cash,
                    'current_balance' => $currentBalance
                ];
                CashBalances::addLine($cashData, null, null, $order->id, null, null);
            } else {
                throw new Exception('No Cash sold');
            }
        }
    }

    public static function runPartition(Order $order, array $order_deal)
    {
        foreach ($order_deal as $dealId => $turnOver) {
            $deal = Deal::find($dealId);
            $oldTurnOver = $deal->current_turnover;
            $newTurnOver = $deal->updateTurnover($turnOver['amount_after_partner_discount']);

            $commissionPercentage = Deal::getCommissionPercentage($deal, $newTurnOver);

            $cumulativeCashback = CommissionBreakDown::getSum($dealId, 'cumulative_cashback');

            $cbData = [
                'order_id' => $order->id,
                'deal_id' => $dealId,
                'platform_id' => $order->platform_id,
                'trigger' => 0,
                'type' => CommissionTypeEnum::IN->value,
                'new_turnover' => $newTurnOver,
                'old_turnover' => $oldTurnOver,
                'purchase_value' => $turnOver['amount_after_partner_discount'],
                'additional_amount' => $turnOver['final_amount'] - $turnOver['amount_after_partner_discount'],
                'deal_paid_amount' => $turnOver['final_amount'],
                'commission_percentage' => $commissionPercentage,
            ];

            $cbData['commission_value'] = $turnOver['amount_after_partner_discount'] * $commissionPercentage / 100;
            $cbData['camembert'] = $cbData['commission_value'] + $cbData['additional_amount'];

            if ($cbData['camembert'] < 0) {
                $cbData['camembert'] = 0;
            }

            $cbData['cash_company_profit'] = $cbData['camembert'] != 0 ? ($cbData['camembert'] * $deal->earn_profit / 100) : $cbData['commission_value'];
            $cbData['cash_jackpot'] = $cbData['camembert'] * $deal->jackpot / 100;
            $cbData['cash_tree'] = $cbData['camembert'] * $deal->tree_remuneration / 100;
            $cbData['cash_cashback'] = $cbData['camembert'] * $deal->proactive_cashback / 100;

            $cbData['cumulative_cashback'] = $cumulativeCashback + $cbData['cash_cashback'];

            Ordering::updateDealRepartition($deal, $cbData);

            CommissionBreakDown::create($cbData);
        }

        $SettingCommissionPercentage = getSettingDecimalParam('GATEWAY_PAYMENT_FEE', 2);

        CommissionBreakDown::create([
            'trigger' => 0,
            'type' => CommissionTypeEnum::OUT->value,
            'order_id' => $order->id,
            'platform_id' => $order->platform_id,
            'purchase_value' => $order->out_of_deal_amount,
            'commission_percentage' => $SettingCommissionPercentage,
            'commission_value' => $order->out_of_deal_amount / 100 * $SettingCommissionPercentage,
        ]);
    }

    public static function updateDealRepartition($deal, $cbData)
    {
        $deal->cash_company_profit = $deal->cash_company_profit + $cbData['cash_company_profit'];
        $deal->cash_jackpot = $deal->cash_jackpot + $cbData['cash_jackpot'];
        $deal->cash_tree = $deal->cash_tree + $cbData['cash_tree'];
        $deal->cash_cashback = $deal->cash_cashback + $cbData['cash_cashback'];
        $deal->save();
    }

    public static function run($simulation)
    {
        DB::beginTransaction();
        try {
            $balances = Balances::getStoredUserBalances($simulation['order']->user()->first()->idUser);
            Ordering::runDiscount($simulation['order'], $simulation['order_deal'], $balances);
            $balances = Balances::getStoredUserBalances($simulation['order']->user()->first()->idUser);
            Ordering::runBFS($simulation['order'], $simulation['bfssTables'], $balances);
            $balances = Balances::getStoredUserBalances($simulation['order']->user()->first()->idUser);
            Ordering::runCASH($simulation['order'], $balances);
            $simulation['order']->updateStatus(OrderEnum::Paid);
            Ordering::runPartition($simulation['order'], $simulation['order_deal']);
            DB::commit();
            $simulation['order']->user()->first()->notify(new OrderCompleted($simulation['order']));

            $simulation['order']->update([
                'payment_datetime' => now(),
                'payment_result' => true,
                'payment_details' => 'Payment successful'
            ]);

            return $simulation['order']->updateStatus(OrderEnum::Dispatched);

        } catch (Exception $exception) {
            DB::rollBack();
            $simulation['order']->update([
                'payment_datetime' => now(),
                'payment_result' => false,
                'payment_details' => 'Payment failed: ' . $exception->getMessage()
            ]);
            Log::error($exception->getMessage());
            return $simulation['order']->updateStatus(OrderEnum::Failed);
        }
    }

}
