<?php

namespace Database\Seeders;

use App\Models\CashBalances;
use App\Models\UserCurrentBalanceHorisontal;
use App\Services\Balances\Balances;
use Core\Enum\BalanceOperationsEnum;
use Illuminate\Database\Seeder;

class AddCashSeeder extends Seeder
{

    public function run()
    {
        $value = 10000;
        $idUsers = [197604395,197604239,197604342];

        foreach ($idUsers as $idUser) {
            $userCurrentBalancehorisontal = UserCurrentBalanceHorisontal::where('user_id', $idUser)->first();
            CashBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::SI_CB->value,
                'operator_id' => $idUser,
                'beneficiary_id' => $idUser,
                'reference' => Balances::getReference(BalanceOperationsEnum::SI_CB->value),
                'description' => "Add cash balance from AddCashSeeder",
                'value' => $value,
                'current_balance' => $userCurrentBalancehorisontal->soldeCB + $value
            ]);
        }
    }
}
