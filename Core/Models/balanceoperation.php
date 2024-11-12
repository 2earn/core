<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;

class BalanceOperation extends Model
{
    public $primaryKey = 'idBalanceOperations';
    protected $table = 'balance_operations';
    public $timestamps = false;


}
