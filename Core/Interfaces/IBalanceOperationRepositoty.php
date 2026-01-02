<?php
namespace Core\Interfaces;

use App\Enums\BalanceOperationsEnum;

interface  IBalanceOperationRepositoty {
    public function getBalanceOperationById(BalanceOperationsEnum $operation);
    public function getBalanceOperation() ;
}
