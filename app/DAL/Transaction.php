<?php
namespace App\DAL;

use App\Interfaces\ITransaction;
use Illuminate\Support\Facades\DB;

class  Transaction implements ITransaction
{

    public function beginTransaction()
    {
        DB::beginTransaction();
    }

    public function commit()
    {
        DB::commit();
    }

    public function rollback()
    {
        DB::rollBack();
    }
}

