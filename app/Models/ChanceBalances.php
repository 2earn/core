<?php

namespace App\Models;

use App\Http\Livewire\Platform;
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
        'description',
        'beneficiary_id_auto',
        'beneficiary_id',
        'operator_id',
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
        return $this->hasOne(Platform::class);
    }

    public function activity()
    {
        return $this->hasOne(Activity::class);
    }


    public function chanceable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function getTotal($ChanceFiels)
    {

        $chance = json_decode($ChanceFiels);
        $soldeChance = 0;
        foreach ($chance as $ch) {
            $soldeChance = $soldeChance + $ch->value;
        }
        return $soldeChance;
    }

    public static function addLine($chanceBalances, $item_id = null, $deal_id = null, $order_id = null, $platform_id = null, $order_detail_id = null)
    {
        $chanceBalances = Balances::addAutomatedFields($chanceBalances, $item_id, $deal_id, $order_id, $platform_id, $order_detail_id);
        self::create($chanceBalances);
    }
}
