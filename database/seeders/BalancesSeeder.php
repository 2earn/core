<?php

namespace Database\Seeders;

use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\DiscountBalances;
use App\Models\SharesBalances;
use App\Models\SMSBalances;
use App\Models\TreeBalances;
use Core\Models\user_balance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BalancesSeeder extends Seeder
{
    const DTAEFORMAT = 'dmY';
    public $stats = [
        'null' => 0,
        CashBalances::class => 0,
        BFSsBalances::class => 0,
        DiscountBalances::class => 0,
        TreeBalances::class => 0,
        SMSBalances::class => 0,
        SharesBalances::class => 0,
    ];

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
                default => $this->insertOther(),
            };
        }

        $this->insertSMS_39();
        foreach ($this->stats as $stat => $count) {
            $this->display($stat . ' : ', $count);

        }
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
            match ($idAmount) {
                1 => $this->stats[CashBalances::class]++,
                2 => $this->stats[BFSsBalances::class]++,
                3 => $this->stats[DiscountBalances::class]++,
                4 => $this->stats[TreeBalances::class]++,
                5 => $this->stats[SMSBalances::class]++,
                6 => $this->stats[SharesBalances::class]++,
            };
        } catch (\Exception $exception) {
            dd($exception->getMessage(), $balance, $idAmount);
        }
    }


    public function getReference($balance)
    {
        if (!is_null($balance->Date)) {
            $date = new \DateTime($balance->Date);
            if (!is_null($balance->ref)) {
                $idRef = substr($balance->ref, 7, strlen($balance->ref) - 1);
            } else {
                $idRef = $balance->id;
            }
            return '0' . $balance->idBalancesOperation . $date->format(self::DTAEFORMAT) . '00' . $idRef;
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
        $this->stats['null']++;
    }

    public function loggging($name, $balance)
    {
        $this->command->line($name . ' idamount: ' . $balance?->idamount . ' idBalancesOperation: ' . $balance?->idBalancesOperation);
    }

    public function insertSMS_39()
    {

        $user_balances_sms = array(
            array('id' => '1', 'sms_id' => NULL, 'deal_id' => NULL, 'item_id' => NULL, 'platform_id' => NULL, 'balance_operation_id' => '39', 'description' => 'perchase of 25 SMS', 'operator_id' => '11111111', 'beneficiary_id' => '197604342', 'recipient_id' => NULL, 'value' => '25', 'current_balance' => '25', 'amount' => '50', 'reference' => '038130520240003775', 'created_at' => '2024-05-13 08:38:22', 'updated_at' => '2024-11-23 20:46:44'),
            array('id' => '2', 'sms_id' => NULL, 'deal_id' => NULL, 'item_id' => NULL, 'platform_id' => NULL, 'balance_operation_id' => '39', 'description' => 'perchase of 75 SMS', 'operator_id' => '11111111', 'beneficiary_id' => '197604342', 'recipient_id' => NULL, 'value' => '75', 'current_balance' => '100', 'amount' => '100', 'reference' => '038240520240003776', 'created_at' => '2024-05-24 09:14:55', 'updated_at' => '2024-11-23 20:46:44')
        );
        foreach ($user_balances_sms as $balance) {
            $sms = [
                'balance_operation_id' => $balance["balance_operation_id"],
                'operator_id' => $balance["operator_id"],
                'platform_id' => 1,
                'beneficiary_id' => $balance["beneficiary_id"],
                'value' => $balance["value"],
                'total_balance' => $balance["current_balance"],
                'reference' => $balance["reference"],
                'description' => $balance["description"] ?? "20$ as welcome gift",
                'created_at' => $balance["created_at"],
                'updated_at' => $balance["created_at"],
            ];
            $this->stats[SMSBalances::class]++;
            $this->insertToBalance($sms, 5);
        }

    }

    public function insertInscription_1_6($balance)
    {
        if ($balance->value == 0) {
            return;
        }
        $insccription = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'total_balance' => $balance->Balance,
            'reference' => $this->getReference($balance), 'ref' => $balance->ref,
            'description' => $balance->Description ?? "20$ as welcome gift",
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];
        $this->insertToBalance($insccription, $balance->idamount);
    }

    public function insertBFS_13_16($balance)
    {
        if ($balance->value == 0) {
            return;
        }
        $bfs = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'total_balance' => $balance->Balance,
            'reference' => $this->getReference($balance),
            'ref' => $balance->ref,
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
    }


    public function insertActionSuite_46_47_48_49_50($balance)
    {
        // 48 *********************************
        if (in_array($balance->idBalancesOperation, [46, 47, 49, 50]) && $balance->value == 0) {
            return;
        }
        $actionSuite = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'total_balance' => $balance->Balance,
            'reference' => $this->getReference($balance), 'ref' => $balance->ref,
            'description' => $balance->Description,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];
        $this->insertToBalance($actionSuite, $balance->idamount);
    }

    public function insertCash_42_43($balance)
    {
        if ($balance->value == 0) {
            return;
        }
        $cash = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'total_balance' => $balance->Balance,
            'reference' => $this->getReference($balance), 'ref' => $balance->ref,
            'description' => $balance->Description,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];
        $this->insertToBalance($cash, $balance->idamount);
    }

    public function insertCash_18($balance)
    {
        if ($balance->value == 0) {
            return;
        }
        $cash = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'total_balance' => $balance->Balance,
            'reference' => $this->getReference($balance), 'ref' => $balance->ref,
            'description' => $balance->Description,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];
        $this->insertToBalance($cash, $balance->idamount);
    }

    public function insertCash_51($balance)
    {
        if ($balance->value == 0) {
            return;
        }
        $cash = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'total_balance' => $balance->Balance,
            'reference' => $this->getReference($balance), 'ref' => $balance->ref,
            'description' => $balance->Description,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];
        $this->insertToBalance($cash, $balance->idamount);
    }

    public function insertBFS_38_39($balance)
    {
        if ($balance->value == 0) {
            return;
        }
        $bfs = [
            'balance_operation_id' => $balance->idBalancesOperation,
            'operator_id' => $balance->idSource,
            'platform_id' => 1,
            'beneficiary_id' => $balance->idUser,
            'value' => $balance->value,
            'total_balance' => $balance->Balance,
            'percentage' => 50,
            'reference' => $this->getReference($balance), 'ref' => $balance->ref,
            'description' => $balance->Description,
            'created_at' => $balance->Date,
            'updated_at' => $balance->Date,
        ];

        $this->insertToBalance($bfs, $balance->idamount);
    }


    public function insertAction_44_54_55($balance)
    {
        $realAmount = 0;
        if ($balance->WinPurchaseAmount == 2) {
            $realAmount = $balance->Balance;
        }
        if ($balance->WinPurchaseAmount == 1) {
            $realAmount = $balance->PU * $balance->value;
        }
        if ($balance->WinPurchaseAmount == 0) {
            $realAmount = 0;
        }
        $total = 0;
        $totalLine = user_balance::where('idBalancesOperation', 48)->where('ref', $balance->ref)->first();
        if (!is_null($totalLine)) {
            $total = $totalLine->value;
        }
        if ($balance->Description == 'action5$') {
            $action = [
                'balance_operation_id' => 53,
                'operator_id' => $balance->idSource,
                'beneficiary_id' => $balance->idUser,
                'value' => $balance->gifted_shares,
                'total_balance' => $total,
                'total_amount' => $balance->Balance,
                'reference' => $this->getReference($balance),
                'ref' => $balance->ref,
                'description' => $balance->Description,
                'amount' => $balance->PU * $balance->value,
                'unit_price' => $balance->PU,
                'payed' => $balance->WinPurchaseAmount,
                'real_amount' => $realAmount,
                'created_at' => $balance->Date,
                'updated_at' => $balance->Date,
            ];

            $this->insertToBalance($action, $balance->idamount);
            return;
        }
        if (str_contains($balance->Description, 'sponsorship commission from')) {
            if ($balance->gifted_shares == 0) {
                return;
            }
            $action = [
                'balance_operation_id' => 52,
                'operator_id' => $balance->idSource,
                'beneficiary_id' => $balance->idUser,
                'value' => $balance->gifted_shares,
                'total_balance' => $total,
                'total_amount' => $balance->Balance,
                'reference' => $this->getReference($balance),
                'ref' => $balance->ref,
                'description' => $balance->Description,
                'amount' => $balance->PU * $balance->value,
                'unit_price' => $balance->PU,
                'payed' => $balance->WinPurchaseAmount,
                'real_amount' => $realAmount,
                'created_at' => $balance->Date,
                'updated_at' => $balance->Date,
            ];

            $this->insertToBalance($action, $balance->idamount);
            return;
        }

        if ($balance->gifted_shares == 0) {
            $action = [
                'balance_operation_id' => 44,
                'operator_id' => $balance->idSource,
                'beneficiary_id' => $balance->idUser,
                'value' => $balance->value,
                'total_balance' => $total,
                'total_amount' => $balance->Balance,
                'reference' => $this->getReference($balance),
                'ref' => $balance->ref,
                'description' => $balance->Description,
                'amount' => $balance->PU * $balance->value,
                'unit_price' => $balance->PU,
                'payed' => $balance->WinPurchaseAmount,
                'real_amount' => $realAmount,
                'created_at' => $balance->Date,
                'updated_at' => $balance->Date,
            ];

            $this->insertToBalance($action, $balance->idamount);
        }

        if ($balance->gifted_shares < $balance->value && $balance->gifted_shares > 0) {
            $action = [
                'balance_operation_id' => 44,
                'operator_id' => $balance->idSource,
                'beneficiary_id' => $balance->idUser,
                'value' => $balance->value,
                'total_balance' => $total,
                'total_amount' => $balance->Balance,
                'reference' => $this->getReference($balance),
                'ref' => $balance->ref,
                'description' => $balance->Description,
                'amount' => $balance->PU * $balance->value,
                'unit_price' => $balance->PU,
                'payed' => $balance->WinPurchaseAmount,
                'real_amount' => $realAmount,
                'created_at' => $balance->Date,
                'updated_at' => $balance->Date,
            ];

            $this->insertToBalance($action, $balance->idamount);

            $giftedShares = [
                'balance_operation_id' => 54,
                'operator_id' => $balance->idSource,
                'beneficiary_id' => $balance->idUser,
                'value' => $balance->gifted_shares,
                'total_balance' => $total,
                'total_amount' => $balance->Balance,
                'reference' => $this->getReference($balance),
                'ref' => $balance->ref,
                'description' => $balance->Description,
                'amount' => 0,
                'unit_price' => 0,
                'payed' => $balance->WinPurchaseAmount,
                'real_amount' => $realAmount,
                'created_at' => $balance->Date,
                'updated_at' => $balance->Date,
            ];

            $this->insertToBalance($giftedShares, $balance->idamount);
        }

        if ($balance->gifted_shares > $balance->value) {

            $action = [
                'balance_operation_id' => 44,
                'operator_id' => $balance->idSource,
                'beneficiary_id' => $balance->idUser,
                'value' => $balance->value,
                'total_balance' => $total,
                'total_amount' => $balance->Balance,
                'reference' => $this->getReference($balance),
                'ref' => $balance->ref,
                'description' => $balance->Description,
                'amount' => $balance->PU * $balance->value,
                'unit_price' => $balance->PU,
                'payed' => $balance->WinPurchaseAmount,
                'real_amount' => $realAmount,
                'created_at' => $balance->Date,
                'updated_at' => $balance->Date,
            ];
            $this->insertToBalance($action, $balance->idamount);
            $gf = $balance->gifted_shares - $balance->value;

            $giftedShares = [
                'balance_operation_id' => 54,
                'operator_id' => $balance->idSource,
                'beneficiary_id' => $balance->idUser,
                'value' => $gf,
                'total_balance' => $total,
                'total_amount' => $balance->Balance,
                'reference' => $this->getReference($balance),
                'ref' => $balance->ref,
                'description' => $balance->Description,
                'amount' => 0,
                'unit_price' => 0,
                'payed' => $balance->WinPurchaseAmount,
                'real_amount' => $realAmount,
                'created_at' => $balance->Date,
                'updated_at' => $balance->Date,
            ];

            $this->insertToBalance($giftedShares, $balance->idamount);

            $vip = [
                'balance_operation_id' => 55,
                'operator_id' => $balance->idSource,
                'beneficiary_id' => $balance->idUser,
                'value' => $balance->gifted_shares - $gf,
                'total_balance' => $total,
                'total_amount' => $balance->Balance,
                'reference' => $this->getReference($balance), 'ref' => $balance->ref,
                'description' => $balance->Description,
                'amount' => 0,
                'unit_price' => 0,
                'payed' => $balance->WinPurchaseAmount,
                'real_amount' => $realAmount,
                'created_at' => $balance->Date,
                'updated_at' => $balance->Date,
            ];
            $this->insertToBalance($vip, $balance->idamount);
        }
    }
}
