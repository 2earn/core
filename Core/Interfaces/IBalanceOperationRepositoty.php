<?php
namespace Core\Interfaces;

use Core\Enum\BalanceOperationsEnum;
interface  IBalanceOperationRepositoty {
    public function getBalanceOperationById(BalanceOperationsEnum $operation);
    public function getBalanceOperation() ;
}
