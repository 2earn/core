<?php

namespace Database\Seeders;

use App\Models\ActionBalances;
use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\DiscountBalances;
use App\Models\SMSBalances;
use App\Models\TreeBalances;
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

        $this->display('cashCountor', $this->cashCountor);
        $this->display('bfsCountor', $this->bfsCountor);
        $this->display('discountCountor', $this->discountCountor);
        $this->display('treeCountor', $this->treeCountor);
        $this->display('smsCountor', $this->smsCountor);
        $this->display('actionCountor', $this->actionCountor);
    }

    public function insertCash($balance)
    {
        $cash = [
            'deal_id' => null,
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'actual_balance' => $balance->Balance,
            'ref' => $balance->ref,
            'description' => $balance->Description,
            'created_at' => now(),
            'updated_at' => now(),
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
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'actual_balance' => $balance->Balance,
            'ref' => $balance->ref,
            'description' => $balance->Description,
            'created_at' => now(),
            'updated_at' => now(),
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
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'actual_balance' => $balance->Balance,
            'ref' => $balance->ref,
            'description' => $balance->Description,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DiscountBalances::create($discount);
        $this->discountCountor++;

    }

    public function inserttree($balance)
    {
        $tree = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'actual_balance' => $balance->Balance,
            'ref' => $balance->ref,
            'description' => $balance->Description,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $this->treeCountor++;
        TreeBalances::create($tree);

    }

    public function insertSMS($balance)
    {
        $sms = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'actual_balance' => $balance->Balance,
            'ref' => $balance->ref,
            'description' => $balance->Description,
            'created_at' => now(),
            'updated_at' => now(),
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
            'ref' => $balance->ref,
            'description' => $balance->Description,
            'win_purchase_amount' => $balance->WinPurchaseAmount,
            'gifted_shares' => $balance->gifted_shares,
            'unit_price' => $balance->PrixUnitaire,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        ActionBalances::create($action);
        $this->actionCountor++;
    }

    public function display($type, $number)
    {
        $this->command->info($type);
        $this->command->line($number);
    }
}
