<?php

namespace App\Models;

use App\Http\Livewire\Platform;
use App\Services\Balances\Balances;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BFSsBalances extends Model
{
    use HasFactory;

    protected $table = 'bfss_balances';

    protected $fillable = [
        'value',
        'description',
        'current_balance',
        'reference',
        'percentage',
    ];

    public function item()
    {
        return $this->hasOne(Item::class);
    }

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
    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(User::class, 'beneficiary_id_auto');
    }

    public static function getTotal($bfsFiels)
    {

        $bfss = json_decode($bfsFiels);
        $soldeBfs = 0;
        foreach ($bfss as $bf) {
            $soldeBfs = $soldeBfs + $bf->value;
        }
        return $soldeBfs;
    }

    public static function addLine($bfssBalances, $item_id = null, $deal_id = null, $order_id = null, $platform_id = null, $order_detail_id = null)
    {
        $bfssBalances = Balances::addAutomatedFields($bfssBalances, $item_id, $deal_id, $order_id, $platform_id, $order_detail_id);
        self::create($bfssBalances);
    }
}
