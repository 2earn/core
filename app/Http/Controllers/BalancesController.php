<?php

namespace App\Http\Controllers;

use Core\Enum\BalanceOperationsEnum;
use Illuminate\Support\Facades\DB;

class BalancesController extends Controller
{
    public function getTransfertQuery()
    {
        return DB::table('cash_balances')
            ->select('value', 'description', 'created_at')
            ->where('balance_operation_id', BalanceOperationsEnum::OLD_ID_42->value)
            ->where('beneficiary_id', Auth()->user()->idUser)
            ->whereNotNull('description')
            ->orderBy('created_at', 'DESC');
    }

    public function getTransfert()
    {
        return datatables($this->getTransfertQuery())->make(true);
    }
}
