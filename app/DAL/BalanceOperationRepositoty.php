<?php

namespace App\DAL;
use Core\Enum\BalanceOperationsEnum;
use Core\Interfaces\IBalanceOperationRepositoty;
use Illuminate\Support\Facades\DB;

class  BalanceOperationRepositoty implements IBalanceOperationRepositoty
{
    public function getBalanceOperationById(BalanceOperationsEnum $operation)
    {
        return DB::table('balanceoperations')
            ->where([
                ['idBalanceOperations', '=', $operation],
            ])
            ->get()->first();
    }
    public function getBalanceOperation()
    {
        return DB::table('balanceoperations')
            ->get() ;
    }
}





















//IBalanceOperationRepositoty
