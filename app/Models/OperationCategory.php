<?php

namespace App\Models;

use Core\Models\BalanceOperation;
use Illuminate\Database\Eloquent\Model;

class OperationCategory extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function balanceOperation()
    {
        return $this->belongsTo(BalanceOperation::class, 'balance_operation_id');
    }
}
