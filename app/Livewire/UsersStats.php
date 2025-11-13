<?php

namespace App\Livewire;

use App\Services\Balances\Balances;
use Livewire\Component;

class UsersStats extends Component
{
    public function render()
    {
        return view('livewire.users-stats', [
            'adminCash' => getAdminCash()[0],
            'totalCashBalance' => Balances::sommeSold('cash_balances'),
            'usersCashBalance' => Balances::sommeSold('cash_balances') - floatval(getAdminCash()[0]),
            'bfsBalance' => Balances::sommeSold('bfss_balances'),
            'discountBalance' => Balances::sommeSold('discount_balances'),
            'smsBalance' => Balances::sommeSold('sms_balances'),
            'sharesSold' => Balances::sommeSold('shares_balances'),
            'sharesRevenue' => Balances::sommeSold('shares_balances', 'amount'),
            'cashFlow' => floatval(Balances::sommeSold('shares_balances', 'amount')) + floatval(Balances::sommeSold('cash_balances')),
        ]);
    }
}

