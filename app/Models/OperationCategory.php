<?php

namespace App\Models;

use Core\Models\BalanceOperation;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class OperationCategory extends Model
{
    use HasAuditing;

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
