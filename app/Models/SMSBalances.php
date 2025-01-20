<?php

namespace App\Models;

use App\Http\Livewire\Platform;
use App\Services\Balances\Balances;
use Core\Models\BalanceOperation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSBalances extends Model
{
    use HasFactory;

    protected $table = 'sms_balances';

    protected $fillable = [
        'value',
        'description',
        'current_balance',
        'reference',
        'amount',
        'balance_operation_id',
        'description',
        'beneficiary_id_auto',
        'beneficiary_id',
        'operator_id',
        'order_id',
    ];

    public function deal()
    {
        return $this->hasOne(Deal::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
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

    public function sms()
    {
        return $this->hasOne(Sms::class);
    }


    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(User::class, 'beneficiary_id_auto');
    }

    public static function addLine($smsBalances, $item_id = null, $deal_id = null, $order_id = null, $platform_id = null, $order_detail_id = null)
    {
        self::create(Balances::addAutomatedFields($smsBalances, $item_id, $deal_id, $order_id, $platform_id, $order_detail_id));
    }
}
