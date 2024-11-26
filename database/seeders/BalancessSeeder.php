<?php

namespace Database\Seeders;

use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\DiscountBalances;
use App\Models\SharesBalances;
use App\Models\SMSBalances;
use App\Models\TreeBalances;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BalancessSeeder extends Seeder
{
    const DTAEFORMAT = 'dmY';
    public $nullOperation = 0;
    public $insccription = 0;

    public $bfs = 0;
    public $bfs_38_39 = 0;
    public $sms = 0;
    public $action = 0;
    public $action_52 = 0;
    public $action_44_54_55 = 0;
    public $actionSuite_46_47_48_49_50 = 0;
    public $cash = 0;
    public $cash_18 = 0;
    public $cash_42_43 = 0;
    public $cash_51 = 0;
    public $other = 0;

    public function run()
    {
        $balances = DB::table('user_balances')->get();
        $this->display('$balances', count($balances));
        foreach ($balances as $balance) {
            match ($balance->idBalancesOperation) {
                19, 20, 21, 37 => $this->insertNullOperation_19_20_21_37(),
                1, 6 => $this->insertInscription_1_6($balance),
                13, 16 => $this->insertBFS_13_16($balance),
                18 => $this->insertCash_18($balance),
                38, 39 => $this->insertBFS_38_39($balance),
                42, 43 => $this->insertCash_42_43($balance),
                44 => $this->insertAction_44_54_55($balance),
                46, 47, 48, 49, 50 => $this->insertActionSuite_46_47_48_49_50($balance),
                51 => $this->insertCash_51($balance),
                52 => $this->insertAction_52($balance),
                default => $this->insertOther(),
            };
        }
        $this->display('nullOperation', $this->nullOperation);
        $this->display('insccription : ', $this->insccription);

        $this->display('bfs : ', $this->bfs);
        $this->display('bfs_38_39 : ', $this->bfs_38_39);

        $this->display('cash', $this->cash);
        $this->display('cash_18', $this->cash_18);
        $this->display('cash_42_43', $this->cash_42_43);
        $this->display('cash_51', $this->cash_51);

        $this->display('action', $this->action);
        $this->display('action_52', $this->action_52);
        $this->display('actionSuite_46_47_48_49_50', $this->actionSuite_46_47_48_49_50);

        $this->display('sms', $this->sms);

        $this->display('other', $this->other);
    }


    public function insertToBalance($balance, $idAmount)
    {
        try {
            match ($idAmount) {
                1 => CashBalances::create($balance),
                2 => BFSsBalances::create($balance),
                3 => DiscountBalances::create($balance),
                4 => TreeBalances::create($balance),
                5 => SMSBalances::create($balance),
                6 => SharesBalances::create($balance),
            };
            $balance = [];
        } catch (\Exception $exception) {
            dd($exception->getMessage(), $balance, $idAmount);
        }
    }


    public function getRef($balance)
    {
        try {
            if (!is_null($balance->Date)) {
                $date = new \DateTime($balance->Date);
                if (!is_null($balance->ref)) {
                    $idRef = substr($balance->ref, 7, strlen($balance->ref) - 1);
                } else {
                    $idRef = $balance->id;
                }
                return '0' . $balance->idBalancesOperation . $date->format(self::DTAEFORMAT) . '00' . $idRef;
            }
        } catch (\Exception $exception) {
        }
    }


    public function display($type, $number)
    {
        $this->command->info($type);
        $this->command->line($number);
    }

    public function insertOther()
    {
        $this->other++;

    }

    public function insertNullOperation_19_20_21_37()
    {
        $this->loggging('insertNullOperation_19_20_21_37', null);
        $this->nullOperation++;
    }

    public function loggging($name, $balance)
    {
        $this->command->line($name . ' idamount: ' . $balance?->idamount . ' idBalancesOperation: ' . $balance?->idBalancesOperation);
    }

    public function insertInscription_1_6($balance)
    {
        $this->loggging('insertInscription_1_6', $balance);
        $insccription = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'total_balance' => $balance->Balance,
            'total_amount' => $balance->Balance,
            'ref' => $this->getRef($balance), 'reference' => null,
            'description' => $balance->Description,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];
        $this->insertToBalance($insccription, $balance->idamount);
        $this->insccription++;
    }

    public function insertBFS_13_16($balance)
    {
        $this->loggging('insertBFS_13_16', $balance);
        $bfs = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'total_balance' => $balance->Balance,
            'total_amount' => $balance->Balance,
            'reference' => $this->getRef($balance), 'ref' => $balance->ref,
            'description' => $balance->Description,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];
        if ($balance->idamount == 2) {
            if (in_array($balance->id, [126538, 126937, 126939])) {
                $bfs['percentage'] = 100;
            } else {
                $bfs['percentage'] = 50;
            }
        }

        $this->insertToBalance($bfs, $balance->idamount);;
        $this->bfs++;

    }

    public function insertCash($balance)
    {
        $this->loggging('insertCash', $balance);
        $cash = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'total_balance' => $balance->Balance,
            'total_amount' => $balance->Balance,
            'ref' => $this->getRef($balance), 'reference' => null,
            'description' => $balance->Description,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];
        $this->insertToBalance($cash, $balance->idamount);
        $this->cash++;
    }

    public function insertActionSuite_46_47_48_49_50($balance)
    {
        $this->loggging('insertActionSuite_46_47_48_49_50', $balance);
        $actionSuite = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'total_balance' => $balance->Balance,
            'total_amount' => $balance->Balance,
            'ref' => $this->getRef($balance),
            'reference' => null,
            'description' => $balance->Description,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];
        $this->insertToBalance($actionSuite, $balance->idamount);
        $this->actionSuite_46_47_48_49_50++;
    }

    public function insertCash_42_43($balance)
    {
        $this->loggging('insertCash_42_43', $balance);
        $cash = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'total_balance' => $balance->Balance,
            'total_amount' => $balance->Balance,
            'ref' => $this->getRef($balance), 'reference' => null,
            'description' => $balance->Description,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];
        $this->insertToBalance($cash, $balance->idamount);
        $this->cash_42_43++;
    }

    public function insertCash_18($balance)
    {
        $this->loggging('insertCash_18', $balance);
        $cash = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'total_balance' => $balance->Balance,
            'total_amount' => $balance->Balance,
            'ref' => $this->getRef($balance), 'reference' => null,
            'description' => $balance->Description,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];
        $this->insertToBalance($cash, $balance->idamount);
        $this->cash_18++;
    }

    public function insertCash_51($balance)
    {
        $this->loggging('insertCash_51', $balance);
        $cash = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'total_balance' => $balance->Balance,
            'total_amount' => $balance->Balance,
            'ref' => $this->getRef($balance), 'reference' => null,
            'description' => $balance->Description,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];
        $this->insertToBalance($cash, $balance->idamount);
        $this->cash_51++;
    }

    public function insertBFS_38_39($balance)
    {
        $this->loggging('insertBFS_38_39', $balance);
        $bfs = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'total_balance' => $balance->Balance,
            'total_amount' => $balance->Balance,
            'percentage' => 50,
            'ref' => $this->getRef($balance), 'reference' => null,
            'description' => $balance->Description,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];

        $this->insertToBalance($bfs, $balance->idamount);
        $this->bfs_38_39++;
    }

    public function insertAction_52($balance)
    {
        $this->loggging('insertAction_52', $balance);
        $action = [
            'balance_operation_id' => 44,
            'operator_id' => $balance->idSource,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'total_balance' => $balance->Balance,
            'total_amount' => $balance->Balance,
            'reference' => $this->getRef($balance), 'ref' => $balance->ref,
            'description' => $balance->Description,
            'amount' => $balance->PU * $balance->value,
            'unit_price' => $balance->PU,
            'payed' => $balance->WinPurchaseAmount,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];

        $this->action_52++;
        $this->insertToBalance($action, $balance->idamount);
    }

    public function insertAction_44_54_55($balance)
    {
        $this->loggging('insertAction_44_54_55', $balance);
        $action = [
            'balance_operation_id' => 44,
            'operator_id' => $balance->idSource,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'total_balance' => $balance->Balance,
            'total_amount' => $balance->Balance,
            'reference' => $this->getRef($balance), 'ref' => $balance->ref,
            'description' => $balance->Description,
            'amount' => $balance->PU * $balance->value,
            'unit_price' => $balance->PU,
            'payed' => $balance->WinPurchaseAmount,
            'real_amount' => $balance->value,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];

        $this->action_44_54_55++;
        $this->insertToBalance($action, $balance->idamount);
        if ($balance->gifted_shares < $balance->value && $balance->gifted_shares > 0) {
            $giftedShares = [
                'balance_operation_id' => 54,
                'operator_id' => $balance->idSource,
                'beneficiary_id' => $balance->idUser,
                'value' => $balance->gifted_shares - $balance->value,
                'total_balance' => $balance->Balance,
                'total_amount' => $balance->Balance,
                'reference' => $this->getRef($balance), 'ref' => $balance->ref,
                'description' => $balance->Description,
                'amount' => 0,
                'unit_price' => 0,
                'payed' => $balance->WinPurchaseAmount,
                'real_amount' => 0,
                'created_at' => $balance->Date,
                'updated_at' => $balance->Date,
            ];

            $this->action_44_54_55++;
            $this->insertToBalance($giftedShares, $balance->idamount);
        }

        if ($balance->gifted_shares > $balance->value) {
            $vip = [
                'balance_operation_id' => 55,
                'operator_id' => $balance->idSource,
                'beneficiary_id' => $balance->idUser,
                'value' => $balance->value,
                'total_balance' => $balance->Balance,
                'total_amount' => $balance->Balance,
                'reference' => $this->getRef($balance), 'ref' => $balance->ref,
                'description' => $balance->Description,
                'amount' => 0,
                'unit_price' => 0,
                'payed' => $balance->WinPurchaseAmount,
                'real_amount' => 0,
                'created_at' => $balance->Date,
                'updated_at' => $balance->Date,
            ];
            $this->action_44_54_55++;
            $this->insertToBalance($vip, $balance->idamount);
        }
    }


    public function insertAchat($balance)
    {
        $this->loggging('insertAchat', $balance);
        $achat = [
            'balance_operation_id' => 53,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'total_balance' => $balance->Balance,
            'total_amount' => $balance->Balance,
            'reference' => $this->getRef($balance), 'ref' => $balance->ref,
            'description' => $balance->Description,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];
        $this->insert($achat, $balance->idamount);
        $this->smsCountor++;

    }
}
