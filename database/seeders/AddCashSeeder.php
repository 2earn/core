<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AddCashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $idUser = 197604395;
        $value = 10000;
        DB::table('user_balances')->insert([
            'Date' => now(),
            'idBalancesOperation' => 18,
            'Description' => 'cash add from system : and update usercurrentbalances',
            'idSource' => '11111111',
            'idUser' => $idUser,
            'idamount' => '1',
            'value' => $value,
            'Balance' => '100',
            'WinPurchaseAmount' => '0',
            'Block_trait' => '0',
            'ref' => '182400000082',
            'PrixUnitaire' => '1',
        ]);

        $idUser = 197604395;
        DB::table('usercurrentbalances')
            ->where('idUser', $idUser)
            ->where('idamounts', 1)->update([
                'value' => $value,
            ]);

    }
}
