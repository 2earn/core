<?php

namespace App\Models;

use Core\Models\BalanceOperation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountBalances extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'description',
        'actual_balance',
        'ref',
    ];

    public function deal()
    {
        return $this->hasOne(Deal::class);
    }

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
