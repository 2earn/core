<?php

namespace App\Models;

use App\Livewire\PlatformIndex;
use App\Services\Balances\Balances;
use Core\Models\BalanceOperation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChanceBalances extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'description',
        'current_balance',
        'reference',
        'balance_operation_id',
        'beneficiary_id_auto',
        'beneficiary_id',
        'operator_id',
        'type',
        'pool_id',
    ];

    public function item()
    {
        return $this->hasOne(Item::class);
    }

    public function deal()
    {
        return $this->hasOne(Deal::class);
    }

    public function balanceOperation()
    {
        return $this->hasOne(BalanceOperation::class);
    }

    public function pool()
    {
        return $this->hasOne(Pool::class);
    }


    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(User::class, 'beneficiary_id_auto');
    }

    public function platform()
    {
        return $this->hasOne(PlatformIndex::class);
    }

    public function activity()
    {
        return $this->hasOne(Activity::class);
    }


    public function chanceable(): MorphTo
    {
        return $this->morphTo();
    }


    public static function addLine($chanceBalances, $item_id = null, $deal_id = null, $order_id = null, $platform_id = null, $order_detail_id = null)
    {
        self::create(Balances::addAutomatedFields($chanceBalances, $item_id, $deal_id, $order_id, $platform_id, $order_detail_id));
    }
}
