<?php

namespace Database\Seeders;

use App\Models\CurrentBalances;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrentBalancesSeeder extends Seeder
{
    private $countor = 0;

    public function run()
    {
        $userCurrentBalances = DB::table('usercurrentbalances')->get();
        foreach ($userCurrentBalances as $userCurrentBalance) {
            $user = User::where('idUser', $userCurrentBalance->idUser)->first();
            CurrentBalances::create([
                'idUser' => $userCurrentBalance->idUser,
                'user_id' => $user->id,
                'amount' => $userCurrentBalance->idamounts,
                'value' => $userCurrentBalance->value,
                'last_value' => $userCurrentBalance->dernier_value,
            ]);
            $this->countor++;
        }


    }

    public function display($type, $number)
    {
        $this->command->info('Inserted');
        $this->command->line($this->countor);
    }
}
