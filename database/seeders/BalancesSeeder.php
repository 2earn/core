<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class BalancesSeeder extends Seeder
{
    public function run()
    {
        Setting::updateOrCreate(
            ['ParameterName' => 'BALANCES_COMPTER'],
            ['IntegerValue' => 1976]
        );

        Setting::updateOrCreate(
            ['ParameterName' => 'INITIAL_CHANCE'],
            ['IntegerValue' => 1]
        );

        Setting::updateOrCreate(
            ['ParameterName' => 'INITIAL_TREE'],
            ['IntegerValue' => 2]
        );

        // Handle legacy parameter name migration before creating/updating INITIAL_DISCOUNT
        Setting::where('ParameterName', 'discount By registering')
            ->update(['ParameterName' => 'INITIAL_DISCOUNT']);

        Setting::updateOrCreate(
            ['ParameterName' => 'INITIAL_DISCOUNT'],
            ['IntegerValue' => 20]
        );
        Setting::updateOrCreate(
            ['ParameterName' => 'TOTAL_TREE'],
            ['IntegerValue' => 125]
        );

        Setting::updateOrCreate(
            ['ParameterName' => 'GATEWAY_PAYMENT_FEE'],
            ['DecimalValue' => 2]
        );
    }

}
