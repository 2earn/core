<?php
namespace App\Services;

use App\Interfaces\ITransaction;

class TransactionManager
{
    public function __construct(private ITransaction $transaction)    {
    }
    public function beginTransaction()
    {
        $this->transaction->beginTransaction() ;
    }
    public function commit()
    {
        $this->transaction->commit() ;
    }
    public function rollback()
    {
        $this->transaction->rollback() ;
    }

}
