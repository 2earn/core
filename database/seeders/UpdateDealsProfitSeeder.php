<?php

namespace Database\Seeders;

use App\Models\CommissionBreakDown;
use App\Models\Deal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class UpdateDealsProfitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deals = Deal::all();
        foreach ($deals as $deal) {
            $commission_break_downs = CommissionBreakDown::where('deal_id', $deal->id)->get();
            $params = [
                'cash_company_profit' => $commission_break_downs->sum('cash_company_profit'),
                'cash_jackpot' => $commission_break_downs->sum('cash_jackpot'),
                'cash_tree' => $commission_break_downs->sum('cash_tree'),
                'cash_cashback' => $commission_break_downs->sum('cash_cashback'),
            ];
            $deal->update($params);
            Log::notice('Update deals profit data / id: ' . $deal->id);
            Log::notice('Update deals profit data / deals data:' . json_encode($params));
        }
    }
}
