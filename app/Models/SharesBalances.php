<?php

namespace App\Models;

use App\Services\Balances\Balances;
use Core\Models\BalanceOperation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharesBalances extends Model
{
    use HasFactory;
    protected $fillable = [
        'value',
        'description',
        'current_balance',
        'reference',
        'total_amount',
        'amount',
        'payed',
        'unit_price',
        'real_amount',
        'balance_operation_id',
        'beneficiary_id_auto',
        'beneficiary_id',
        'operator_id',
        'order_id',
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
        return $this->belongsTo(User::class, 'beneficiary_id_auto');
    }

    public static function addLine($shareBalances, $item_id = null, $deal_id = null, $order_id = null, $platform_id = null, $order_detail_id = null)
    {
        self::create(Balances::addAutomatedFields($shareBalances, $item_id, $deal_id, $order_id, $platform_id, $order_detail_id));
    }
}
