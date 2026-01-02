<?php

namespace App\Models;

use App\Models\BalanceOperation;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class OperationCategory extends Model
{
    use HasAuditing;

    protected $fillable = [
        'name',
        'code',
        'description',
        'created_by',
        'updated_by',
    ];

    public function balanceOperation()
    {
        return $this->belongsTo(BalanceOperation::class, 'balance_operation_id');
    }
}
