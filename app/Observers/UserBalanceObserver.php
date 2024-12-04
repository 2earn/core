<?php

namespace App\Observers;

use App\Models\DiscountBalances;
use App\Services\Balances\Balances;
use Core\Models\user_balance;
use Core\Services\BalancesManager;
use Core\Services\settingsManager;

use Carbon\Carbon;
use Core\Models\Setting;

class UserBalanceObserver
{

    public function __construct(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        $this->settingsManager = $settingsManager;
        $this->balancesManager = $balancesManager;
        $this->name = "fsdf";
    }

    private BalancesManager $balancesManager;

    public function saving(user_balance $user_balance)
    {
    }

    public function creating(user_balance $user_balance)
    {
    }

    public function created(user_balance $user_balance)
    {
        $table = [13, 14, 23, 24, 29, 46, 50];

        if (in_array($user_balance->idBalancesOperation, $table)) {
            $setting = Setting::WhereIn('idSETTINGS', ['22', '23'])->orderBy('idSETTINGS')->pluck('IntegerValue');
            $md = $setting[0];
            $rc = $setting[1];
            // user__balance old
            $ub = new user_balance([
                'ref' => $user_balance->ref,
                'idBalancesOperation' => 47,
                'Date' => date('Y-m-d H:i:s'),
                'idSource' => '11111111',
                'idUser' => $user_balance->idUser,
                'idamount' => 3,
                'value' => min($md, $user_balance->value * (pow(abs($user_balance->value - 10), 1.5) / $rc)),
                'WinPurchaseAmount' => "0.000",
                'PrixUnitaire' => 1,
                'Description' => number_format(100 * min($md, $user_balance->value * (pow(abs($user_balance->value - 10), 1.5) / $rc)) / $md, 2, '.', '') . '%',
                'Balance' => $this->balancesManager->getBalances($user_balance->idUser, -1)->soldeDB + min($md, $user_balance->value * (pow(abs($user_balance->value - 10), 1.5) / $rc))
            ]);
            // user__balance new
            DiscountBalances::addLine(
                [
                    'item_id' => null,
                    'deal_id' => null,
                    'order_id' => null,
                    'platform_id' => 1,
                    'order_detail_id' => null,
                    'balance_operation_id' => 47,
                    'operator_id' => Balances::SYSTEM_SOURCE_ID,
                    'beneficiary_id' => $user_balance->idUser,
                    'reference' => $user_balance->ref,
                    'value' => min($md, $user_balance->value * (pow(abs($user_balance->value - 10), 1.5) / $rc)),
                    'description' =>  number_format(100 * min($md, $user_balance->value * (pow(abs($user_balance->value - 10), 1.5) / $rc)) / $md, 2, '.', '') . '%',
                    'current_balance' => $this->balancesManager->getBalances($user_balance->idUser, -1)->soldeDB + min($md, $user_balance->value * (pow(abs($user_balance->value - 10), 1.5) / $rc))
                ]
            );
            $ub->save();
        }
    }

}
