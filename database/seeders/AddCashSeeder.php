<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddCashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $idUsers = [197604550, 197604395, 197604239, 197604342];

        $value = 10000;

        foreach ($idUsers as $idUser) {

            $userCurrentBalances = DB::table('usercurrentbalances')
                ->where('idUser', $idUser)
                ->where('idamounts', 1)->first();

            $OldValue = $userCurrentBalances->value ?? 0;


            // CHECKED IN BALANCES
            DB::table('user_balances')->insert([
                'Date' => now(),
                'idBalancesOperation' => 18,
                'Description' => 'cash add from system : and update usercurrentbalances',
                'idSource' => '11111111',
                'idUser' => $idUser,
                'idamount' => '1',
                'value' => $value,
                'Balance' => $OldValue + $value,
                'WinPurchaseAmount' => '0',
                'Block_trait' => '0',
                'ref' => '182400000082',
                'PrixUnitaire' => '1',
            ]);

            DB::table('usercurrentbalances')->where('idUser', $idUser)->where('idamounts', 1)->update(
                [
                    'value' => $OldValue + $value,
                    'dernier_value' => $OldValue,
                ]
            );
        }

    }
}
