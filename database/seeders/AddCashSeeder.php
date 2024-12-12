<?php

namespace Database\Seeders;

use App\Models\CashBalances;
use App\Models\UserCurrentBalanceHorisontal;
use Core\Enum\BalanceOperationsEnum;
use Illuminate\Database\Seeder;

class AddCashSeeder extends Seeder
{

    public function run()
    {
        $value = 10000;
        $idUsers = [197604395,197604239,197604342];

        foreach ($idUsers as $idUser) {
            $userCurrentBalancehorisontal = UserCurrentBalancehorisontal::where('user_id', $idUser)->first();
            CashBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::SI_CB->value,
                'operator_id' => $idUser,
                'beneficiary_id' => $idUser,
                'reference' => BalanceOperationsEnum::SI_CB->value,
                'description' => "add cash balance",
                'value' => $value,
                'current_balance' => $userCurrentBalancehorisontal->soldeCB + $value
            ]);
        }
    }
}
