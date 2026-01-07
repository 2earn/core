<?php

namespace Database\Seeders;

use App\Enums\BalanceOperationsEnum;
use App\Models\CashBalances;
use App\Services\Balances\Balances;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class AddCashSeeder extends Seeder
{
    const USERS_IDS = [197604395, 197604239, 197604342, 197604161, 197607204,197607274];

    public function run()
    {
        if (!App::isProduction()) {
            $value = 20000;
            $balances = new Balances();
            foreach (self::USERS_IDS as $idUser) {
                $userCurrentBalancehorisontal = Balances::getStoredUserBalances($idUser);
                CashBalances::addLine([
                    'balance_operation_id' => BalanceOperationsEnum::OLD_ID_63->value,
                    'operator_id' => $idUser,
                    'beneficiary_id' => $idUser,
                    'reference' => $balances->getReference(BalanceOperationsEnum::OLD_ID_63->value),
                    'description' => "Add cash balance from AddCashSeeder",
                    'value' => $value,
                    'current_balance' => $userCurrentBalancehorisontal->cash_balance + $value
                ]);
            }
        }
    }
}
