<?php
namespace  Core\Services ;

use Core\Interfaces\ITransaction;

class TransactionManager
{
    private ITransaction $transaction;

    public function __construct(
        ITransaction $transaction
    )
    {
        $this->transaction = $transaction;
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
