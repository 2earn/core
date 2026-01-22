<?php

namespace Database\Seeders;

use App\Enums\BalanceEnum;
use App\Enums\StatusRequest;
use App\Models\User;
use App\Models\UserCurrentBalanceHorisontal;
use App\Models\UserCurrentBalanceVertical;
use App\Services\Balances\Balances;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class UserBalancesUpdater extends Seeder
{
    public $output;
    public $horisentalBalances = 0;
    public $verticalBalances = [];

    public function initUserCurrentBalanceVertical($user)
    {
        $user = User::where('idUser', $user->idUser)->first();
        foreach (BalanceEnum::cases() as $case) {
            $userCurrentBalanceVertical = UserCurrentBalanceVertical::where('user_id', $user->idUser)
                ->where('balance_id', $case->value)
                ->first();
            if (is_null($userCurrentBalanceVertical)) {
                UserCurrentBalanceVertical::create([
                    'user_id' => $user->idUser,
                    'user_id_auto' => $user->id,
                    'balance_id' => $case->value,
                    'previous_balance' => 0,
                    'current_balance' => 0,
                    'last_operation_id' => 0,
                    'last_operation_date' => 0,
                    'last_operation_value' => 0,
                ]);
                if (isset($this->verticalBalances[$case->value])) {
                    $this->verticalBalances[$case->value] = $this->verticalBalances[$case->value] + 1;
                } else {
                    $this->verticalBalances[$case->value] = 1;
                }
                Log::notice(json_encode('idUser  : ' . $user->idUser . ' Created  : ' . $user->created_at. ' balance : ' . BalanceEnum::tryFrom($case->value)->name));
            }
        }
    }

    public function initUserCurrentBalanceHorisontal($user)
    {
        $user = User::where('idUser', $user->idUser)->first();
        $userCurrentBalancehorisontal = Balances::getStoredUserBalances($user->idUser);

        if (is_null($userCurrentBalancehorisontal)) {
            UserCurrentBalanceHorisontal::create([
                'user_id' => $user->idUser,
                'user_id_auto' => $user->id,
                'cash_balance' => 0,
                'bfss_balance' => [],
                'sms_balance' => 0,
                'discount_balance' => 0,
                'tree_balance' => 0,
                'share_balance' => 0,
                'chances_balance' => 0,
            ]);

            $this->horisentalBalances = $this->horisentalBalances + 1;
            Log::notice('idUser  : ' . $user->idUser . ' Created  : ' . $user->created_at);
        }

    }

    public function run(): void
    {
        $users = User::where('status', '>=', StatusRequest::OptValidated->value)->get();
        foreach ($users as $user) {
            $this->initUserCurrentBalanceHorisontal($user);
            $this->initUserCurrentBalanceVertical($user);
        }
        Log::alert(json_encode($this->horisentalBalances));
        Log::alert(json_encode($this->verticalBalances));
    }
}
