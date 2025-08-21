<?php

namespace Database\Seeders;

use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\ChanceBalances;
use App\Models\DiscountBalances;
use App\Models\SharesBalances;
use App\Models\SMSBalances;
use App\Models\TreeBalances;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class BalancesMigrationSeeder extends Seeder
{


    public function changeCashbalanceOperationId($cashBalances)
    {
        match ($cashBalances->balance_operation_id) {
            48 => $cashBalances->balance_operation_id = 15,
            60 => $cashBalances->balance_operation_id = 16,
            43 => $cashBalances->balance_operation_id = 21,
            42 => $cashBalances->balance_operation_id = 22,
            16 => $cashBalances->balance_operation_id = 23,
            49 => $cashBalances->balance_operation_id = 59,
            51 => $cashBalances->balance_operation_id = 61,
            63 => $cashBalances->balance_operation_id = 63,
            default => Log::notice('cashBalances ' . $cashBalances->balance_operation_id)
        };
        $cashBalances->save();
    }

    public function changeBfsbalanceOperationId($BFSsBalances)
    {
        match ($BFSsBalances->balance_operation_id) {
            59 => $BFSsBalances->balance_operation_id = 17,
            13 => $BFSsBalances->balance_operation_id = 24,
            28 => $BFSsBalances->balance_operation_id = 25,
            14 => $BFSsBalances->balance_operation_id = 26,
            46 => $BFSsBalances->balance_operation_id = 27,
            57 => $BFSsBalances->balance_operation_id = 41,
            50 => $BFSsBalances->balance_operation_id = 60,
            62 => $BFSsBalances->balance_operation_id = 65,
            default => Log::notice('BFSsBalances ' . $BFSsBalances->balance_operation_id)

        };
        $BFSsBalances->save();
    }

    public function changeDiscountbalanceOperationId($discountBalances)
    {
        match ($discountBalances->balance_operation_id) {
            6 => $discountBalances->balance_operation_id = 3,
            58 => $discountBalances->balance_operation_id = 18,
            47 => $discountBalances->balance_operation_id = 28,
            61 => $discountBalances->balance_operation_id = 64,
            default => Log::notice('discountBalances ' . $discountBalances->balance_operation_id)
        };
        $discountBalances->save();
    }

    public function changeChancebalanceOperationId($chanceBalances)
    {
        match ($chanceBalances->balance_operation_id) {
            56 => $chanceBalances->balance_operation_id = 7,
            default => Log::notice('chanceBalances ' . $chanceBalances->balance_operation_id)
        };
        $chanceBalances->save();
    }


    public function changeSharebalanceOperationId($sharesBalances)
    {
        match ($sharesBalances->balance_operation_id) {
            44 => $sharesBalances->balance_operation_id = 20,
            53 => $sharesBalances->balance_operation_id = 30,
            54 => $sharesBalances->balance_operation_id = 31,
            55 => $sharesBalances->balance_operation_id = 32,
            52 => $sharesBalances->balance_operation_id = 58,
            64 => $sharesBalances->balance_operation_id = 63,
            default => Log::notice('sharesBalances ' . $sharesBalances->balance_operation_id)
        };
        $sharesBalances->save();
    }

    public function changeTreebalanceOperationId($treeBalances)
    {
        match ($treeBalances->balance_operation_id) {
            1 => $treeBalances->balance_operation_id = 4,
            5 => $treeBalances->balance_operation_id = 29,
            default => Log::notice('treeBalances ' . $treeBalances->balance_operation_id)
        };
        $treeBalances->save();
    }

    public function changeSMSbalanceOperationId($SMSBalances)
    {
        match ($SMSBalances->balance_operation_id) {
            39 => $SMSBalances->balance_operation_id = 19,
            default => Log::notice('SMSBalances ' . $SMSBalances->balance_operation_id)
        };
        $SMSBalances->save();
    }

    public function run(): void
    {
        CashBalances::all()->each(function (CashBalances $cashBalances) {
            $this->changeCashbalanceOperationId($cashBalances);
        });

        BFSsBalances::all()->each(function (BFSsBalances $BFSsBalances) {
            $this->changeBfsbalanceOperationId($BFSsBalances);
        });

        DiscountBalances::all()->each(function (DiscountBalances $discountBalances) {
            $this->changeDiscountbalanceOperationId($discountBalances);
        });

        ChanceBalances::all()->each(function (ChanceBalances $chanceBalances) {
            $this->changeChancebalanceOperationId($chanceBalances);
        });

        SharesBalances::all()->each(function (SharesBalances $sharesBalances) {
            $this->changeSharebalanceOperationId($sharesBalances);
        });

        TreeBalances::all()->each(function (TreeBalances $treeBalances) {
            $this->changeTreebalanceOperationId($treeBalances);
        });

        SMSBalances::all()->each(function (SMSBalances $SMSBalances) {
            $this->changeSMSbalanceOperationId($SMSBalances);
        });
    }
}
