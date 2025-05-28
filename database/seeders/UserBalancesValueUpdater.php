<?php

namespace Database\Seeders;

use App\Models\ChanceBalances;
use App\Models\TreeBalances;
use App\Models\User;
use App\Services\Balances\Balances;
use App\Services\Balances\BalancesFacade;
use Core\Enum\BalanceOperationsEnum;
use Core\Enum\ChanceTypeEnum;
use Core\Enum\StatusRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class UserBalancesValueUpdater extends Seeder
{
    public $chances_balance = 0;
    public $tree_balance = 0;

    public function run(): void
    {
        $users = User::where('status', '>=', StatusRequest::OptValidated->value)->get();
        TreeBalances::truncate();
        ChanceBalances::truncate();
        foreach ($users as $user) {
            $initialTree = getSettingIntegerParam('INITIAL_TREE', 0);
            $initialChance = getSettingIntegerParam('INITIAL_CHANCE', 0);

            $userCurrentBalancehorisontal = Balances::getStoredUserBalances($user->idUser);
            $userCurrentBalancehorisontal->update(['tree_balance' => 0]);

            TreeBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::BY_REGISTERING_TREE->value,
                'operator_id' => Balances::SYSTEM_SOURCE_ID,
                'beneficiary_id' => $user->idUser,
                'reference' => BalancesFacade::getReference(BalanceOperationsEnum::BY_REGISTERING_TREE->value),
                'description' => BalanceOperationsEnum::BY_REGISTERING_TREE->name,
                'value' => $initialTree,
                'current_balance' => $initialTree
            ]);


            $this->tree_balance = $this->tree_balance + 1;
            Log::notice('tree_balance : idUser  : ' . $user->idUser . ' Created  : ' . $user->created_at);

            $userCurrentBalancehorisontal = Balances::getStoredUserBalances($user->idUser);
            $userCurrentBalancehorisontal->update(['chances_balance' => 0]);

            ChanceBalances::addLine([
                'balance_operation_id' => BalanceOperationsEnum::INITIAL_CHANE->value,
                'operator_id' => Balances::SYSTEM_SOURCE_ID,
                'beneficiary_id' => $user->idUser,
                'reference' => BalancesFacade::getReference(BalanceOperationsEnum::INITIAL_CHANE->value),
                'description' => BalanceOperationsEnum::INITIAL_CHANE->name,
                'value' => $initialChance,
                'pool_id' => 1,
                'current_balance' => $initialChance
            ]);


            $this->chances_balance = $this->chances_balance + 1;
            Log::notice('chances_balance : idUser  : ' . $user->idUser . ' Created  : ' . $user->created_at);
        }
        Log::notice('chances_balance ' . $this->chances_balance);
        Log::notice('tree_balance ' . $this->tree_balance);
    }
}
