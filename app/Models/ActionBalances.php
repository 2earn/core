<?php

namespace App\Models;

use Core\Models\BalanceOperation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionBalances extends Model
{
    use HasFactory;
    protected $fillable = [
        'value',
        'description',
        'actual_balance',
        'ref',
        'win_purchase_amount',
        'gifted_shares',
        'unit_price',
    ];


    public function balanceOperation()
    {
        return $this->hasOne(BalanceOperation::class);
    }


    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(User::class, 'beneficiary_id');
    }
}
