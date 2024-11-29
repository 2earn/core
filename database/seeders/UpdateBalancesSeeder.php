<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateBalancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $procedures = [
            'UpdateCurrentBalance',
            'UpdateCurrentBalancebfs',
            'UpdateCurrentBalanceDiscount',
            'UpdateCurrentBalanceshares',
            'UpdateCurrentBalancesms',
            'UpdateCurrentBalancetree',
            'UpdateTotalAmountshares',
        ];
        foreach ($procedures as $procedure) {
            DB::statement('CALL ' . $procedure . '()');
        }
    }
}
