<?php

namespace App\Models;

use App\Http\Livewire\Platform;
use Core\Models\BalanceOperation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreeBalances extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'description',
        'total_balance',
        'reference',
    ];

    public function deal()
    {
        return $this->hasOne(Deal::class);
    }

    public function item()
    {
        return $this->hasOne(Item::class);
    }

    public function orderDetail()
    {
        return $this->hasOne(Order::class);
    }

    public function platform()
    {
        return $this->hasOne(Platform::class);
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
