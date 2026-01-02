<?php
namespace App\Interfaces;

use App\Enums\BalanceOperationsEnum;

interface  IBalanceOperationRepositoty {
    public function getBalanceOperationById(BalanceOperationsEnum $operation);
    public function getBalanceOperation() ;
}
