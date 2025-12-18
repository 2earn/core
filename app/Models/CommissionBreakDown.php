<?php

namespace App\Models;

use Core\Enum\CommissionTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class CommissionBreakDown extends Model
{
    use HasFactory, HasAuditing;

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
        'cash_company_profit',
        'cash_jackpot',
        'cash_tree',
        'cash_cashback',
        'camembert',
        'deal_paid_amount',
        'additional_amount',
        'created_by',
        'updated_by',
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

    public function platform()
    {
        return $this->belongsTo(\Core\Models\Platform::class, 'platform_id', 'id');
    }

    public function getRecoveredPercentage()
    {
        $lastTwoRecords = CommissionBreakDown::where('deal_id', $this->deal_id)
            ->where('type', CommissionTypeEnum::IN)
            ->orderBy('id', 'desc')
            ->take(2)
            ->get();

        return  $lastTwoRecords[0]->commission_percentage - $lastTwoRecords[1]->commission_percentage;
    }
    public static function getSum($dealId,$column)
    {
      return  CommissionBreakDown::where('deal_id', $dealId)->where('type', CommissionTypeEnum::IN)->sum($column);
    }
}
