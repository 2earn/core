<?php

namespace Database\Seeders;

use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\DiscountBalances;
use App\Models\SharesBalances;
use App\Models\SMSBalances;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BalancessSeeder extends Seeder
{
    public $cashCountor = 0;
    public $bfsCountor = 0;
    public $discountCountor = 0;
    public $treeCountor = 0;
    public $smsCountor = 0;
    public $actionCountor = 0;
    public $currentDate;

    public function run()
    {
        $balances = DB::table('user_balances')->get();
        foreach ($balances as $balance) {

            match ($balance->idamount) {
                1 => $this->insertCash($balance),
                2 => $this->insertBFS($balance),
                3 => $this->insertDiscount($balance),
                4 => $this->inserttree($balance),
                5 => $this->insertSMS($balance),
                6 => $this->insertAction($balance),
            };
        }
        $this->currentDate = new \DateTime('now');

        $this->display('cashCountor', $this->cashCountor);
        $this->display('bfsCountor', $this->bfsCountor);
        $this->display('discountCountor', $this->discountCountor);
        $this->display('treeCountor', $this->treeCountor);
        $this->display('smsCountor', $this->smsCountor);
        $this->display('actionCountor', $this->actionCountor);
    }

    public function getRef($balance)
    {
        if (!is_null($balance->ref)) return $balance->ref;
        try {
            if (!is_null($balance->Date)) {
                $date = new \DateTime($balance->Date);
                return $balance->idBalancesOperation . $date->format('Ymd') . $balance->id;
            }
        } catch (\Exception $exception) {
        }
        return $balance->idBalancesOperation . $this->currentDate->format('Ymd') . $balance->id;

    }

    public function insertCash($balance)
    {
        $cash = [
            'deal_id' => null,
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'actual_balance' => $balance->Balance,
            'reference' => $this->getRef($balance),
            'description' => $balance->Description,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];
        CashBalances::create($cash);
        $this->cashCountor++;

    }

    public function insertBFS($balance)
    {
        $bfs = [
            'deal_id' => null,
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'actual_balance' => $balance->Balance,
            'reference' => $this->getRef($balance),
            'description' => $balance->Description,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];
        BFSsBalances::create($bfs);
        $this->bfsCountor++;

    }

    public function insertDiscount($balance)
    {
        $discount = [
            'deal_id' => null,
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'actual_balance' => $balance->Balance,
            'reference' => $this->getRef($balance),
            'description' => $balance->Description,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];
        DiscountBalances::create($discount);
        $this->discountCountor++;

    }

    public function inserttree($balance)
    {


    }

    public function insertSMS($balance)
    {
        $sms = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'actual_balance' => $balance->Balance,
            'reference' => $this->getRef($balance),
            'description' => $balance->Description,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];
        SMSBalances::create($sms);
        $this->smsCountor++;

    }

    public function insertAction($balance)
    {
        $action = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'actual_balance' => $balance->Balance,
            'reference' => $this->getRef($balance),
            'description' => $balance->Description,
            'amount' => $balance->PU * $balance->value,
            'unit_price' => $balance->PU,
            'payed' => $balance->WinPurchaseAmount,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];

        SharesBalances::create($action);
        $this->actionCountor++;

        if ($balance->gifted_shares != 0) {
            $giftedShares = [
                'balance_operation_id' => $balance->idBalancesOperation,
                'operator_id' => $balance->idSource,
                'beneficiary_id' => $balance->idUser,
                'value' => $balance->gifted_shares,
                'actual_balance' => $balance->Balance,
                'reference' => $this->getRef($balance),
                'description' => $balance->Description,
                'amount' => 0,
                'unit_price' => 0,
                'payed' => $balance->WinPurchaseAmount,
                'created_at' => $balance->Date,
                'updated_at' => $balance->Date,
            ];
            $this->actionCountor++;
            SharesBalances::create($giftedShares);
        }
    }

    public function display($type, $number)
    {
        $this->command->info($type);
        $this->command->line($number);
    }
}
