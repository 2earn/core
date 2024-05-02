<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserBalancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_balances')->insert([
            'Date' => now(),
            'idBalancesOperation' => 44,
            'Description' => 'purchase of 34 shares for me',
            'idSource' => '999931611',
            'idUser' => '999931611',
            'idamount' => '1',
            'value' => '300',
            'Balance' => '2.330',
            'WinPurchaseAmount' => '3.000',
            'PU' => '5.7785294117647',
            'Block_trait' => '0',
            'ref' => '442400000082',
            'PrixUnitaire' => '1',
        ]);
    }
}
