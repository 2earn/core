<?php

namespace App\Models;

use Core\Enum\CommissionTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionBreakDown extends Model
{
    use HasFactory;

    protected $fillable = [
        'trigger',
        'type',
        'order_id',
        'deal_id',
        'new_turnover',
        'old_turnover',
        'purchase_value',
        'commission_percentage',
        'commission_value',
        'cumulative_commission',
        'cumulative_commission_percentage',
        'cash_company_profit',
        'cash_jackpot',
        'cash_tree',
        'cash_cashback',
        'cumulative_cashback',
        'cashback_allocation',
        'earned_cashback',
        'commission_difference',
        'additional_commission_value',
        'final_cashback',
        'final_cashback_percentage',
    ];
    protected $casts = ['type' => CommissionTypeEnum::class];


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function deal()
    {
        return $this->hasOne(Deal::class, 'deal_id', 'id');
    }
}
